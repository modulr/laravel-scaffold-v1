<?php

namespace App\Http\Controllers\Reservations;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApprovedReservationsController extends Controller
{
    public function store()
    {
        $event = Event::find($eventId);
        $event->attendings()->updateExistingPivot($userId, ['approved' => true]);

        $attends = $event->attendings()->where('user_id', $userId)->get();
        $event->load('owner', 'images','attendings');
        
        Mail::to($attends[0])->send(new EventAttendApprove($event, Auth::user()));

        $_event = $event->toArray();
        $_event['pivot'] = $event->attendings[0]->pivot;
        $_event['attendings'] = count($attends);
        $_event['name'] = $attends[0]->name;
        $_event['email'] = $attends[0]->email;
        $_event['avatar_url'] = $attends[0]->avatar_url;

        $_events = array();



        array_push($_events, (object)$_event);

        return $_events;
    }

    public function destroy()
    {
        $event = Event::find($eventId);
        $event->attendings()->detach($userId);

        $attend = User::findOrFail($userId);

        Mail::to($attend)->send(new EventAttendReject($event, Auth::user()));

        return $event;
    }
}
