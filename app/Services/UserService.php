<?php 

namespace App\Services;

use App\Repositories\User\UserRepositoryInterface;

class UserService
{
    protected $userRepo;

    public function __construct(UserRepositoryInterface $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function getAll($perPage = 1, $search = null)
    {
        return $this->userRepo->getAll();
    }


    public function getUsers($perPage = 1, $search = null)
    {
        return $this->userRepo->getPaginated($perPage, $search);
    }


    public function createUser(array $data)
    {
        return $this->userRepo->create($data);
    }

    public function updateUser(array $data , $user)
    {
        return $this->userRepo->update($user , $data);
    }
   
    public function destroyUser($user)
    {
        return $this->userRepo->destroy($user);
    }   

}