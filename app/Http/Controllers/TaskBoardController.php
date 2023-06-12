<?php

namespace App\Http\Controllers;

use App\TaskBoard;
use Illuminate\Http\Request;

class TaskBoardController extends Controller
{
    public function __construct()
    {

        $this->middleware('auth:api');

    }

    public function updateTasks(Request $request)
    {
        $this->authorize('hasPermission', 'update_task');

        $updatedData = $request->input('data');

        // Perform the necessary database updates using the $updatedData

        $TaskBoard = TaskBoard::findOrFail(1);

        $TaskBoard->tasks = $updatedData;

        if ($TaskBoard->save()) {
            return response()->json(['message' => 'Tasks updated successfully']);
        } else {
            return response()->json(['message' => 'failed updating tasks']);
        }
    }
    public function tasks()
    {

        $this->authorize('hasPermission', 'view_tasks');

        $TaskBoard = TaskBoard::first();

        return response()->json(['data' => $TaskBoard]);
    }
}
