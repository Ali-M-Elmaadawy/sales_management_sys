<?php 

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\UserService;

class UserController extends Controller
{


    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function get_all() {

        return $this->userService->getAll();
    }


    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 5);
        $search = $request->get('search');

        return $this->userService->getUsers($perPage, $search);
    }


    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|min:3|max:255',

            'email' => 'required|email|unique:users,email',

            'phone' => 'required|string|min:10|max:15',

            'address' => 'required|string|min:5|max:255',
        ]);

        return $this->userService->createUser($request->all());
    }


    public function show(User $user)
    {
        return $user;
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|min:3|max:255',

            'email' => 'required|email|unique:users,email,' . $request->id,

            'phone' => 'required|string|min:10|max:15',

            'address' => 'required|string|min:5|max:255',
        ]);


        return $this->userService->updateUser($request->all() , $user);
    }


    public function destroy(User $user)
    {
       return $this->userService->destroyUser($user);
    }

    public function userOrders(User $user)
    {
        return $this->userService->userOrders($user);
    }

}