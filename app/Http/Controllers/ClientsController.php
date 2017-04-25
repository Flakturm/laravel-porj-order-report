<?php

namespace turnip\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Facades\Datatables;
use Carbon\Carbon;
use App\Http\Requests;
use turnip\Clients;
use turnip\Orders;
use turnip\Products;

class ClientsController extends Controller
{
    public function index()
    {

        if (\Request::ajax())
        {
            $results = Clients::orderBy('route', 'asc')->orderBy('route_number', 'asc')->get();
            return Datatables::of($results)
                    ->addColumn('action', function ($result) {
                        $action = '<a href="" class="hidden on-editing save-row"><i class="fa fa-save"></i></a>
                                    <a href="" class="hidden on-editing cancel-row"><i class="fa fa-times"></i></a>
                                    <a href=' . url('clients', [$result->id, 'pdf', 'preview']) . ' target="_blank" data-toggle="tooltip" data-placement="bottom" title="Preview"><i class="fa fa-print"></i></a>
                                    <a href=' . route('clients.edit',  $result->id) . ' class="view-edit" data-toggle="tooltip" data-placement="bottom" title="Edit"><i class="fa fa-search"></i></a>
                                    <a href="" class="on-default edit-row" data-toggle="tooltip" data-placement="bottom" title="Quick edit"><i class="fa fa-pencil"></i></a>
                                    <a href="" class="on-default remove-row"><i class="fa fa-trash-o"></i></a>
                                    <input name="row_id" type="hidden" value=' . $result->id . '>';
                        return $action;
                    })
                    ->make(true);
        }

        \Crumbs::add('/clients', 'Clients');
        
        return view('pages.clients');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store( Request $request )
    {
        // Setup the validator
        $rules = [
            'route' => 'required|unique_with:clients,route_number',
            'route_number' => 'required|integer',
            'name' => 'required',
        ];

        $messages = [
            'route.unique_with' => 'This type and number combination already exists!',
        ];

        $attributes = [
            'route' => 'Type',
            'route_number' => 'Number',
            'name' => 'Client'
        ];
        
        $validator = \Validator::make( $request->all(), $rules, $messages, $attributes );

        // Validate the input and return correct response
        if ( $validator->fails() )
        {
            return \Response::json(array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()

            ), 400); // 400 being the HTTP code for an invalid request.
        }

        $client = Clients::create( $request->all() );

        return \Response::json(array('success' => true, 'record_id' => $client->id), 200);
    }

    public function edit( $id )
    {
        try
        {
            $results = Clients::with('orders.orderProducts')->findOrFail( $id );
            \Crumbs::add('/clients', '客戶名單');
            \Crumbs::addCurrent($results->name);
            $order_products = [];
            foreach ( $results->orders as $key => $val)
            {
                $order_products[$key] = $val;
            }
            $order_products = json_encode($order_products);
            return view('pages.client_edit', compact('results', 'order_products'));
        }
        catch (ModelNotFoundException $err)
        {
            return \Redirect::action('ClientsController@index');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update( Request $request )
    {
        // Setup the validator
        $rules = array(
            'route' => 'required|unique_with:clients,route_number,' . $request->input('id'),
            'route_number' => 'required|integer',
            'name' => 'required',
        );

        $messages = [
            'route.unique_with' => 'Type exists!',
        ];

        $attributes = [
            'route' => 'Type',
            'route_number' => 'Number',
            'name' => 'Client'
        ];

        $validator = \Validator::make( $request->all(), $rules, $messages, $attributes );

        // Validate the input and return correct response
        if ( $validator->fails() )
        {
            return \Response::json(array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()

            ), 400); // 400 being the HTTP code for an invalid request.
        }

        try
        {
            Clients::findOrFail( $request->input('id') )
                    ->fill( $request->all() )
                    ->save();
            if ( $request->input('redirect') )
            {
                $request->session()->flash('state', 'success');
                $request->session()->flash('message', 'Client saved');
                return \Response::json(array('success' => true, 'redirect' => route('clients.index')), 200);
            }
            return \Response::json(array('success' => true, 'message' => 'Client saved'), 200);
        }
        catch (ModelNotFoundException $err)
        {
            return \Response::json(array('success' => false, 'errors' => $err), 400);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy( Request $request )
    {
        // delete
        Clients::destroy( $request->input('id') );

        if ( $request->input('redirect') == true )
        {
            $request->session()->flash('state', 'success');
            $request->session()->flash('message', 'Client deleted');
            return \Response::json(array('success' => true, 'redirect' => route('clients.index')), 200);
        }

        return \Response::json(array('success' => true, 'message' => 'Client deleted'), 200);
    }

    public function pdf( int $id, $action = 'preview' )
    {
        $now_obj = Carbon::now();
        $current_month = $now_obj->format('Y-m');
        $client = Clients::where( 'id', '=', $id )->first();

        if ( \Request::ajax() )
        {
            $now_obj->modify('first day of');
            $start = $now_obj->format('Y-m-d');
            $now_obj->modify('last day of');
            $end = $now_obj->format('Y-m-d');

            $monthly_orders = Orders::monthlyOrders($id, $start, $end)->get();

            if ( $monthly_orders->count() == 0 )
            {
                \Session::flash('state', 'warning');
                \Session::flash('message', 'No record found');
                return \Redirect::route('clients.index');
            }

            $clients_arr = [];
            
            for ($i = 0; $i < 1; $i++)
            {
                $obj = new \stdClass();
                $obj->client = $client;
                $obj->monthly_orders = $monthly_orders;
                $obj->daily_sums = Orders::dailySums( $id, $start, $end )->get();
                $obj->monthly_sums = Orders::monthlySums( $id, $client->is_small, $start, $end )->get();
                $obj->client_sum = Orders::clientSums( $id, $start, $end )->first();
                $clients_arr[$i] = $obj;
            }
            
            $products = Products::all();
            return \Response::json( compact('products', 'clients_arr') );
        }
        $title = $client->route . $client->route_number . ' ' . $client->name . ' Report';
        return view('pdf.print', compact('title', 'current_month') );
    }
}
