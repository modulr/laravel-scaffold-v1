<?php

namespace App\Http\Controllers\Kitchen;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Events\Event;

class PublishedEventsController extends Controller
{
    public function store()
    {
        $this->validate(request(), [
            'event_id' => 'required'
        ]);

        $event = Event::with('owner', 'images')->findOrFail(request('event_id'));

        $event->update([
            'enabled' => true
        ]);

        return $event;
    }

    public function destroy()
    {
        $this->validate(request(), [
            'event_id' => 'required'
        ]);

        $event = Event::with('owner', 'images')->findOrFail(request('event_id'));

        $event->update([
            'enabled' => false
        ]);

        return $event;
    }
}
