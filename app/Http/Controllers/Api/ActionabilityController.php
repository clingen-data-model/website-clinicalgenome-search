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
        $input = $request->only(['search', 'order', 'offset', 'limit', 'context']);

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

        $context = $input['context'] ?? null;

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
                if (empty($condition->actionability_assertions))
                    continue;

                // because of a genegraph anomoly, have to parse throug the diseases too
                $diseases[] = $condition->disease;

                $adult_entry = null;
                $pedentry = null;

                // Map the proper adult and ped fields and deal with the strange way genegraph handles diseases
                foreach ($condition->actionability_assertions as $assertion)
                {
                    if ($assertion->attributed_to->label == "Adult Actionability Working Group" && $context != "periatric")
                    {
                        $adult_entry = [ 'report_date' => (empty($assertion->report_date) ? null : Carbon::parse($assertion->report_date)->format('m/d/Y')),
                                    'source' => $assertion->source,
                                    'attributed_to' => $assertion->attributed_to->label,
                                    'classification' => Genelib::actionabilityAssertionString($assertion->classification->label)
                                ];
                        $total++;

                    }
                    if ($assertion->attributed_to->label == "Pediatric Actionability Working Group" && $context != "adult")
                    {
                        $pedentry = [ 'report_date' => (empty($assertion->report_date) ? null : Carbon::parse($assertion->report_date)->format('m/d/Y')),
                                    'source' => $assertion->source,
                                    'attributed_to' => $assertion->attributed_to->label,
                                    'classification' => Genelib::actionabilityAssertionString($assertion->classification->label)
                                ];
                        $total++;
                    }
                }

                //ugly quick add for context
                if ($context == "adult" && $adult_entry == null)
                    continue;

                if ($context == "pediatric" && $pedentry == null)
                    continue;

                if ($adult_entry !== null & $pedentry !== null)
                {
                    $adults[] = $adult_entry;
                    $pediatrics[] = $pedentry;
                }
                else if ($adult_entry === null)
                {
                    $adults[] = $adult_entry;
                    $pediatrics[] = $pedentry;
                }
                else{
                    $adults[] = $adult_entry;
                    $pediatrics[] = $pedentry;
                }


            }

            $node->diseases = $diseases;
            $node->adults = $adults;
            $node->pediatrics = $pediatrics;

            if (($context == "adult" && empty($adults)) || ($context == "pediatric" && empty($pediatrics)))
                continue;

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
