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
use App\Notifications\NewsCreated;
use App\Notifications\NewsLiked;
use App\Events\StatusLiked;

class NewsController extends Controller
{
    public function index()
    {
        return view('news.news');
    }

    public function all()
    {
        return News::with('user', 'images')->orderBy('id', 'desc')->paginate(10);
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
            'user_id' => Auth::id(),
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

        $news = News::with('user', 'images')->find($news->id);

        if ($news->type == 1) {
            $message = [
                'title' => 'Add a post '.$news->name,
                'data' => $news,
            ];
        } else if ($news->type == 2) {
            $message = [
                'title' => 'Add a photo '.$news->name,
                'data' => $news,
            ];
        } else {
            $message = [
                'title' => 'Add a video '.$news->name,
                'data' => $news,
            ];
        }

        $users = User::where('id', '<>', Auth::id())->get();

        Notification::send($users, new NewsCreated($news->user, $message));

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

        if (!$request->like && ($news->user_id != Auth::id())) {
            if ($news->type == 1) {
                $message = [
                    'title' => 'Like your post '.$news->name,
                    'data' => $news,
                ];
            } else if ($news->type == 2) {
                $message = [
                    'title' => 'Like your post '.$news->name,
                    'data' => $news,
                ];
            } else {
                $message = [
                    'title' => 'Like your post '.$news->name,
                    'data' => $news,
                ];
            }
            $news->user->notify(new NewsLiked($news->user, $message));
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
