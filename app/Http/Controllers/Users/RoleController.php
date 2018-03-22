<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Role;
use App\Permission;
use App\Models\Modules\Module;

class RoleController extends Controller
{
    public function all()
    {
        return Role::get();
    }

    public function filter(Request $request)
    {
        $query = Role::query();

        if($request->search) {
            $query->where('name', 'LIKE', '%'.$request->search.'%');
        }

        $users = $query->orderBy('id', 'asc')
                    ->paginate(50);
        $users->load('users');

        return $users;
    }

    public function show($id)
    {
        $role = Role::with('users', 'permissions')->find($id);
        $modules = Module::has('permissions')->orderBy('name')->get();

        foreach ($modules as $key => $value) {
            foreach ($value->permissions as $ke => $val) {
                foreach ($role->permissions as $k => $v) {
                    if ($v->name == $val->name) {
                        $val->allow = true;
                    }
                }
            }
        }

        $role->modules = $modules;

        return $role;
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'display_name' => 'required|string|unique:roles',
            'description' => 'nullable|string',
            'modules' => 'array'
        ]);

        $role = Role::create([
            'name' => strtolower(str_replace(' ', '-', $request->display_name)),
            'display_name' => $request->display_name,
            'description' => $request->description
        ]);

        $newPermissions = [];
        foreach ($request->modules as $key => $value) {
            foreach ($value['permissions'] as $ke => $val) {
                if (isset($val['allow']) && $val['allow']) {
                    array_push($newPermissions, $val['id']);
                }
            }
        }

        $role->permissions()->attach($newPermissions);
        $role->users = [];
        return $role;
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'display_name' => 'required|string',
            'description' => 'nullable|string',
            'modules' => 'array'
        ]);

        $role = Role::with('users')->find($id);
        $role->name = strtolower(str_replace(' ', '-', $request->display_name));
        $role->display_name = $request->display_name;
        $role->description = $request->description;
        $role->save();

        $newPermissions = [];
        foreach ($request->modules as $key => $value) {
            foreach ($value['permissions'] as $ke => $val) {
                if (isset($val['allow']) && $val['allow']) {
                    array_push($newPermissions, $val['id']);
                }
            }
        }

        $role->permissions()->sync($newPermissions);
        return $role;
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        return $role;
    }
}
