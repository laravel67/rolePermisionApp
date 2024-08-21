<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('permission:users-data', only : ['index']),
            new Middleware('permission:users-create', only : ['create', 'store']),
            new Middleware('permission:users-update', only : ['edit', 'update   ']),
            new Middleware('permission:users-delete', only : ['destroy']),
        ];
    }

    public function index()
    {
        // get all users
        $users = User::with('roles')
            ->when(request('search'), fn($query) => $query->where('name', 'like', '%'.request('search').'%'))
            ->latest()
            ->paginate(6);

        // render view
        return inertia('Users/Index', ['users' => $users]);
    }

    public function create()
    {
        // get roles
        $roles = Role::where('name', '!=', 'super-admin')->get();

        // render view
        return inertia('Users/Create', ['roles' => $roles]);
    }

    public function store(Request $request)
    {
        // validate request
        $request->validate([
            'name' => 'required|min:3|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:4',
            'selectedRoles' => 'required|array|min:1',
        ]);

        // create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // attach roles
        $user->assignRole($request->selectedRoles);

        // render view
        return to_route('users.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    public function edit(User $user)
    {
        // get roles
        $roles = Role::where('name', '!=', 'super-admin')->get();

        // load roles
        $user->load('roles');

        // render view
        return inertia('Users/Edit', ['user' => $user, 'roles' => $roles]);
    }

       public function update(Request $request, User $user)
    {
        // validate request
        $request->validate([
            'name' => 'required|min:3|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'selectedRoles' => 'required|array|min:1',
        ]);

        // update user data
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // attach roles
        $user->syncRoles($request->selectedRoles);

        // render view
        return to_route('users.index');
    }

    public function destroy(User $user)
    {
        // delete user data
        $user->delete();

        // render view
        return back();
    }
}
