<?php

namespace App\Http\Controllers\Stb;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Helpers\Upload;

use App\Models\Stb\Saucer;
use App\Models\Stb\Reservation;
use App\Models\Stb\SaucerImage;


class SaucerController extends Controller
{
    public function active()
    {
        return Saucer::with([
            'owner',
            'reservations',
            'images' => function ($query) {
                $query->orderBy('order', 'asc');
            }])
            ->where('enabled', 1)
            ->orderBy('id', 'desc')
            ->paginate(20);
    }

    public function byOwner()
    {
        return Saucer::with([
            'owner',
            'reservations',
            'images' => function ($query) {
                    $query->orderBy('order', 'asc');
            }])
            ->where('owner_id', Auth::id())
            ->orderBy('id', 'desc')
            ->paginate(20);
    }

    public function show($id)
    {
        return Saucer::with([
            'owner',
            'reservations.user',
            'images' => function ($query) {
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

        $saucer = Saucer::create([
            'name' => $request->name,
            'description' => $request->description,
            'place' => $request->place,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'content' => $request->content,
            'price' => $request->price,
            'limit' => $request->limit,
            'owner_id' => Auth::id(),
        ]);

        if (count($request->images)) {
            $upload = new Upload();
            foreach ($request->images as $key => $value) {
                if (isset($value['path'])) {
                    $upload->move($value['path'], 'saucers/'.$saucer->id.'/images')
                                    ->resize(800,500)->thumbnail(360,130)
                                    ->getData();

                    SaucerImage::create([
                        'basename' => $value['basename'],
                        'order' => $key,
                        'saucer_id' => $saucer->id
                    ]);
                }
            }
        }

        return Saucer::with([
            'owner',
            'reservations',
            'images' => function ($query) {
                $query->orderBy('order', 'asc');
            }])->find($saucer->id);
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

        $saucer = Saucer::with('owner', 'reservations', 'images')->find($id);
        $saucer->name = $request->name;
        $saucer->description = $request->description;
        $saucer->place = $request->place;
        $saucer->date = $request->date;
        $saucer->start_time = $request->start_time;
        $saucer->end_time = $request->end_time;
        $saucer->content = $request->content;
        $saucer->price = $request->price;
        $saucer->limit = $request->limit;
        $saucer->save();

        return $saucer;
    }

    public function destroy($id)
    {
        return Saucer::destroy($id);
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
        $data = $upload->upload($request->file, 'saucers/'.$request->id.'/images')
                        ->resize(800,500)->thumbnail(360,130)
                        ->getData();

        $maxOrder = SaucerImage::where('saucer_id', $request->id)->max('order');
        $maxOrder ++;

        return SaucerImage::create([
            'basename' => $data['basename'],
            'order' => $maxOrder,
            'saucer_id' => $request->id
        ]);
    }

    public function sortImage(Request $request, $saucerId)
    {
        foreach ($request->images as $key => $v) {
            SaucerImage::where('id', $v['id'])
                        ->where('saucer_id', $saucerId)
                        ->update(['order' => $key]);
        }

        return SaucerImage::where('saucer_id', $saucerId)->orderBy('order', 'asc')->get();
    }

    public function destroyImage($id)
    {
        return SaucerImage::destroy($id);
    }


    public function toogleEnabled($id) {

        $saucer = Saucer::with('owner', 'images')->find($id);
        $saucer->enabled = !$saucer->enabled;
        $saucer->save();

        return $saucer;
    }

}
