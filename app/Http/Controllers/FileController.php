<?php

namespace App\Http\Controllers;

use App\File;
use App\Http\Resources\FileResource;
use App\User;
use Illuminate\Http\Request;
use Auth;

class FileController extends Controller
{

    public function __construct()
    {

        $this->middleware('auth:api');
    }

    public function index()
    {

        $this->authorize('hasPermission', 'view_files');

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;

        return FileResource::collection(File::with('user')->with('folder')->paginate(8));
    }

    public function store(Request $request)
    {

        $this->authorize('hasPermission', 'add_file)');

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;

        $this->validate($request, [

            'name'    => 'required',

            'description' => 'required',

            'folder_id' => 'required',

            'upload_file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4048',


        ]);

        $file = new File();

        $file->name = $request->input('name');

        $file->description = $request->input('description');

        $file->folder_id = $request->input('folder_id');

        //file 
        // $file->file_location = $request->input('file_location');



        if ($request->hasFile('upload_file')) {
            $fileName = '/merofiles/' . time() . '.' . $request->upload_file->getClientOriginalExtension();
            $request->upload_file->move(public_path('merofiles'), $fileName);
            $file->file_location = $fileName;
            $file->original_file_name = $request->upload_file->getClientOriginalName();
        }


        $file->user_id = Auth::user()->id;

        $file->status = 'active';

        if ($file->save()) {

            return response()->json([
                'msg' => 'File Successfully Created',
                'status' => 'success',
            ]);
        } else {
            return response()->json([

                'msg'    => 'Error while adding file',

                'status' => 'error',
            ]);
        }
    }

    public function update(Request $request)
    {

        $this->authorize('hasPermission', 'edit_file)');

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;

        $this->validate($request, [


            'name'    => 'required',

            'description' => 'required',

            'folder_id' => 'required',


        ]);

        $id = $request->input('id'); //get id from edit modal

        $file = File::where('id', $id)->first();

        $file->name = $request->input('name');

        $file->description = $request->input('description');

        $file->folder_id = $request->input('folder_id');

        //file 
        // $file->file_location = $request->input('file_location');


        if ($request->hasFile('uploadFile')) {

            $file_ext = $request->uploadFile->getClientOriginalExtension();

            $checkExt = array("jpg", "png", "jpeg");

            if (in_array($file_ext, $checkExt)) {

                $fileName = '/merofiles/' . time() . '.' . $request->uploadFile->getClientOriginalExtension();
                $request->uploadFile->move(public_path('merofiles'), $fileName);
                $file->file_location = $fileName;
                $file->original_file_name = $request->upload_file->getClientOriginalName();

            } else {
                return response()->json([
                    'msg' => 'Opps! My Back got cracked while working in Database',
                    'status' => 'error',
                ]);
            }
        }


        $file->user_id = Auth::user()->id;

        $file->status = 'active';

        if ($file->save()) {

            return response()->json([
                'msg' => 'File Successfully Updated ',
                'status' => 'success',
            ]);
        } else {

            return response()->json([

                'msg'    => 'Error while updating file',
                'status' => 'error',
            ]);
        }
    }


    public function destroy($id)
    {

        $this->authorize('hasPermission', 'delete_file)');

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;

        $file = File::where('id', $id)->first();
        if ($file->delete()) {
            return response()->json([
                'msg' => 'File Successfully Deleted',
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

        $this->authorize('hasPermission', 'show_file');

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;

        $file = File::where('id', $id)->first();

        if ($file->save()) {
            return response()->json([
                'file' => $file,
                'status' => 'success',
            ]);
        } else {
            return response()->json([
                'msg' => 'Error while retriving Customer',
                'status' => 'error',
            ]);
        }
    }

    public function searchFiles(Request $request)
    {

        $this->authorize('hasPermission', 'search_file)');

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;

        $searchKey = $request->input('searchQuery');
        if ($searchKey != '') {
            return FileResource::collection(File::where('name', 'like', '%' . $searchKey . '%')->paginate(8));
        } else {
            return response()->json([
                'msg'    => 'Error while retriving Files. No Data Supplied as key.',
                'status' => 'error',
            ]);
        }
    }
}
