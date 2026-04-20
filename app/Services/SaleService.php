<?php 

namespace App\Services;

use App\Repositories\Sale\SaleRepositoryInterface;

class SaleService
{
    protected $saleRepo;

    public function __construct(SaleRepositoryInterface $saleRepo)
    {
        $this->saleRepo = $saleRepo;
    }

    public function getSales($perPage = 2, $search = null)
    {
        return $this->saleRepo->getPaginated($perPage, $search);
    }


    public function createSale(array $data)
    {
        return $this->saleRepo->create($data);
    }

    public function updateProduct(array $data , $product)
    {
        return $this->productRepo->update($product , $data);
    }

    public function destroySale($sale)
    {
        return $this->saleRepo->destroy($sale);
    } 

    public function restoreSale($sale)
    {
        return $this->saleRepo->restore($sale);
    } 

}