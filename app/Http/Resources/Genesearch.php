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
            'type' => 0,
            'hgnc_id' => $this->hgnc_id,
            'location' => $this->location,
            'chr' => $this->chr,
            'start' => $this->start,
            'stop' => $this->stop,
            'relationship' => $this->relationship,
            //'type' => $this->type,
            'has_actionability' => $this->activity['actionability'] ?? false,
            'has_validity' => $this->activity['validity'] ?? false,
            'has_dosage' => $this->activity['dosage'] ?? false,
            'date_last_curated' => $this->displayDate($this->date_last_curated),
            'rawdate' => $this->date_last_curated,
            'status' => 1
        ];
    }
}
