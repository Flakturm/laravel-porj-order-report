<?php

namespace turnip\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use Yajra\Datatables\Facades\Datatables;
use turnip\Clients;
use turnip\Orders;
use turnip\OrderProducts;
use turnip\Products;

class OrdersController extends Controller
{
    public function index()
    {
        $orders = Orders::with('clients')->where( DB::raw('MONTH(ordered_date)'), '=', date('n') );
        if (\Request::ajax())
        {
            return Datatables::of($orders)
                    ->addColumn('action', function ($order) {
                        return '<a href=' . route('orders.edit', $order->id) . ' class="table-action-btn"><i class="md md-edit"></i></a><a href="" class="table-action-btn" data-id=' . $order->id . ' data-action=' . route('orders.destroy', $order->id) . ' data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>';
                    })
                    ->make(true);
        }
        $results = $orders->get();

        \Crumbs::add('/orders', '當月訂單');
        
        return view('pages.orders', compact('results'));
    }

    public function all()
    {
        $results = Orders::with('clients')->get();

        \Crumbs::add('/orders', '全部訂單');
        
        return view('pages.orders', compact('results'));
    }

    public function create()
    {
        \Crumbs::add('/orders', '訂單');
        \Crumbs::addCurrent("新增");
        $routes = Clients::select('route')->distinct()->get();
        $clients = Clients::orderBy('route_number')->get();
        $products = Products::all();
        
        return view('pages.order_create', compact('routes', 'clients', 'products'));
    }

    public function store( Request $request )
    {
        // Setup the validator
        $rules = [
            'client_id' => 'required',
            'ordered_date' => 'required|date'
        ];

        $attributes = [
            'client_id' => '客戶',
            'ordered_date' => '訂購日期'
        ];
        
        $validator = \Validator::make( $request->all(), $rules, [], $attributes );

        // Validate the input and return correct response
        if ( $validator->fails() )
        {
            return \Response::json(array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()

            ), 400); // 400 being the HTTP code for an invalid request.
        }
        $items = $request->input('order_value');
        $flag = 0;
        foreach ( $items as $item )
        {
            // Setup the validator
            $rules = [
                'quantity' => 'required|integer|min:1'
            ];

            $attributes = [
                'quantity' => '數量'
            ];
            
            $validator = \Validator::make( $item, $rules, [], $attributes );

            // Validate the input and return correct response
            if ( $validator->fails() )
            {
                $flag++;
            }
        }
        if ( $flag == count( $items ) )
        {
            return \Response::json(array(
                    'success' => false,
                    'errors' => $validator->getMessageBag()->toArray()
            ), 400); // 400 being the HTTP code for an invalid request.
        }

        $client = Clients::find( $request->input('client_id') );

        $order = $client->orders()->create( $request->all() );
        
        foreach ( $items as $item )
        {
            if ( $item['quantity'] )
            {
                $ordered_product = new OrderProducts;
                $ordered_product->order_id = $order->id;
                $ordered_product->product_id = $item['product_id'];
                $ordered_product->quantity = $item['quantity'];
                $ordered_product->price = $item['price'];
                $ordered_product->total = $item['total'];
                $ordered_product->save();
            }
        }

        $request->session()->flash('success', '儲存成功');
        if ( $request->input('redirect') )
        {
            return \Response::json(array('success' => true, 'redirect' => route('orders.index')), 200);
        }
        return \Response::json(array('success' => true, 'redirect' => route('orders.create')), 200);
    }

    public function edit( $id )
    {
        try
        {
            \Crumbs::add('/orders', '訂單');
            \Crumbs::addCurrent("編輯");
            $results = Orders::with('clients', 'orderProducts.products')->findOrFail( $id );
            $client = Clients::select('is_small')->find( $results->client_id );
            if ( $client->is_small )
            {
                $product_prices = Products::select('id', 'price2 AS price')->get();
            }
            else
            {
                $product_prices = Products::select('id', 'price')->get();
            }

            $products = Products::select('products.id', 'products.name', 'products.unit', 'order_products.quantity', 'order_products.price', 'order_products.id AS order_products_id', 'order_products.total')
                                ->leftJoin('order_products', function($leftJoin)use($results)
                                {
                                    $leftJoin->on('order_products.product_id', '=', 'products.id')
                                            ->on('order_products.order_id', '=', DB::raw($results->id) );
                                })
                                ->orderBy('products.id')
                                ->get();

            return view('pages.order_edit', compact('results', 'products', 'product_prices'));
        }
        catch (ModelNotFoundException $err)
        {
            return \Redirect::action('OrdersController@index');
        }
    }

    public function update( Request $request )
    {
        // Setup the validator
        $rules = [
            'ordered_date' => 'required|date'
        ];

        $attributes = [
            'ordered_date' => '訂購日期'
        ];
        
        $validator = \Validator::make( $request->all(), $rules, [], $attributes );

        // Validate the input and return correct response
        if ( $validator->fails() )
        {
            return \Response::json(array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()

            ), 400); // 400 being the HTTP code for an invalid request.
        }

        Orders::findOrFail( $request->input('order_id') )
                ->fill( $request->all() )
                ->save();

        $items = $request->input('order_value');
        $flag = 0;
        foreach ( $items as $item )
        {
            // Setup the validator
            $rules = [
                'quantity' => 'required|integer|min:1'
            ];

            $attributes = [
                'quantity' => '數量'
            ];
            
            $validator = \Validator::make( $item, $rules, [], $attributes );

            // Validate the input and return correct response
            if ( $validator->fails() )
            {
                $flag++;
            }
        }
        if ( $flag == count( $items ) )
        {
            return \Response::json(array(
                    'success' => false,
                    'errors' => $validator->getMessageBag()->toArray()
            ), 400); // 400 being the HTTP code for an invalid request.
        }

        foreach ( $items as $item )
        {
            if ( $item['quantity'] > 0 )
            {
                OrderProducts::updateOrCreate(
                    ['id' => $item['order_product_id']],
                    [
                        'order_id' => $request->input('order_id'),
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'total' => $item['total']
                    ]
                );
            }
            else
            {
                OrderProducts::destroy( $item['order_product_id'] );
            }
        }

        if ( $request->input('redirect') )
        {
            $request->session()->flash('success', '儲存成功');
            return \Response::json(array('success' => true, 'redirect' => route('orders.index')), 200);
        }
        return \Response::json(array('success' => true, 'message' => '儲存成功'), 200);
    }

    public function destroy( Request $request )
    {
        // delete
        Orders::destroy( $request->input('id') );

        if ( $request->input('redirect') )
        {
            $request->session()->flash('success', '刪除成功');
            return \Response::json(array('success' => true, 'redirect' => route('orders.index')), 200);
        }

        return \Response::json(array('success' => true, 'message' => '刪除成功'), 200);
    }

    public function ajaxProductPrice( int $id = null )
    {
        if ( is_null($id) )
        {
            return \Response::json(array('success' => false,), 400);
        }
        
        $client = Clients::select('is_small')->find( $id );
        if ( $client->is_small )
        {
            $products = Products::select('id', 'price2 AS price')->get();
        }
        else
        {
            $products = Products::select('id', 'price')->get();
        }

        return \Response::json(array('success' => true, 'products' => $products), 200);
    }

    public function ajaxOrders( Request $request )
    {
        return Datatables::of(Orders::all())->make(true);
    }
}
