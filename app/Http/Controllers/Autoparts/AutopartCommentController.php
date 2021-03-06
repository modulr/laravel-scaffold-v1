<?php

namespace App\Http\Controllers\Autoparts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Autoparts\AutopartComment;

class AutopartCommentController extends Controller
{
    public function __construct()
    {
        $this->relationships = ['creator', 'editor'];
    }

    public function index()
    {
        return AutopartComment::with($this->relationships)->get();
    }

    public function show($id)
    {
        return AutopartComment::with($this->relationships)->find($id);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'comment' => 'required|string',
            'autopart_id' => 'required|numeric'
        ]);

        $comment = AutopartComment::create([
            'comment' => $request->comment,
            'autopart_id' => $request->autopart_id
        ])->load($this->relationships);

        return $comment;
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'comment' => 'required|string',
            'autopart_id' => 'required|numeric'
        ]);

        $comment = AutopartComment::find($id);
        $comment->comment = $request->comment;
        $comment->autopart_id = $request->autopart_id;

        return $comment;
    }

    public function destroy($id)
    {
        return AutopartComment::destroy($id);
    }

}
