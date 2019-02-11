<?php

namespace App\Http\Controllers\Api;

use App\Models\Children\Child;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Lang;
class ChildController extends Controller
{
    
    /**
     * [store child info]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function store(Request $request)
    {
        $validator = $request->validate([
//            'firstname' => 'required',
//            'surname' => 'required',
            'city_id' => 'required',
            'school_id' => 'required',
            'school_level_id' => 'required'
        ]);

         $child = Child::updateOrCreate([
                'parent_id' => auth()->id(),
                'firstname' => $request->firstname,
//                'surname'   => $request->surname,
                'birthdate' => $request->birthdate,
            ],[
                'firstname'     =>  $request->firstname,
//                'surname'       =>  $request->surname,
                'birthdate'     =>  $request->birthdate,
                'sexe'          =>  $request->sexe ? true : false,
                'parent_id'     =>  auth()->id(),
                'city_id'       =>  $request->city_id,
                'school_id'     =>  $request->school_id,
                'school_level_id'   =>  $request->school_level_id,
            ]);
        
        return response()->json([
            'success'   => true,
            'message'   => Lang::get('messages.child_save'),
            'child_id'  => $child->id
        ], 200);
    }

    /**
     * [update child info]
     * @param  Child  $child [description]
     * @return [type]        [description]
     */
    public function update(Child $child)
    {
        $child = Child::where('id', $child)->first();
        try
        {
            $child->surname = request('surname');
            $child->firstname = request('firstname');
            $child->picture = request('picture');
            $child->birthdate = request('birthdate');
            $child->sexe = request('sexe')? true : false;
            $child->city_id =  request('city_id');
            $child->school_id =  request('school_id');
            $child->school_level_id =  request('school_level_id');
            $child->save();

            return response()->json([
                'success'=>true,
                'message'=>'successfully updated'
            ],200);
        }
        catch (\Throwable $throwable)
        {
            return response()->json([
                'success'   =>  false,
                'message'   =>  'something went wrong'
            ],500);
        }
    }

    /**
     * [show specific child info]
     * @param  Child  $child [description]
     * @return [type]        [description]
     */
    public function show(Child $child)
    {
        return $child;
    }

    public function remove(Child $child)
    {
        $child->delete();

        return response()->json([
            'success'=>true,
            'message'=>Lang::get('messages.child_delete')
        ], 201);
    }

}
