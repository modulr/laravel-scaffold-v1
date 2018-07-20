<?php

namespace App\Http\Controllers\Kitchen;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Upload;
use App\Models\Events\EventImage;

class EventImagesController extends Controller
{


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
}
