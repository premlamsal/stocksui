<?php

namespace App\Http\Controllers;

use App\Http\Resources\NoteResource;
use App\Note;
use App\User;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;

class NoteController extends Controller
{

    public function __construct()
    {

        $this->middleware('auth:api');
    }

    public function index()
    {

        $this->authorize('hasPermission', 'view_notes');

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;

        return NoteResource::collection(Note::where('store_id', $store_id)->with('user')->paginate(8));
    }

    public function store(Request $request)
    {

        $this->authorize('hasPermission', 'add_note)');

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;

        $this->validate($request, [

            'title'    => 'required|regex:/^[\pL\s\-]+$/u',

            'description' => 'required|string|max:400',


        ]);

        $note = new Note();

        $note->title = $request->input('title');

        $note->description = $request->input('description');
        $note->created_at = Carbon::now();
        $note->updated_at = Carbon::now();
        $note->user_id = $user->id;
        $note->store_id = $store_id;

        $note->store_id = $store_id;

        if ($note->save()) {


            return response()->json([
                'msg' => 'Note added successfully',
                'status' => 'success',
            ]);
        } else {
            return response()->json([

                'msg'    => 'Error while adding note',

                'status' => 'error',
            ]);
        }
    }

    public function update(Request $request)
    {

        $this->authorize('hasPermission', 'edit_note)');

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;

        $this->validate($request, [

            'title'    => 'required|regex:/^[\pL\s\-]+$/u',

            'description' => 'required|string|max:400',


        ]);

        $id = $request->input('id'); //get id from edit modal

        $note = Note::where('id', $id)->where('store_id', $store_id)->first();

        $note->title = $request->input('title');

        $note->description = $request->input('description');


        $note->updated_at = Carbon::now();
        $note->user_id = $user->id;
        $note->store_id = $store_id;

        if ($note->save()) {

            return response()->json([
                'msg' => 'Note updated successfully',
                'status' => 'success',
            ]);
        } else {

            return response()->json([

                'msg'    => 'Error while updating note',
                'status' => 'error',
            ]);
        }
    }


    public function destroy($id)
    {

        $this->authorize('hasPermission', 'delete_note)');

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;

        $note = Note::where('id', $id)->where('store_id', $store_id)->first();
        if ($note->delete()) {
            return response()->json([
                'msg' => 'successfully Deleted',
                'status' => 'success',
            ]);
        } else {
            return response()->json([
                'msg'    => 'Error while deleting data',
                'status' => 'error',
            ]);
        }
    }

    public function show($id)
    {

        $this->authorize('hasPermission', 'show_note');

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;

        $note = Note::where('id', $id)->where('store_id', $store_id)->first();

        if ($note->save()) {
            return response()->json([
                'note' => $note,
                'status' => 'success',
            ]);
        } else {
            return response()->json([
                'msg' => 'Error while retriving Note',
                'status' => 'error',
            ]);
        }
    }

    public function searchNotes(Request $request)
    {

        $this->authorize('hasPermission', 'search_note)');

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;

        $searchKey = $request->input('searchQuery');
        if ($searchKey != '') {
            return NoteResource::collection(Note::where('store_id', $store_id)->where('name', 'like', '%' . $searchKey . '%')->paginate(8));
        } else {
            return response()->json([
                'msg'    => 'Error while retriving Notes. No Data Supplied as key.',
                'status' => 'error',
            ]);
        }
    }
}
