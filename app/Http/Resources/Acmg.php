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
            'type' => 0
        ];
    }
}
