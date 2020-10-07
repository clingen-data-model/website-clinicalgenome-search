<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\GeneLib;

class Acmg59 extends JsonResource
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
            'key' => $this->key,
            'pheno' => $this->pheno,
            'omims' => $this->omims,
            'pmids' => $this->pmids,
            'age' => $this->age,
            'gene' => $this->gene,
            'omimgene' => $this->omimgene,
            'haplo_assertion' => $this->gain,
            'triplo_assertion' => $this->loss
            //'date' => $this->displayDate($this->dosage_report_date),
            //'rawdate' => $this->dosage_report_date
        ];
    }
}
