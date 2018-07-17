<?php

namespace App\Http\Controllers\Events;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Events\Event;

class PublishedEventsController extends Controller
{
    public function store()
    {
        $event = Event::with('owner', 'images')->find($id);
        $new_status = !$event->enabled;

        $event->enabled = $new_status;
        $event->save();

        return $event;
    }

    public function destroy()
    {
        // Unpublish
    }
}
