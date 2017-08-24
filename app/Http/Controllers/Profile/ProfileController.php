<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\user;
use App\Models\Profile\ProfilePersonal;

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
            'profilePlace',
            'profileWork.position'
            )->find($id);

        return view('profile.information', ['breadcrumb' => $request->path(), 'user' => $user]);
    }

    public function work(Request $request, $id)
    {
        $user = User::with(
            'profileWork.profession',
            'profileWork.position',
            'profileWork.department',
            'profileWork.boss'
            )->find($id);

        return view('profile.work', ['breadcrumb' => $request->path(), 'user' => $user]);
    }

    public function notifications(Request $request, $id)
    {
        $user = User::with('profileWork.position')->find($id);
        return view('profile.notifications', ['breadcrumb' => $request->path(), 'user' => $user]);
    }

    public function updatePersonal(Request $request, $id)
    {
        $this->validate($request, [
            'birthday' => 'date|nullable',
            'place_of_birth' => 'string|nullable',
            'gender_id' => 'integer',
            'relationship_id' => 'integer',
            'rfc' => 'alpha_num|nullable',
            'curp' => 'alpha_num|nullable',
            'nss' => 'alpha_num|nullable',
        ]);

        $q = ProfilePersonal::with('gender', 'relationship')->find($id);
        $q->birthday = $request->birthday;
        $q->place_of_birth = $request->place_of_birth;
        $q->gender_id = $request->gender_id;
        $q->relationship_id = $request->relationship_id;
        $q->rfc = $request->rfc;
        $q->curp = $request->curp;
        $q->nss = $request->nss;
        $q->save();

        return ProfilePersonal::with('gender', 'relationship')->find($id);
    }
}
