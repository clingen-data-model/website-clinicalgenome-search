<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/*
    ATTENTION
    ATTENTION
    ATTENTION
    ATTENTION
    ATTENTION
    ATTENTION
    +++++++++++++++++++++++++++++++++++++++++++++++
        Actionability will redirect to ACI pages and reports
    +++++++++++++++++++++++++++++++++++++++++++++++
*/

class ActionabilityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // redirect to ACI
        $url = env('CG_URL_CURATIONS_ACTIONABILITY', 'http://www.clinicalgenome.org');
        return redirect()->away($url);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        // redirect to ACI
        $url = env('CG_URL_CURATIONS_ACTIONABILITY', 'http://www.clinicalgenome.org');
        return redirect()->away($url);
    }


}
