<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Task;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        return view('tasks', ['breadcrumb' => $request->path()]);
    }

    public function byUser($id)
    {
        return Task::where('user_id', $id)->orderBy('id', 'desc')->get();
    }
}
