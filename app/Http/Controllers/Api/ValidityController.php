<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\ApiRequest;
use App\Http\Resources\Validity as ValidityResource;

use App\GeneLib;

class ValidityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ApiRequest $request)
    {
        $input = $request->only(['search', 'order', 'offset', 'limit']);
        
        $results = GeneLib::validityList([	'page' => $input['offset'] ?? 0,
											'pagesize' => $input['limit'] ?? "null",
                                            //'sort' => $sort ?? 'symbol',
                                            'sort' => $sort ?? 'GENE_LABEL',
											'search' => $input['search'] ?? null,
                                            'direction' => $input['order'] ?? 'ASC',
                                            'curated' => false
										]);

		if ($results === null)
			die(print_r(GeneLib::getError()));

        return ['total' => $results->count, 
                'totalNotFiltered' => $results->count,
                'rows'=> ValidityResource::collection($results->collection),
                'ngenes' => $results->ngenes,
                'npanels' => $results->npanels
                ];
    }

    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $record = GeneLib::AffiliateDetail([ 'affiliate' => $id ]);

        if ($record === null)
			die("throw an error");

        return new AffiliateResource($record);
    }
}
