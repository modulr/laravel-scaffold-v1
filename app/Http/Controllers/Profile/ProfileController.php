<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\user;
use App\Models\Profile\ProfilePersonal;
use App\Models\Profile\ProfileContact;
use App\Models\Profile\ProfileEducation;
use App\Models\Profile\ProfileFamily;
use App\Models\Profile\ProfilePlace;
use App\Models\Profile\ProfileWork;

class ProfileController extends Controller
{
    public function profile($id)
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

        return view('profile.profile', ['user' => $user]);
    }

    public function profileEdit($id)
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

        return view('profile.edit.profile', ['user' => $user]);
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

        $personal = ProfilePersonal::find($id);
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

    public function work(Request $request, $id)
    {
        $user = User::with(
            'profileWork.profession',
            'profileWork.position',
            'profileWork.department',
            'profileWork.boss'
            )->find($id);

        return view('profile.work', ['user' => $user]);
    }

    public function workEdit(Request $request, $id)
    {
        $user = User::with(
            'profileWork.profession',
            'profileWork.position',
            'profileWork.department',
            'profileWork.boss'
            )->find($id);

        return view('profile.edit.work', ['user' => $user]);
    }

    public function updateWork(Request $request, $id)
    {
        $this->validate($request, [
            'profession_id' => 'integer',
            'position_id' => 'integer',
            'department_id' => 'integer',
            'boss_id' => 'integer',
            'starting_from' => 'date',
        ]);

        $personal = ProfileWork::find($id);
        $personal->profession_id = $request->profession_id;
        $personal->position_id = $request->position_id;
        $personal->department_id = $request->department_id;
        $personal->boss_id = $request->boss_id;
        $personal->starting_from = $request->starting_from;
        $personal->save();

        return ProfileWork::with('profession', 'position', 'department', 'boss')->find($id);
    }

    public function password(Request $request, $id)
    {
        $user = User::find($id);

        return view('profile.edit.password', ['user' => $user]);
    }
}
