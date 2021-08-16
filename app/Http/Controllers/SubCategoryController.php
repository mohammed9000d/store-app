<?php

namespace App\Http\Controllers;

use App\Models\Sub_category;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $sub_categories = Sub_category::all();
        return response()->json([
            'status' => true,
            'message' => 'Success',
            'sub_categories' => $sub_categories
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
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:45',
            'description'  => 'required|string',
            'status' => 'required'
        ];

        $validator = Validator($request->all(), $roles);
        if(!$validator->fails()) {
            $sub_category = new Sub_category();
            $sub_category->title = $request->get('title');
            $sub_category->description = $request->get('description');
            $sub_category->category_id = $request->get('category_id');
            $sub_category->status = $request->has('status') ? 'Visible' : 'InVisible';
            $isSaved = $sub_category->save();
            if($isSaved) {
                return ControllerHelper::response(true, 'sub_category created successfully');
            }else {
                return ControllerHelper::response(false, 'Failed to create sub_category!');
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
            'id'=>'integer|exists:sub_categories,id',
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:45',
            'description'  => 'required|string',
            'status' => 'required'
        ];

        $validator = Validator($request->all(), $roles);
        if(!$validator->fails()) {
            $sub_category = Sub_category::find($id);
            $sub_category->title = $request->get('title');
            $sub_category->description = $request->get('description');
            $sub_category->category_id = $request->get('category_id');
            $sub_category->status = $request->has('status') ? 'Visible' : 'InVisible';
            $isUpdated = $sub_category->save();
            if($isUpdated) {
                return ControllerHelper::response(true, 'sub_category updated successfully');
            }else {
                return ControllerHelper::response(false, 'Failed to update sub_category!');
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
        $isDeleted = Sub_category::destroy($id);
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
