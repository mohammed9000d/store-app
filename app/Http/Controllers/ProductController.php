<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $products = Product::all();
        return response()->json([
            'status' => true,
            'message' => 'Success',
            'products' => $products
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
            'sub_category_id' => 'required|exists:sub_categories,id',
            'name' => 'required|string|max:45',
            'description'  => 'required|string',
            'price'  => 'required|numeric',
            'stock'  => 'required|integer',
            'status' => 'required'
        ];

        $validator = Validator($request->all(), $roles);
        if(!$validator->fails()) {
            $product = new Product();
            $product->name = $request->get('name');
            $product->description = $request->get('description');
            $product->price = $request->get('price');
            $product->stock = $request->get('stock');
            $product->sub_category_id = $request->get('sub_category_id');
            $product->status = $request->has('status') ? 'Visible' : 'InVisible';
            $isSaved = $product->save();
            if($isSaved) {
                return ControllerHelper::response(true, 'product created successfully');
            }else {
                return ControllerHelper::response(false, 'Failed to create product!');
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
            'id'=>'integer|exists:products,id',
            'sub_category_id' => 'required|exists:sub_categories,id',
            'name' => 'required|string|max:45',
            'description'  => 'required|string',
            'price'  => 'required|numeric',
            'stock'  => 'required|integer',
            'status' => 'required'
        ];

        $validator = Validator($request->all(), $roles);
        if(!$validator->fails()) {
            $product = Product::find($id);
            $product->name = $request->get('name');
            $product->description = $request->get('description');
            $product->price = $request->get('price');
            $product->stock = $request->get('stock');
            $product->sub_category_id = $request->get('sub_category_id');
            $product->status = $request->has('status') ? 'Visible' : 'InVisible';
            $isUpdated = $product->save();
            if($isUpdated) {
                return ControllerHelper::response(true, 'product updated successfully');
            }else {
                return ControllerHelper::response(false, 'Failed to update product!');
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
        $isDeleted = Product::destroy($id);
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
