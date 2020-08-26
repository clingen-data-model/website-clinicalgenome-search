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
    public function index(Request $request, $page = 1, $size = 100)
    {
        // process request args
		foreach ($request->only(['page', 'size', 'order', 'sort', 'search']) as $key => $value)
            $$key = $value;

        /* build cqching of these values with cross-section updates
        * total counts for gene and diseases on relevant pages
        * category would be for setting default select of dropdown */
        $display_tabs = collect([
            'active' => "gene",
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

        return view('affiliate.index', compact('display_tabs'))
                        ->with('apiurl', '/api/affiliates')
                        ->with('pagesize', $size)
                        ->with('page', $page);

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
