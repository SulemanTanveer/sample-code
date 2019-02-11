<?php

namespace App\Http\Controllers\Admin;

use App\Models\Level\Level;
use App\Models\SupplyList;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Lang;
class LevelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Level::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Level::firstOrCreate(['name'=> $request->name]);
        return response()->json([
            'success'   =>  true,
            'message'   =>  Lang::get('messages.level_add')
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
     * @param  Level  $level
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Level $level)
    {
        $level->update(['name'=> $request->name]);

        return response()->json([
            'success'   =>  true,
            'message'   =>  Lang::get('messages.level_update')
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Level  $level
     * @return \Illuminate\Http\Response
     */
    public function destroy(Level $level)
    {
        $level->supplyList()->delete();
        $level->delete();

        return response()->json([
            'success'   =>  true,
            'message'   =>  Lang::get('messages.level_delete')
        ],200);

    }
}
