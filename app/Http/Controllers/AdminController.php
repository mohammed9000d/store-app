<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $admins = Admin::all();
        return response()->json([
            'status' => true,
            'message' => 'Success',
            'admins' => $admins
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
            'name' => 'required|string|min:5|max:25',
            'email'  => 'required|email|unique:admins,email',
            'mobile' => 'required|numeric',
            'status' => 'required'
        ];

        $validator = Validator($request->all(), $roles);
        if(!$validator->fails()) {
            $admin = new Admin();
            $admin->name = $request->get('name');
            $admin->email = $request->get('email');
            $admin->mobile = $request->get('mobile');
            $admin->password = Hash::make('pass123$');
            $admin->status = $request->has('status') ? 'Active' : 'InActive';
            $isSaved = $admin->save();
            if($isSaved) {
                return ControllerHelper::response(true, 'admin Created successfully');
            }else {
                return ControllerHelper::response(false, 'Failed to create admin!');
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
            'id'=>'integer|exists:admins,id',
            'name' => 'required|string|min:5|max:25',
            'email'  => 'required|email|unique:admins,email'.$id,
            'mobile' => 'required|numeric|min:3',
            'status' => 'required'
        ];

        $validator = Validator($request->all(), $roles);
        if(!$validator->fails()) {
            $admin = Admin::find($id);
            $admin->name = $request->get('name');
            $admin->email = $request->get('email');
            $admin->mobile = $request->get('mobile');
            $admin->password = Hash::make('pass123$');
            $admin->status = $request->has('status') ? 'Active' : 'InActive';
            $isUpdated = $admin->save();
            if($isUpdated) {
                return ControllerHelper::response(true, 'admin Updated successfully');
            }else {
                return ControllerHelper::response(false, 'Failed to update admin!');
            }
        }else {
            return ControllerHelper::response(false, $validator->getMessageBag());
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
        $isDeleted = Admin::destroy($id);
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
