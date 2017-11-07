<?php

namespace App\Http\Controllers\Events;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Events\Event;

class EventController extends Controller
{
    public function index()
    {
        return view('events.events');
    }

    public function all()
    {
        return Event::with('user')
                      ->orderBy('id', 'desc')
                      ->paginate(20);
    }

    public function byUser()
    {
        return Event::with('user')
                      ->where('user_id', Auth::id())
                      ->orderBy('id', 'desc')
                      ->paginate(20);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
        ]);

        $event = Event::create([
            'name' => $request->name,
            'description' => $request->description,
            'place' => $request->place,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'attendees' => '[]',
            'user_id' => Auth::id(),
        ]);

        return Event::with('user')->find($event->id);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string',
        ]);

        $event = Event::find($id);
        $event->name = $request->name;
        $event->description = $request->description;
        $event->place = $request->place;
        $event->date = $request->date;
        $event->start_time = $request->start_time;
        $event->end_time = $request->end_time;
        $event->save();

        return $event;
    }

    public function destroy($id)
    {
        return Event::destroy($id);
    }
}
