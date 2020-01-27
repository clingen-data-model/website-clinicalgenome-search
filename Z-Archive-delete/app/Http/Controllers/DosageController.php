<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DosageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    $display_tabs = collect([
            'active'                            => "dosage",
            'query'                             => "",
            'counts'    => [
                'dosage'                        => "1434",
            'gene_disease'          => "500",
            'actionability'         => "270",
            'variant_path'          => "300"]
    ]);
        return view('gene-dosage.index', compact('display_tabs'));
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id = '')
    {
    $display_tabs = collect([
            'active'                            => "gene",
            'query'                             => "BRCA2",
            'counts'    => [
                'dosage'                        => "1434",
                'gene_disease'                  => "500",
                'actionability'                 => "270",
                'variant_path'                  => "300"
            ]
    ]);
        return view('gene-dosage.show', compact('display_tabs'));
    }

    public function stats()
    {
    $display_tabs = collect([
            'active'                            => "dosage",
            'query'                             => "",
            'counts'    => [
                'dosage'                        => "1434",
            'gene_disease'          => "500",
            'actionability'         => "270",
            'variant_path'          => "300"]
    ]);
        return view('gene-dosage.stats', compact('display_tabs'));
    }

    public function reports()
    {
    $display_tabs = collect([
            'active'                            => "dosage",
            'query'                             => "",
            'counts'    => [
                'dosage'                        => "1434",
            'gene_disease'          => "500",
            'actionability'         => "270",
            'variant_path'          => "300"]
    ]);
        return view('gene-dosage.reports', compact('display_tabs'));
    }

    public function download()
    {
    $display_tabs = collect([
            'active'                            => "dosage",
            'query'                             => "",
            'counts'    => [
                'dosage'                        => "1434",
            'gene_disease'          => "500",
            'actionability'         => "270",
            'variant_path'          => "300"]
    ]);
        return view('gene-dosage.downloads', compact('display_tabs'));
    }

}
