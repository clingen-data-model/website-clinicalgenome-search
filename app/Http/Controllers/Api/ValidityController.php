<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\ApiRequest;
use App\Http\Resources\Validity as ValidityResource;

use App\GeneLib;
use App\Curation;

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
            return GeneLib::getError();

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
            return GeneLib::getError();

        return new AffiliateResource($record);
    }



    /**
     * Display the summary notes of the curation
     *
     * @return \Illuminate\Http\Response
     */
    public function expand(ApiRequest $request, $id = null)
    {

        
        $curation = Curation::validity()->active()->sid($id)->first();

        if ($curation === null)
            return null;


        return view('gene-validity.expand')
                ->with('curation', $curation);
    }
}
