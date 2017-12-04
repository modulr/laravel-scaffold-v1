<?php

namespace App\Http\Controllers\Files;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Helpers\Upload;
use App\Models\Files\File;

class FileController extends Controller
{
    public function index()
    {
        return view('files.files');
    }

    public function byOwner(Request $request, $folderId = null)
    {
        $file = File::with('owner')
            ->orderBy('is_folder', 'desc')
            ->where('owner_id', Auth::id());

        if ($folderId) {
            $file->where('parent_id', $folderId);
        } else {
            $file->where('parent_id', 0);
        }

        return $file->get();
    }

    public function store(Request $request)
    {
        if ($request->is_folder) {
            $request->is_folder = true;
        } else {
            $this->validate($request, [
                'file' => 'required|max:10000',
            ]);
            $upload = new Upload();
            // Is image
            if (is_array(getimagesize($request->file))) {
                $uploadData = $upload->upload($request->file, 'files/'.Auth::id())
                                     ->resize(1024)->thumbnail(200,200)->getData();
            } else {
                $uploadData = $upload->upload($request->file)->getData();
            }
            $request->name = $request->file->getClientOriginalName();
            $request->basename = $uploadData['basename'];
            $request->type = $uploadData['type'];
            $request->size = $uploadData['size'];
            $request->is_folder = false;
        }

        $this->validate($request, [
            'name' => 'string|max:255',
            'description' => 'string',
            'basename' => 'string',
            'type' => 'string',
            'size' => 'float',
            'is_folder' => 'boolean',
            'parent_id' => 'integer',
        ]);

        $file = File::create([
            'name' => $request->name,
            'description' => $request->description,
            'basename' => $request->basename,
            'type' => $request->type,
            'size' => $request->size,
            'is_folder' => $request->is_folder,
            'parent_id' => $request->parent_id,
            'owner_id' => Auth::id(),
        ]);

        // if (count($request->share)) {
        //     $collection = collect($request->share);
        //     $unique = $collection->unique();
        //     $unique->values()->all();
        //
        //     foreach ($unique as $key => $value) {
        //         if (!$file->share->contains($value['id'])) {
        //             $file->share()->attach($value['id']);
        //         }
        //     }
        // }

        return File::with('owner')->find($file->id);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'description' => 'string'
        ]);

        $file = File::find($id);
        $file->name = $request->name;
        $file->description = $request->description;
        $file->save();

        return $file;
    }

    public function destroy($id)
    {
        return File::destroy($id);
    }

    // public function addShare(Request $request, $id)
    // {
    //     $this->validate($request, [
    //         'ownerId' => 'required'
    //     ]);
    //
    //     $file = File::find($id);
    //
    //     if (!$file->share->contains($request->ownerId)) {
    //         $file->share()->attach($request->ownerId);
    //     }
    //
    //     return 'OK';
    // }

    // public function removeShare($id,$shareId)
    // {
    //     $file = File::find($id);
    //     $file->share()->detach($shareId);
    //     return 'OK';
    // }

    public function favorite(Request $request, $id)
    {
        $file = File::find($id);
        $file->favorite = !$file->favorite;
        $file->save();

        return $file;
    }
}
