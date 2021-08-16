<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $users = User::all();
        return response()->json([
            'status' => true,
            'message' => 'Success',
            'users' => $users
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
            'email'  => 'required|email|unique:users,email',
            'mobile' => 'required|numeric',
            'status' => 'required'
        ];

        $validator = Validator($request->all(), $roles);
        if(!$validator->fails()) {
            $user = new User();
            $user->name = $request->get('name');
            $user->email = $request->get('email');
            $user->mobile = $request->get('mobile');
            $user->password = Hash::make('pass123$');
            $user->status = $request->has('status') ? 'Active' : 'InActive';
            $isSaved = $user->save();
            if($isSaved) {
                return ControllerHelper::response(true, 'User Created successfully');
            }else {
                return ControllerHelper::response(false, 'Failed to create user!');
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
            'id'=>'integer|exists:users,id',
            'name' => 'required|string|min:5|max:25',
            'email'  => 'required|email|unique:users,email'.$id,
            'mobile' => 'required|numeric|min:3',
            'status' => 'required'
        ];

        $validator = Validator($request->all(), $roles);
        if(!$validator->fails()) {
            $user = User::find($id);
            $user->name = $request->get('name');
            $user->email = $request->get('email');
            $user->mobile = $request->get('mobile');
            $user->password = Hash::make('pass123$');
            $user->status = $request->has('status') ? 'Active' : 'InActive';
            $isUpdated = $user->save();
            if($isUpdated) {
                return ControllerHelper::response(true, 'User Updated successfully');
            }else {
                return ControllerHelper::response(false, 'Failed to update user!');
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
        $isDeleted = User::destroy($id);
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
