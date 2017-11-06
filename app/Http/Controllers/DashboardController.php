<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        return view('dashboard');
    }

    public function markAsRead()
    {
        $user = User::find(Auth::id());

        return $user->unreadNotifications->markAsRead();
    }
}
