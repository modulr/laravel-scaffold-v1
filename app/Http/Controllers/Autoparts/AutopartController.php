<?php

namespace App\Http\Controllers\Autoparts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Autoparts\Autopart;
use App\Models\Autoparts\AutopartImage;
use App\Models\Autoparts\AutopartStatus;
use App\Models\Autoparts\AutopartListMakes;
use App\Models\Autoparts\AutopartListModels;
use App\Models\Autoparts\AutopartListYears;
use App\Http\Helpers\Upload;


class AutopartController extends Controller
{
    public function all()
    {
        return Autopart::with(['make', 'model', 'year', 'status', 'images' => function ($query) {
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

        if ($request->year)
            $query->where('year_id', $request->year['id']);

        $autoparts = $query->orderBy('id', 'desc')
                        ->paginate(50);

        $autoparts->load(['make', 'model', 'year', 'status', 'images' => function ($query) {
                            $query->orderBy('order', 'asc');
                        }]);

        return $autoparts;
    }

    public function show($id)
    {
        return Autopart::with(['make', 'model', 'year', 'status', 'creator', 'images' => function ($query) {
                        $query->orderBy('order', 'asc');
                    }])->find($id);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'purchase_price' => 'required|numeric',
            'sale_price' => 'required|numeric',
            'make_id' => 'required|integer',
            'model_id' => 'required|integer',
            'year_id' => 'required|integer',
            'status_id' => 'required|integer',
        ]);

        $autopart = Autopart::create([
            'name' => $request->name,
            'description' => $request->description,
            'observations' => $request->observations,
            'purchase_price' => $request->purchase_price,
            'sale_price' => $request->sale_price,
            'make_id' => $request->make_id,
            'model_id' => $request->model_id,
            'year_id' => $request->year_id,
            'status_id' => $request->status_id,
        ]);

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
            'purchase_price' => 'required|numeric',
            'sale_price' => 'required|numeric',
            'make_id' => 'required|integer',
            'model_id' => 'required|integer',
            'year_id' => 'required|integer',
            'status_id' => 'required|integer',
        ]);

        $autopart = Autopart::find($id);
        $autopart->name = $request->name;
        $autopart->description = $request->description;
        $autopart->purchase_price = $request->purchase_price;
        $autopart->sale_price = $request->sale_price;
        $autopart->make_id = $request->make_id;
        $autopart->model_id = $request->model_id;
        $autopart->year_id = $request->year_id;
        $autopart->status_id = $request->status_id;
        $autopart->save();

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
    public function status()
    {
        return AutopartStatus::get();
    }

    public function makes()
    {
        return AutopartListMakes::orderBy('order')->get();
    }

    public function makesFull()
    {
        return AutopartListMakes::with(['models' => function ($query) {
            $query->orderBy('order');
        }])->orderBy('order')->get();
    }

    public function models()
    {
        return AutopartListModels::orderBy('order')->get();
    }

    public function years()
    {
        return AutopartListYears::orderBy('order', 'desc')->get();
    }

    public function storeMake(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string'
        ]);

        $maxOrder = AutopartListMakes::max('order') + 1;

        $make = AutopartListMakes::create([
            'name' => $request->name,
            'order' => $maxOrder
        ]);

        return AutopartListMakes::with('models')->find($make->id);
    }

    public function destroyMake($id)
    {
        return AutopartListMakes::destroy($id);
    }

    public function orderMake(Request $request)
    {
        $order = 0;
        foreach ($request->all() as $key => $v) {
            $order++;
            AutopartListMakes::where('id', $v['id'])
                            ->update(['order' => $order]);
        }
        return $order;
    }

    public function storeModel(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'makeId' => 'required|integer'
        ]);

        $maxOrder = AutopartListModels::max('order') + 1;

        return AutopartListModels::create([
            'name' => $request->name,
            'make_id' => $request->makeId,
            'order' => $maxOrder
        ]);
    }

    public function destroyModel($id)
    {
        return AutopartListModels::destroy($id);
    }

    public function orderModel(Request $request)
    {
        $order = 0;
        foreach ($request->all() as $key => $v) {
            $order++;
            AutopartListModels::where('id', $v['id'])
                            ->update(['order' => $order]);
        }
        return $order;
    }

    public function storeYear(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|integer'
        ]);

        $maxOrder = AutopartListYears::max('order') + 1;

        return AutopartListYears::create([
            'name' => $request->name,
            'order' => $maxOrder
        ]);
    }

    public function destroyYear($id)
    {
        return AutopartListYears::destroy($id);
    }

    public function orderYear(Request $request)
    {
        $order = AutopartListYears::count() + 1;
        foreach ($request->all() as $key => $v) {
            $order--;
            AutopartListYears::where('id', $v['id'])
                            ->update(['order' => $order]);
        }

        return $order;
    }
}
