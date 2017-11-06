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
            'title' => 'required|string',
        ]);

        $q = Event::create([
            'title' => $request->title,
            'description' => $request->description,
            'place' => $request->place,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'attendees' => '[]',
            'user_id' => Auth::id(),
        ]);

        return Event::with('user')->find($q->id);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required|string',
        ]);

        $q = Event::find($id);
        $q->title = $request->title;
        $q->description = $request->description;
        $q->place = $request->place;
        $q->date = $request->date;
        $q->start_time = $request->start_time;
        $q->end_time = $request->end_time;
        $q->save();

        return $q;
    }

    public function destroy($id)
    {
        return Event::destroy($id);
    }
}
