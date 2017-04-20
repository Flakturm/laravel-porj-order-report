<?php

namespace turnip\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use turnip\Products;

class ProductsController extends Controller
{
    public function index()
    {
        $results = Products::all();

        \Crumbs::add('/products', '產品');
        
        return view('pages.products', compact('results'));
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
            'name' => 'required|unique:products,name',
            'price' => 'required|numeric|regex:/^\d*(\.\d{1,2})?$/',
            'price2' => 'required|numeric|regex:/^\d*(\.\d{1,2})?$/',
            'unit' => 'required',
        ];

        $attributes = [
            'name' => '產品',
            'price' => '單價',
            'price2' => '單價(小)',
            'unit' => '單位'
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

        $product = Products::create( $request->all() );

        return \Response::json(array('success' => true, 'record_id' => $product->id), 200);
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
        $rules = [
            'name' => 'required|unique:products,name,' . $request->input('id'),
            'price' => 'required|numeric|regex:/^\d*(\.\d{1,2})?$/',
            'price2' => 'required|numeric|regex:/^\d*(\.\d{1,2})?$/',
            'unit' => 'required',
        ];

        $attributes = [
            'name' => '產品',
            'price' => '單價',
            'price2' => '單價(小)',
            'unit' => '單位'
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

        Products::findOrFail( $request->input('id') )
                ->fill( $request->all() )
                ->save();

        return \Response::json(array('success' => true), 200);
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
        Products::destroy( $request->input('id') );

        return \Response::json(array('success' => true), 200);
    }
}
