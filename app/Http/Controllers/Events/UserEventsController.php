<?php

namespace App\Http\Controllers\Events;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Events\Event;
use Illuminate\Support\Facades\Auth;
use App\Http\Helpers\Upload;
use App\Models\Events\EventImage;

class UserEventsController extends Controller
{
    public function index(Request $request)
    {
        return Event::with(['owner', 'attendings', 'images' => function ($query) {
            $query->orderBy('order', 'asc');
        }])
            ->where('owner_id', Auth::id())
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

        return Event::with(['owner', 'attendings', 'images' => function ($query) {
            $query->orderBy('order', 'asc');
        }])->find($event->id);
    }
}
