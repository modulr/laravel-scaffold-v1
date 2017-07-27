<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Storage;
use Avatar;
use App\User;
use App\Role;
use App\Http\Helpers\Upload;

class UserController extends Controller
{
    public function index(Request $request)
    {
        return view('users', ['breadcrumb' => $request->path()]);
    }

    public function all()
    {
        return User::with('role')->get();
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string',
            'active' => 'required|boolean'
        ]);

        $q = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'avatar' => $request->avatar,
            'active' => $request->active,
        ]);

        if ($request->avatar_url) {
            $upload = new Upload();
            $avatar = $upload->moveFromTemp($request->avatar_url, 'avatars/'.$q->id)->resize(200)->getInfo();
            $request->avatar = $avatar['basename'];
        } else {
            $avatar = Avatar::create($q->name)->getImageObject()->encode('png');
            Storage::put('avatars/'.$q->id.'/avatar.png', $avatar);
            $request->avatar = 'avatar.png';
        }

        $q = User::find($q->id);
        $q->avatar = $request->avatar;
        $q->save();

        return $q;
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,'.$id,
            'active' => 'required|boolean'
        ]);

        $q = User::find($id);

        if ($q->name != $request->name) {
            $avatar = Avatar::create($request->name)->getImageObject()->encode('png');
            Storage::put('avatars/'.$id.'/avatar.png', $avatar);
            $q->name = $request->name;
        }

        if ($q->email != $request->email) {
            $q->email = $request->email;
        }

        if ($q->active != $request->active) {
            $q->active = $request->active;
        }

        if ($request->password != '') {
            $q->password = Hash::make($request->password);
        }

        $q->save();

        return $q;
    }

    public function destroy($id)
    {
        return User::destroy($id);
    }

    public function uploadAvatarTemp(Request $request)
    {
        $upload = new Upload();
        $avatar = $upload->uploadTemp($request->file('files'))->getInfo();
        return ['avatar' => $avatar['basename'], 'avatar_url' => $avatar['pathFile']];
    }

    public function uploadAvatar(Request $request, $id)
    {
        $upload = new Upload();
        $avatar = $upload->upload($request->file('files'), 'avatars/'.$id)->resize(200)->getInfo();

        $q = User::find($id);
        $q->avatar = $avatar['basename'];
        $q->save();

        return $q;
    }
}
