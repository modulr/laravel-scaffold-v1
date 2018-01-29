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
                        ->paginate(20);
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

    public function status()
    {
        return AutopartStatus::get();
    }

    public function makes()
    {
        return AutopartListMakes::get();
    }

    public function models()
    {
        return AutopartListModels::get();
    }

    public function years()
    {
        return AutopartListYears::get();
    }
}
