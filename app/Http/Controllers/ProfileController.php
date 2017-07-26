<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\user;

class ProfileController extends Controller
{
    public function show(Request $request, $id)
    {
        $user = User::find($id);
        return view('profile.show', ['breadcrumb' => $request->path(), 'user' => $user]);
    }
}
