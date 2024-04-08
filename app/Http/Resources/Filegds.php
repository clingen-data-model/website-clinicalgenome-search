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
            'gene_symbol' => $this->label,
            'hgnc_id' => $this->hgnc_id,
            'disease_label' => $this->disease,
            'disease_id' => $this->mondo,
            'moi' => $this->displayMoi($this->moi),
            'sop' => Genelib::ValidityCriteriaString($this->sop),
            'classification' => Genelib::ValidityClassificationString($this->classification),
            'online_report' => route('validity-show', ['id' => $this->perm_id]),
            'classification_date' => $this->released,
            'gcep' => $this->ep ?? ''
        ];
    }
}
