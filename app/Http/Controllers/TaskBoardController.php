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

        $TaskBoard = TaskBoard::where('id', 1)->first();

        $checkTaskAvailable=false;

        if ($TaskBoard !== null) {
           $checkTaskAvailable=true;
        } else {

            $TaskBoard = new TaskBoard();
            $TaskBoard->id = 1;
            $TaskBoard->tasks = "";
            if ($TaskBoard->save()) {
                $checkTaskAvailable = true;
            } else {
                $checkTaskAvailable = false;
            }
        }
        if ($checkTaskAvailable) {
            $TaskBoard->tasks = $updatedData;

            if ($TaskBoard->save()) {
                return response()->json(['message' => 'Tasks Successfully Updated!']);
            } else {
                return response()->json(['message' => 'Failed updating Tasks']);
            }
        } else {
            return response()->json(['message' => 'failed updating tasks. Since no tasks available']);
        }
    }
    public function tasks()
    {

        $this->authorize('hasPermission', 'view_tasks');

        $TaskBoard = TaskBoard::where('id',1)->first();

        return response()->json(['data' => $TaskBoard]);
    }
}
