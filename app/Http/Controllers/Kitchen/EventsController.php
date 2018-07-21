<?php

namespace App\Http\Controllers\Kitchen;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Events\Event;
use Illuminate\Support\Facades\Auth;
use App\Http\Helpers\Upload;
use App\Models\Events\EventImage;

class EventsController extends Controller
{
    public function index()
    {
        return Event::with(['owner', 'images' => function ($query) {
            $query->orderBy('order', 'asc');
        }])
            ->where('owner_id', Auth::id())
            ->orderBy('id', 'desc')
            ->paginate(20);
    }

    public function reserved()
    {
        return Event::has('reservations')->with(['owner', 'images' => function ($query) {
            $query->orderBy('order', 'asc');
        }])
            ->where('owner_id', Auth::id())
            ->where('enabled', 1)
            ->orderBy('id', 'desc')
            ->paginate(20);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'place' => 'required|string',
            'price' => 'required|numeric',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'end_time' => 'after_or_equal:start_time',
        ]);

        $event = Event::create([
            'name' => $request->name,
            'description' => $request->description,
            'place' => $request->place,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'content' => $request->content,
            'price' => $request->price,
            'attending_limit' => $request->attending_limit,
            'owner_id' => Auth::id(),
        ]);

        if (count($request->images)) {
            $upload = new Upload();
            foreach ($request->images as $key => $value) {
                if (isset($value['path'])) {
                    $upload->move($value['path'], 'events/' . $event->id . '/images')
                                    ->resize(800, 500)->thumbnail(360, 130)
                                    ->getData();

                    EventImage::create([
                        'basename' => $value['basename'],
                        'order' => $key,
                        'event_id' => $event->id
                    ]);
                }
            }
        }

        $event = $event->load(['owner', 'reservations.user', 'images' => function ($query) {
            $query->orderBy('order', 'asc');
        }]);
        
        return response()->json($event, 201);
    }

    public function update(Request $request, $id)
    {
        $event = auth()->user()->ownEvents()->findOrFail($id);

        $this->validate($request, [
            'name' => 'required|string',
            'place' => 'required|string',
            'price' => 'required|numeric',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'end_time' => 'nullable|after_or_equal:start_time',
        ]);

        $event = $event->load('owner', 'reservations.user', 'images');
        $event->name = $request->name;
        $event->description = $request->description;
        $event->place = $request->place;
        $event->date = $request->date;
        $event->start_time = $request->start_time;
        $event->end_time = $request->end_time;
        $event->content = $request->content;
        $event->price = $request->price;
        $event->attending_limit = $request->attending_limit;
        $event->save();

        return $event;
    }

    public function destroy($eventId)
    {
        $event = auth()->user()->ownEvents()->findOrFail($eventId);

        $event->delete();

        return response()->json(null, 204);
    }
}
