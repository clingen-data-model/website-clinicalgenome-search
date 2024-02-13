<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;


class Acmg extends JsonResource
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
            'symbol' => $this->gene->name,
            'hgnc_id' => $this->gene->hgnc_id,
            'disease_name' => $this->disease->label,
            'mondo' => $this->disease->curie,
            'clinvar_link' => $this->clinvar_link,
            'cspec_link' => 'https://cspec.genome.network/cspec/ui/svi/?search=' . $this->gene->name,
            'curation' => ($this->gene->hasActivity('dosage') ? 'D' : '') . 
                          ($this->gene->hasActivity('actionability') ? 'A' : '') . 
                          ($this->gene->hasActivity('validity') ? 'V' : '') . 
                          ($this->gene->hasActivity('varpath') ? 'R' : '') . 
                          ($this->gene->hasActivity('pharma') ? 'P' : ''),
            'has_actionability' => $this->gene->activity['actionability'] ?? false,
            'has_validity' => $this->gene->activity['validity'] ?? false,
            'has_dosage' => $this->gene->activity['dosage'] ?? false,
            'has_pharma' => $this->gene->activity['pharma'] ?? false,
            'has_variant' => $this->gene->activity['varpath'] ?? false,
            'type' => 0
        ];
    }
}
