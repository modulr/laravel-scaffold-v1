<?php

namespace App\Http\Controllers\Reservations;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Events\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\EventAttend;
use App\Models\Events\Reservation;

class ReservationsController extends Controller
{
    /**
     * Make reservation
     */
    public function store()
    {
        $this->validate(request(), [
            'event_id' => 'required'
        ]);

        $event = Event::findOrFail(request('event_id'));

        $reservation = $event->reserveFor(Auth::user());

        Mail::to($event->owner)->send(new EventAttend($event, Auth::user()));

        return response()->json($reservation, 201);
    }

    public function destroy(Reservation $reservation)
    {
        // Checkear permiso: la reservacion debe pertenecer al comensal logueado
        $reservation->delete();

        return response()->json(null, 200);
    }
}
