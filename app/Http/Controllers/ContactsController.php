<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class ContactsController extends Controller
{
    public function index()
    {
        return view('contacts');
    }

    public function all()
    {
        return User::orderBy('name', 'asc')->get();
    }
}
