<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Resources\Affiliate as AffiliateResource;
use App\Http\Resources\AffiliateDetail as AffiliateDetailResource;

use Ahsan\Neo4j\Facade\Cypher;

use App\GeneLib;

class AffiliateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $input = $request->only(['search', 'order', 'offset', 'limit']);

        $results = GeneLib::affiliateList([	'page' => $input['offset'] ?? 0,
										'pagesize' => $input['limit'] ?? "null",
										'sort' => $sort ?? 'GENE_LABEL',
                                        'direction' => $input['order'] ?? 'ASC',
                                        'search' => $input['search'] ?? null,
                                        'curated' => false ]);
                                        
		if ($results === null)
			die(print_r(GeneLib::getError()));
//dd($results);
        return ['total' => $results->count, 'totalNotFiltered' => $results->count,
                'rows'=> AffiliateResource::collection($results->collection)];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $input = $request->only(['search', 'order', 'offset', 'limit']);

        $results = GeneLib::AffiliateDetail([ 'affiliate' => $id ]);
        
        if ($results === null)
			die("throw an error");

        return ['total' => $results->count, 'totalNotFiltered' => $results->count, 'id' => $results->label,
        'rows'=> AffiliateDetailResource::collection($results->collection)];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
