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
            'symbol' => $this->gene_label,
            'hgnc_id' => $this->gene_hgnc_id,
            'disease_name' => $this->disease_label,
            'mondo' => $this->disease_mondo,
            'clinvar_link' => $this->clinvar_link ?? '/clinvar/?term=' . $this->gene_label . '%5Bgene%5D+AND+(clinsig_pathogenic%5Bprop%5D+OR+clinsig_likely_pathogenic%5Bprop%5D)+AND+"single+gene"[prop]',
            'cspec_link' => 'https://cspec.genome.network/cspec/ui/svi/?search=' . $this->gene_label,
            'curation' => $this->curation ?? 'V',
            'has_actionability' => $this->has_actionability ?? false,
            'has_validity' => $this->has_validity ?? false,
            'has_dosage' => $this->has_dosage ?? false,
            'has_pharma' => $this->has_pharma ?? false,
            'has_variant' => $this->has_variant ?? false,
            'has_comment' => $this->has_comment ?? false,
            'comments' => $this->comments ?? '',
            'reportable' => $this->reportable ?? false,
            'type' => $this->type ?? 0
        ];
    }
}
