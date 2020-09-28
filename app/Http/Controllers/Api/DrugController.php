<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Resources\Drug as DrugResource;

use App\GeneLib;

class DrugController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $input = $request->only(['search', 'order', 'offset', 'limit']);
        
        $results = GeneLib::DrugList([	'page' => $input['offset'] ?? 0,
                                        'pagesize' => $input['limit'] ?? "null",
                                        'sort' => $sort ?? null,
                                        'search' => $input['search'] ?? null,
                                        'direction' => $input['order'] ?? 'ASC',
                                        'curated' => true
									]);

		if ($results === null)
			die(print_r(GeneLib::getError()));

        return ['total' => $results->count,
                'totalNotFiltered' => $results->count,
                'rows'=> DrugResource::collection($results->collection)];
    }
}
