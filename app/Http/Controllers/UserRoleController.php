<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserRoleController extends Controller
{
    use SoftDeletes;

    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $userRoleList = Role::latest()->paginate($perPage);

        return view('pages.user-role.index', [
            'userRoleList' => $userRoleList,
            'perPage' => $perPage
        ]);
    }

    public function create()
    {
        $userPermissionList = Permission::all()->groupBy('category')->map(function ($permission, $category) {
            return [
                'category' => $category,
                'permissions' => $permission->pluck('name')->toArray(),
            ];
        })->values();

        return view('pages.user-role.create', [
            'save_route' => route('user-role.store'),
            'userPermissionList' => $userPermissionList,
            'str_mode' => 'Tambah',
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'required|array',
            'publish_status' => 'required|in:1,0',
        ]);

        $role = Role::create([
            'name' => $request->name,
            'publish_status' => $request->publish_status,
        ]);

        $role->syncPermissions($request->permissions);

        return redirect()->route('user-role')->with('success', 'Peranan pengguna telah berjaya ditambah');
    }


    public function show($id)
    {
        $userRole = Role::findOrFail($id);
        $userPermissionList = Permission::all()->groupBy('category')->map(function ($permissions, $category) {
            return [
                'category' => $category,
                'permissions' => $permissions->pluck('name')->toArray(),
            ];
        })->values();

        return view('pages.user-role.view', [
            'userRole' => $userRole,
            'userPermissionList' => $userPermissionList,
        ]);
    }

    public function edit($id)
    {
        $userRole = Role::findOrFail($id);
        $userPermissionList = Permission::all()->groupBy('category')->map(function ($permissions, $category) {
            return [
                'category' => $category,
                'permissions' => $permissions->pluck('name')->toArray(),
            ];
        })->values();

        return view('pages.user-role.edit', [
            'save_route' => route('user-role.update', $id),
            'userRole' => $userRole,
            'userPermissionList' => $userPermissionList,
            'str_mode' => 'Kemaskini',
            'update_route' => route('user-role.update', $userRole->id),
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $id,
            'permissions' => 'required|array',
            'publish_status' => 'required|in:1,0',
        ]);

        $role = Role::findOrFail($id);
        $role->update([
            'name' => $request->name,
            'publish_status' => $request->publish_status, // Update this line
        ]);

        $role->syncPermissions($request->permissions);

        return redirect()->route('user-role')->with('success', 'Peranan pengguna telah berjaya dikemaskini');
    }


    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        return redirect()->route('user-role')->with('success', 'Peranan pengguna telah berjaya dipadam');
    }

    public function trashList()
    {
        $trashList = Role::onlyTrashed()->latest()->paginate(10);

        return view('pages.user-role.trash', [
            'trashList' => $trashList,
        ]);
    }

    public function restore($id)
    {
        $role = Role::withTrashed()->findOrFail($id);
        $role->restore();
        return redirect()->route('user-role')->with('success', 'Peranan pengguna telah berjaya dipulihkan');
    }

    public function forceDelete($id)
    {
        $role = Role::withTrashed()->findOrFail($id);
        $role->forceDelete();
        return redirect()->route('user-role')->with('success', 'Peranan pengguna telah berjaya dipadam secara kekal');
    }

    public function search(Request $request)
    {
        $search = $request->input('search');

        if ($search) {
            $userRoleList = Role::where('name', 'LIKE', "%$search%")
                ->latest()
                ->paginate(10);
        } else {
            $userRoleList = Role::latest()->paginate(10);
        }

        return view('pages.user-role.index', [
            'userRoleList' => $userRoleList,
        ]);
    }
}
