<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SchoolRequest;
use App\Models\Level\Level;
use App\Models\School\School;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Lang;
class SchoolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return School::paginate(20);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SchoolRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(SchoolRequest $request)
    {
        $school = School::create([
            'name'      =>  $request->name,
            'street'    =>  $request->street??null,
            'zip'       =>  $request->zip,
            'city_id'   =>  $request->city_id,
            'geolloc_lt'       =>  $request->geolloc_lt,
            'geolloc_lg'   =>  $request->geolloc_lg
        ]);
        $levels = collect($request->levels)->pluck('id');
        $school->levels()->sync($levels);
        return response()->json([
            'success'   =>  true,
            'message'   =>  Lang::get('messages.school_added')
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       return School::whereId($id)->with(['levels'])->first();
       
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SchoolRequest $request, School $school)
    {
        $school->name = $request->name;
        $school->street = $request->street;
        $school->zip = $request->zip;
        $school->city_id = $request->city_id;
        $school->geolloc_lt = $request->geolloc_lt;
        $school->geolloc_lg = $request->geolloc_lg;

        $levels = collect($request->levels)->pluck('id');
        $school->levels()->sync($levels);

        $school->save();

        return response()->json([
            'success'   =>  true,
            'message'   =>  Lang::get('messages.school_update')
        ],201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  School  $school
     * @return \Illuminate\Http\Response
     */
    public function destroy(School $school)
    {
        $school->supplyList()->delete();
//        $school->levels()->delete();
        $school->delete();

        return response()->json([
            'success'   =>  true,
            'message'   =>  Lang::get('messages.school_delete')
        ],200);
    }

    public function addLevelToSchool(School $school, $level)
    {
        $school->levels()->attach($level);

        return response()->json([
            'success'   =>  true,
            'message'   =>  Lang::get('messages.add_level_to_school')
        ],200);
    }

    public function searchSchool($query)
    {
        return School::where('name','like','%'.$query.'%')
            ->orWhereHas('city',function ($q) use($query){
                $q->where('name','like','%'.$query.'%');
            })
            ->orWhere('street','like','%'.$query.'%')
            ->paginate(20);
    }
}