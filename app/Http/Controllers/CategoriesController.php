<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $categories = Category::all();
        return response()->json([
            'status' => true,
            'message' => 'Success',
            'categories' => $categories
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
            'title' => 'required|string|max:45',
            'description'  => 'required|string',
            'status' => 'required'
        ];

        $validator = Validator($request->all(), $roles);
        if(!$validator->fails()) {
            $category = new Category();
            $category->title = $request->get('title');
            $category->description = $request->get('description');
            $category->status = $request->has('status') ? 'Visible' : 'InVisible';
            $isSaved = $category->save();
            if($isSaved) {
                return ControllerHelper::response(true, 'Category created successfully');
            }else {
                return ControllerHelper::response(false, 'Failed to create category!');
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
            'id'=>'integer|exists:categories,id',
            'title' => 'required|string|max:45',
            'description'  => 'required|string',
            'status' => 'required'
        ];

        $validator = Validator($request->all(), $roles);
        if(!$validator->fails()) {
            $category = Category::find($id);
            $category->title = $request->get('title');
            $category->description = $request->get('description');
            $category->status = $request->has('status') ? 'Visible' : 'InVisible';
            $isUpdated = $category->save();
            if($isUpdated) {
                return ControllerHelper::response(true, 'Category updated successfully');
            }else {
                return ControllerHelper::response(false, 'Failed to update category!');
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
        $isDeleted = Category::destroy($id);
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
