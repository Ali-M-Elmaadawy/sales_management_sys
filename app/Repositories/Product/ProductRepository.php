<?php

namespace App\Repositories\Product;

use App\Models\Product;

class ProductRepository implements ProductRepositoryInterface
{

    public function getAll() {
        return Product::select('id' , 'name' , 'price' , 'qty' , 'tax' , 'tax_type')->get();
    }

    public function getPaginated($perPage = 5, $search = null)
    {
        $query = Product::query();

        if ($search) {
            $query->where('name', 'like', "%$search%");
        }

        return $query->orderBy('id' , 'DESC')->paginate($perPage);
    }

    public function find($id)
    {
        return Product::findOrFail($id);
    }

    public function create(array $data)
    {
        return Product::create($data);
    }

    public function update($product, array $data)
    {
        $product->update($data);
        return $product;
    }

    public function destroy($product)
    {

        if($product->orderItems->count()) {
            return response()->json(['status' => false , 'msg' => 'Cant Delete Related Records']);
        } else {
            if ($product->image && \Storage::disk('public')->exists($product->image)) {
                \Storage::disk('public')->delete($product->image);
            }
            $product->delete();
            return response()->json(['status' => true , 'msg' => 'Record Deleted Success']);
        }
    }

    public function slow()
    {
        $data = Product::where('sale_count', '<', 5)->get()->map(function ($p) {
            return [
                'name' => $p->name,
                'price' => $p->price,
                'qty' => $p->qty,
                'sold' => $p->sale_count,
                'last_sale' => $p->last_sale_date,
                'has_image' => (bool) $p->image,
            ];
        });

        $response = \Http::withHeaders([
            'Content-Type' => 'application/json',
        ])
        ->post(
            'https://generativelanguage.googleapis.com/v1/models/gemini-2.0-flash:generateContent?key='
            . env('GEMINI_API_KEY'),
            [
                'contents' => [
                    [
                        'parts' => [
                            [
                                'text' => "Analyze these low-selling products and give reasons + actionable suggestions:\n"
                                . json_encode($data, JSON_PRETTY_PRINT)
                            ]
                        ]
                    ]
                ]
            ]
        );

        return $response->json();
            
    }


}