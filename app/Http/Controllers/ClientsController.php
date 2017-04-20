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
        $results = Clients::all();

        if (\Request::ajax())
        {
            return Datatables::of($results)
                    ->addColumn('action', function ($result) {
                        $action = '<a href="" class="hidden on-editing save-row"><i class="fa fa-save"></i></a>
                                    <a href="" class="hidden on-editing cancel-row"><i class="fa fa-times"></i></a>
                                    <a href=' . url('clients', [$result->id, 'pdf', 'preview']) . ' target="_blank" data-toggle="tooltip" data-placement="bottom" title="預覽本月訂單"><i class="fa fa-print"></i></a>
                                    <a href=' . route('clients.edit',  $result->id) . ' class="view-edit" data-toggle="tooltip" data-placement="bottom" title="查看編輯"><i class="fa fa-search"></i></a>
                                    <a href="" class="on-default edit-row" data-toggle="tooltip" data-placement="bottom" title="快速編輯"><i class="fa fa-pencil"></i></a>
                                    <a href="" class="on-default remove-row"><i class="fa fa-trash-o"></i></a>
                                    <input name="row_id" type="hidden" value=' . $result->id . '>';
                        return $action;
                    })
                    ->make(true);
        }

        \Crumbs::add('/clients', '客戶名單');
        
        return view('pages.clients', compact('results'));
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
            'route.unique_with' => '已經有相同的路線標號組合了!',
        ];

        $attributes = [
            'route' => '路線',
            'route_number' => '編號',
            'name' => '客戶名稱'
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
            'route.unique_with' => '已經有相同的路線標號組合了!',
        ];

        $attributes = [
            'route' => '路線',
            'route_number' => '編號',
            'name' => '客戶名稱'
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
                $request->session()->flash('message', '儲存成功');
                return \Response::json(array('success' => true, 'redirect' => route('clients.index')), 200);
            }
            return \Response::json(array('success' => true, 'message' => '儲存成功'), 200);
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
            $request->session()->flash('message', '刪除成功');
            return \Response::json(array('success' => true, 'redirect' => route('clients.index')), 200);
        }

        return \Response::json(array('success' => true, 'message' => '刪除成功'), 200);
    }

    public function pdf( int $id, $action = 'preview' )
    {
        $now_obj = Carbon::now();
        $current_month = $now_obj->format('Y-m');
        $now_obj->modify('first day of');
        $start = $now_obj->format('Y-m-d');
        $now_obj->modify('last day of');
        $end = $now_obj->format('Y-m-d');

        $monthly_orders = Orders::monthlyOrders($id, $start, $end)->get();

        if ( $monthly_orders->count() == 0 )
        {
            \Session::flash('state', 'warning');
            \Session::flash('message', '查無資料');
            return \Redirect::route('clients.index');
        }

        $clients_arr = [];
        $client = '';
        
        for ($i = 0; $i < 1; $i++)
        {
            $obj = new \stdClass();
            $client = Clients::where( 'id', '=', $id )->first();
            $obj->client = $client;
            $obj->monthly_orders = $monthly_orders;
            $obj->daily_sums = Orders::dailySums( $id, $start, $end )->get();
            $obj->monthly_sums = Orders::monthlySums( $id, $client->is_small, $start, $end )->get();
            $obj->client_sum = Orders::clientSums( $id, $start, $end )->first();
            $clients_arr[$i] = $obj;
        }
        
        $products = Products::all();
        $pdf_url = url('clients', [$id, 'pdf', 'print']);
        $title = $client->route . $client->route_number . ' ' . $client->name . ' 月銷售單';
        if ( $action == 'print' )
        {
             $pdf = \PDF::loadView('pdf.print', compact('title', 'products', 'clients_arr', 'current_month'))
                ->setPaper('a4', 'landscape');
            return $pdf->stream($client->route . $client->route_number . '-' . $current_month . '-月銷售單.pdf');
        }
        else
        {
           return view('pdf.print', compact('title', 'products', 'clients_arr', 'current_month', 'pdf_url'));
        }
    }
}
