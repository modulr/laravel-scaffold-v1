<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Storage;

use App\Attachment;

class AttachmentController extends Controller
{
  public function store(Request $request)
  {
      $this->validate($request, [
          'name' => 'string|max:255',
          'basename' => 'string'
      ]);

      $upload = $this->upload($request->file,$request->quote_id);
      $request->name = $request->file->getClientOriginalName();
      $request->basename = $upload['basename'];
      $request->extension = $upload['extension'];

      $file = Attachment::create([
          'quote_id' => $request->quote_id,
          'route' => $request->basename
      ]);

      return $file;
  }

  private function upload($file, $quote_id)
  {
      $path = $file->store('files/'.Auth::id().'/'.$quote_id);

      $infoFile = pathinfo($path);
      Storage::put('files/'.Auth::id().'/'.$quote_id.'/'.$infoFile['basename'], $file);

      return $infoFile;
  }
}
