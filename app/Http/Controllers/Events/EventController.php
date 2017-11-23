<?php

namespace App\Http\Controllers\Events;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Events\Event;
use App\Models\Events\EventAttachment;
use App\Http\Helpers\Upload;


class EventController extends Controller
{
    public function index()
    {
        return view('events.events');
    }

    public function all()
    {
        $events = Event::with(['user', 'attachments' => function ($query) {
                            $query->where('cover', true);
                        }])
                        ->orderBy('id', 'desc')
                        ->paginate(20);
        return $events;
    }

    public function byUser()
    {
        return Event::with('user', 'attachments')
                    ->where('user_id', Auth::id())
                    ->orderBy('id', 'desc')
                    ->paginate(20);
    }

    public function show($id)
    {
        return Event::with('user', 'attachments')->find($id);
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

        if (count($request->attachments)) {
            $upload = new Upload();
            foreach ($request->attachments as $key => $value) {
                $data = $upload->move($value['path'], 'events/'.$event->id.'/attachments')
                                ->resize(1024)->thumbnail(360,130)
                                ->getData();

                EventAttachment::create([
                    'basename' => $value['basename'],
                    'name' => $value['name'],
                    'type' => $value['type'],
                    'size' => $data['size'],
                    'cover' => $value['cover'],
                    'event_id' => $event->id
                ]);
            }
        }

        return Event::with(['user', 'attachments' => function ($query) {
                        $query->where('cover', true);
                    }])->find($event->id);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string',
        ]);

        $event = Event::with('user')->find($id);
        $event->name = $request->name;
        $event->description = $request->description;
        $event->place = $request->place;
        $event->date = $request->date;
        $event->start_time = $request->start_time;
        $event->end_time = $request->end_time;
        $event->save();

        $event->attachments = $event->attachments()->where('cover', true)->get();

        return $event;
    }

    public function destroy($id)
    {
        return Event::destroy($id);
    }


    public function destroyAttachment($id)
    {
        return EventAttachment::destroy($id);
    }

    public function makeCoverAttachment(Request $request, $id)
    {
        $this->validate($request, [
            'eventId' => 'required|integer',
            'cover' => 'required|boolean',
        ]);

        EventAttachment::where('event_id', $request->eventId)
                        ->where('cover', true)
                        ->update(['cover' => false]);

        $event = EventAttachment::find($id);
        $event->cover = $request->cover;
        $event->save();

        return $event;
    }

    public function uploadAttachmentTemp(Request $request)
    {
        $upload = new Upload();
        $uploadData = $upload->uploadTemp($request->file)->getData();
        return $uploadData;
    }

    public function uploadAttachment(Request $request)
    {
        $upload = new Upload();
        $data = $upload->upload($request->file, 'events/'.$request->id.'/attachments')
                        ->resize(1024)->thumbnail(360,130)
                        ->getData();

        return EventAttachment::create([
            'basename' => $data['basename'],
            'name' => $data['name'],
            'type' => $data['type'],
            'size' => $data['size'],
            'event_id' => $request->id
        ]);
    }
}
