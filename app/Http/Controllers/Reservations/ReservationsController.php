<?php

namespace App\Http\Controllers\Reservations;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Mail\EventAttend;
use App\Models\Events\Event;
use Illuminate\Support\Facades\Mail;

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

        $event = Event::find(request('event_id'));
        $query = $event->users()->attach(Auth::id());

        Mail::to($event->owner)->send(new EventAttend($event, Auth::user()));

        return response()->json(Auth::user()->events()->find($event->id)->pivot, 201);
    }

    public function destroy()
    {
        $this->validate(request(), [
            'event_id' => 'required'
        ]);

        $event = Event::find(request('event_id'));
        $query = $event->users()->detach(Auth::id());

        return response()->json(null, 200);
    }
}
