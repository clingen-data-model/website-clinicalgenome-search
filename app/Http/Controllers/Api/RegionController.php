<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\ApiRequest;
use App\Http\Resources\Genesearch as GenesearchResource;

use App\GeneLib;

class RegionController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(ApiRequest $request, $type = '', $region = '')
    {
        $input = $request->only(['search', 'order', 'offset', 'limit', 'region', 'type']);

        $results = GeneLib::geneRegionList(['page' => $input['offset'] ?? 0,
										'pagesize' => $input['limit'] ?? "null",
										'sort' => $sort ?? 'GENE_LABEL',
										'direction' => $input['order'] ?? 'ASC',
                                        'search' => $input['search'] ?? null,
                                        'type' => $type ?? '',
                                        'region' => $region ?? '',
										'curated' => true ]);

        if ($results === null)
            return GeneLib::getError();

        return ['total' => $results->count,
                'totalNotFiltered' => $results->count,
                'rows'=> GenesearchResource::collection($results->collection),
                'gene_count' => $results->gene_count,
                'region_count' => $results->region_count
                ];
    }
}
