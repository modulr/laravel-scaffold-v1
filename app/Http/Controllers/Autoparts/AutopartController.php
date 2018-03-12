<?php

namespace App\Http\Controllers\Autoparts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Autoparts\Autopart;
use App\Models\Autoparts\AutopartImage;
use App\Models\Autoparts\AutopartYear;
use App\Models\Autoparts\AutopartListMake;
use App\Models\Autoparts\AutopartListModel;
use App\Models\Autoparts\AutopartListOrigin;
use App\Models\Autoparts\AutopartListStatus;
use App\Models\Autoparts\AutopartListYear;
use App\Http\Helpers\Upload;
use QrCode;
use Storage;


class AutopartController extends Controller
{
    public function all()
    {
        return Autopart::with(['make', 'model', 'years', 'origin', 'status', 'images' => function ($query) {
                            $query->orderBy('order', 'asc');
                        }])
                        ->orderBy('id', 'desc')
                        ->paginate(50);
    }

    public function filter(Request $request)
    {
        $query = Autopart::query();

        if ($request->make)
            $query->where('make_id', $request->make['id']);

        if ($request->model)
            $query->where('model_id', $request->model['id']);

        if ($request->years) {
            $query->whereHas('years', function($query) use($request) {
                $query->whereIn('year_id', $request->years);
            });
        }

        $status = [];

        if ($request->available)
            array_push($status, 1);

        if ($request->notAvailable)
            array_push($status, 2);

        if ($request->separated)
            array_push($status, 3);

        if ($request->sold)
            array_push($status, 4);

        if (count($status))
            $query->whereIn('status_id', $status);

        $autoparts = $query->orderBy('id', 'desc')
                        ->paginate(50);

        $autoparts->load(['make', 'model', 'years', 'origin', 'status', 'images' => function ($query) {
                            $query->orderBy('order', 'asc');
                        }]);

        return $autoparts;
    }

    public function show($id)
    {
        return Autopart::with(['make', 'model', 'years', 'origin', 'status', 'creator', 'images' => function ($query) {
                        $query->orderBy('order', 'asc');
                    }])->find($id);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'purchase_price' => 'required|numeric|min:1',
            'sale_price' => 'required|numeric|min:1',
            'make_id' => 'required|integer',
            'model_id' => 'required|integer',
            'origin_id' => 'required|integer',
            'status_id' => 'required|integer',
            'years' => 'required|array'
        ]);

        $autopart = Autopart::create([
            'name' => $request->name,
            'description' => $request->description,
            'purchase_price' => $request->purchase_price,
            'sale_price' => $request->sale_price,
            'make_id' => $request->make_id,
            'model_id' => $request->model_id,
            'origin_id' => $request->origin_id,
            'status_id' => $request->status_id
        ]);

        if (count($request->years)) {
            $yearsIds = [];
            foreach ($request->years as $key => $value) {
                array_push($yearsIds, $value['id']);
            };
            $autopart->years()->attach($yearsIds);
        }

        $qr = QrCode::format('png')->size(200)->margin(1)->generate($autopart->id);
        Storage::put('autoparts/'.$autopart->id.'/qr/'.$autopart->id.'.png', (string) $qr);

        if (count($request->images)) {
            $upload = new Upload();
            foreach ($request->images as $key => $value) {
                if (isset($value['path'])) {
                    $upload->move($value['path'], 'autoparts/'.$autopart->id.'/images')
                            ->resize(800,500)->thumbnail(130,130)
                            ->getData();

                    AutopartImage::create([
                        'basename' => $value['basename'],
                        'order' => $key,
                        'autopart_id' => $autopart->id
                    ]);
                }
            }
        }

        return $this->show($autopart->id);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'purchase_price' => 'required|numeric|min:1',
            'sale_price' => 'required|numeric|min:1',
            'make_id' => 'required|integer',
            'model_id' => 'required|integer',
            'origin_id' => 'required|integer',
            'status_id' => 'required|integer',
            'years' => 'required|array'
        ]);

        $autopart = Autopart::find($id);
        $autopart->name = $request->name;
        $autopart->description = $request->description;
        $autopart->purchase_price = $request->purchase_price;
        $autopart->sale_price = $request->sale_price;
        $autopart->make_id = $request->make_id;
        $autopart->model_id = $request->model_id;
        $autopart->origin_id = $request->origin_id;
        $autopart->status_id = $request->status_id;
        $autopart->save();

        if (count($request->years)) {
            $yearsIds = [];
            foreach ($request->years as $key => $value) {
                array_push($yearsIds, $value['id']);
            };
            $autopart->years()->sync($yearsIds);
        }

        return $this->show($autopart->id);
    }

    public function destroy($id)
    {
        return Autopart::destroy($id);
    }

    // Images
    public function uploadImageTemp(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|max:10000',
        ]);
        $upload = new Upload();
        $uploadData = $upload->uploadTemp($request->file)->getData();
        return $uploadData;
    }

    public function uploadImage(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|max:10000',
        ]);
        $upload = new Upload();
        $data = $upload->upload($request->file, 'autoparts/'.$request->id.'/images')
                        ->resize(800,500)->thumbnail(130,130)
                        ->getData();
        $maxOrder = AutopartImage::where('autopart_id', $request->id)->max('order');
        $maxOrder ++;
        return AutopartImage::create([
            'basename' => $data['basename'],
            'order' => $maxOrder,
            'autopart_id' => $request->id
        ]);
    }

    public function sortImage(Request $request, $autopartId)
    {
        foreach ($request->images as $key => $v) {
            AutopartImage::where('id', $v['id'])
                        ->where('autopart_id', $autopartId)
                        ->update(['order' => $key]);
        }
        return AutopartImage::where('autopart_id', $autopartId)->orderBy('order', 'asc')->get();
    }

    public function destroyImage($id)
    {
        return AutopartImage::destroy($id);
    }

    // Lists
    public function makes()
    {
        return AutopartListMake::orderBy('name')->get();
    }

    public function makesFull()
    {
        return AutopartListMake::with(['models' => function ($query) {
            $query->orderBy('name');
        }])->orderBy('name')->get();
    }

    public function models()
    {
        return AutopartListModel::orderBy('name')->get();
    }

    public function status()
    {
        return AutopartListStatus::get();
    }

    public function origins()
    {
        return AutopartListOrigin::get();
    }

    public function years()
    {
        return AutopartListYear::orderBy('name', 'desc')->get();
    }

    public function storeMake(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string'
        ]);

        $make = AutopartListMake::create([
            'name' => $request->name
        ]);

        return AutopartListMake::with('models')->find($make->id);
    }

    public function destroyMake($id)
    {
        return AutopartListMake::destroy($id);
    }

    public function storeModel(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'makeId' => 'required|integer'
        ]);

        return AutopartListModel::create([
            'name' => $request->name,
            'make_id' => $request->makeId
        ]);
    }

    public function destroyModel($id)
    {
        return AutopartListModel::destroy($id);
    }

    public function storeYear(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|integer'
        ]);

        return AutopartListYear::create([
            'name' => $request->name
        ]);
    }

    public function destroyYear($id)
    {
        return AutopartListYear::destroy($id);
    }
}
