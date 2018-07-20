<?php

namespace App\Http\Controllers\Kitchen;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Events\Event;
use Illuminate\Support\Facades\Mail;
use App\Mail\EventAttendApprove;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Models\Events\Reservation;

class ApprovedReservationsController extends Controller
{
    public function store()
    {
        $this->validate(request(), [
            'reservation_id' => 'required'
        ]);

        $reservation = Reservation::findOrFail(request('reservation_id'));
        
        $reservation->approve();

        Mail::to($reservation->user)->send(new EventAttendApprove($reservation->event, Auth::user()));

        return response()->json($reservation, 200);
    }
}
