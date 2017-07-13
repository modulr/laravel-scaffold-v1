<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class ContactsController extends Controller
{
    public function index(Request $request)
    {
        return view('contacts', ['breadcrumb' => $request->path()]);
    }

    public function all()
    {
        return User::orderBy('name', 'asc')->get();
    }
}
