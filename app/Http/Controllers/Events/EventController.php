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


class EventController extends Controller
{
    public function all()
    {
        return Event::with(['owner', 'images' => function ($query) {
                            $query->orderBy('order', 'asc');
                        }])
                        ->where('enabled', 1)
                        ->orderBy('id', 'desc')
                        ->paginate(20);
    }

    public function byOwner()
    {
        return Event::with(['owner', 'attendings', 'images' => function ($query) {
                            $query->orderBy('order', 'asc');
                    }])
                    ->where('owner_id', Auth::id())
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

        $event = Event::with('owner', 'images')->find($id);
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


    public function uploadImageTemp(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|max:2000000',
        ]);
        $upload = new Upload();
        $uploadData = $upload->uploadTemp($request->file)->getData();
        return $uploadData;
    }

    public function uploadImage(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|max:2000000',
        ]);
        $upload = new Upload();
        $data = $upload->upload($request->file, 'events/'.$request->id.'/images')
                        ->resize(800,500)->thumbnail(360,130)
                        ->getData();

        $maxOrder = EventImage::where('event_id', $request->id)->max('order');
        $maxOrder ++;

        return EventImage::create([
            'basename' => $data['basename'],
            'order' => $maxOrder,
            'event_id' => $request->id
        ]);
    }

    public function sortImage(Request $request, $eventId)
    {
        foreach ($request->images as $key => $v) {
            EventImage::where('id', $v['id'])
                        ->where('event_id', $eventId)
                        ->update(['order' => $key]);
        }

        return EventImage::where('event_id', $eventId)->orderBy('order', 'asc')->get();
    }

    public function destroyImage($id)
    {
        return EventImage::destroy($id);
    }


    public function attend($id)
    {
        $event = Event::find($id);
        $query = $event->attendings()->toggle(Auth::id());

        if(count($query['attached'])>0){
            Mail::to($event->owner)->send(new EventAttend($event, Auth::user()));
        }

        $attendings = $event->attendings;

        return ['attendings' => $attendings, 'attend' => count($query['attached'])>0];
    }

    public function approve($eventId, $userId)
    {
        $event = Event::find($eventId);
        $event->attendings()->updateExistingPivot($userId, ['approved' => true]);

        //Mail::to(Auth::user())->send(new EventAttendApprove($event, Auth::user()));
        $attends = $event->attendings()->where('user_id', $userId)->get();
        $event->load('owner', 'images','attendings');

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

    public function reject($eventId, $userId)
    {
        $event = Event::find($eventId);
        $event->attendings()->detach($userId);

        //Mail::to(Auth::user())->send(new EventAttendReject($event, Auth::user()));

        return $event;
    }

    public function attendings()
    {
        $events = Event::all();

        $events = Event::whereHas("attendings", function($q) {
            $q->where('user_id', Auth::user()->id);
        })->get();

        $events->load('owner', 'images','attendings');

        $_events = array();

        foreach ($events as $event) { 
            $_event = $event->toArray();
            $_event['pivot'] = $event->attendings[0]->pivot;
            $_oEvent = (object)$_event;
          
            array_push($_events, $_oEvent);
        }        
        
        return $_events;
    }

    public function attendingsByOwner()
    {

      $events = Event::with('attendings', 'images')->where('owner_id', Auth::id())->get();
      $attendings = array();
      foreach ($events as $event) {
        $_attendings = $event->attendings;
        //dd($_attendings);
        foreach ($_attendings as $attend) {
          $_attend = $attend->toArray();
          $_attend['name'] = $event->name;
          $_attend['images'] = $event->images;
          $_attend['event_id'] = $event->id;
          $_attend['attending_limit'] = $event->attending_limit;
          $_attend['attendings'] = count($event->attendings);
          $_oAttend = (object)$_attend;
          
          array_push($attendings, $_oAttend);
          //$attendings += $_oAttend;
          //array_push($attendings->attends, $_attend);
          //$attendings->attends[] = $_attend;
        }
      }


      return $attendings;
    }

    public function toogleEnabled($id) {

        $event = Event::with('owner', 'images')->find($id);
        $new_status = !$event->enabled;

        $event->enabled = $new_status;
        $event->save();

        return $event;
    }

}
