<?php

namespace App\Http\Controllers\Events;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Events\Event;

class EventController extends Controller
{
    public function index()
    {
        return Event::with(['owner', 'images' => function ($query) {
            $query->orderBy('order', 'asc');
        }])
                        ->where('enabled', 1)
                        ->orderBy('id', 'desc')
                        ->paginate(20);
    }

    public function reserved()
    {
        return Event::whereHas('reservations', function ($query) {
            return $query->where('user_id', Auth::id());
        })->with(['owner', 'images' => function ($query) {
            $query->orderBy('order', 'asc');
        }])
            ->where('enabled', 1)
            ->orderBy('id', 'desc')
            ->paginate(20);
    }

    public function show(Event $event)
    {
        return $event->load(['owner', 'reservations', 'images' => function ($query) {
            $query->orderBy('order', 'asc');
        }]);
    }
}
