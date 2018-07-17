<?php

namespace App\Http\Controllers\Platillos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CocinaPlatillosController extends Controller
{
    public function index()
    {
        return view('events.myevents');
    }

    public function reservaciones($id)
    {
        // vista del cocinero para las reservaciones de un plato
    }
}
