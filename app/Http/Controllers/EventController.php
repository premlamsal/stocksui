<?php

namespace App\Http\Controllers;

use App\Event;
use App\Http\Resources\EventResource;
use App\User;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;

class EventController extends Controller
{

    public function __construct()
    {

        $this->middleware('auth:api');
    }

    public function index()
    {

        $this->authorize('hasPermission', 'view_events');

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;

        return EventResource::collection(Event::where('store_id', $store_id)->paginate(8));
    }

    public function store(Request $request)
    {

        $this->authorize('hasPermission', 'add_event)');

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;

        $this->validate($request, [

            'title'    => 'required|regex:/^[\pL\s\-]+$/u',

            'start' => 'required',

            'end' => 'required',

            'description' => 'required',

            'back_color' => 'required',

            // 'text_color' => 'required',



        ]);

        $event = new Event();

        $event->title = $request->input('title');

        $event->start = Carbon::parse( $request->input('start'));

        $event->end = Carbon::parse( $request->input('end'));
        $event->description = $request->input('description');
        $event->back_color = $request->input('back_color');
        $event->text_color = $request->input('text_color');



        $event->store_id = $store_id;

        if ($event->save()) {


            return response()->json([
                'msg' => 'Event added successfully',
                'status' => 'success',
            ]);
        } else {
            return response()->json([

                'msg'    => 'Error while adding event',

                'status' => 'error',
            ]);
        }
    }

    public function update(Request $request)
    {

        $this->authorize('hasPermission', 'edit_event)');

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;

        $this->validate($request, [

            'title'    => 'required|regex:/^[\pL\s\-]+$/u',

            'start' => 'required',

            'end' => 'required',

            'description' => 'required',

            'back_color' => 'required',

            // 'text_color' => 'required',

        ]);

        $id = $request->input('id'); //get id from edit modal

        $event = Event::where('id', $id)->where('store_id', $store_id)->first();

        $event->title = $request->input('title');

        $event->start = Carbon::parse( $request->input('start'));

        $event->end = Carbon::parse( $request->input('end'));
        $event->description = $request->input('description');
        $event->back_color = $request->input('back_color');
        $event->text_color = $request->input('text_color');
        $event->store_id = $store_id;

        if ($event->save()) {

            return response()->json([
                'msg' => 'Event updated successfully',
                'status' => 'success',
            ]);
        } else {

            return response()->json([

                'msg'    => 'Error while updating event',
                'status' => 'error',
            ]);
        }
    }


    public function destroy($id)
    {

        $this->authorize('hasPermission', 'delete_event)');

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;

        $event = Event::where('id', $id)->where('store_id', $store_id)->first();
        if ($event->delete()) {
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

        $this->authorize('hasPermission', 'show_event');

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;

        $event = Event::where('id', $id)->where('store_id', $store_id)->first();

        if ($event->save()) {
            return response()->json([
                'event' => $event,
                'status' => 'success',
            ]);
        } else {
            return response()->json([
                'msg' => 'Error while retriving Event',
                'status' => 'error',
            ]);
        }
    }

    public function searchEvents(Request $request)
    {

        $this->authorize('hasPermission', 'search_event)');

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;

        $searchKey = $request->input('searchQuery');
        if ($searchKey != '') {
            return EventResource::collection(Event::where('store_id', $store_id)->where('name', 'like', '%' . $searchKey . '%')->paginate(8));
        } else {
            return response()->json([
                'msg'    => 'Error while retriving Events. No Data Supplied as key.',
                'status' => 'error',
            ]);
        }
    }
}