<?php 

namespace App\Repositories\Product;

interface ProductRepositoryInterface
{
    public function getAll();
    public function getPaginated();
    public function find($id);
    public function create(array $data);
    public function update($product , array $data);
    public function destroy($product);
    public function slow();
}