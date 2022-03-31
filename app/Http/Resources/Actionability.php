<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\GeneLib;

class Actionability extends JsonResource
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
            'diseases' => $this->diseases,
            'adults' => $this->adults,
            'pediatrics' => $this->pediatrics,
        ];
    }
}
