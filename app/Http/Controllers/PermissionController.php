<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\Routing\Controllers\Middleware;

class PermissionController extends Controller
{
    public static function middleware(){
        return [
            new Middleware('permission:permissions-data', only: ['index']),
            new Middleware('permission:permissions-create', only: ['create', 'store']),
            new Middleware('permission:permissions-update', only: ['edit', 'update']),
            new Middleware('permission:permissions-delete', only: ['destroy']),
        ];
    }
    
    public function index(Request $request)
    {
        $permissions = Permission::select('id', 'name')
            ->when($request->search,fn($search) => $search->where('name', 'like', '%'.$request->search.'%'))
            ->latest()
            ->paginate(6)->withQueryString();
        return inertia('Permissions/Index', ['permissions' => $permissions]);
    }

    public function create()
    {
        return inertia('Permissions/Create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|min:3|max:255|unique:permissions']);
        Permission::create(['name' => $request->name]);
        return to_route('permissions.index');
    }

    // public function show(Permission $permission)
    // {
    // }
    
    public function edit(Permission $permission)
    {
        return inertia('Permissions/Edit', ['permission' => $permission]);
    }

    public function update(Request $request, Permission $permission)
    {
        $request->validate(['name' => 'required|min:3|max:255|unique:permissions,name,'.$permission->id]);
        $permission->update(['name' => $request->name]);
        return to_route('permissions.index');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();
        return back();
    }
}
