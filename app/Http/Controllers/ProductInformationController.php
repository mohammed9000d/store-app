<?php

namespace App\Http\Controllers;

use App\Models\ProductInformation;
use Illuminate\Http\Request;

class ProductInformationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $product_informations = ProductInformation::all();
        return response()->json([
            'status' => true,
            'message' => 'Success',
            'products' => $product_informations
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $roles = [
            'product_id' => 'required|exists:products,id',
            'bar_code' => 'required|string|max:45',
            'purchasing_price'  => 'required|numeric',
            'purchased_count'  => 'required|integer',
            'status' => 'required'
        ];

        $validator = Validator($request->all(), $roles);
        if(!$validator->fails()) {
            $product = new ProductInformation();
            $product->bar_code = $request->get('bar_code');
            $product->purchasing_price = $request->get('purchasing_price');
            $product->purchased_count = $request->get('purchased_count');
            $product->product_id = $request->get('product_id');
            $isSaved = $product->save();
            if($isSaved) {
                return ControllerHelper::response(true, 'product_information created successfully');
            }else {
                return ControllerHelper::response(false, 'Failed to create product_information!');
            }
        }else {
            return ControllerHelper::response(false, $validator->getMessageBag()->first());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $request->request->add(['id'=>$id]);
        $roles = [
            'id'=>'integer|exists:product_informations,id',
            'product_id' => 'required|exists:products,id',
            'bar_code' => 'required|string|max:45',
            'purchasing_price'  => 'required|numeric',
            'purchased_count'  => 'required|integer',
            'status' => 'required'
        ];

        $validator = Validator($request->all(), $roles);
        if(!$validator->fails()) {
            $product = ProductInformation::find($id);
            $product->bar_code = $request->get('bar_code');
            $product->purchasing_price = $request->get('purchasing_price');
            $product->purchased_count = $request->get('purchased_count');
            $product->product_id = $request->get('product_id');
            $isUpdated = $product->save();
            if($isUpdated) {
                return ControllerHelper::response(true, 'product_information updated successfully');
            }else {
                return ControllerHelper::response(false, 'Failed to update product_information!');
            }
        }else {
            return ControllerHelper::response(false, $validator->getMessageBag()->first());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $isDeleted = ProductInformation::destroy($id);
        if($isDeleted) {
            return response()->json([
                'status' => true,
                'message' => 'Deleted successfully',
            ]);
        }else {
            return response()->json([
                'status' => false,
                'message' => 'Delete failed!'
            ], 400);
        }
    }
}
