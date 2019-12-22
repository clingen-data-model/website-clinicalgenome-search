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
        This Contoller is available demo only... please use ValidtyController for acual work
    +++++++++++++++++++++++++++++++++++++++++++++++
*/

class GeneValidityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    $display_tabs = collect([
            'active'                            => "gene_disease",
            'query'                             => "",
            'counts'    => [
                'dosage'                        => "1434",
            'gene_disease'          => "500",
            'actionability'         => "270",
            'variant_path'          => "300"]
    ]);
        return view('gene-disease-validity.index', compact('display_tabs'));
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
            'active'                            => "gene_disease",
            'query'                             => "",
            'counts'    => [
                'dosage'                        => "1434",
            'gene_disease'          => "500",
            'actionability'         => "270",
            'variant_path'          => "300"]
    ]);
        return view('gene-disease-validity.show', compact('display_tabs'));
    }

}
