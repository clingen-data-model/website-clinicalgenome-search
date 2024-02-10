<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\ApiRequest;
use App\Http\Resources\Gene as GeneResource;
use App\Http\Resources\Acmg as AcmgResource;

use App\GeneLib;
use App\Gene;
use App\Acmg;

class GeneController extends Controller
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
                                        'curated' => false ]);

        if ($results === null)
            return GeneLib::getError();

        return ['total' => $results->count,
                'totalNotFiltered' => $results->count,
                'rows'=> GeneResource::collection($results->collection),
                'search' => $input['search'] ?? null,
                'naction' => $results->naction,
                'ndosage' => $results->ndosage,
                'nvalid' => $results->nvalid,
                'npharma' => $results->npharma ?? 0,
                'nvariant' => $results->nvariant ?? 0,
                'ncurated' => $results->ncurated];
    }


    /**
     * Display a listing of the acmg entries.
     *
     * @return \Illuminate\Http\Response
     */
    public function acmg_index(ApiRequest $request)
    {

        $input = $request->only(['search', 'order', 'offset', 'limit']);

        $results = GeneLib::acmgList([	'page' => $input['offset'] ?? 0,
										'pagesize' => $input['limit'] ?? "null",
										'sort' => $sort ?? 'GENE_LABEL',
                                        'direction' => $input['order'] ?? 'ASC',
                                        'search' => $input['search'] ?? null,
                                        'curated' => false ]);

        if ($results === null)
            return GeneLib::getError();

        return ['total' => $results->count,
                'totalNotFiltered' => $results->count,
                'rows'=> AcmgResource::collection($results->collection),
                'ngenes' => $results->ngenes,
                'ndiseases' => $results->ndiseases];
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function look(Request $request, $term = null)
    {
        $results = GeneLib::geneLook([	'page' => $input['offset'] ?? 0,
										'pagesize' => $input['limit'] ?? "null",
										'sort' => $sort ?? 'GENE_LABEL',
                                        'direction' => $input['order'] ?? 'ASC',
                                        'search' => $term ?? null,
                                        'curated' => false ]);

        return $results;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function find(Request $request, $term = null)
    {
        $results = GeneLib::geneFind([	'page' => $input['offset'] ?? 0,
										'pagesize' => $input['limit'] ?? "null",
										'sort' => $sort ?? 'GENE_LABEL',
                                        'direction' => $input['order'] ?? 'ASC',
                                        'search' => $term ?? null,
                                        'curated' => false ]);

        return $results;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function expand(ApiRequest $request, $id = null)
    {
        // ...otherwise assume gene
        $gene = Gene::hgnc($id)->first();

        return view('gene.expand')
                    ->with('gene', $gene);
    }
}
