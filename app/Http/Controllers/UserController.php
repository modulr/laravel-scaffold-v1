<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Storage;
use Avatar;
use App\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        return view('users', ['breadcrumb' => $request->path()]);
    }

    public function all()
    {
        return User::orderBy('name', 'asc')->get();
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
            'active' => $request->active,
        ]);

        $avatar = Avatar::create($q->name)->getImageObject()->encode('png');
        Storage::put('avatars/'.$q->id.'/avatar.png', $avatar);

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
        return $request;
        // $pathFile = $request->file('files')->store('avatars/'.$id);
        // $infoFile = pathinfo($pathFile);
        // $file = Storage::disk('public')->get('avatars/'.$id.'/'.$infoFile['basename']);
        // $image = Image::make($file)
        //     ->resize(100, 100)
        //     ->save(storage_path('app/public/avatars/'.$id.'/'.$infoFile['basename']));
        // $user = User::find($id);
        // $user->avatar = $infoFile['basename'];
        // $user->save();
        // return response()->json([
        //     'data' => ['avatar' => $user->avatar]
        // ]);
    }
}
