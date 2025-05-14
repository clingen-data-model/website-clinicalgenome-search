<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Fileacmgcurated extends JsonResource
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
            'gene_symbol' => $this['gene_symbol'] ?? "",
            'hgnc_id' => $this[ 'hgnc_id'] ?? "",
            'disease_label ' => $this['disease_label'] ?? "",
            'mondo_id' => $this['mondo_id'] ?? "",
            'mode_of_inheritance' => $this[ 'mode_of_inheritance'] ?? "",
            'assertion' => $this['assertion'] ?? "",
            'reportability' => $this['reportability'] ?? "",
        ];
    }

}
