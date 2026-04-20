<?php

namespace App\Repositories\User;

use App\Models\User;

class UserRepository implements UserRepositoryInterface
{

    public function getAll() {
        return User::select('id' , 'name')->get();
    }

    public function getPaginated($perPage = 5, $search = null)
    {
        $query = User::query();

        if ($search) {
            $query->where('name', 'like', "%$search%");
        }

        return $query->orderBy('id' , 'DESC')->paginate($perPage);
    }

    public function find($id)
    {
        return User::findOrFail($id);
    }

    public function create(array $data)
    {
        return User::create($data);
    }

    public function update($user, array $data)
    {
        $user->update($data);
        return $user;
    }

    public function destroy($user)
    {
        if($user->orders->count()) {
            return response()->json(['status' => false , 'msg' => 'Cant Delete Related Records']);
        } else {
            $user->delete();
            return response()->json(['status' => true , 'msg' => 'Record Deleted Success']);
        }
    }


}