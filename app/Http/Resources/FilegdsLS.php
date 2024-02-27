<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\GeneLib;

class FilegdsLS extends JsonResource
{
    
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'gene_symbol' => $this->gene->label,
            'hgnc_id' => $this->gene->hgnc_id,
            'disease_label' => $this->disease->label,
            'disease_id' => $this->disease->curie,
            'moi' => $this->displayMoi($this->mode_of_inheritance->curie),
            'sop' => Genelib::ValidityCriteriaString($this->specified_by->label),
            'classification' => Genelib::ValidityClassificationString($this->classification->label),
            'online_report' => route('validity-show', ['id' => $this->curie]),
            'classification_date' => $this->report_date,
            'gcep' => $this->attributed_to->label ?? '',
            'included_mims' => (empty($this->las_included) ? "No Included MIM Phenotypes were specified"
                                                : implode(', ', $this->las_included)),
            'excluded_mims' => (empty($this->las_excluded) ? "No Excluded MIM Phenotypes were specified"
                                                : implode(', ', $this->las_excluded)),
            "evaluation_date" => (empty($this->las_date) ? "No Date was specified"
                                                : date('m/d/Y', strtotime($this->las_date))),
            "curation_type" => (empty($this->las_curation) ? "No curation type was specified"
                                                : $this->las_curation),
            "rationales" => (empty($this->las_rationale['rationales']) ? "No rationales were specified"
                                                : implode(', ', $this->las_rationale['rationales'])),
            "pmids" => (empty($this->las_rationale['pmids']) ? "No PMIDs were specified"
                                                : implode(', ', $this->las_rationale['pmids'])),
            "notes" => (empty($this->las_rationale['notes']) ? "No notes were specified"
                                                : $this->las_rationale['notes'])
        ];
    }
}
