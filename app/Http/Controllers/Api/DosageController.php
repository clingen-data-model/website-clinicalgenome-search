<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\ApiRequest;
use App\Http\Resources\Dosage as DosageResource;
use App\Http\Resources\Region as RegionResource;
use App\Http\Resources\Acmg59 as Acmg59Resource;

use App\GeneLib;

class DosageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ApiRequest $request)
    {
        $input = $request->only(['search', 'order', 'offset', 'limit']);

        $results = GeneLib::dosageList(['page' => $input['offset'] ?? 0,
										'pagesize' => $input['limit'] ?? "null",
										'sort' => $sort ?? 'GENE_LABEL',
										'direction' => $input['order'] ?? 'ASC',
										'search' => $input['search'] ?? null,
                                        'curated' => true ]);
                                    
        if ($results === null)
            return GeneLib::getError();

        // query the regions
        $jresults = GeneLib::regionList(['page' =>  0,
                                        'pagesize' =>  "null",
                                        'sort' => 'GENE_LABEL',
                                        'direction' =>  'ASC',
                                        'search' =>  null,
                                        'curated' => false ]);

        if ($jresults === null)
            return GeneLib::getError();

        // combine the results

        return ['total' => $results->count + $jresults->count, 
                'totalNotFiltered' => $results->count + $jresults->count,
                'rows'=> DosageResource::collection($results->collection->concat($jresults->collection)),
                'nhaplo' => $results->nhaplo + $jresults->nhaplo,
                'ntriplo' => $results->ntriplo + $jresults->ntriplo];
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function cnv(ApiRequest $request)
    {
        $input = $request->only(['search', 'order', 'offset', 'limit']);

        $results = GeneLib::cnvList(['page' => $input['offset'] ?? 0,
										'pagesize' => $input['limit'] ?? "null",
										'sort' => $sort ?? 'GENE_LABEL',
										'direction' => $input['order'] ?? 'ASC',
										'search' => $input['search'] ?? null,
										'curated' => true ]);
        
        if ($results === null)
            return GeneLib::getError();

        return ['total' => $results->count, 
                'totalNotFiltered' => $results->count,
                'rows'=> RegionResource::collection($results->collection),
                'nhaplo' => $results->nhaplo,
                'ntriplo' => $results->ntriplo];
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function acmg59(ApiRequest $request)
    {
        $input = $request->only(['search', 'order', 'offset', 'limit']);

        $results = GeneLib::acmg59List(['page' => $input['offset'] ?? 0,
										'pagesize' => $input['limit'] ?? "null",
										'sort' => $sort ?? 'GENE_LABEL',
										'direction' => $input['order'] ?? 'ASC',
										'search' => $input['search'] ?? null,
										'curated' => true ]);
        
        if ($results === null)
            return GeneLib::getError();

        return ['total' => $results->count, 
                'totalNotFiltered' => $results->count,
                'rows'=> Acmg59Resource::collection($results->collection),
                'nhaplo' => $results->nhaplo,
                'ntriplo' => $results->ntriplo];
    }
}
