<?php

namespace App\Http\Controllers\Reservations;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReservationsController extends Controller
{
    /**
     * Make reservation
     */
    public function store(Request $request)
    {
        $event = Event::find($id);
        $query = $event->attendings()->toggle(Auth::id());

        if(count($query['attached'])>0){
            Mail::to($event->owner)->send(new EventAttend($event, Auth::user()));
        }

        $attendings = $event->attendings;

        return ['attendings' => $attendings, 'attend' => count($query['attached'])>0];
    }

}
