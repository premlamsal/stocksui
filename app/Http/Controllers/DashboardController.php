<?php

namespace App\Http\Controllers;

use App\Contact;
use App\Event;
use App\Invoice;
use App\Note;
use App\Product;
use App\ProductCategory;
use App\Purchase;
use App\Stock;
use App\Supplier;
use App\Task;
use App\TaskBoard;
use App\User;
use App\WeeklyOrder;
use Auth;
use DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    //provide dashboard information like number of purchase, invoices, products and supplier
    public function dashInfo()
    {

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;

        $product = Product::where('store_id', $store_id)->count();

        $contact = Contact::where('store_id', $store_id)->count();

        $category = ProductCategory::where('store_id', $store_id)->count();

        $calendar_events = Event::where('store_id', $store_id)->count();

        $supplier = Supplier::where('store_id', $store_id)->count();

        $note = Note::where('store_id', $store_id)->count();

        $weekly_order = WeeklyOrder::where('store_id', $store_id)->count();

        $purchase = Purchase::where('store_id', $store_id)->sum('grand_total');

        $task = TaskBoard::where('id', 1)->first();

        $task_json = $task->tasks;

        //json parsing block to calculate no of tasks
        // Assuming you have the JSON data stored in a variable called $json

        // Step 1: Parse the JSON
        $data = json_decode($task_json, true);

        // Step 2: Count tasks in 'toDo' section
        $todoCount = 0;
        foreach ($data['toDo'] as $priority => $tasks) {
            $todoCount += count($tasks);
        }

        // Step 3: Count tasks in 'arrInProgress' section
        $inProgressCount = 0;
        foreach ($data['arrInProgress'] as $priority => $tasks) {
            $inProgressCount += count($tasks);
        }

        // Step 4: Count tasks in 'arrDone' section
        $doneCount = 0;
        foreach ($data['arrDone'] as $priority => $tasks) {
            $doneCount += count($tasks);
        }

        // // Display the task counts
        // echo "Number of tasks in 'toDo': " . $todoCount . "<br>";
        // echo "Number of tasks in 'arrInProgress': " . $inProgressCount . "<br>";
        // echo "Number of tasks in 'arrDone': " . $doneCount . "<br>";

        $sum_of_total_tasks = $todoCount + $inProgressCount + $doneCount;



        //end of json parsing block

        return response()->json([
            'product'  => $product,
            'supplier' => $supplier,
            'purchase' => $purchase,
            'contact' => $contact,
            'category' => $category,
            'weekly_order' => $weekly_order,
            'note' => $note,
            'task' => $sum_of_total_tasks,
            'event' => $calendar_events,

            'status'   => 'success',
        ]);
    }

    //supply before_moth and get records before that months
    //eg.if you supply 6 then you will get past 6 month records
    public function salesChart($before_month)
    {

        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;

        $invoice = Invoice::select(
            'invoice_date',
            DB::raw('YEAR(invoice_date) as year'),
            DB::raw('MONTH(invoice_date)as month'),
            DB::raw('Day(invoice_date) as day'),
            DB::raw('SUM(grand_total) as grand_total')
        )
            ->where('store_id', $store_id)
            //select past months records
            ->where('invoice_date', '>', now()->subMonthNoOverflow($before_month)->format('Y-m-d'))

            // ->where('status', 'Paid') //only select paid invoice sales
            // ->orderBy('year','desc')
            ->groupBy('year', 'month') //grouping by year and month

            ->get();

        $month = array();

        $data = array();

        $newmonth = array(); //new array to hold month
        $newdata  = array(); //new array to hold data i.e sales

        foreach ($invoice as $key) {
            //enabling this will supply month name to response data for chart
            $date = $key->year . '-' . $key->month . '-' . $key->day; //joins year-month-day to single variable

            $date = date('Y-m', strtotime($date)); //will give month name i.e Janurary, Februray etc.
            // $month[]=$date;
            $data[$date] = $key->grand_total; //pushing sum to array

            // print_r($data);

            // print_r($data[now()->subMonthNoOverflow(1)->format('Y-m')]);
        }

        //finding all records and assigning 0 if no sales in that month
        for ($i = $before_month; $i > 0; $i--) {
            //decrement loop from 6 to 1 months

            //checks array has that key or not
            if (array_key_exists(now()->subMonthNoOverflow($i - 1)->format('Y-m'), $data)) {

                // echo now()->subMonthNoOverflow($i)->format('F');

                //assinging data to new data array //eg.leaving as it is if month has sales
                $newmonth[] = now()->subMonthNoOverflow($i - 1)->format('Y-m');
                $newdata[]  = $data[now()->subMonthNoOverflow($i - 1)->format('Y-m')];
            } else {
                // assging 0 to non-sales months
                $newmonth[] = now()->subMonthNoOverflow($i - 1)->format('Y-m');
                $newdata[]  = "0.00";

                // echo now()->subMonthNoOverflow($i)->format('F');
            }
        }

        return response()->json(['month' => $newmonth, 'data' => $newdata]);
        // return response()->json(['data' => $data]);
    }
}
