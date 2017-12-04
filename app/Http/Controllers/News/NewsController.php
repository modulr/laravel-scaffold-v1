<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Http\Helpers\Upload;
use App\User;
use App\Models\News\News;
use App\Models\News\NewsImage;
use App\Notifications\NewsNotification;
//use App\Notifications\NewsLiked;
//use App\Events\StatusLiked;

class NewsController extends Controller
{
    public function all()
    {
        return News::with('creator', 'images')->orderBy('id', 'desc')->paginate(10);
    }

    public function show($id)
    {
        return News::with(['creator', 'images'])->find($id);
    }

    public function store(Request $request)
    {
        if ($request->type == 3) { // video
            $url = parse_url($request->video);
            if ($url['host'] == 'www.youtube.com' || $url['host'] == 'youtube.com') {
                $video = explode('=', $url['query']);
                $request['video'] = 'https://www.youtube.com/embed/' . $video[1];
            } else if ($url['host'] == 'youtu.be') {
                $video = explode('/', $url['path']);
                $request['video'] = 'https://www.youtube.com/embed/' . $video[1];
            } else {
                $request['video'] = 'Invalidate URL';
            }
        }

        $this->validate($request, [
            'name' => 'required_if:type,1|string',
            'type' => 'required|numeric',
            'images' => 'required_if:type,2|array',
            'video' => 'required_if:type,3|url'
        ]);

        $news = News::create([
            'name' => $request->name,
            'video' => $request->video,
            'type' => $request->type,
            'likes' => [],
        ]);

        if ($request->type == 2) { // Image
            if (count($request->images)) {
                foreach ($request->images as $key => $value) {
                    if (isset($value['path'])) {
                        $upload = new Upload();
                        $upload->move($value['path'], 'news/'.$news->id)->resize(1024,800);
                        NewsImage::create([
                            'basename' => $value['basename'],
                            'news_id' => $news->id
                        ]);
                    }
                }
            }
        }

        $news = News::with('creator', 'images')->find($news->id);

        if ($news->type == 1) {
            $message = [
                'title' => 'add a post '.$news->name,
                'url' => '/news/'.$news->id,
                'userName' => $news->creator->name,
                'userAvatarUrl' => $news->creator->avatar_url,
            ];
        } else if ($news->type == 2) {
            $message = [
                'title' => 'add a photo '.$news->name,
                'url' => '/news/'.$news->id,
                'userName' => $news->creator->name,
                'userAvatarUrl' => $news->creator->avatar_url,
                'imageUrl' => $news->images[0]->url,
            ];
        } else {
            $message = [
                'title' => 'add a video '.$news->name,
                'url' => '/news/'.$news->id,
                'userName' => $news->creator->name,
                'userAvatarUrl' => $news->creator->avatar_url,
            ];
        }

        $users = User::where('id', '<>', Auth::id())->get();

        Notification::send($users, new NewsNotification($message));

        // event(new StatusLiked([
        //     'user' => Auth::user(),
        //     'message' => $news
        // ]));

        return $news;
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

        $news->images ;

        if (!$request->like && ($news->created_by != Auth::id())) {
            if ($news->type == 1) {
                $message = [
                    'title' => 'like your post '.$news->name,
                    'url' => '/news/'.$news->id,
                    'userName' => Auth::user()->name,
                    'userAvatarUrl' => Auth::user()->avatar_url,
                ];
            } else if ($news->type == 2) {
                $message = [
                    'title' => 'like your photo '.$news->name,
                    'url' => '/news/'.$news->id,
                    'userName' => Auth::user()->name,
                    'userAvatarUrl' => Auth::user()->avatar_url,
                    'imageUrl' => $news->images[0]->url,
                ];
            } else {
                $message = [
                    'title' => 'like your video '.$news->name,
                    'url' => '/news/'.$news->id,
                    'userName' => Auth::user()->name,
                    'userAvatarUrl' => Auth::user()->avatar_url,
                ];
            }
            $news->creator->notify(new NewsNotification($message));
        }

        return $news;
    }

    public function uploadTemp(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|max:10000',
        ]);
        $upload = new Upload();
        $uploadData = $upload->uploadTemp($request->file)->getData();
        return $uploadData;
    }

}
