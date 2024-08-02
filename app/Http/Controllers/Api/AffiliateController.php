<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\ApiRequest;
use App\Http\Resources\Affiliate as AffiliateResource;
use App\Http\Resources\AffiliateDetail as AffiliateDetailResource;

use Ahsan\Neo4j\Facade\Cypher;

use App\GeneLib;
use App\Panel;

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
            return GeneLib::getError();

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
        //dd($results);
        if ($results === null)
            return GeneLib::getError();

            //dd(AffiliateDetailResource::collection($results->collection));

        return ['total' => $results->count,
                'totalNotFiltered' => $results->count,
                'ngenes' => $results->ngenes,
                'id' => $results->label,
                'rows'=> AffiliateDetailResource::collection($results->collection, $results->label)];
    }


     /**
     * Display the summary notes of the curation
     *
     * @return \Illuminate\Http\Response
     */
    public function expand(ApiRequest $request, $id = null)
    {

        
        $panel = Panel::affiliate($id)->first();

        if ($panel === null)
            return null;


        return view('affiliate.expand')
                ->with('panel', $panel);
    }
}
