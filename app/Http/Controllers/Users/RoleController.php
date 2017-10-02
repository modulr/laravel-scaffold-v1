<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Users\Role;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        return view('users.roles', ['breadcrumb' => $request->path()]);
    }

    public function all()
    {
        return Role::get();
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string',
            'description' => 'nullable|string'
        ]);

        $q = Role::create([
            'title' => $request->title,
            'description' => $request->description
        ]);

        return $q;
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required|string',
            'description' => 'nullable|string'
        ]);

        $q = Role::find($id);
        $q->title = $request->title;
        $q->description = $request->description;
        $q->save();

        return $q;
    }

    public function destroy($id)
    {
        $q = Role::find($id);

        if ($q->lock)
            return response()->json(['The role is locked cannot delete'], 401);

        return Role::destroy($id);
    }
}
