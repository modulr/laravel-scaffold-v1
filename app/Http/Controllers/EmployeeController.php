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
        return Employee::with('role')->get();
    }

    public function store(Request $request)
    {
        return Employee::create([
          'name' => $request->name,
          'role' => $request->role
        ])->load('role');
    }

    public function update($id, Request $request)
    {
        $q = Employee::find($id);
        $q->name = $request->name;
        $q->save();
        return $q;
    }

    public function sellers()
    {
      return Employee::where('role', "2")->get();
    }

    public function designers()
    {
      return Employee::where('role', "1")->get();
    }

    public function destroy($id)
    {
        return Employee::destroy($id);
    }
}
