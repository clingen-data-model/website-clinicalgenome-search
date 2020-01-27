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
        This feature is not required for the 4.0 release
        Controller available for testing and tinkering only
        WISHLIST would be to pull data from the e-repo
    +++++++++++++++++++++++++++++++++++++++++++++++
*/

class VariantPathController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $url = env('CG_URL_CURATIONS_VARANTS', 'http://www.clinicalgenome.org');
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
        $url = env('CG_URL_CURATIONS_VARANTS', 'http://www.clinicalgenome.org');
        return redirect()->away($url);
    }
}
