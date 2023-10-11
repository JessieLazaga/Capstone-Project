<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Spatie\Permission\Models\Permission;

class RolesController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        $permissions = Permission::all();
        return view('admin.roles.index', compact('roles', 'permissions'));
    }
    public function indexUser()
    {
        $roles = Role::all();
        $users = User::all();
        return view('admin.roles.role', compact('roles', 'users'));
    }
    public function register()
    {
        return view('admin.roles.register');
    }
    public function store()
    {
        $data = request()->validate([
            'name' => 'required|unique:roles,name',
        ]);
        Role::create($data);
        return redirect()->route('roles.index');
    }
    public function check()
    {
        $data = request()->validate([
            'role' => 'required',
            'permission' => 'required',
        ]);
        $role = Role::find($data['role']);
        $permission = Permission::find($data['permission']);
        $role->givePermissionTo($permission->name);
    }
    public function uncheck()
    {
        $data = request()->validate([
            'role' => 'required',
            'permission' => 'required',
        ]);
        $role = Role::find($data['role']);
        $permission = Permission::find($data['permission']);
        $role->revokePermissionTo($permission->name);
    }
    public function checkUser()
    {
        $data = request()->validate([
            'role' => 'required',
            'user' => 'required',
        ]);
        $role = Role::find($data['role']);
        $user = User::find($data['user']);
        $user->assignRole($role->name);
    }
    public function uncheckUser()
    {
        $data = request()->validate([
            'role' => 'required',
            'user' => 'required',
        ]);
        $role = Role::find($data['role']);
        $user = User::find($data['user']);
        $user->removeRole($role->name);
    }
}
