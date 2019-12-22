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
    $display_tabs = collect([
            'active'                            => "variant_path",
            'query'                             => "",
            'counts'    => [
                'dosage'                        => "1434",
            'gene_disease'          => "500",
            'actionability'         => "270",
            'variant_path'          => "300"]
    ]);
        return view('variant-path.index', compact('display_tabs'));
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    $display_tabs = collect([
            'active'                            => "variant_path",
            'query'                             => "",
            'counts'    => [
                'dosage'                        => "1434",
            'gene_disease'          => "500",
            'actionability'         => "270",
            'variant_path'          => "300"]
    ]);
        return view('variant-path.show', compact('display_tabs'));
    }
}
