<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\ApiRequest;
use App\Http\Resources\Dosage as DosageResource;

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

        return ['total' => $results->count, 
                'totalNotFiltered' => $results->count,
                'rows'=> DosageResource::collection($results->collection),
                'nhaplo' => $results->nhaplo,
                'ntriplo' => $results->ntriplo];
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
}
