<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Role\Role;
use App\User;
use Lang;
class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Role::get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        $data = $request->validate([
//            'users' => 'required|array|exists:users,id',
//            'roles' => 'required|array|exists:roles,id',
//        ]);
//
//        foreach ($data['users'] as $user) {
//            $findUser = User::where('id', $user)->first();
//            $findUser->roles()->sync($data['roles']);
//        }
//        return response([
//                'success' => true,
//                'message' => 'roles assigned'
//            ], 201);


        $user = User::whereId(request('user_id'))->first();
        $user->roles()->sync(request('roles'));
        return response()->json([
            'success'   =>  true,
            'message'   =>  Lang::get('messages.roles_update')
        ],200);
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
    }
}
