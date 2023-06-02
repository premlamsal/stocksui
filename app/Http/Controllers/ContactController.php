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

        $this->authorize('hasPermission', 'view_contacts');

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;

        return ContactResource::collection(Contact::where('store_id', $store_id)->paginate(8));
    }

    public function store(Request $request)
    {

        $this->authorize('hasPermission', 'add_contact)');

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;

        $this->validate($request, [

            'name'    => 'required|regex:/^[\pL\s\-]+$/u',

            'address' => 'required|string|max:200',

            'phone'   =>  'required|unique:contacts,phone|numeric',

            'role' => 'required|string|max:400',

            'details' => 'required|string|max:400',


        ]);

        $contact = new Contact();

        $contact->name = $request->input('name');

        $contact->address = $request->input('address');

        $contact->phone = $request->input('phone');

        $contact->role = $request->input('role');


        $contact->details = $request->input('details');


        $contact->store_id = $store_id;

        if ($contact->save()) {


            return response()->json([
                'msg' => 'Contact added successfully',
                'status' => 'success',
            ]);
        } else {
            return response()->json([

                'msg'    => 'Error while adding contact',

                'status' => 'error',
            ]);
        }
    }

    public function update(Request $request)
    {

        $this->authorize('hasPermission', 'edit_contact)');

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;

        $this->validate($request, [

            'name'    => 'required|regex:/^[\pL\s\-]+$/u',

            'address' => 'required|string|max:200',

            'phone'   => 'required|numeric',

            'details' => 'required|string|max:400',

            'role' => 'required|string|max:400',




        ]);

        $id = $request->input('id'); //get id from edit modal

        $contact = Contact::where('id', $id)->where('store_id', $store_id)->first();

        $contact->name = $request->input('name');

        $contact->address = $request->input('address');

        $contact->role = $request->input('role');

        $contact->phone = $request->input('phone');


        $contact->store_id = $store_id;

        if ($contact->save()) {

            return response()->json([
                'msg' => 'Contact updated successfully',
                'status' => 'success',
            ]);
        } else {

            return response()->json([

                'msg'    => 'Error while updating contact',
                'status' => 'error',
            ]);
        }
    }


    public function destroy($id)
    {

        $this->authorize('hasPermission', 'delete_contact)');

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;

        $contact = Contact::where('id', $id)->where('store_id', $store_id)->first();
        if ($contact->delete()) {
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

        $this->authorize('hasPermission', 'show_contact');

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;

        $contact = Contact::where('id', $id)->where('store_id', $store_id)->first();

        if ($contact->save()) {
            return response()->json([
                'contact' => $contact,
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

        $this->authorize('hasPermission', 'search_contact)');

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
