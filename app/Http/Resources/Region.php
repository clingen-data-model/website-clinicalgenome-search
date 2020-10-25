<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\GeneLib;

class Region extends JsonResource
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
            'name' => $this->summary,
            'location' => $this->GRCh37_position,
            'haplo_assertion' => $this->haplo_score,
            'triplo_assertion' => $this->triplo_score,
            'date' => $this->displayDate($this->jira_report_date),
            'rawdate' => $this->jira_report_date
        ];
    }
}
