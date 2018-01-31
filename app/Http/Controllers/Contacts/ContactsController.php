<?php

namespace App\Http\Controllers\Contacts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;

class ContactsController extends Controller
{
    public function all()
    {
        return User::orderBy('name', 'asc')->get();
    }

    public function show($id)
    {
        return User::with(
            'profileContact',
            'profilePlace',
            'profileWork.profession',
            'profileWork.position',
            'profileWork.department',
            'profileWork.boss')
            ->find($id);
    }
}
