<?php

namespace App\Http\Controllers;

use App\Customer;
use App\CustomerPayment;
use App\CustomerTransaction;
use App\Http\Resources\Customer as CustomerResource;
use App\Invoice;
use App\User;
use Auth;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function __construct()
    {

        $this->middleware('auth:api');

        // $this->authorize('hasPermission','delete_customer');

    }

    public function index()
    {

        $this->authorize('hasPermission', 'view_customers');

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;

        return CustomerResource::collection(Customer::where('store_id', $store_id)->paginate(8));
    }

    public function store(Request $request)
    {
        $this->authorize('hasPermission', 'add_customer');

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;

        $this->validate($request, [
            'name' => 'required|regex:/^[\pL\s\-]+$/u',
            'address' => 'required|string|max:200',
            'phone' => 'required|unique:customers,phone|digits:10',
            'details' => 'required|string|max:400',

        ]);

        $customer = new Customer();
        $customer->name = $request->input('name');
        $customer->address = $request->input('address');
        $customer->phone = $request->input('phone');
        $customer->details = $request->input('details');
        $customer->store_id = $store_id;

        if ($customer->save()) {

         
                return response()->json([
                    'msg' => 'Customer added successfully',
                    'status' => 'success',
                ]);
            
        } else {
            return response()->json([
                'msg' => 'Error while adding customer',
                'status' => 'error',
            ]);
        }
    }
   

    public function update(Request $request)
    {

        $this->authorize('hasPermission', 'edit_customer');

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;

        $this->validate($request, [
            'name' => 'required|regex:/^[\pL\s\-]+$/u',
            'address' => 'required|string|max:200',
            'phone' => 'required|digits:10',
            'details' => 'required|string|max:400',
        ]);

        $id = $request->input('id'); //get id from edit modal
        $customer = Customer::where('store_id', $store_id)->where('id', $id)->first();
        $customer->name = $request->input('name');
        $customer->address = $request->input('address');
        $customer->phone = $request->input('phone');
        $customer->details = $request->input('details');
        $customer->store_id = $store_id;

        if ($customer->save()) {
                return response()->json([
                    'msg' => 'Customer updated successfully',
                    'status' => 'success',
                ]);
           
        } else {
            return response()->json([
                'msg' => 'Error while updating customer',
                'status' => 'error',
            ]);
        }
    }

   

    public function destroy($id)
    {

        $this->authorize('hasPermission', 'delete_customer');

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;

        $customer = Customer::where('store_id', $store_id)->where('id', $id)->first();
        if ($customer->delete()) {
            $customerTransaction = CustomerTransaction::where('customer_id', $customer->id)->where('transaction_type', 'opening_balance')->first();
            if ($customerTransaction->delete()) {
                return response()->json([
                    'msg' => 'successfully Deleted',
                    'status' => 'success',
                ]);
            } else {
                return response()->json([
                    'msg' => 'Error while deleting customer transaction',
                    'status' => 'error',
                ]);
            }
        } else {
            return response()->json([
                'msg' => 'Error while deleting data',
                'status' => 'error',
            ]);
        }
    }

    public function show($id)
    {

        $this->authorize('hasPermission', 'show_customer');

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;

        $customer = Customer::where('store_id', $store_id)->where('id', $id)->first();

        $invoice_amount = Invoice::where('store_id', $store_id)->where('customer_id', $id)->sum('grand_total');



        if ($customer->save()) {
            return response()->json([
                'customer' => $customer,
                'invoice_amount' => $invoice_amount,
                'status' => 'success',
            ]);
        } else {
            return response()->json([
                'msg' => 'Error while retriving Customer',
                'status' => 'error',
            ]);
        }
    }

    public function searchCustomers(Request $request)
    {

        $this->authorize('hasPermission', 'search_customer');

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;

        $searchKey = $request->input('searchQuery');
        if ($searchKey != '') {
            return CustomerResource::collection(Customer::where('store_id', $store_id)->where('name', 'like', '%' . $searchKey . '%')->paginate(8));
        } else {
            return response()->json([
                'msg' => 'Error while retriving Customer. No Data Supplied as key.',
                'status' => 'error',
            ]);
        }
    }
}
