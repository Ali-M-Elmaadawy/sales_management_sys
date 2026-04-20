<?php

namespace App\Repositories\Sale;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class SaleRepository implements SaleRepositoryInterface
{


    public function getPaginated($perPage = 5, $search = null)
    {
         $query = Order::with(['user', 'items.product']);

        if ($search) {
            $query->whereHas('user' , function($que) use ($search) {
                $que->where('id' , $search)->orWhere('name', 'like', '%' . $search . '%');
            });
        }

        return $query->orderBy('id' , 'DESC')->paginate($perPage);
    }


    public function find($id)
    {
        return Sale::where('id' , $id)->with('user' , 'items.product')->first();
    }

    public function create(array $data)
    {
      
            $subtotal = 0;
            $totalTax = 0;

            foreach ($data['items'] as $item) {
                $product = Product::find($item['product_id']);

                if ($product->qty < $item['qty']) {
                    return response()->json([
                        'message' => "Only {$product->qty} available for {$product->name}"
                    ], 422);
                }
            }
            $user  = User::find($data['user_id']);
            $order = Order::create([
                'user_id' => $data['user_id'],
                'subtotal' => 0,
                'tax' => 0,
                'total' => 0
            ]);
            $user->sale_count = $user->sale_count +1;
            $user->last_sale_date = now();
            $sale_products_count = $user->sale_products_count;

            

            foreach ($data['items'] as $item) {

                $product = Product::find($item['product_id']);
                $product->sale_count = $product->sale_count +  $item['qty'];
                $product->last_sale_date = now();
                $product->save();

                $sale_products_count += $item['qty'];

                $price = $product->price * $item['qty'];

                // tax calc
                if ($product->tax_type == 'percent') {
                    $tax = $price * $product->tax / 100;
                } else {
                    $tax = $product->tax * $item['qty'];
                }

                $subtotal += $price;
                $totalTax += $tax;

                // save item
                $order->items()->create([
                    'product_id' => $product->id,
                    'qty' => $item['qty'],
                    'price' => $price,
                    'tax' => $tax
                ]);

                // reduce stock
                $product->qty -= $item['qty'];
                $product->save();
            }
            $user->sale_products_count = $sale_products_count;
            $user->save();

            $order->update([
                'subtotal' => $subtotal,
                'tax' => $totalTax,
                'total' => $subtotal + $totalTax
            ]);

            return response()->json($order);
    }

    
    


    public function destroy($sale)
    {
        $sale->items()->delete();
        $sale->delete();
        return response()->json([
            'message' => 'Order deleted success'
        ]);
    }

    public function restore($sale)
    {
        return \DB::transaction(function () use ($sale) {

            $order = Order::with('items')->findOrFail($sale->id);
            $user = User::find($sale->user_id);
            $user->sale_count = $user->sale_count - 1;
            $user->last_sale_date = NULL;
            $sale_products_count = 0;
            foreach ($order->items as $item) {
                $product = Product::find($item->product_id);
                $product->sale_count = $product->sale_count - $item['qty'];
                $product->last_sale_date = NULL;
                $product->save();

                $sale_products_count += $item['qty'];

                if ($product) {
                    $product->qty += $item->qty;
                    $product->save();
                }
            }

            $user->sale_products_count = $user->sale_products_count - $sale_products_count;
            $user->save();

            $sale->items()->delete();
            $sale->delete();
            return response()->json([
                'message' => 'Order deleted and stock updated'
            ]);
        });
    }


}