<?php

namespace App\Http\Controllers;

use App\Http\Resources\WeeklyOrderResource;
use App\Store;
use App\User;
use App\WeeklyOrder;
use App\WeeklyOrderDetailC;
use App\WeeklyOrderDetailD;
use App\WeeklyOrderDetailM;
use Illuminate\Http\Request;
use Auth;
use Spatie\Browsershot\Browsershot;

class WeeklyOrderController extends Controller
{
    public function __construct()
    {

        $this->middleware('auth:api');
    }
    public function index()
    {
        $this->authorize('hasPermission', 'view_weekly_orders');

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;

        return WeeklyOrderResource::collection(WeeklyOrder::where('store_id', $store_id)->with('WeeklyOrderDetailC')->with('WeeklyOrderDetailM')->with('WeeklyOrderDetailD')->paginate(8));
    }
    public function store(Request $request)
    {

        $this->authorize('hasPermission', 'add_weekly_order');

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;
        //validation
        $this->validate($request, [

            'info.boat_name' => 'required | string |max:200',
            'info.date_order_requested' => 'required ',
            'info.delivery_date' => 'required',
            'info.note' => 'required',
        ]);

        $data = $request->info;
        $data['store_id'] = $store_id;



        //collecting data
        $items_cp = collect($request->cp)->transform(function ($item) {
            return new WeeklyOrderDetailC($item);
        });
        $items_m = collect($request->m)->transform(function ($item) {
            return new WeeklyOrderDetailM($item);
        });
        $items_d = collect($request->d)->transform(function ($item) {
            return new WeeklyOrderDetailD($item);
        });



        $weekly_order = WeeklyOrder::create($data);
        $weekly_order->WeeklyOrderDetailC()->saveMany($items_cp);
        $weekly_order->WeeklyOrderDetailM()->saveMany($items_m);
        $weekly_order->WeeklyOrderDetailD()->saveMany($items_d);



        return response()->json(['msg' => 'Weekly Order Successfully Created!', 'status' => 'success']);
    
    }
    public function update(Request $request)
    {

        $this->authorize('hasPermission', 'add_weekly_order');

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;
        //validation
        $this->validate($request, [

            'info.boat_name' => 'required | string |max:200',
            'info.date_order_requested' => 'required ',
            'info.delivery_date' => 'required',
            'info.note' => 'required',
        ]);

        $data = $request->info;
        $data['store_id'] = $store_id;



        //collecting data
        $items_cp = collect($request->cp)->transform(function ($item) {
            return new WeeklyOrderDetailC($item);
        });
        $items_m = collect($request->m)->transform(function ($item) {
            return new WeeklyOrderDetailM($item);
        });
        $items_d = collect($request->d)->transform(function ($item) {
            return new WeeklyOrderDetailD($item);
        });



        $weekly_order = WeeklyOrder::where('id',$request->id)->where('store_id', $store_id)->first();

        WeeklyOrderDetailC::where('weekly_order_id', $request->id)->delete();
        WeeklyOrderDetailM::where('weekly_order_id', $request->id)->delete();
        WeeklyOrderDetailD::where('weekly_order_id', $request->id)->delete();


        $weekly_order->WeeklyOrderDetailC()->saveMany($items_cp);
        $weekly_order->WeeklyOrderDetailM()->saveMany($items_m);
        $weekly_order->WeeklyOrderDetailD()->saveMany($items_d);


        return response()->json(['msg' => 'Weekly Order Successfully Updated!', 'status' => 'success']);

    }

    public function show($id)
    {
        $this->authorize('hasPermission', 'show_weekly_order');

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;

        $weekly_order = WeeklyOrder::where('id', $id)->where('store_id', $store_id)->with('WeeklyOrderDetailC')->with('WeeklyOrderDetailM')->with('WeeklyOrderDetailD')->first();

        if ($weekly_order) {
            return response()->json([
                'weeklyorder' => $weekly_order,
                'status' => 'success',
            ]);
        } else {
            return response()->json([
                'msg' => 'Error while retriving Customer',
                'status' => 'error',
            ]);
        }
    }
    public function destroy($id)
    {
        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;
        // Get Purchase
        $weekly_order = WeeklyOrder::where('id', $id)->where('store_id', $store_id)->first();

        WeeklyOrderDetailC::where('weekly_order_id', $id)->delete();
        WeeklyOrderDetailM::where('weekly_order_id', $id)->delete();
        WeeklyOrderDetailD::where('weekly_order_id', $id)->delete();

        if ($weekly_order->delete()) {

            return response()->json([
                'msg' => 'Weekly Order Successfully Deleted!',
                'status' => 'success',
            ]);
        } else {
            return response()->json([
                'msg' => 'Erro Deleting Weekly Order',
                'status' => 'error',
            ]);
        }
    }

    public function pdfdownload($id)
    {

        $this->authorize('hasPermission', 'download_weeklyorder');

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;
        // Get pick_list

        $weekly_order = WeeklyOrder::where('store_id', $store_id)->with('WeeklyOrderDetailC')->with('WeeklyOrderDetailM')->with('WeeklyOrderDetailD')->findOrFail($id);


        // return response()->json([
        //     'msg' => $pick_list,
        //     'status' => 'error',
        // ], 200);

        $path= 'img/logo1.png';
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

        $pdf = \PDF::loadView('weekly_order_pdf', ['weeklyorder' => $weekly_order,'image'=>$base64]);
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif','isRemoteEnabled'=>true]);
        $pdf->setPaper('a4', 'portrait');
        return $pdf->output();
    }
    public function pdfdownload2($id)
    {

        $this->authorize('hasPermission', 'download_weeklyorder');

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;
        // Get pick_list

        $weekly_order = WeeklyOrder::where('store_id', $store_id)->with('WeeklyOrderDetailC')->with('WeeklyOrderDetailM')->with('WeeklyOrderDetailD')->findOrFail($id);


      $data=  Browsershot::url('https://google.com')->pdf();


        return response()->json([
            'data' => $data,
            'status' => 'error',
        ], 200);
    }
}
