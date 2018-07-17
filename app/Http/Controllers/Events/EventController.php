<?php

namespace App\Http\Controllers\Events;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Events\Event;
use App\Models\Events\EventImage;
use App\Http\Helpers\Upload;
use App\Mail\EventAttend;
use App\Mail\EventAttendApprove;
use App\Mail\EventAttendReject;
use Illuminate\Support\Facades\Mail;
use App\User;


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

    public function show($id)
    {
        return Event::with(['owner', 'attendings', 'images' => function ($query) {
                            $query->orderBy('order', 'asc');
                          }])->find($id);
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
                    $upload->move($value['path'], 'events/'.$event->id.'/images')
                                    ->resize(800,500)->thumbnail(360,130)
                                    ->getData();

                    EventImage::create([
                        'basename' => $value['basename'],
                        'order' => $key,
                        'event_id' => $event->id
                    ]);
                }
            }
        }

        return Event::with(['owner', 'attendings', 'images' => function ($query) {
                            $query->orderBy('order', 'asc');
                        }])->find($event->id);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'place' => 'required|string',
            'price' => 'required|numeric',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'end_time' => 'nullable|after_or_equal:start_time',
        ]);

        $event = Event::with('owner', 'attendings', 'images')->find($id);
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

    public function destroy($id)
    {
        return Event::destroy($id);
    }
}
