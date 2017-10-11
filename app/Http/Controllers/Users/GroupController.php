<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Users\Group;

class GroupController extends Controller
{
    public function index(Request $request)
    {
        return view('users.groups', ['breadcrumb' => $request->path()]);
    }

    public function all()
    {
        return Group::get();
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string',
            'description' => 'nullable|string'
        ]);

        $q = Group::create([
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

        $q = Group::find($id);
        $q->title = $request->title;
        $q->description = $request->description;
        $q->save();

        return $q;
    }

    public function destroy($id)
    {
        $q = Group::find($id);

        if ($q->lock)
            return response()->json(['The group is locked cannot delete'], 401);

        return Group::destroy($id);
    }
}
