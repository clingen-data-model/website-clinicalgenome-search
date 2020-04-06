<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\Affiliate as AffiliateResource;

use Ahsan\Neo4j\Facade\Cypher;

use App\GeneLib;

class AffiliateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $display_tabs = collect([
            'active' => "affiliate",
            'query' => "",
            'category' => "",
            'counts' => [
                'total' => 'something',
                'dosage' => "1434",
                'gene_disease' => "500",
                'actionability' => "270",
                'variant_path' => "300"
            ]
        ]);

        $records = GeneLib::AffiliateList([]);
        //dd($records);
        if ($records === null)
            die("throw an error");

        return view('affiliate.index', compact('display_tabs', 'records'));
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
            'active' => "affiliate",
            'query' => "",
            'category' => "",
            'counts' => [
                'total' => 'something',
                'dosage' => "1434",
                'gene_disease' => "500",
                'actionability' => "270",
                'variant_path' => "300"
            ]
        ]);
        //
        $record = GeneLib::AffiliateDetail(['affiliate' => $id]);
        //dd($record);
        if ($record === null)
            die("throw an error");

        return view('affiliate.show', compact('display_tabs', 'record'));
    }

}
