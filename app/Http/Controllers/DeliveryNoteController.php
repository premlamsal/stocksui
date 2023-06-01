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
        $delivery_note_status_save = false;

        $this->authorize('hasPermission', 'add_delivery_note');

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;
        //validation
        $this->validate($request, [

            'info.note' => 'required | string |max:200',
            'info.supplier_name' => 'required | string| max:200',
            'info.supplier_id' => 'required',
            'info.due_date' => 'required | date',
            'info.delivery_note_date' => 'required | date',

            'info.delivery_note_reference_number' => 'required | string| max:200',


            'items.*.product_name' => 'required | string |max:200',
            'items.*.price' => 'required | numeric',
            'items.*.quantity' => 'required | numeric',

        ]);

        $store = Store::findOrFail($store_id);

        //old delivery note id
        $delivery_note_id_count = $store->delivery_note_id_count;

        //explode delivery note id from database

        $custom_delivery_note_id = explode('-', $delivery_note_id_count);

        $custom_delivery_note_id[1] = $custom_delivery_note_id[1] + 1; //increase delivery note

        //new custom_delivery_note_id
        $new_count_delivery_note_id = implode('-', $custom_delivery_note_id);

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

        $data['delivery_note_reference_id'] = $data['supplier_short_name'] . '-' . $data['delivery_note_reference_number'];

        $data['custom_delivery_note_id'] = $new_count_delivery_note_id;

        $delivery_note = DeliveryNote::create($data);

        $delivery_note->deliveryNoteDetail()->saveMany($items);

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

            //adding current stock with new devlivery note product quantity
            $new_stock_quantity = $in_stock_quantity + $items[$i]['quantity'];

            //found product on stock
            if ($stock_id != 0) {
               $stock = Stock::findOrFail($stock_id);

                $stock->quantity = $new_stock_quantity;

                $stock->price = $items[$i]['price'];

                $stock->updated_at = $timeStamp;

                $stock->updated_at = $timeStamp;

                if ($stock->save()) {
                    //set current delivery_note_id_count to store table
                    $store->delivery_note_id_count = $new_count_delivery_note_id;
                    if ($store->save()) {

                        $delivery_note_status_save = true;
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
}