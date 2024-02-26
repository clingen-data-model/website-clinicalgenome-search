<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\ApiRequest;
use App\Http\Resources\Gene as GeneResource;
use App\Http\Resources\Acmg as AcmgResource;

use App\GeneLib;
use App\Gene;
use App\Disease;
use App\Curation;
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
     * Expand a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function acmg_expand(ApiRequest $request, $id = null)
    {
        // ...otherwise assume gene
        $gene = Gene::with('curations')->hgnc($id)->first();

        $dids = $gene->curations->unique('disease_id')->pluck('disease_id')->toArray();

        $diseases = Disease::whereIn('id', $dids)->get();

        // match up the various activity scores for the matrix
        $scores = [];

        foreach ($diseases as $disease)
        {
            // validity
            $validity = $gene->curations->where('type', Curation::TYPE_GENE_VALIDITY)
                                        ->whereIn('status', [Curation::STATUS_ACTIVE, Curation::STATUS_ACTIVE_REVIEW])
                                        ->where('disease_id', $disease->id)->first();
            
            if ($validity !== null)
            {
                if ($validity->subtype == Curation::SUBTYPE_VALIDITY_GGP)
                {
                    $validity_score = GeneLib::validityClassificationString($validity->score_details['label']);
                    $validity_tooltip = GeneLib::validityMoiString($validity->scores['moi']);
                    $validity_moi = GeneLib::validityMoiAbvrString($validity->scores['moi']);
                }
                else if ($validity->subtype == Curation::SUBTYPE_VALIDITY_GCE)
                {
                    $validity_score = GeneLib::validityClassificationString($validity->score_details['label']);
                    $validity_tooltip = GeneLib::validityMoiString($validity->scores['moi']);
                    $validity_moi = GeneLib::validityMoiAbvrString($validity->scores['moi']);
                }
                else    // topic stream
                {
                    $validity_score = $validity->score_details['FinalClassification'];
                    $validity_moi = $validity->scores['moi'];

                    // remove the HP term
                    $validity_moi = substr($validity_moi, 0, strpos($validity_moi, ' (HP:0'));
                    $validity_tooltip = $validity_moi;
                    $validity_moi = GeneLib::validityMoiAbvrString($validity_moi);
                }
             
                // temp hack to deal with line length
                if ($validity_score == "No Known Disease Relationship")
                    $validity_score = "No Known";
            }
            else
            {
                $validity_score = $validity_tooltip = $validity_moi = null;
            }

            // dosage
            $dosage = $gene->curations->where('type', Curation::TYPE_DOSAGE_SENSITIVITY)
                                        ->whereIn('status', [Curation::STATUS_ACTIVE, Curation::STATUS_ACTIVE_REVIEW])
                                        ->where('disease_id', $disease->id)->first();

            if (isset($dosage->assertions['haploinsufficiency_assertion']))
            {
                $haplo_score = $dosage->assertions['haploinsufficiency_assertion']['dosage_classification']['ordinal'];
                $haplo_tooltip = GeneLib::shortAssertionString($haplo_score) . " for Haploinsufficiency";
            }
            else
            {
                $haplo_score = null;
                $haplo_tooltip = "";

            }

            if (isset($dosage->assertions['triplosensitivity_assertion']))
            {
                $triplo_score = $dosage->assertions['triplosensitivity_assertion']['dosage_classification']['ordinal'];
                $triplo_tooltip = GeneLib::shortAssertionString($triplo_score) . " for Triplosensitivity";
            }
            else
            {
                $triplo_score = null;
                $triplo_tooltip = "";
            }
            
            // actionability
            $actionability_link = ($disease->has_actionability ? "https://actionability.clinicalgenome.org/ac/" : null);


            // variant
            $variant_link = ($disease->has_variant ? "https://erepo.clinicalgenome.org/evrepo/ui/summary/classifications?columns=gene&values="
                             . $gene->name . "&matchTypes=exact&pgSize=25" : null);


            $scores[$disease->id] = ['validity_score' => $validity_score ?? null, 'validity_moi' => $validity_moi ?? null,
                                     'validity_tooltip' => $validity_tooltip ?? null,
                                     'variant_link' => $variant_link,
                                     'dosage_haplo_score' => $haplo_score ?? null, 'dosage_triplo_score' => $triplo_score ?? null,
                                     'dosage_haplo_tooltip' => $haplo_tooltip, 'dosage_triplo_tooltip' => $triplo_tooltip,
                                     'actionability_link' => $actionability_link
                                    ];
        }

                /*foreach($diseases as $disease)
                {
                    $activity = [];
                    if ($disease->curation_activities['dosage'])
                        $activity[] = 'GENE_DOSAGE';
                    if ($disease->curation_activities['validity'])
                        $activity[] = 'GENE_VALIDITY';
                    if ($disease->curation_activities['varpath'] ?? false)
                        $activity[] = 'VAR_PATH';
                    if ($disease->curation_activities['actionability'])
                        $activity[] = 'ACTIONABILITY';

                    $node = new Nodal([ 'gene_label' => $disease->label,
                                    'gene_hgnc_id' => $disease->curie,
                                    'disease_label' => $disease->label,
                                    'disease_mondo' => $disease->curie,
                                    'disease_count' => 1,
                                    'curation' => ($disease->hasActivity('dosage') ? 'D' : '') . 
                                                    ($disease->hasActivity('actionability') ? 'A' : '') . 
                                                    ($disease->hasActivity('validity') ? 'V' : '') . 
                                                    ($disease->hasActivity('varpath') ? 'R' : ''),
                                    'curation_activities' => $activity,
                                    'has_comment' => false,
                                    'comments' =>  $disease->notes ?? '',
                                    'reportable' => false,
                                    'id' => 100000 + $disease_index,
                                    'pid' => $gene->id,
                                    'type' => 3
                                    ]);
                    $collection->push($node);

                    $disease_index++;
                }*/

        return view('gene.acmg_expand')
                    ->with('gene', $gene)
                    ->with('scores', $scores)
                    ->with('diseases', $diseases);
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
