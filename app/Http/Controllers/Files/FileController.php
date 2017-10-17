<?php

namespace App\Http\Controllers\Files;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Storage;
use Image;
use App\Models\Files\File;
use App\FileShare;

class FileController extends Controller
{
    public function view(Request $request)
    {
        return view('files.files');
    }

    public function byUser(Request $request, $folderId = null)
    {
        $query = File::with('user')
            ->orderBy('type', 'desc')
            ->where('user_id', Auth::id());

        if ($folderId) {
            $query->where('parent_id', $folderId);
        } else {
            $query->where('parent_id', 0);
        }

        return $query->get();
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'string|max:255',
            'basename' => 'string',
            'description' => 'string',
            'favorite' => 'boolean',
            'parent_id' => 'integer',
            'type' => 'required|integer',
        ]);

        if ($request->type == 1) { // File
            $upload = $this->upload($request->file);
            $request->name = $request->file->getClientOriginalName();
            $request->basename = $upload['basename'];
            $request->extension = $upload['extension'];
        }

        $file = File::create([
            'name' => $request->name,
            'basename' => $request->basename,
            'extension' => $request->extension,
            'description' => $request->description,
            'user_id' => Auth::id(),
            'parent_id' => $request->parent_id,
            'type' => $request->type,
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

        return File::with('user')->find($file->id);
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

    public function addShare(Request $request, $id)
    {
        $this->validate($request, [
            'userId' => 'required'
        ]);

        $file = File::find($id);

        if (!$file->share->contains($request->userId)) {
            $file->share()->attach($request->userId);
        }

        return 'OK';
    }

    public function removeShare($id,$shareId)
    {
        $file = File::find($id);
        $file->share()->detach($shareId);
        return 'OK';
    }

    public function favorite(Request $request, $id)
    {
        $file = File::find($id);
        $file->favorite = !$file->favorite;
        $file->save();

        return $file;
    }

    private function upload($file)
    {
        $path = $file->store('files/'.Auth::id());

        $infoFile = pathinfo($path);

        if (in_array($infoFile['extension'], ['jpg', 'jpeg', 'png'])) {
            $img = Image::make($file)
                ->widen(1024)
                ->encode($infoFile['extension']);

            Storage::put('files/'.Auth::id().'/'.$infoFile['basename'], $img);
        }

        return $infoFile;
    }
}
