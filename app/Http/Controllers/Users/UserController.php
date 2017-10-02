<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Storage;
use Avatar;
use App\Http\Helpers\Upload;

use App\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        return view('users.users', ['breadcrumb' => $request->path()]);
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

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'avatar' => $request->avatar,
            'active' => $request->active,
            'role_id' => 2,
        ]);

        if ($request->avatar_url) {
            $upload = new Upload();
            $avatar = $upload->moveFromTemp($request->avatar_url, 'avatars/'.$user->id)->resize(200)->getInfo();
            $request->avatar = $avatar['basename'];
        } else {
            $avatar = Avatar::create($user->name)->getImageObject()->encode('png');
            Storage::put('avatars/'.$user->id.'/avatar.png', $avatar);
            $request->avatar = 'avatar.png';
        }

        $user = User::find($user->id);
        $user->avatar = $request->avatar;
        $user->save();

        return $user;
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,'.$id,
            'active' => 'required|boolean'
        ]);

        $user = User::find($id);

        if ($user->name != $request->name) {
            $avatar = Avatar::create($request->name)->getImageObject()->encode('png');
            Storage::put('avatars/'.$id.'/avatar.png', $avatar);
            $user->name = $request->name;
        }

        if ($user->email != $request->email) {
            $user->email = $request->email;
        }

        if ($user->active != $request->active) {
            $user->active = $request->active;
        }

        if ($request->password != '') {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return $user;
    }

    public function updatePassword(Request $request, $id)
    {
        $this->validate($request, [
            'current' => 'required',
            'password' => 'required',
            'password_confirmation' => 'required|same:password'
        ]);

        $user = User::find($id);

        if (!Hash::check($request->current, $user->password)) {
            return response()->json(['current' => ['Current password does not match']], 422);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return $user;
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

        $user = User::find($id);
        $user->avatar = $avatar['basename'];
        $user->save();

        return $user;
    }
}
