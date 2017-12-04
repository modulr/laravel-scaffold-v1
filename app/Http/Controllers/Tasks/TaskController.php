<?php

namespace App\Http\Controllers\Tasks;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tasks\Task;

class TaskController extends Controller
{
    public function byCreator()
    {
        return Task::where('created_by', Auth::id())->orderBy('order', 'asc')->get();
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
        ]);

        $maxOrder = Task::where('created_by', Auth::id())->max('order');
        $maxOrder ++;

        $task = Task::create([
            'name' => $request->name,
            'order'=>$maxOrder
        ]);

      return Task::with('creator')->find($task->id);
    }

    public function destroy($id)
    {
        return Task::destroy($id);
    }

    public function markDone(Request $request, $id)
    {
        $this->validate($request, [
            'done' => 'required|boolean',
        ]);

        return Task::where('id', $id)
                     ->update(['done' => $request->done]);
    }

    public function updateOrder(Request $request)
    {
        $x=0;
        foreach ($request->tasks as $key => $v) {
            $x++;
            Task::where('id', $v['id'])
                  ->update(['order' => $x]);
        }

        return $request->tasks;
    }

}
