<?php

namespace App\Http\Controllers\Kitchen;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Mail\EventAttend;
use App\Models\Events\Event;
use Illuminate\Support\Facades\Mail;
use App\Models\Events\Reservation;

class ReservationsController extends Controller
{
    public function destroy(Reservation $reservation)
    {
        // Checkear permiso: el event de la reservacion debe pertenecer al cocinero logueado
        $reservation->delete();

        return response()->json(null, 200);
    }
}
