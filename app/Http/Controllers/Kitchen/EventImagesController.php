<?php

namespace App\Http\Controllers\Kitchen;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Upload;
use App\Models\Events\EventImage;

class EventImagesController extends Controller
{
    public function uploadImageTemp($eventId)
    {
        $event = auth()->user()->ownEvents()->findOrFail($eventId);

        $this->validate(request(), [
            'file' => 'required|max:2000000',
        ]);

        $upload = new Upload();
        $uploadData = $upload->uploadTemp(request('file'))->getData();

        return response()->json($uploadData, 201);
    }

    public function uploadImage($eventId)
    {
        $event = auth()->user()->ownEvents()->findOrFail($eventId);

        $this->validate(request(), [
            'file' => 'required|max:2000000',
        ]);
        
        $upload = new Upload();
        $data = $upload->upload(request('file'), 'events/'.$event->id.'/images')
            ->resize(800,500)->thumbnail(360,130)
            ->getData();

        $orderPosition = (int) $event->images()->max('order') + 1;

        $eventImage = $event->images()->create([
            'basename' => $data['basename'],
            'order' => $orderPosition,
        ]);

        return response()->json($eventImage, 201);
    }

    public function sortImage($eventId)
    {
        $event = auth()->user()->ownEvents()->findOrFail($eventId);

        $this->validate(request(), [
            'images' => 'required|array',
        ]);

        $startOrder = 1;
        foreach (request('images') as $image) {
            $event->images()->where('id', $image['id'])->update(['order' => $startOrder++]);
        }

        return $event->images()->orderBy('order', 'asc')->get();
    }

    public function destroyImage($id)
    {
        return EventImage::destroy($id);
    }
}
