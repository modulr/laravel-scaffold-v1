<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\user;
use App\Models\Profile\ProfileContact;
use App\Models\Profile\ProfilePlace;

class ProfileController extends Controller
{
    public function profile($id)
    {
        $user = User::with(
            'profilePlace',
            'profileContact'
            )->find($id);

        return view('profile.profile', ['user' => $user]);
    }

    public function profileEdit($id)
    {
        $user = User::with(
            'profilePlace',
            'profileContact'
            )->find($id);

        if (!Auth::user()->hasPermission('update-all-profile') && Auth::user()->id != $user->id)
            return redirect('dashboard');

        return view('profile.edit.profile', ['user' => $user]);
    }

    public function updateProfile(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'string|required',
            'age' => 'string|nullable',
            'description' => 'string|nullable'
        ]);

        $user = User::find($id);
        $user->name = $request->name;
        $user->age = $request->age;
        $user->description = $request->description;
        $user->save();

        return $user;
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


    public function storePlace(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required|integer',
            'place' => 'required|string|max:255',
            'currently' => 'required|boolean',
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

    public function password(Request $request, $id)
    {
        $user = User::find($id);
        if (!Auth::user()->hasPermission('update-all-profile') && Auth::user()->id != $user->id)
            return redirect('dashboard');
        return view('profile.edit.password', ['user' => $user]);
    }
}
