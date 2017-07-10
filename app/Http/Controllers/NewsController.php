<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Validator;
use Storage;
use Image;
use App\News;
use App\NewsImage;

class NewsController extends Controller
{
    public function index()
    {
        return News::with('user', 'images')->orderBy('id', 'desc')->paginate(10);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required_if:type,1|string',
            'type' => 'required|numeric',
            'images' => 'required_if:type,2|array',
            'video' => 'required_if:type,3|url'
        ]);

        if ($request->type == 3) { // video
            $video = explode('=', $request->video);
            $video = explode('&', $video[1]);
            $request->video = 'https://www.youtube.com/embed/' . $video[0];
        }

        $news = News::create([
            'title' => $request->title,
            'video' => $request->video,
            'type' => $request->type,
            'user_id' => Auth::id(),
            'likes' => [],
        ]);

        if ($request->type == 2) { // Image
            if (count($request->images)) {
                foreach ($request->images as $key => $value) {
                    $this->moveFromTemp($value['infoFile'], $news->id);
                    NewsImage::create([
                        'basename' => $value['infoFile']['basename'],
                        'extension' => $value['infoFile']['extension'],
                        'news_id' => $news->id
                    ]);
                }
            }
        }

        return News::with('user', 'images')->find($news->id);
    }

    public function destroy($id)
    {
        return News::destroy($id);
    }

    public function like(Request $request, $id)
    {
        $this->validate($request, [
            'like' => 'required|boolean',
        ]);

        $news = News::find($id);
        $likes = $news->likes;

        if ($request->like) {
            if (($key = array_search(Auth::id(), $likes)) !== false) {
                unset($likes[$key]);
            }
        } else {
            array_push($likes, Auth::id());
        }

        $news->likes = $likes;
        $news->save();

        return $news;
    }

    public function uploadTemp(Request $request)
    {
        $pathFile = $request->file->store('news/temp/'.Auth::id());
        $infoFile = pathinfo($pathFile);
        return ['pathFile' => $pathFile, 'infoFile' => $infoFile];
    }

    public function moveFromTemp($infoFile, $newsId)
    {
        Storage::move(
            $infoFile['dirname'].'/'.$infoFile['basename'],
            'news/'.$newsId.'/'.$infoFile['basename']
        );

        $this->resize($newsId, $infoFile);
    }

    private function resize($newsId, $infoFile)
    {
        $file = Storage::get('news/'.$newsId.'/'.$infoFile['basename']);

        $img = Image::make($file)
            ->widen(1024)
            ->encode($infoFile['extension']);

        Storage::put('news/'.$newsId.'/'.$infoFile['basename'], $img);

    }

}
