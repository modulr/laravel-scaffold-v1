<?php

namespace App\Http\Controllers\Platillos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Events\Event;

class PlatillosController extends Controller
{
    public function index()
    {
        return view('platillos.index');
    }

    public function show(Event $event)
    {
        return view('platillos.show', ['id' => $event->id]);
    }
}
