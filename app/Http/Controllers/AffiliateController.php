<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\Affiliate as AffiliateResource;

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

        // set display context for view
        $display_tabs = collect([
            'active' => "affiliate",
            'title' => "Gene Curation Expert Panels"
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
    public function show(Request $request, $id, $page = 1, $size = 100)
    {
        // set display context for view
        $display_tabs = collect([
            'active' => "affiliate",
            'title' => "ClinGen Expert Panel Curations"
        ]);

        // the affiliate id is expected to be numeric.
        if (!ctype_digit($id))
            $id = 0;

        return view('affiliate.show', compact('display_tabs'))
                        ->with('apiurl', '/api/affiliates/' . $id)
                        ->with('pagesize', $size)
                        ->with('page', $page);
    }

}
