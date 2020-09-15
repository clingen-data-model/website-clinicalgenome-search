<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\GeneLib;

class Filegds extends JsonResource
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
            'gcep' => $this->attributed_to->label ?? ''
        ];
    }
}
