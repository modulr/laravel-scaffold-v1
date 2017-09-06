<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\user;
use App\Models\Profile\ProfilePersonal;
use App\Models\Profile\ProfileContact;
use App\Models\Profile\ProfileEducation;
use App\Models\Profile\ProfileFamily;
use App\Models\Profile\ProfilePlace;

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

        $personal = ProfilePersonal::with('gender', 'relationship')->find($id);
        $personal->birthday = $request->birthday;
        $personal->place_of_birth = $request->place_of_birth;
        $personal->gender_id = $request->gender_id;
        $personal->relationship_id = $request->relationship_id;
        $personal->rfc = $request->rfc;
        $personal->curp = $request->curp;
        $personal->nss = $request->nss;
        $personal->save();

        return ProfilePersonal::with('gender', 'relationship')->find($id);
    }

    public function storeContact(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required|integer',
            'contact' => 'required|string|max:255',
            'type_id' => 'required|integer',
        ]);

        return ProfileContact::create([
            'user_id' => $request->user_id,
            'contact' => $request->contact,
            'type_id' => $request->type_id,
        ]);
    }

    public function destroyContact($id)
    {
        return ProfileContact::destroy($id);
    }

    public function storeEducation(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required|integer',
            'title' => 'required|string|max:255',
            'school_name' => 'required|string|max:255',
            'start_year' => 'required|numeric',
            'end_year' => 'required|numeric',
        ]);

        return ProfileEducation::create([
            'user_id' => $request->user_id,
            'title' => $request->title,
            'school_name' => $request->school_name,
            'start_year' => $request->start_year,
            'end_year' => $request->end_year,
        ]);
    }

    public function destroyEducation($id)
    {
        return ProfileEducation::destroy($id);
    }

    public function storeFamily(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required|integer',
            'name' => 'required|string|max:255',
            'gender_id' => 'required|integer',
            'relation_id' => 'required|integer',
            'birthday' => 'required|date',
        ]);

        $family = ProfileFamily::create([
            'user_id' => $request->user_id,
            'name' => $request->name,
            'gender_id' => $request->gender_id,
            'relation_id' => $request->relation_id,
            'birthday' => $request->birthday,
        ]);

        return ProfileFamily::with('relation', 'gender')->find($family->id);
    }

    public function destroyFamily($id)
    {
        return ProfileFamily::destroy($id);
    }

    public function storePlace(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required|integer',
            'place' => 'required|string|max:255',
            'currently' => 'boolean',
        ]);

        return ProfilePlace::create([
            'user_id' => $request->user_id,
            'place' => $request->place,
            'currently' => $request->currently,
        ]);
    }

    public function destroyPlace($id)
    {
        return ProfilePlace::destroy($id);
    }
}
