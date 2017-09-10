<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Employee;
class EmployeeController extends Controller
{
    public function sellers()
    {
      return Employee::where('role', "1")->get();
    }

    public function designers()
    {
      return Employee::where('role', "0")->get();
    }
}
