<?php

namespace App\Http\Controllers\Platillos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CocinaPlatillosController extends Controller
{
    public function index()
    {
        return view('platillos.cocina.index');
    }

    public function reservaciones($eventId)
    {
        if (! $event = auth()->user()->events()->find($eventId)) {
            return redirect('/platillos');
        }

        return view('platillos.reservations');
    }

    public function menuDelDia()
    {
        return view('platillos.cocina.menu_del_dia');
    }

    public function create()
    {
        return view('platillos.cocina.create');
    }
}
