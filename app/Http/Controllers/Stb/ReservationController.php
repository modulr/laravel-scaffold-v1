<?php

namespace App\Http\Controllers\Stb;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

use App\Models\Stb\Saucer;
use App\Models\Stb\Reservation;

use App\Mail\Reserve;
use App\Mail\Approve;
use App\Mail\Reject;


class ReservationController extends Controller
{
    public function reservations()
    {
        return Reservation::with([
            'saucer',
            'saucer.owner',
            'saucer.images' => function ($query) {
                $query->orderBy('order', 'asc');
            }])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);
    }

    public function reserve($saucerId, $quantity)
    {
        Reservation::create([
            'saucer_id' => $saucerId,
            'user_id' => Auth::id(),
            'quantity' => $quantity
        ]);

        $saucer = Saucer::find($saucerId);

        Mail::to($saucer->owner)->send(new Reserve($saucer, Auth::user()));

        return $saucer;
    }

    public function cancel($reservationId)
    {
        return Reservation::destroy($reservationId);
    }

    public function myReservations()
    {
        $saucers = Saucer::where('owner_id', Auth::id())->pluck('id');

        return Reservation::with([
            'user',
            'saucer',
            'saucer.images' => function ($query) {
                $query->orderBy('order', 'asc');
            }])
            ->whereIn('saucer_id', $saucers)
            ->orderBy('created_at', 'desc')
            ->paginate(20);
    }

    public function approve($reservationId)
    {
        $reservation = Reservation::find($reservationId);
        $reservation->approved = true;
        $reservation->save();

        $saucer = Saucer::find($reservation->saucer_id);

        Mail::to($reservation->user)->send(new Approve($saucer));

        return $reservation;
    }

    public function reject($reservationId)
    {
        $reservation = Reservation::find($reservationId);

        Reservation::destroy($reservationId);

        $saucer = Saucer::find($reservation->saucer_id);

        Mail::to($reservation->user)->send(new Reject($saucer));

        return $reservation;
    }

}
