<?php

namespace App\Http\Controllers;

use App\Models\Tasks;
use Illuminate\Http\Request;

class TasksController extends Controller
{

    public function index()
    {
        return response()->json(Tasks::all(), 200);

    }

    public function store()
    {
        $data = request()->all();

        $task = Tasks::create($data);


        return response()->json($task, 201);
    }

    public function completedTask(Tasks $task)
    {
        if (\request()->get('status') == true) {
            $task->status = true;
            if ($task->save()) {
                return response()->json($task, 201);
            }
        }
    }

    public function destroy(Tasks $id)
    {
        if ($id->delete()) {
            return response()->json([], 201);
        }
    }
}
