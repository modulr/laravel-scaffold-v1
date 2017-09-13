<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Customer;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        return view('customers.customers', ['breadcrumb' => $request->path()]);
    }

    public function all()
    {
        return Customer::with('contact')->get();
    }
}
