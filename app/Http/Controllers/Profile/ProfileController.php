<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\user;

class ProfileController extends Controller
{
    public function information(Request $request, $id)
    {
        $user = User::with(
            'profilePersonal.gender',
            'profilePersonal.relationship',
            'profileContact',
            'profileEducation',
            'profileFamily.gender',
            'profileFamily.relation',
            'profilePlace'
            )->find($id);

        return view('profile.information', ['breadcrumb' => $request->path(), 'user' => $user]);
    }

    public function notifications(Request $request, $id)
    {
        $user = User::find($id);
        return view('profile.notifications', ['breadcrumb' => $request->path(), 'user' => $user]);
    }
}
