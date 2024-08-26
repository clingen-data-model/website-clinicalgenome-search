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
use App\Region;

class SearchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ApiRequest $request)
    {
    }


    /**
     * Expand a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function expand(ApiRequest $request, $id = null)
    {
        $filter = false;     // set to true to filter out non-preferred disease terms;

        if (substr($id, 0, 5) == "ISCA-")
        {
            $gene = Region::with('curations')->curie($id)->first();
            $dosage_type = Curation::TYPE_DOSAGE_SENSITIVITY_REGION;
        }
        else
        {
            $gene = Gene::with('curations')->hgnc($id)->first();
            $dosage_type = Curation::TYPE_DOSAGE_SENSITIVITY;
        }

        $curations = $gene->curations->whereIn('status', [Curation::STATUS_ACTIVE, Curation::STATUS_ACTIVE_REVIEW]);

        // if there are no curations, just return a prefab expansion
        if ($curations->isEmpty())
            return view('region.search_expand_blank')->with('type', $dosage_type);

        if ($filter == 'preferred_only')
        {
            $curations = $curations->filter(function ($item) {
                return (($item->type != Curation::TYPE_ACTIONABILITY) ||
                        ($item->type == Curation::TYPE_ACTIONABILITY && $item->conditions[0] == $item->evidence_details[0]['curie']));
            });
        }

        $dids = $curations->unique('disease_id')->pluck('disease_id')->toArray();

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
                    $validity_tooltip = $validity_score;
                    $validity_moi = GeneLib::validityMoiAbvrString($validity->scores['moi']);
                }
                else if ($validity->subtype == Curation::SUBTYPE_VALIDITY_GCE)
                {
                    $validity_score = GeneLib::validityClassificationString($validity->score_details['label']);
                    $validity_tooltip = $validity_score;
                    $validity_moi = GeneLib::validityMoiAbvrString($validity->scores['moi']);
                }
                else    // topic stream
                {
                    $validity_score = $validity->score_details['FinalClassification'];
                    $validity_moi = $validity->scores['moi'];

                    // remove the HP term
                    $validity_moi = substr($validity_moi, 0, strpos($validity_moi, ' (HP:0'));
                    $validity_tooltip = $validity_score;
                    $validity_moi = GeneLib::validityMoiAbvrString($validity_moi);
                }
             
                // temp hack to deal with line length
                if ($validity_score == "No Known Disease Relationship")
                    $validity_score = "No Known";

                $validity_link = "/kb/gene-validity/" . $validity->source_uuid;
            }
            else
            {
                $validity_score = $validity_tooltip = $validity_moi = null;
            }

            // dosage
            $dosages = $gene->curations->where('type', $dosage_type)
                                        ->whereIn('status', [Curation::STATUS_ACTIVE, Curation::STATUS_ACTIVE_REVIEW])
                                        ->where('disease_id', $disease->id);

            $haplo_score = null;
            $haplo_tooltip = "";
            $triplo_score = null;
            $triplo_tooltip = "";
            $dosage_link = '#';

            foreach ($dosages as $dosage)
            {
                switch ($dosage->context)
                {
                    case 'haploinsufficiency_assertion':
                        if (isset($dosage->scores['classification']))
                        {
                            $haplo_score = $dosage->scores['classification'];
                            if ($haplo_score == "30: Gene associated with autosomal recessive phenotype")
                                $haplo_score = 30;
                            else if ($haplo_score == "40: Dosage sensitivity unlikely")
                                $haplo_score = 40;
                            else if ($haplo_score == "Not yet evaluated")
                                $haplo_score = -5;
                            $haplo_tooltip = GeneLib::shortAssertionString($haplo_score) . " for Haploinsufficiency";
                            $haplo_score = GeneLib::wordAssertionString($haplo_score);
                        }
                        else
                        {
                            if (isset($dosage->scores['haploinsufficiency_assertion']))
                            {
                                $haplo_score = $dosage->scores['haploinsufficiency_assertion'];
                                $haplo_tooltip = GeneLib::shortAssertionString($haplo_score) . " for Haploinsufficiency";
                                $haplo_score = GeneLib::wordAssertionString($haplo_score);
                            }
                            else if (isset($dosage->scores['haploinsufficiency']))
                            {
                                $haplo_score = $dosage->scores['haploinsufficiency']['value'];

                                // for 30 and 40, Jira also sends text
                                if ($haplo_score == "30: Gene associated with autosomal recessive phenotype")
                                    $haplo_score = 30;
                                else if ($haplo_score == "40: Dosage sensitivity unlikely")
                                    $haplo_score = 40;
                                else if ($haplo_score == "Not yet evaluated")
                                    $haplo_score = -5;
                                $haplo_tooltip = GeneLib::shortAssertionString($haplo_score) . " for Haploinsufficiency";
                                $haplo_score = GeneLib::wordAssertionString($haplo_score);
                            }
                        }
                        break;
                    case 'triplosensitivity_assertion':
                        if (isset($dosage->scores['classification']))
                        {
                            $triplo_score = $dosage->scores['classification'];
                            if ($triplo_score == "30: Gene associated with autosomal recessive phenotype")
                                $triplo_score = 30;
                            else if ($triplo_score == "40: Dosage sensitivity unlikely")
                                $triplo_score = 40;
                            else if ($triplo_score == "Not yet evaluated")
                                $triplo_score = -5;

                            $triplo_tooltip = GeneLib::shortAssertionString($triplo_score) . " for Triplosensitivity";
                            $triplo_score = GeneLib::wordAssertionString($triplo_score);
                        }
                        else
                        {
                            if (isset($dosage->scores['triplosensitivity_assertion']))
                            {
                                $triplo_score = $dosage->scores['triplosensitivity_assertion'];
                                $triplo_tooltip = GeneLib::shortAssertionString($triplo_score) . " for Triplosensitivity";
                                $triplo_score = GeneLib::wordAssertionString($triplo_score);
                            }
                            else if (isset($dosage->scores['triplosensitivity']))
                            {
                                $triplo_score = $dosage->scores['triplosensitivity']['value'];

                                // for 30 and 40, Jira also sends text
                                if ($triplo_score == "30: Gene associated with autosomal recessive phenotype")
                                    $triplo_score = 30;
                                else if ($triplo_score == "40: Dosage sensitivity unlikely")
                                    $triplo_score = 40;
                                else if ($triplo_score == "Not yet evaluated")
                                    $triplo_score = -5;

                                $triplo_tooltip = GeneLib::shortAssertionString($triplo_score) . " for Triplosensitivity";
                                $triplo_score = GeneLib::wordAssertionString($triplo_score);
                            }
                        }
                        break;
                    }

                $dosage_link = "/kb/gene-dosage/" . $dosage->gene_hgnc_id;
            }

            // actionability
            $adult = $gene->curations->where('type', Curation::TYPE_ACTIONABILITY)
                                        ->whereIn('status', [Curation::STATUS_ACTIVE, Curation::STATUS_ACTIVE_REVIEW])
                                        ->where('context', 'Adult')
                                        ->where('disease_id', $disease->id)->first();

            $ped = $gene->curations->where('type', Curation::TYPE_ACTIONABILITY)
                                        ->whereIn('status', [Curation::STATUS_ACTIVE, Curation::STATUS_ACTIVE_REVIEW])
                                        ->where('context', 'Pediatric')
                                        ->where('disease_id', $disease->id)->first();

            $adult_score = $actionability_adult_link = null;

            if ($adult !== null)
            {
                $adult_score = $adult->assertions['assertion'];
                $adult_long_score = $adult_score;
                $adult_score = strtok($adult_score, " "); // extract first word
                if ($adult_score == "Assertion")
                    $adult_score = "Assert Pend";
                else if ($adult_score == "N/A")
                    $adult_score = "Early RO";
                $actionability_adult_link = "https://actionability.clinicalgenome.org/ac/Adult/ui/stg2SummaryRpt?doc=" . $adult->document;

            }

            $ped_score = $actionability_ped_link = null;
            
            if ($ped !== null)
            {
                $ped_score = $ped->assertions['assertion'];
                $ped_long_score = $ped_score;
                $ped_score = strtok($ped_score, " "); // extract first word
                if ($ped_score == "Assert Pend")
                    $ped_score = "Pending";
                else if ($ped_score == "N/A")
                    $ped_score = "Early RO";
                $actionability_ped_link = "https://actionability.clinicalgenome.org/ac/Pediatric/ui/stg2SummaryRpt?doc=" . $ped->document;

            }


            // variant
            $variant = $gene->curations->where('type', Curation::TYPE_VARIANT_PATHOGENICITY)
                                    ->whereIn('status', [Curation::STATUS_ACTIVE, Curation::STATUS_ACTIVE_REVIEW])
                                    ->where('disease_id', $disease->id)->first();
                    
            $variant_link = ($variant !== null ? "https://erepo.clinicalgenome.org/evrepo/ui/summary/classifications?columns=gene,mondoId&values="
                             . $gene->name . "," . $disease->curie
                             . "&matchTypes=exact,exact&pgSize=25&pg=1&matchMode=and" : null);

            $scores[$disease->id] = ['validity_score' => $validity_score ?? null, 'validity_moi' => $validity_moi ?? null,
                                     'validity_tooltip' => $validity_tooltip ?? null, 'validity_link' => $validity_link ?? '#',
                                     'variant_link' => $variant_link,
                                     'dosage_haplo_score' => $haplo_score ?? null, 'dosage_triplo_score' => $triplo_score ?? null,
                                     'dosage_haplo_tooltip' => $haplo_tooltip, 'dosage_triplo_tooltip' => $triplo_tooltip,
                                     'dosage_link' => $dosage_link,
                                     'actionability_adult_score' => $adult_score, 'actionability_pediatric_score' => $ped_score,
                                     'actionability_adult_tooltip' => $adult_long_score ?? '', 'actionability_ped_tooltip' => $ped_long_score ?? '',
                                     'actionability_adult_link' => $actionability_adult_link, 'actionability_pediatric_link' => $actionability_ped_link,
                                    ];
        }
        
        // Check if there are gene level dosage classifications
        $dosages = $gene->curations->where('type', $dosage_type)
                                        ->whereIn('status', [Curation::STATUS_ACTIVE, Curation::STATUS_ACTIVE_REVIEW])
                                        ->whereNull('disease_id');

        if ($dosages->isNotEmpty())
        {
            $haplo_gene_score = $haplo_gene_tooltip = $triplo_gene_score = $triplo_gene_tooltip = null;

            foreach($dosages as $dosage)
            {
                switch ($dosage->context)
                {
                    case 'haploinsufficiency_assertion':
                        if (isset($dosage->scores['classification']))
                        {
                            $haplo_gene_score = $dosage->scores['classification'];
                            
                            if ($haplo_gene_score == "30: Gene associated with autosomal recessive phenotype")
                                $haplo_gene_score = 30;
                            else if ($haplo_gene_score == "40: Dosage sensitivity unlikely")
                                $haplo_gene_score = 40;
                            $haplo_gene_tooltip = GeneLib::shortAssertionString($haplo_gene_score) . " for Haploinsufficiency";
                            $haplo_gene_score = GeneLib::wordAssertionString($haplo_gene_score);
                        }
                        else
                        {
                            if (isset($dosage->scores['haploinsufficiency_assertion']))
                            {
                                $haplo_gene_score = $dosage->scores['haploinsufficiency_assertion'];
                                $haplo_gene_tooltip = GeneLib::shortAssertionString($haplo_gene_score) . " for Haploinsufficiency";
                                $haplo_gene_score = GeneLib::wordAssertionString($haplo_gene_score);
                            }
                            else if (isset($dosage->scores['haploinsufficiency']))
                            {
                                $haplo_gene_score = $dosage->scores['haploinsufficiency']['value'];

                                // for 30 and 40, Jira also sends text
                                if ($haplo_gene_score == "30: Gene associated with autosomal recessive phenotype")
                                    $haplo_gene_score = 30;
                                else if ($haplo_gene_score == "40: Dosage sensitivity unlikely")
                                    $haplo_gene_score = 40;

                                $haplo_gene_tooltip = GeneLib::shortAssertionString($haplo_gene_score) . " for Haploinsufficiency";
                                $haplo_gene_score = GeneLib::wordAssertionString($haplo_gene_score);
                            }
                        }
                        break;
                    case 'triplosensitivity_assertion':
                        if (isset($dosage->scores['classification']))
                        {
                            $triplo_gene_score = $dosage->scores['classification'];
                            if ($triplo_gene_score == "30: Gene associated with autosomal recessive phenotype")
                                $triplo_gene_score = 30;
                            else if ($triplo_gene_score == "40: Dosage sensitivity unlikely")
                                $triplo_gene_score = 40;
                           
                            $triplo_gene_tooltip = GeneLib::shortAssertionString($triplo_gene_score) . " for Triplosensitivity";
                            $triplo_gene_score = GeneLib::wordAssertionString($triplo_gene_score);
                        }
                        else
                        {
                            if (isset($dosage->scores['triplosensitivity_assertion']))
                            {
                                $triplo_gene_score = $dosage->scores['triplosensitivity_assertion'];
                                $triplo_gene_tooltip = GeneLib::shortAssertionString($triplo_gene_score) . " for Triplosensitivity";
                                $triplo_gene_score = GeneLib::wordAssertionString($triplo_gene_score);
                            }
                            else if (isset($dosage->scores['triplosensitivity']))
                            {
                                $triplo_gene_score = $dosage->scores['triplosensitivity']['value'];

                                // for 30 and 40, Jira also sends text
                                if ($triplo_gene_score == "30: Gene associated with autosomal recessive phenotype")
                                    $triplo_gene_score = 30;
                                else if ($triplo_gene_score == "40: Dosage sensitivity unlikely")
                                    $triplo_gene_score = 40;

                                $triplo_gene_tooltip = GeneLib::shortAssertionString($triplo_gene_score) . " for Triplosensitivity";
                                $triplo_gene_score = GeneLib::wordAssertionString($triplo_gene_score);
                            }
                        }
                        break;
                }

            }

            $scores[0] = ['dosage_haplo_gene_score' => $haplo_gene_score ?? null, 'dosage_triplo_gene_score' => $triplo_gene_score ?? null,
                        'dosage_haplo_gene_tooltip' => $haplo_gene_tooltip, 'dosage_triplo_gene_tooltip' => $triplo_gene_tooltip,
                        'dosage_link' => "/kb/gene-dosage/" . $dosage->gene_hgnc_id
                        ];

        }

        return view('region.search_expand')
                    ->with('gene', $gene)
                    ->with('scores', $scores)
                    ->with('diseases', $diseases);
    }
}
