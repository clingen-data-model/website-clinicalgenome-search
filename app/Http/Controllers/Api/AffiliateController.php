<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\ApiRequest;
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
    public function index(ApiRequest $request)
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

        return ['total' => $results->count, 
                'totalNotFiltered' => $results->count,
                'rows'=> AffiliateResource::collection($results->collection),
                'ncurations' => $results->ncurations];
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ApiRequest $request, $id)
    {
        $input = $request->only(['search', 'order', 'offset', 'limit']);

        $results = GeneLib::AffiliateDetail([ 'affiliate' => $id ]);
        
        if ($results === null)
			die("throw an error");

        return ['total' => $results->count,
                'totalNotFiltered' => $results->count, 
                'id' => $results->label,
                'rows'=> AffiliateDetailResource::collection($results->collection)];
    }
}
