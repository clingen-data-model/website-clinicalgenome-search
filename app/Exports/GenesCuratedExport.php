<?php

namespace App\Exports;

use App\GeneLib;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

use App\Http\Resources\Filegenescurated;

use Carbon\Carbon;

class GenesCuratedExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $results = GeneLib::geneListForExportReport(['page' => 0,
										'pagesize' => 20000,
										'sort' => 'GENE_LABEL',
										'direction' => 'ASC',
										'search' => '',
                                        'curated' => true ]);

        foreach ($results->collection as $gene) {
            $haploinsufficiency_assertion = '';
            $triplosensitivity_assertion = '';
            $dosage_report = '';
            $dosage_group = '';
            
            if (isset($gene->dosage_curation)) {
                //dd($gene->dosage_curation->haploinsufficiency_assertion->dosage_classification);
                    //foreach ($gene->dosage_curation as $dosage_curation) {
                        if(isset($gene->dosage_curation->haploinsufficiency_assertion->dosage_classification->ordinal)) {
                            $report_date = strtotime($gene->dosage_curation->report_date);
                            $report_date = date("m/d/Y", $report_date);
                            $haploinsufficiency_assertion .= "" . $gene->dosage_curation->haploinsufficiency_assertion->dosage_classification->ordinal . " - " . \App\GeneLib::haploAssertionString($gene->dosage_curation->haploinsufficiency_assertion->dosage_classification->ordinal ?? null) . " (" . $report_date .")";
                        }
                        if(isset($gene->dosage_curation->triplosensitivity_assertion->dosage_classification->ordinal)) {
                            $report_date = strtotime($gene->dosage_curation->report_date);
                            $report_date = date("m/d/Y", $report_date);
                    $triplosensitivity_assertion .= "" . $gene->dosage_curation->triplosensitivity_assertion->dosage_classification->ordinal . " - " . \App\GeneLib::haploAssertionString($gene->dosage_curation->triplosensitivity_assertion->dosage_classification->ordinal ?? null) . " (" . $report_date . ")";
                        }
                    //}
                    $dosage_report = route('dosage-show', $gene->hgnc_id);
                    $dosage_group = "Dosage Working Group";
            }

            if (isset($gene->genetic_conditions)) {
                foreach ($gene->genetic_conditions as $genetic_condition) {
                    //dd($genetic_condition->disease);

                    $mois = array();
                    $gene_validity_assertions_classifications = array();
                    $gene_validity_assertion_reports = array();
                    $gene_validity_gceps = array();
                    $actionability_assertion_classifications = array();
                    $actionability_assertions_reports = array();
                    $actionability_groups = array();

                    if (isset($genetic_condition->gene_validity_assertions)) {
                        //dd("gene_validity_assertions");
                        foreach ($genetic_condition->gene_validity_assertions as $gene_validity_assertion) {
                            //dd($gene_validity_assertion);
                            $report_date = strtotime($gene_validity_assertion->report_date);
                            $report_date = date("m/d/Y", $report_date);
                            $gene_validity_assertions_classifications[] = $gene_validity_assertion->classification->label . " (" . $report_date . ")";
                            $mois[] = $gene_validity_assertion->mode_of_inheritance->label;
                            $gene_validity_assertion_reports[] = route('validity-show', ['id' => $gene_validity_assertion->curie]);
                            $gene_validity_gceps[] = $gene_validity_assertion->attributed_to->label;
                        }
                    }
                    if (isset($genetic_condition->actionability_assertions)) {

                        foreach ($genetic_condition->actionability_assertions as $actionability_assertions) {
                            $report_date = strtotime($actionability_assertions->report_date);
                            $report_date = date("m/d/Y", $report_date);
                            $actionability_assertion_classifications[] = $actionability_assertions->classification->label . " (" . $report_date . ")";
                            //$mois[] = $actionability_assertions->mode_of_inheritance->label;
                           //$actionability_assertions_reports[] = route('actionability-show', ['id' => $actionability_assertions->source]);
                            $actionability_assertions_reports[] = $actionability_assertions->source;
                            $actionability_groups[] = $actionability_assertions->attributed_to->label;
                        }
                    }
                    if($mois) {
                        $mois = implode(' | ', $mois);
                    } else {
                        $mois = "N/A";
                    }
                    $gene_validity_assertions_classifications = implode(' | ', $gene_validity_assertions_classifications);
                    $gene_validity_assertion_reports = implode(' | ', $gene_validity_assertion_reports);
                    $actionability_assertion_classifications = implode(' | ', $actionability_assertion_classifications);
                    $actionability_assertions_reports = implode(' | ', $actionability_assertions_reports);
                    $gene_validity_gceps = implode(' | ', $gene_validity_gceps);
                    $actionability_groups = implode(' | ', $actionability_groups);

                    $return[] = [
                        'gene_symbol' => $gene->label,
                        'hgnc_id' => $gene->hgnc_id,
                        'gene_url' => route('gene-show', ['id' => $gene->hgnc_id]),
                        'disease_label' => $genetic_condition->disease->label,
                        'mondo_id' => $genetic_condition->disease->curie,
                        'disease_url' => route('condition-show', ['id' => $genetic_condition->disease->curie]),
                        'mois' => $mois,
                        'haploinsufficiency_assertion' => $haploinsufficiency_assertion,
                        'triplosensitivity_assertion' => $triplosensitivity_assertion,
                        'dosage_report' => $dosage_report,
                        'dosage_group' => $dosage_group,
                        'gene_validity_assertion_classifications' => $gene_validity_assertions_classifications,
                        'gene_validity_assertion_reports' => $gene_validity_assertion_reports,
                        'gene_validity_gceps' => $gene_validity_gceps,
                        'actionability_assertion_classifications' => $actionability_assertion_classifications,
                        'actionability_assertion_reports' => $actionability_assertions_reports,
                        'actionability_groups' => $actionability_groups,

                    ];
                    //dd($return);
                }
            }
        }

        //dd($return);

        return Filegenescurated::collection($return);
    }

    public function headings(): array
    {
        return [
            ["ClinGen Curation Activity Summary Report - FILE CREATED: " . Carbon::now()->format('Y-m-d')],
            ["README 1: ", " This file provides summary curation information about Gene-Disease Validity, Dosage Sensitivity, and Clinical Actionability. Curations for each activity are grouped by gene (HGNC) and disease (MONDO)."],
            ["README 2: ", " Noted columns may have multiple entries: mode_of_inheritance, gene_disease_validity_assertion_classifications, gene_disease_validity_assertion_reports, gene_disease_validity_gceps, actionability_assertion_classifications, actionability_assertion_reports, and actionability_groups.  If so, the entries will be seperated by a | (pipe)."],
            ["gene_symbol", "hgnc_id", "gene_url", "disease_label", "mondo_id", "disease_url", "mode_of_inheritance", "dosage_haploinsufficiency_assertion", "dosage_triplosensitivity_assertion", "dosage_report", "dosage_group", "gene_disease_validity_assertion_classifications", "gene_disease_validity_assertion_reports", "gene_disease_validity_gceps", "actionability_assertion_classifications","actionability_assertion_reports", "actionability_groups"],
        ];
    }


    /**
    * @return \Illuminate\Support\Collection
    */
    public function newcollection()
    {
        $items = [];

		// get all the genes with active curations
		$genes = Gene::whereHas('curations', function ($query) {
			$query->whereIn('status', [Curation::STATUS_ACTIVE, Curation::STATUS_ACTIVE_REVIEW]);
		})->with('curations')->orderBy('name')->get();

		// process each gene, extracting and formating by disease
		foreach ($genes as $gene)
		{
			$results = self::curation_report($gene);

            foreach ($results as $result)
			    $items[] = $result;
		}
        
        return Filegenescurated::collection($items);
    }


     /**
     * Expand a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function curation_report($gene)
    {
        $filter = false;     // set to true to filter out non-preferred disease terms;

        $curations = $gene->curations;

        if ($filter == 'preferred_only')
        {
            $curations = $curations->filter(function ($item) {
                return (($item->type != Curation::TYPE_ACTIONABILITY) ||
                        ($item->type == Curation::TYPE_ACTIONABILITY && $item->conditions[0] == $item->evidence_details[0]['curie']));
            });
        }

        $dids = $curations->unique('disease_id')->pluck('disease_id')->toArray();

        $diseases = Disease::whereIn('id', $dids)->orderBy('curie')->get();

		$scores = [];

        foreach ($diseases as $disease)
        {
			$score = [
				'gene_symbol' => $gene->name,
				'hgnc_id' => $gene->hgnc_id,
				'gene_url' => 'https://search.clinicalgenome.org/kb/genes/' . $gene->hgnc_id,
				'disease_label' => $disease->label,
				'mondo_id' => $disease->curie,
				'disease_url' => 'https://searcj/clinicalgenome.org/kb/conditions/' . $disease->curie,
				'mois' => 'N/A',
				'haploinsufficiency_assertion' => null,
				'triplosensitivity_assertion' => null,
				'dosage_report' => null,			// https://search.clinicalgenome.org/kb/gene-dosage/ . $gene->hgnc_id
				'dosage_group' => null,			// Dosage Working Group
				'gene_validity_assertion_classifications' => null,
				'gene_validity_assertion_reports' => null,		// https://search.clinicalgenome.org/kb/gene-validity/ . $validity_assertion_id
				'gene_validity_gceps' => null,		// validity gcep
				'actionability_assertion_classifications' => null,
				'actionability_assertion_reports' => null,					// https://actionability.clinicalgenome.org/ac/Adult/ui/stg2SummaryReport?doc= . adult_docid | https://actionability.clinicalgenome.org/ac/Adult/ui/stg2SummaryReport?doc= . ped_docid
				'actionability_groups' => null,					// Adult Pediatric Actionability Working Group
				// Variant stuff?
			];

            // validity
            $validity = $gene->curations->where('type', Curation::TYPE_GENE_VALIDITY)
                                        ->whereIn('status', [Curation::STATUS_ACTIVE, Curation::STATUS_ACTIVE_REVIEW])
                                        ->where('disease_id', $disease->id)->first();

            if ($validity !== null)
            {
                if ($validity->subtype == Curation::SUBTYPE_VALIDITY_GGP)
                {
                    $score['gene_validity_assertion_classifications'] = $validity->score_details['label'] . ' (' . $validity->displayDate($validity->events['report_date'])  . ')';
                    $score['mois'] = $validity->scores['moi'];
					$score['gene_validity_gceps'] = $validity->affiliate_details['label'];
                }
                else if ($validity->subtype == Curation::SUBTYPE_VALIDITY_GCE)
                {
                    $score['gene_validity_assertion_classifications']  = $validity->score_details['label'] . ' (' . $validity->displayDate($validity->events['report_date'])  . ')';
                    $score['mois'] = $validity->scores['moi'];
					$score['gene_validity_gceps'] = $validity->affiliate_details['label'];
                }
                else    // topic stream
                {
                    $score['gene_validity_assertion_classifications']  = $validity->score_details['FinalClassification'] . ' (' . $validity->displayDate($validity->events['report_date'])  . ')';
                    $validity_moi = $validity->scores['moi'];

                    // remove the HP term
                    $score['mois'] = substr($validity_moi, 0, strpos($validity_moi, ' (HP:0'));
					$score['gene_validity_gceps'] = $validity->affiliate_details['label'];
				}

                $score['gene_validity_assertion_reports'] = "https://search.clinicalgenome.org/kb/gene-validity/" . $validity->source_uuid;
            }

            // dosage
            $dosage = $gene->curations->where('type', Curation::TYPE_DOSAGE_SENSITIVITY)
                                        ->whereIn('status', [Curation::STATUS_ACTIVE, Curation::STATUS_ACTIVE_REVIEW])
                                        ->where('disease_id', $disease->id)->first();

            if (isset($dosage->assertions['haploinsufficiency_assertion']))
            {
                $score['haploinsufficiency_assertion'] = GeneLib::wordAssertionString($dosage->assertions['haploinsufficiency_assertion']['dosage_classification']['ordinal']);
            }

            if (isset($dosage->assertions['triplosensitivity_assertion']))
            {
                $score['triplosensitivity_assertion'] = GeneLib::wordAssertionString($dosage->assertions['triplosensitivity_assertion']['dosage_classification']['ordinal']);
            }

			$score['dosage_group'] = ($dosage === null ? null : 'Dosage Working Group');
            $score['dosage_report'] = ($dosage === null ? null : "https://search.clinicalgenome.org/kb/gene-dosage/" . $dosage->gene_hgnc_id);

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
				$score['actionability_assertion_classifications'] = $adult_score;
                $score['actionability_assertion_reports'] = "https://actionability.clinicalgenome.org/ac/Adult/ui/stg2SummaryRpt?doc=" . $adult->document;
                $score['actionability_groups'] = 'Adult Pediatric Actionability Working Group';
            }

            $ped_score = $actionability_ped_link = null;

            if ($ped !== null)
            {
                $ped_score = $ped->assertions['assertion'];
                $score['actionability_assertion_classifications'] = $ped_score;
                $score['actionability_assertion_reports'] = "https://actionability.clinicalgenome.org/ac/Pediatric/ui/stg2SummaryRpt?doc=" . $ped->document;
                $score['actionability_groups'] = 'Pediatric Actionability Working Group';
            }


            // variant
			/*
            $variant = $gene->curations->where('type', Curation::TYPE_VARIANT_PATHOGENICITY)
                                    ->whereIn('status', [Curation::STATUS_ACTIVE, Curation::STATUS_ACTIVE_REVIEW])
                                    ->where('disease_id', $disease->id)->first();

            $variant_link = ($variant !== null ? "https://erepo.clinicalgenome.org/evrepo/ui/summary/classifications?columns=gene,mondoId&values="
                             . $gene->name . "," . $disease->curie
                             . "&matchTypes=exact,exact&pgSize=25&pg=1&matchMode=and" : null);
			*/
			$scores[] = $score;
        }

        // Check if there are gene level dosage classifications
		/*
        $dosages = $gene->curations->where('type', Curation::TYPE_DOSAGE_SENSITIVITY)
                                        ->whereIn('status', [Curation::STATUS_ACTIVE, Curation::STATUS_ACTIVE_REVIEW])
                                        ->whereNull('disease_id');

        if ($dosages->isNotEmpty())
        {
            $haplo_gene_score = $haplo_gene_tooltip = $triplo_gene_score = $triplo_gene_tooltip = null;

            foreach($dosages as $dosage)
            {
                if (isset($dosage->assertions['haploinsufficiency_assertion']))
                {
                    $haplo_gene_score = $dosage->assertions['haploinsufficiency_assertion']['dosage_classification']['ordinal'];
                    $haplo_gene_tooltip = GeneLib::shortAssertionString($haplo_gene_score) . " for Haploinsufficiency";
                    $haplo_gene_score = GeneLib::wordAssertionString($haplo_gene_score);
                }


                if (isset($dosage->assertions['triplosensitivity_assertion']))
                {
                    $triplo_gene_score = $dosage->assertions['triplosensitivity_assertion']['dosage_classification']['ordinal'];
                    $triplo_gene_tooltip = GeneLib::shortAssertionString($triplo_gene_score) . " for Triplosensitivity";
                    $triplo_gene_score = GeneLib::wordAssertionString($triplo_gene_score);
                }
            }

            $gene_scores = ['dosage_haplo_gene_score' => $haplo_gene_score ?? null, 'dosage_triplo_gene_score' => $triplo_gene_score ?? null,
                        'dosage_haplo_gene_tooltip' => $haplo_gene_tooltip, 'dosage_triplo_gene_tooltip' => $triplo_gene_tooltip,
                        'dosage_link' => "/kb/gene-dosage/" . $dosage->gene_hgnc_id
                        ];

        }*/

		/*
        usort($scores, function($a, $b){
            if ($a['validity_order'] == $b['validity_order'])
                return strcasecmp($a['disease'], $b['disease']);

            return ($a['validity_order'] > $b['validity_order'] ? -1 : 1);
        });
		*/

		return ($scores);
    }
}
