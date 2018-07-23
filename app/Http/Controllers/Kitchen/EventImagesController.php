<?php

namespace App\Http\Controllers\Kitchen;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Upload;
use App\Models\Events\EventImage;
use Illuminate\Support\Facades\DB;

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
        $data = $upload->upload(request('file'), 'events/' . $event->id . '/images')
            ->resize(800, 500)->thumbnail(360, 130)
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

        try {
            DB::beginTransaction();

            foreach (request('images') as $image) {
                $event->images()->findOrFail($image['id'])->update(['order' => $startOrder++]);
            }
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['error' => 'Alguna/s ids no pertenecen al evento ' . $event->id], 422);
        }

        DB::commit();

        return $event->images()->orderBy('order', 'asc')->get();
    }

    public function destroyImage($id)
    {
        return EventImage::destroy($id);
    }
}
