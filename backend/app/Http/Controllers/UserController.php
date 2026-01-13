<?php
namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
    //

    public function index()
    {
        $this->authorize('viewAny', User::class);
        return User::with('role')->paginate(10);
    }
}
