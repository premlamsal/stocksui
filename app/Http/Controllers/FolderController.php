<?php

namespace App\Http\Controllers;

use App\Folder;
use App\Http\Resources\FolderResource;
use App\User;
use Illuminate\Http\Request;
use Auth;

class FolderController extends Controller
{

    public function __construct()
    {

        $this->middleware('auth:api');
    }

    public function index()
    {

        $this->authorize('hasPermission', 'view_folders');

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;

        return FolderResource::collection(Folder::where('store_id', $store_id)->paginate(8));
    }

    public function store(Request $request)
    {

        $this->authorize('hasPermission', 'add_folder)');

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;

        $this->validate($request, [

            'name'    => 'required|regex:/^[\pL\s\-]+$/u',

            'email' => 'required|email|max:200',

            'phone'   => 'numeric',

            'role' => 'required|string|max:30',

            'company' => 'required|string|max:50',


        ]);

        $folder = new Folder();

        $folder->name = $request->input('name');

        $folder->email = $request->input('email');

        $folder->phone = $request->input('phone');

        $folder->role = $request->input('role');


        $folder->company = $request->input('company');


        $folder->store_id = $store_id;

        if ($folder->save()) {


            return response()->json([
                'msg' => 'Folder Successfully Created',
                'status' => 'success',
            ]);
        } else {
            return response()->json([

                'msg'    => 'Error while adding folder',

                'status' => 'error',
            ]);
        }
    }

    public function update(Request $request)
    {

        $this->authorize('hasPermission', 'edit_folder)');

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;

        $this->validate($request, [

            'name'    => 'required|regex:/^[\pL\s\-]+$/u',

            'email' => 'required|email|max:200',

            'phone'   => 'numeric',

            'company' => 'required|string|max:50',

            'role' => 'required|string|max:30',




        ]);

        $id = $request->input('id'); //get id from edit modal

        $folder = Folder::where('id', $id)->where('store_id', $store_id)->first();

        $folder->name = $request->input('name');

        $folder->email = $request->input('email');

        $folder->role = $request->input('role');

        $folder->phone = $request->input('phone');


        $folder->store_id = $store_id;

        if ($folder->save()) {

            return response()->json([
                'msg' => 'Folder Successfully Updated ',
                'status' => 'success',
            ]);
        } else {

            return response()->json([

                'msg'    => 'Error while updating folder',
                'status' => 'error',
            ]);
        }
    }


    public function destroy($id)
    {

        $this->authorize('hasPermission', 'delete_folder)');

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;

        $folder = Folder::where('id', $id)->where('store_id', $store_id)->first();
        if ($folder->delete()) {
            return response()->json([
                'msg' => 'Folder Successfully Deleted',
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

        $this->authorize('hasPermission', 'show_folder');

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;

        $folder = Folder::where('id', $id)->where('store_id', $store_id)->first();

        if ($folder->save()) {
            return response()->json([
                'folder' => $folder,
                'status' => 'success',
            ]);
        } else {
            return response()->json([
                'msg' => 'Error while retriving Customer',
                'status' => 'error',
            ]);
        }
    }

    public function searchFolders(Request $request)
    {

        $this->authorize('hasPermission', 'search_folder)');

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;

        $searchKey = $request->input('searchQuery');
        if ($searchKey != '') {
            return FolderResource::collection(Folder::where('store_id', $store_id)->where('name', 'like', '%' . $searchKey . '%')->paginate(8));
        } else {
            return response()->json([
                'msg'    => 'Error while retriving Folders. No Data Supplied as key.',
                'status' => 'error',
            ]);
        }
    }
}