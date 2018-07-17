<?php

namespace App\Http\Controllers\Platillos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PlatillosController extends Controller
{
    public function index()
    {
        return view('events.events');
    }

    public function show($id)
    {
        return view('events.event', ['id' => $id]);
    }
}
