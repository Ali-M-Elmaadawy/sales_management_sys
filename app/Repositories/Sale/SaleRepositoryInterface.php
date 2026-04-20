<?php 

namespace App\Repositories\Sale;

interface SaleRepositoryInterface
{
    public function getPaginated();
    public function find($id);
    public function create(array $data);
    public function destroy($sale);
    public function restore($sale);
}