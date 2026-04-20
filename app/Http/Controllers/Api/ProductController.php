<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\ProductService;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function get_all() {

        return $this->productService->getAll();
    }

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 5);
        $search = $request->get('search');

        return $this->productService->getProducts($perPage, $search);
    }


    public function show(Product $product)
    {
        return $product;
    }


    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|min:3|max:255',

            'price' => 'required|numeric|min:0',

            'qty' => 'required|integer|min:1',

            'tax_type' => 'required|in:percent,fixed',

            'tax' => 'required|numeric|min:0',

            'description' => 'required|string',

            'image' => 'required|image',
        ]);


        $data = $request->all();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $data['image'] = $path;
        }

        return $this->productService->createProduct($data);
    }


    public function update(Request $request, Product $product)
    {

        $request->validate([
            'name' => 'required|string|min:3|max:255',

            'price' => 'required|numeric|min:0',

            'qty' => 'required|integer|min:1',

            'tax_type' => 'required|in:percent,fixed',

            'tax' => 'required|numeric|min:0',

            'description' => 'required|string',

            'image' => 'required|nullable',
        ]);


        $data = $request->all();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $data['image'] = $path;
        }

        return $this->productService->updateProduct($data , $product);
    }


    public function destroy(Product $product)
    {

        return $this->productService->destroyProduct($product);
    }

    public function slow()
    {

        return $this->productService->slowProducts();
    }
}




