<?php

namespace App\Http\Controllers;

use App\Http\Resources\PickListResource;
use App\PickList;
use App\PickListDetail;
use App\Store;
use App\User;
use Illuminate\Http\Request;
use Auth;

class PickListController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        $this->authorize('hasPermission', 'view_pick_lists');

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;

        return PickListResource::collection(PickList::where('store_id', $store_id)->with('pickListDetail')->orderBy('updated_at', 'desc')->paginate(8));
    }

    public function store(Request $request)
    {
        $pick_list_status_save = false;

        $this->authorize('hasPermission', 'add_pick_list');

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;
        //validation
        $this->validate($request, [

            'info.note' => 'required | string |max:200',
            'info.supplier_name' => 'required | string| max:200',
            'info.supplier_id' => 'required',
            'info.due_date' => 'required | date',
            'info.pick_list_date' => 'required | date',

            'info.pick_list_reference_number' => 'required | string| max:200',


            'items.*.product_name' => 'required | string |max:200',
            'items.*.price' => 'required | numeric',
            'items.*.quantity' => 'required | numeric',

        ]);

        $store = Store::findOrFail($store_id);

        //old pick list id
        $pick_list_id_count = $store->pick_list_id_count;

        //explode pick list id from database

        $custom_pick_list_id = explode('-', $pick_list_id_count);

        $custom_pick_list_id[1] = $custom_pick_list_id[1] + 1; //increase pick list

        //new custom_pick_list_id
        $new_count_pick_list_id = implode('-', $custom_pick_list_id);

        //collecting data
        $items = collect($request->items)->transform(function ($item) {
            $item['line_total'] = $item['quantity'] * $item['price'];
            return new PickListDetail($item);
        });

        if ($items->isEmpty()) {
            return response()
                ->json([
                    'items_empty' => 'One or more Item is required.',
                ], 422);
        }

        $data = $request->info;

        $data['sub_total'] = $items->sum('line_total');

        $data['grand_total'] = $data['sub_total'];

        $data['store_id'] = $store_id;

        $data['pick_list_reference_id'] = $data['supplier_short_name'] . '-' . $data['pick_list_reference_number'];

        $data['custom_pick_list_id'] = $new_count_pick_list_id;

        $pick_list = PickList::create($data);

        $pick_list->pickListDetail()->saveMany($items);

        //for inserting in stock and altering if already has one initialized stock and previous stock
        $items = collect($request->items);

        $countItems = count($items);

        $timeStamp = now();

        $jsonResponse = array();

        $jsonResponse = ['msg' => 'Successfully created pick list', 'status' => 'success'];

        return response()->json($jsonResponse);
    }
    public function show($id)
    {

        $this->authorize('hasPermission', 'show_pick_list');

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;
        // Get pick_list

        $pick_list = PickList::where('store_id', $store_id)->with('pickListDetail')->findOrFail($id);

        return response()
            ->json([
                'pick_list' => $pick_list,

            ]);
    }
    public function update(Request $request)
    {

        $this->authorize('hasPermission', 'edit_pick_list');

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;
        // //validation
        $this->validate($request, [

            'info.due_date' => 'required | date',
            'info.pick_list_date' => 'required | date',

            'items.*.product_name' => 'required | string |max:200',
            'items.*.price' => 'required | numeric',
            'items.*.quantity' => 'required | numeric',

        ]);
        $id = $request->id; //we will get pick list id here

        $pick_list = PickList::where('id', $id)->where('store_id', $store_id)->first();

        $items = collect($request->items)->transform(function ($item) {
            $item['line_total'] = $item['quantity'] * $item['price'];
            return new PickListDetail($item);
        });

        if ($items->isEmpty()) {
            return response()
                ->json([
                    'items_empty' => ['One or more Item is required.'],
                ], 422);
        }

        $store = Store::findOrFail($store_id);


        $data = $request->info;

        $data['store_id'] = $store_id;

        $data['pick_list_reference_id'] = $data['supplier_short_name'] . '-' . $data['pick_list_reference_number'];

        //first get old items
        // Get pick_list
        $pick_list = PickList::where('id', $id)->where('store_id', $store_id)->first();

        //get pick_list details
        $pick_list_detail = PickListDetail::where('pick_list_id', $id)->get();

        $countItems = count($pick_list_detail);

        $check_save_stock = false;

        $pick_list->update($data);

        PickListDetail::where('pick_list_id', $pick_list->id)->delete();

        $pick_list->pickListDetail()->saveMany($items);
        return response()->json(['msg' => 'You have successfully updated the Delivery note.', 'status' => 'success']);
    }

    public function pdfdownload($id)
    {

        $this->authorize('hasPermission', 'show_pick_list');

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;
        // Get pick_list

        $pick_list = PickList::where('store_id', $store_id)->with('pickListDetail')->findOrFail($id);


        // return response()->json([
        //     'msg' => $pick_list,
        //     'status' => 'error',
        // ], 200);

        $pdf = \PDF::loadView('pick_list_pdf', ['pick_list' => $pick_list]);
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        $pdf->setPaper('a4', 'portrait');
        return $pdf->output();
    }
    public function destroy($id)
    {

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;
        // Get Purchase
        $pick_list = PickList::where('id', $id)->where('store_id', $store_id)->first();

        //get pick_list details
        $pick_list_detail = PickListDetail::where('pick_list_id', $id)->get();

        $countItems = count($pick_list_detail);

        // $timeStamp=now();

        if ($pick_list->delete()) {

            return response()->json([
                'msg' => 'successfully Deleted',
                'status' => 'success',
            ]);
        } else {
            return response()->json([
                'msg' => 'Delete Failed',
                'status' => 'error',
            ]);
        }
    }
}
