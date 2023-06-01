<?php

namespace App\Http\Controllers;

use App\DeliveryNote;
use App\DeliveryNoteDetail;
use Illuminate\Http\Request;
use Auth;
use App\Http\Resources\DeliveryNoteResource as DeliveryNoteResource;
use App\Stock;
use App\Store;
use App\Supplier;
use App\User;

class DeliveryNoteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        $this->authorize('hasPermission', 'view_delivery_notes');

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;

        return DeliveryNoteResource::collection(DeliveryNote::where('store_id', $store_id)->with('deliveryNoteDetail')->orderBy('updated_at', 'desc')->paginate(8));
    }

    public function store(Request $request)
    {
        $delivery_note_detail_status_save = false;

        $this->authorize('hasPermission', 'add_delivery_notes');

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;
        //validation
        $this->validate($request, [

            'info.note' => 'required | string |max:200',
            'info.supplier_name' => 'required | string| max:200',
            'info.supplier_id' => 'required',
            'info.due_date' => 'required | date',
            'info.delivery_note_detail_date' => 'required | date',

            'info.delivery_note_detail_reference_number' => 'required | string| max:200',


            'items.*.product_name' => 'required | string |max:200',
            'items.*.price' => 'required | numeric',
            'items.*.quantity' => 'required | numeric',

        ]);

        $store = Store::findOrFail($store_id);

        //old invoice id
        $delivery_note_detail_id_count = $store->delivery_note_detail_id_count;

        //explode invoice id from database

        $custom_delivery_note_detail_id = explode('-', $delivery_note_detail_id_count);

        $custom_delivery_note_detail_id[1] = $custom_delivery_note_detail_id[1] + 1; //increase delivery_note_detail

        //new custom_delivery_note_detail_id
        $new_count_delivery_note_detail_id = implode('-', $custom_delivery_note_detail_id);

        //collecting data
        $items = collect($request->items)->transform(function ($item) {
            $item['line_total'] = $item['quantity'] * $item['price'];
            return new DeliveryNoteDetail($item);
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

        $data['delivery_note_detail_reference_id'] = $data['supplier_short_name'] . '-' . $data['delivery_note_detail_reference_number'];

        $data['custom_delivery_note_detail_id'] = $new_count_delivery_note_detail_id;

        $delivery_note_detail = DeliveryNote::create($data);

        $delivery_note_detail->deliveryNoteDetail()->saveMany($items);

        //for inserting in stock and altering if already has one initialized stock and previous stock
        $items = collect($request->items);

        $countItems = count($items);

        $timeStamp = now();

        $jsonResponse = array();

        for ($i = 0; $i < $countItems; $i++) {

            $p_id = $items[$i]['product_id'];

            $stock = Stock::where('store_id', $store_id)->where('product_id', $p_id);

            //retirving current product-> stock quantity
            $in_stock_quantity = $stock->value('quantity');

            //get stock id
            $stock_id = $stock->value('id');

            $stock_price_old = $stock->value('price');

            //adding current stock with new delivery_note_detaild product quantity
            $new_stock_quantity = $in_stock_quantity + $items[$i]['quantity'];

            //found product on stock
            if ($stock_id != 0) {
               $stock = Stock::findOrFail($stock_id);

                $stock->quantity = $new_stock_quantity;

                $stock->price = $items[$i]['price'];

                $stock->updated_at = $timeStamp;

                $stock->updated_at = $timeStamp;

                if ($stock->save()) {
                    //set current delivery_note_detail_id_count to store table
                    $store->delivery_note_detail_id_count = $new_count_delivery_note_detail_id;
                    if ($store->save()) {

                        $delivery_note_detail_status_save = true;
                    } else {
                        $jsonResponse = ['msg' => 'Failed updating the Data to the store.', 'status' => 'error3'];
                    }
                } else {

                    $jsonResponse = ['msg' => 'Failed Saving the Data to the Stock.', 'status' => 'error3'];
                }
            } else {

                //couldn't find the product on stock
                $jsonResponse = ['msg' => 'couldn\'t find the product on stock', 'status' => 'error3'];
            }
        }


        return response()->json($jsonResponse);
    }
    public function update(Request $request)
    {

        $this->authorize('hasPermission', 'edit_delivery_note_detail');

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;
        // //validation
        $this->validate($request, [

            'info.note' => 'required | string |max:200',
            'info.supplier_name' => 'required | string| max:200',
            'info.supplier_id' => 'required',
            'info.due_date' => 'required | date',
            'info.delivery_note_detail_date' => 'required | date',

            'items.*.product_name' => 'required | string |max:200',
            'items.*.price' => 'required | numeric',
            'items.*.quantity' => 'required | numeric',

        ]);
        $id = $request->id; //we will get delivery_note_detail id here

        $delivery_note_detail = DeliveryNote::where('id', $id)->where('store_id', $store_id)->first();

        $items = collect($request->items)->transform(function ($item) {
            $item['line_total'] = $item['quantity'] * $item['price'];
            return new DeliveryNoteDetail($item);
        });

        if ($items->isEmpty()) {
            return response()
                ->json([
                    'items_empty' => ['One or more Item is required.'],
                ], 422);
        }

        $store = Store::findOrFail($store_id);


        $data = $request->info;

        $data['sub_total'] = $items->sum('line_total');
        $data['grand_total'] = $data['sub_total'];
        $data['store_id'] = $store_id;

        //first get old items
        // Get DeliveryNote
        $DeliveryNote = DeliveryNote::where('id', $id)->where('store_id', $store_id)->first();

        //get delivery_note_detail details
        $deliveryNoteDetail = DeliveryNoteDetail::where('delivery_note_id', $id)->get();

        $countItems = count($deliveryNoteDetail);

        $check_save_stock = false;

        // $timeStamp=now();
        if ($countItems != 0) {

            for ($i = 0; $i < $countItems; $i++) {
                //get product id from each delivery_note_detail details
                $p_id = $deliveryNoteDetail[$i]['product_id'];

                $old_delivery_note_detail_detail_qty = $deliveryNoteDetail[$i]['quantity'];

                //finding stock to decrease the quantity of this delivery_note_detail
                $stock = Stock::where('product_id', $p_id)->where('store_id', $store_id);

                $stock_id = $stock->value('id');

                $stock_qty = $stock->value('quantity');

                $old_stock_qty = $stock_qty - $old_delivery_note_detail_detail_qty;

                $stock = Stock::where('id', $stock_id)->where('store_id', $store_id)->first();

                $stock->quantity = $old_stock_qty + $items[$i]['quantity'];

                if ($stock->save()) {
                    $check_save_stock = true;
                } else {
                    $check_save_stock = false;
                }
            }
            if ($check_save_stock) {

                $delivery_note_detail->update($data);

                DeliveryNoteDetail::where('delivery_note_id', $delivery_note_detail->id)->delete();

                $delivery_note_detail->deliveryNoteDetail()->saveMany($items);
                return response()->json(['msg' => 'You have successfully updated the DeliveryNote.', 'status' => 'success']);
            } else {
                //saving stock fails
                return response()->json(['msg' => 'Initial update to stock failed.', 'status' => 'error'], 500);
            }

            // check stock save status and do following

        } else {

            return response()->json([
                'msg' => 'Update Failed. There is no items in this delivery_note_detail',
                'status' => 'error',
            ], 500);
        }
    }



    public function show($id)
    {

        $this->authorize('hasPermission', 'show_delivery_note_detail');

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;
        // Get DeliveryNote

        $DeliveryNote = DeliveryNote::where('store_id', $store_id)->with('deliveryNoteDetail.product.unit')->with('supplier')->findOrFail($id);
        $supplier_id = $DeliveryNote->supplier_id;
        $Supplier = Supplier::where('id', $supplier_id)->where('store_id', $store_id);

        return response()
            ->json([
                'delivery_note_detail' => $DeliveryNote,
                'supplier' => $Supplier,

            ]);
    }

    public function destroy($id)
    {

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;
        // Get DeliveryNote
        $DeliveryNote = DeliveryNote::where('id', $id)->where('store_id', $store_id)->first();

        //get delivery_note_detail details
        $deliveryNoteDetail = DeliveryNoteDetail::where('delivery_note_id', $id)->get();

        $countItems = count($deliveryNoteDetail);

        // $timeStamp=now();

        for ($i = 0; $i < $countItems; $i++) {
            //get product id from each delivery_note_detail details
            $p_id = $deliveryNoteDetail[$i]['product_id'];

            $p_qty = $deliveryNoteDetail[$i]['quantity'];

            //finding stock to decrease the quantity of this delivery_note_detail
            $stock = Stock::where('store_id', $store_id)->where('product_id', $p_id);

            $stock_id = $stock->value('id');

            $stock_qty = $stock->value('quantity');

            $stock = Stock::findOrFail($stock_id);

            if ($stock_qty >= $p_qty) {

                $stock->quantity = $stock_qty - $p_qty;
            }
            if ($stock->save()) {

                if ($DeliveryNote->delete()) {

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
    }
    public function searchDeliveryNotes(Request $request)
    {

        $this->authorize('hasPermission', 'search_delivery_note_detail');

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;

        $searchKey = $request->input('searchQuery');
        if ($searchKey != '') {
            return DeliveryNoteResource::collection(DeliveryNote::where('store_id', $store_id)->where('customer_name', 'like', '%' . $searchKey . '%')->get());
        } else {
            return response()->json([
                'msg' => 'Error while retriving DeliveryNotes. No Data Supplied as key.',
                'status' => 'error',
            ]);
        }
    }

    public function changeDeliveryNoteStatus(Request $request)
    {

        $this->authorize('hasPermission', 'edit_delivery_note_detail');

        // $user = User::findOrFail(Auth::user()->id);

        // $store_id = $user->stores[0]->id;

        $key = $request->input('key');

        $value = $request->input('value');

        $delivery_note_detail = DeliveryNote::findOrFail($key);
        $delivery_note_detail->status = $value;
        $delivery_note_detail->updated_at = time();

        if ($delivery_note_detail->save()) {
            return response()->json(['status' => 'success', 'msg' => $delivery_note_detail->custom_delivery_note_detail_id . ' changed to ' . $value . '']);
        } else {

            return response()->json(['status' => 'failed', 'msg' => 'DeliveryNote status changed Failed']);
        }
    }
}