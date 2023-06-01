<?php

namespace App\Http\Controllers;

use App\Http\Resources\Contact as ContactResource;
use App\Purchase;
use App\Contact;
use App\ContactTransaction;
use App\User;
use Auth;
use Illuminate\Http\Request;

class ContactController extends Controller
{

    public function __construct()
    {

        $this->middleware('auth:api');
    }

    public function index()
    {

        $this->authorize('hasPermission', 'view_suppliers');

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;

        return ContactResource::collection(Contact::where('store_id', $store_id)->paginate(8));
    }

    public function store(Request $request)
    {

        $this->authorize('hasPermission', 'add_supplier)');

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;

        $this->validate($request, [

            'name'    => 'required|regex:/^[\pL\s\-]+$/u',

            'address' => 'required|string|max:200',

            'phone'   =>  'required|unique:suppliers,phone|numeric',

            'contact_person' => 'required|string|max:400',

            'details' => 'required|string|max:400',


        ]);

        $supplier = new Contact();

        $supplier->name = $request->input('name');

        $supplier->address = $request->input('address');

        $supplier->phone = $request->input('phone');

        $supplier->contact_person = $request->input('contact_person');


        $supplier->details = $request->input('details');


        $supplier->store_id = $store_id;

        if ($supplier->save()) {


            return response()->json([
                'msg' => 'Contact added successfully',
                'status' => 'success',
            ]);
        } else {
            return response()->json([

                'msg'    => 'Error while adding supplier',

                'status' => 'error',
            ]);
        }
    }

    public function update(Request $request)
    {

        $this->authorize('hasPermission', 'edit_supplier)');

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;

        $this->validate($request, [

            'name'    => 'required|regex:/^[\pL\s\-]+$/u',

            'address' => 'required|string|max:200',

            'phone'   => 'required|digits:10',

            'details' => 'required|string|max:400',

            'contact_person' => 'required|string|max:400',




        ]);

        $id = $request->input('id'); //get id from edit modal

        $supplier = Contact::where('id', $id)->where('store_id', $store_id)->first();

        $supplier->name = $request->input('name');

        $supplier->address = $request->input('address');

        $supplier->contact_person = $request->input('contact_person');

        $supplier->phone = $request->input('phone');


        $supplier->store_id = $store_id;

        if ($supplier->save()) {

            return response()->json([
                'msg' => 'Contact updated successfully',
                'status' => 'success',
            ]);
        } else {

            return response()->json([

                'msg'    => 'Error while updating supplier',
                'status' => 'error',
            ]);
        }
    }


    public function destroy($id)
    {

        $this->authorize('hasPermission', 'delete_supplier)');

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;

        $supplier = Contact::where('id', $id)->where('store_id', $store_id)->first();
        if ($supplier->delete()) {
            $ContactTransaction = ContactTransaction::where('customer_id', $supplier->id)->where('transaction_type', 'opening_balance')->first();
            if ($ContactTransaction->delete()) {
                return response()->json([
                    'msg' => 'successfully Deleted',
                    'status' => 'success',
                ]);
            } else {
                return response()->json([
                    'msg' => 'Error while deleting Contact transaction',
                    'status' => 'error',
                ]);
            }
        } else {
            return response()->json([
                'msg'    => 'Error while deleting data',
                'status' => 'error',
            ]);
        }
    }

    public function show($id)
    {

        $this->authorize('hasPermission', 'show_supplier');

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;

        $supplier = Contact::where('id', $id)->where('store_id', $store_id)->first();

        $purchase_amount = Purchase::where('store_id', $store_id)->where('supplier_id', $id)->sum('grand_total');

        if ($supplier->save()) {
            return response()->json([
                'supplier' => $supplier,
                'purchase_amount' => $purchase_amount,
                'status' => 'success',
            ]);
        } else {
            return response()->json([
                'msg' => 'Error while retriving Customer',
                'status' => 'error',
            ]);
        }
    }

    public function searchContacts(Request $request)
    {

        $this->authorize('hasPermission', 'search_supplier)');

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;

        $searchKey = $request->input('searchQuery');
        if ($searchKey != '') {
            return ContactResource::collection(Contact::where('store_id', $store_id)->where('name', 'like', '%' . $searchKey . '%')->paginate(8));
        } else {
            return response()->json([
                'msg'    => 'Error while retriving Contacts. No Data Supplied as key.',
                'status' => 'error',
            ]);
        }
    }
}
