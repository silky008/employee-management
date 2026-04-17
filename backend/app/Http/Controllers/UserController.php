<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //

    public function index(Request $request, UserService $service)
    {
        $this->authorize('viewAny', User::class);
        $users = $service->list($request->all());
        return UserResource::collection($users);
    }

    public function store(StoreUserRequest $request, UserService $service)
    {
        $this->authorize('create', User::class);
        $user = $service->create($request->validated());
        return new UserResource($user);
    }

    public function update(UpdateUserRequest $request, User $user, UserService $service)
    {
        $this->authorize('update', $user);

        $user = $service->update($user, $request->validated());

        return new UserResource($user);
    }

    public function destroy(User $user, UserService $service)
    {
        $this->authorize('delete', $user);
        $service->delete($user);
        return response()->json([
            'message' => 'User deleted successfully',
        ]);
    }

}
