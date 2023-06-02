<?php

namespace App\Http\Controllers;

use App\Http\Resources\Purchase as PurchaseResource;
use App\Purchase;
use App\PurchaseDetail;
use App\Stock;
use App\StockHistory;
use App\Store;
use App\Supplier;
use App\SupplierTransaction;
use App\User;
use Auth;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        $this->authorize('hasPermission', 'view_purchases');

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;

        return PurchaseResource::collection(Purchase::where('store_id', $store_id)->with('purchaseDetail')->orderBy('updated_at', 'desc')->paginate(8));
    }

    public function store(Request $request)
    {
        $purchase_status_save = false;

        $this->authorize('hasPermission', 'add_purchase');

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;
        //validation
        $this->validate($request, [

            'info.note' => 'required | string |max:200',
            'info.supplier_name' => 'required | string| max:200',
            'info.supplier_id' => 'required',
            'info.due_date' => 'required | date',
            'info.purchase_date' => 'required | date',

            'info.purchase_reference_number' => 'required | string| max:200',


            'items.*.product_name' => 'required | string |max:200',
            'items.*.price' => 'required | numeric',
            'items.*.quantity' => 'required | numeric',

        ]);

        $store = Store::findOrFail($store_id);

        //old invoice id
        $purchase_id_count = $store->purchase_id_count;

        //explode invoice id from database

        $custom_purchase_id = explode('-', $purchase_id_count);

        $custom_purchase_id[1] = $custom_purchase_id[1] + 1; //increase purchase

        //new custom_purchase_id
        $new_count_purchase_id = implode('-', $custom_purchase_id);

        //collecting data
        $items = collect($request->items)->transform(function ($item) {
            $item['line_total'] = $item['quantity'] * $item['price'];
            return new PurchaseDetail($item);
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

        $data['purchase_reference_id'] = $data['supplier_short_name'] . '-' . $data['purchase_reference_number'];

        $data['custom_purchase_id'] = $new_count_purchase_id;

        $purchase = Purchase::create($data);

        $purchase->purchaseDetail()->saveMany($items);

        //for inserting in stock and altering if already has one initialized stock and previous stock
        $items = collect($request->items);

        $countItems = count($items);

        $timeStamp = now();

        $jsonResponse = array();

        //set current purchase_id_count to store table
        $store->purchase_id_count = $new_count_purchase_id;
        if ($store->save()) {
            $jsonResponse = ['msg' => 'Successfully created purchase note', 'status' => 'success'];
        } else {
            $jsonResponse = ['msg' => 'Failed updating the Data to the store.', 'status' => 'error3'];
        }

        return response()->json($jsonResponse);
    }
    public function update(Request $request)
    {

        $this->authorize('hasPermission', 'edit_purchase');

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;
        // //validation
        $this->validate($request, [

            'info.note' => 'required | string |max:200',
            'info.supplier_name' => 'required | string| max:200',
            'info.supplier_id' => 'required',
            'info.due_date' => 'required | date',
            'info.purchase_date' => 'required | date',

            'items.*.product_name' => 'required | string |max:200',
            'items.*.price' => 'required | numeric',
            'items.*.quantity' => 'required | numeric',

        ]);
        $id = $request->id; //we will get purchase id here

        $purchase = Purchase::where('id', $id)->where('store_id', $store_id)->first();

        $items = collect($request->items)->transform(function ($item) {
            $item['line_total'] = $item['quantity'] * $item['price'];
            return new PurchaseDetail($item);
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
        // Get Purchase
        $Purchase = Purchase::where('id', $id)->where('store_id', $store_id)->first();

        //get purchase details
        $purchaseDetail = PurchaseDetail::where('purchase_id', $id)->get();

        $countItems = count($purchaseDetail);
        
        $purchase->update($data);

        PurchaseDetail::where('purchase_id', $purchase->id)->delete();

        $purchase->purchaseDetail()->saveMany($items);
        return response()->json(['msg' => 'You have successfully updated the Delivery note.', 'status' => 'success']);
    }



    public function show($id)
    {

        $this->authorize('hasPermission', 'show_purchase');

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;
        // Get Purchase

        $Purchase = Purchase::where('store_id', $store_id)->with('purchaseDetail.product')->with('supplier')->findOrFail($id);
        $supplier_id = $Purchase->supplier_id;
        $Supplier = Supplier::where('id', $supplier_id)->where('store_id', $store_id);

        return response()
            ->json([
                'purchase' => $Purchase,
                'supplier' => $Supplier,

            ]);
    }

    public function destroy($id)
    {

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;
        // Get Purchase
        $Purchase = Purchase::where('id', $id)->where('store_id', $store_id)->first();

        //get purchase details
        $purchaseDetail = PurchaseDetail::where('purchase_id', $id)->get();

        $countItems = count($purchaseDetail);

        // $timeStamp=now();

        if ($Purchase->delete()) {

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
    public function searchPurchases(Request $request)
    {

        $this->authorize('hasPermission', 'search_purchase');

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;

        $searchKey = $request->input('searchQuery');
        if ($searchKey != '') {
            return PurchaseResource::collection(Purchase::where('store_id', $store_id)->where('customer_name', 'like', '%' . $searchKey . '%')->get());
        } else {
            return response()->json([
                'msg' => 'Error while retriving Purchases. No Data Supplied as key.',
                'status' => 'error',
            ]);
        }
    }

    public function changePurchaseStatus(Request $request)
    {

        $this->authorize('hasPermission', 'edit_purchase');

        // $user = User::findOrFail(Auth::user()->id);

        // $store_id = $user->stores[0]->id;

        $key = $request->input('key');

        $value = $request->input('value');

        $purchase = Purchase::findOrFail($key);
        $purchase->status = $value;
        $purchase->updated_at = time();

        if ($purchase->save()) {
            return response()->json(['status' => 'success', 'msg' => $purchase->custom_purchase_id . ' changed to ' . $value . '']);
        } else {

            return response()->json(['status' => 'failed', 'msg' => 'Purchase status changed Failed']);
        }
    }
}
