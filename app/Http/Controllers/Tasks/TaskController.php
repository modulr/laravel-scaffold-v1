<?php

namespace App\Http\Controllers\Tasks;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tasks\Task;

class TaskController extends Controller
{

    public function view(Request $request)
    {
        return view('tasks.tasks', ['breadcrumb' => $request->path()]);
    }

    public function byUser()
    {
        return Task::where('user_id', Auth::id())->orderBy('order', 'asc')->get();
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string',
        ]);

      $maxOrder = Task::where('user_id', Auth::id())->max('order');
      $maxOrder ++;

      $task = Task::create([
          'title' => $request->title,
          'user_id' => Auth::id(),
          'order'=>$maxOrder
      ]);

      return Task::with('user')->find($task->id);
    }

    public function destroy($id)
    {
      return Task::destroy($id);
    }

    public function updateOrder(Request $request)
    {
        info($request->tasks);
        $x=0;
        foreach ($request->tasks as $key => $v) {
            $x++;
            info($v['id']);
            info($x);
            Task::where('id', $v['id'])
                 ->update(['order' => $x]);
        }
        return Task::orderBy('order', 'asc')->get();
    }

    public function markDone(Request $request, $id)
    {
        $this->validate($request, [
            'done' => 'required|boolean',
        ]);

        return Task::where('id', $id)
             ->update(['done' => $request->done]);
    }
}
