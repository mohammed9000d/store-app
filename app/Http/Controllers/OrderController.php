<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $orders = Order::all();
        return response()->json([
            'status' => true,
            'message' => 'Success',
            'orders' => $orders
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
            'user_id' => 'required|exists:users,id',
            'total' => 'required|numeric',
            'payment_status'  => 'required',
            'payment_type'  => 'required',
            'status' => 'required'
        ];

        $validator = Validator($request->all(), $roles);
        if(!$validator->fails()) {
            $product = new Order();
            $product->user_id = $request->get('user_id');
            $product->total = $request->get('total');
            $product->payment_status = $request->get('payment_status');
            $product->payment_type = $request->get('payment_type');
            $product->status = $request->has('status') ? 'Visible' : 'InVisible';
            $isSaved = $product->save();
            if($isSaved) {
                return ControllerHelper::response(true, 'Order created successfully');
            }else {
                return ControllerHelper::response(false, 'Failed to create order!');
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
            'id'=>'integer|exists:orders,id',
            'user_id' => 'required|exists:users,id',
            'total' => 'required|numeric',
            'payment_status'  => 'required',
            'payment_type'  => 'required',
            'status' => 'required'
        ];

        $validator = Validator($request->all(), $roles);
        if(!$validator->fails()) {
            $product = Order::find($id);
            $product->user_id = $request->get('user_id');
            $product->total = $request->get('total');
            $product->payment_status = $request->get('payment_status');
            $product->payment_type = $request->get('payment_type');
            $product->status = $request->has('status') ? 'Visible' : 'InVisible';
            $isUpdated = $product->save();
            if($isUpdated) {
                return ControllerHelper::response(true, 'Order updated successfully');
            }else {
                return ControllerHelper::response(false, 'Failed to update order!');
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
        $isDeleted = Order::destroy($id);
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
