<?php
namespace App\Http\Controllers\Admin;

use App\Events\AdminAddedEvent;
use App\Http\Requests\{
    UpdateInfoRequest,
    UpdatePasswordRequest,
    UserCreateRequest,
    UserUpdateRequest
};
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Support\Facades\{
    Auth,
    Gate,
    Hash
};
use Symfony\Component\HttpFoundation\Response;

class UserController
{
    public function index()
    {
        Gate::authorize('view', 'users');

        $users =  User::paginate();

        return UserResource::collection($users);
    }

    public function show($id)
    {
        Gate::authorize('view', 'users');
        $user = User::find($id);
        return new UserResource($user);
    }

    public function store(UserCreateRequest $request)
    {
        Gate::authorize('edit', 'users');
        $user = User::create($request->only('first_name', 'last_name', 'email') + [
            'password' => Hash::make(1234),
        ]);

        event(new AdminAddedEvent($user));

        UserRole::create([
            'user_id' => $user->id,
            'role_id' => $request->input('role_id')
        ]);

        return response(new UserResource($user), Response::HTTP_CREATED);
    }

    public function update(UserUpdateRequest $request, $id)
    {
        Gate::authorize('edit', 'users');
        $user = User::find($id);

        $user->update($request->only('first_name', 'last_name', 'email', 'role_id'));

        UserRole::where('user_id', $user->id)->delete();
        
        UserRole::create([
            'user_id' => $user->id,
            'role_id' => $request->input('role_id')
        ]);

        return response(new UserResource($user), Response::HTTP_ACCEPTED);
    }

    public function destroy($id)
    {
        Gate::authorize('edit', 'users');
        User::destroy($id);

        return response(null,Response::HTTP_NO_CONTENT);
    }
}