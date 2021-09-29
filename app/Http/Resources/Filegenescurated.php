<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\GeneLib;

class Filegenescurated extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //dd($this['gene_symbol']);
        return [
            'gene_symbol' => $this['gene_symbol'] ?? "",
            'hgnc_id' => $this[ 'hgnc_id'] ?? "",
            'gene_url' => $this['gene_url'] ?? "",
            'disease_label ' => $this['disease_label'] ?? "",
            'mondo_id' => $this['mondo_id'] ?? "",
            'disease_url' => $this['disease_url'] ?? "",
            'mode_of_inheritances' => $this[ 'mois'] ?? "",
            'gene_dosage_haploinsufficiency_assertion' => $this['haploinsufficiency_assertion'] ?? "",
            'gene_dosage_triplosensitivity_assertion ' => $this[ 'triplosensitivity_assertion'] ?? "",
            'gene_dosage_report' => $this['dosage_report'] ?? "",
            'dosage_group' => $this['dosage_group'] ?? "",
            'gene_disease_validity_assertion_classifications ' => $this[ 'gene_validity_assertion_classifications'] ?? "",
            'gene_disease_validity_assertion_reports' => $this[ 'gene_validity_assertion_reports'] ?? "",
            'gene_validity_gceps' => $this['gene_validity_gceps'] ?? "",
            'actionability_assertion_classifications ' => $this[ 'actionability_assertion_classifications'] ?? "",
            'actionability_assertion_reports' => $this[ 'actionability_assertion_reports'] ?? "",
            'actionability_groups' => $this['actionability_groups'] ?? "",
        ];
    }

            // 'disease_id' => $this->disease->curie,
            // 'moi' => $this->displayMoi($this->mode_of_inheritance->curie),
            // 'sop' => Genelib::ValidityCriteriaString($this->specified_by->label),
            // 'classification' => Genelib::ValidityClassificationString($this->classification->label),
            // 'online_report' => route('validity-show', ['id' => $this->curie]),
            // 'classification_date' => $this->report_date,
            // 'gcep' => $this->attributed_to->label ?? ''
}
