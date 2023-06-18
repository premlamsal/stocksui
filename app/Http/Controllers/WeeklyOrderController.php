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


        $jsonResponse = ['msg' => 'Successfully created weekly order', 'status' => 'success'];
    }
    public function update(Request $request)
    {
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
    public function delete($id)
    {
    }
}
