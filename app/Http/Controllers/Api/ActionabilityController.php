<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\ApiRequest;
use App\Http\Resources\Actionability as ActionabilityResource;

use App\GeneLib;
use App\Nodal;

use Carbon\Carbon;

class ActionabilityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ApiRequest $request)
    {
        $input = $request->only(['search', 'order', 'offset', 'limit']);

        $results = GeneLib::actionabilityList([	'page' => $input['offset'] ?? 0,
											'pagesize' => $input['limit'] ?? "null",
                                            //'sort' => $sort ?? 'symbol',
                                            'sort' => $sort ?? 'GENE_LABEL',
											'search' => $input['search'] ?? null,
                                            'direction' => $input['order'] ?? 'ASC',
                                            'report' => true,
                                            'curated' => false
										]);

		if ($results === null)
            return GeneLib::getError();

        // restructure the collection
        $assertions = collect();

        $total = 0;

        foreach ($results->collection as $gene)
        {
            $diseases = [];
            $adults = [];
            $pediatrics = [];

            $node = new Nodal([
                                'gene_label' => $gene->label,
                                'gene_hgnc_id' => $gene->hgnc_id,
                                'status' => 1
                            ]);

            foreach($gene->genetic_conditions as $condition)
            {
                $diseases[] = $condition->disease;

                // Map the proper adult and ped fields
                foreach ($condition->actionability_assertions as $assertion)
                {
                    if ($assertion->attributed_to->label == "Adult Actionability Working Group")
                    {
                        $adults[] = [ 'report_date' => (empty($assertion->report_date) ? null : Carbon::parse($assertion->report_date)->format('m/d/Y')),
                                    'source' => $assertion->source,
                                    'attributed_to' => $assertion->attributed_to->label,
                                    'classification' => $assertion->classification->label
                                ];
                        $total++;

                    }
                    else
                    {
                        $adults[] = null;
                    }
                    if ($assertion->attributed_to->label == "Pediatric Actionability Working Group")
                    {
                        $pediatrics[] = [ 'report_date' => (empty($assertion->report_date) ? null : Carbon::parse($assertion->report_date)->format('m/d/Y')),
                                    'source' => $assertion->source,
                                    'attributed_to' => $assertion->attributed_to->label,
                                    'classification' => $assertion->classification->label
                                ];
                        $total++;
                    }
                    else
                    {
                        $pediatrics[] = null;
                    }
                }
            }

            $node->diseases = $diseases;
            $node->adults = $adults;
            $node->pediatrics = $pediatrics;

            $assertions->push($node);
        }

        return ['total' => $assertions->count(),
                'totalNotFiltered' => $assertions->count(),
                'rows'=> ActionabilityResource::collection($assertions),
                'nassert' => $total,
                'npanels' => 2
                ];
    }
}
