<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\GeneLib;

class Genesearch extends JsonResource
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
            'symbol' => $this->name,
            'hgnc_id' => $this->hgnc_id,
            'location' => $this->location,
            'chr' => $this->chr,
            'start' => $this->start,
            'stop' => $this->stop,
            'relationship' => $this->relationship,
            'type' => $this->type,
            'status' => 1
        ];
    }
}
