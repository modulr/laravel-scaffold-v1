<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\user;

class ProfileController extends Controller
{
    public function index(Request $request, $id)
    {
        $user = User::find($id);
        return view('profile.profile', ['breadcrumb' => $request->path(), 'user' => $user]);
    }
}
