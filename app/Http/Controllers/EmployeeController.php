<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Employee;

class EmployeeController extends Controller
{

    public function index(Request $request)
    {
        return view('employees.employees', ['breadcrumb' => $request->path()]);
    }

    public function all(Request $request)
    {
        return Employee::all();
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required',
        ]);

        $employee = Employee::create([
          'name' => $request->name,
          'email' => $request->email,
          'phone' => $request->phone,
        ]);

        return $employee;
    }

    public function update($id, Request $request)
    {
        $q = Employee::find($id);
        $q->name = $request->name;
        $q->email = $request->email;
        $q->phone = $request->phone;
        $q->save();
        return $q;
    }

    public function destroy($id)
    {
        return Employee::destroy($id);
    }
}
