<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\ApiRequest;
use App\Http\Resources\Curated as CuratedResource;

use App\GeneLib;

class CurationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ApiRequest $request)
    {
        $input = $request->only(['search', 'order', 'offset', 'limit']);

        $results = GeneLib::geneList([	'page' => $input['offset'] ?? 0,
										'pagesize' => $input['limit'] ?? "null",
										'sort' => $sort ?? 'GENE_LABEL',
                                        'direction' => $input['order'] ?? 'ASC',
                                        'search' => $input['search'] ?? null,
                                        'curated' => true ]);
                                        
        if ($results === null)
            return GeneLib::getError();

        return ['total' => $results->count, 
                'totalNotFiltered' => $results->count,
                'rows'=> CuratedResource::collection($results->collection),
                'naction' => $results->naction,
                'ndosage' => $results->ndosage,
                'nvalid' => $results->nvalid];
    }
}
