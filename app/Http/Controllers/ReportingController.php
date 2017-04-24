<?php

namespace turnip\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use turnip\Clients;
use turnip\Orders;
use turnip\Products;

class ReportingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        \Crumbs::add('/reporting', 'Reporting');
        $routes = Clients::select('route')->distinct()->orderBy('route', 'asc')->get();

        $now_obj = Carbon::now();
        $current_month = $now_obj->month;
        $now_obj->modify('last month');
        $last_month = $now_obj->month;

        return view('pages.reporting', compact('routes', 'current_month', 'last_month'));
    }

    public function pdf($month, $route, $action = 'preview')
    {
        $date_obj = Carbon::createFromDate (null, $month, null );
        $current_month = $date_obj->format('Y-m');
        $date_obj->modify('first day of');
        $start = $date_obj->format('Y-m-d');
        $date_obj->modify('last day of');
        $end = $date_obj->format('Y-m-d');

        // get first 10 records
        $clients = Clients::where( 'route', '=', strtoupper($route) )->orderBy('route_number')->get();
        if ( $clients->count() == 0 )
        {
            \Session::flash('state', 'warning');
            \Session::flash('message', 'No record found');
            return \Redirect::route('reporting.index');
        }
        $products = Products::all();
        $clients_arr = [];
        foreach ($clients as $key => $client)
        {
            $obj = new \stdClass();
            $obj->client = $client;
            $obj->monthly_orders = Orders::monthlyOrders( $client->id, $start, $end )->get();
            $obj->daily_sums = Orders::dailySums( $client->id, $start, $end )->get();
            $obj->monthly_sums = Orders::monthlySums( $client->id, $client->is_small, $start, $end )->get();
            $obj->client_sum = Orders::clientSums( $client->id, $start, $end )->first();
            $clients_arr[$key] = $obj;
        }

        $pdf_url = url('reporting', ['pdf', $month, $route, 'print']);
        $title = $route . ' ' . $current_month . ' Report';
        // dd($clients_arr);
        if ( $action == 'print' )
        {
            ini_set('memory_limit','2048M');
            set_time_limit(0);
             $pdf = \PDF::loadView('pdf.print', compact('title', 'products', 'clients_arr', 'current_month'))
                ->setPaper('a4', 'landscape');
            return $pdf->download($route . '-' . $current_month . '-report.pdf');
        }
        else
        {
            ini_set('memory_limit','1024M');
            ini_set('max_execution_time', 300); //300 seconds = 5 minutes
            set_time_limit(0);
            return view('pdf.print', compact('title', 'products', 'clients_arr', 'current_month', 'pdf_url'));
        }
    }
}
