<?php

namespace App\Http\Controllers\Admin;

use App\Models\TermsAndConditions;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Lang;
class TermsAndConditionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return TermsAndConditions::first();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

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
     * @param  int  $terms
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $terms)
    {
        $terms = TermsAndConditions::whereId($terms)->first();
        $terms->update($request->all());
        return response()->json([
            'success'   =>  true,
            'message'   =>  Lang::get('messages.terms_update')
        ],200);
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
