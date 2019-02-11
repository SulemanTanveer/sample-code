<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Lang;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return  User::parent()->with('profile')->get();
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
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return User::with([
                    'profile', 'children',
                    'orders', 'orders.shipment','orders.address','orders.orderStatus'])
                ->findOrFail($id);
    
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

    public function allUsers()
    {
        if(request('role'))
        {
            return User::byRole(request('role'))->get();
        }
        return User::get();
    }

    public function searchUser($keyword)
    {
        return User::where('email','like','%'.$keyword.'%')
            ->orWhereHas('profile',function($query)use ($keyword){
                $query->where('firstname','like','%'.$keyword.'%')
                    ->orWhere('surname','like','%'.$keyword.'%');
            })
            ->take(20)->get();
    }


}
