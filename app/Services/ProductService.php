<?php 

namespace App\Services;

use App\Repositories\Product\ProductRepositoryInterface;

class ProductService
{
    protected $productRepo;

    public function __construct(ProductRepositoryInterface $productRepo)
    {
        $this->productRepo = $productRepo;
    }

    public function getAll($perPage = 1, $search = null)
    {
        return $this->productRepo->getAll();
    }


    public function getProducts($perPage = 1, $search = null)
    {
        return $this->productRepo->getPaginated($perPage, $search);
    }


    public function createProduct(array $data)
    {
        return $this->productRepo->create($data);
    }

    public function updateProduct(array $data , $product)
    {
        return $this->productRepo->update($product , $data);
    }

    public function destroyProduct($product)
    {
        return $this->productRepo->destroy($product);
    } 

    public function slowProducts()
    {
        return $this->productRepo->slow();
    } 

}