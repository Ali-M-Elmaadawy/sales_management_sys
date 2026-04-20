<?php 

namespace App\Repositories\User;

interface UserRepositoryInterface
{
    public function getAll();
    public function getPaginated();
    public function find($id);
    public function create(array $data);
    public function update($user , array $data);
    public function destroy($user);
}