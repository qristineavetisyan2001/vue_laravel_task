<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskAddRequest;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function addTask(TaskAddRequest $req)
    {
        $newTask = new Task;

        $newTask->title = $req->title;
        $newTask->text = $req->text;
        $newTask->status = "To Do";

        $newTask->save();
        return response()->json($newTask);
    }

    public function getTasks()
    {
        $taskModel = new Task;
        $tasks = Task::all();
        return response()->json([
            "tasks" => $tasks,
            'statusEnum' => $taskModel->getEnumValues('status')
        ]);
    }

    public function deleteTask($id)
    {
        return Task::find($id)->delete();
    }

    public function editTask($id, TaskAddRequest $req){
        $taskToEdit = Task::find($id);

        $taskToEdit->title = $req->title;
        $taskToEdit->text = $req->text;
        $taskToEdit->save();
        return response()->json($taskToEdit);
    }
    public function editTaskStatus($id, Request $req){
        $taskToEdit = Task::find($id);

        $taskToEdit->status = $req->status;

        $taskToEdit->save();
        return response()->json($taskToEdit);
    }
}
