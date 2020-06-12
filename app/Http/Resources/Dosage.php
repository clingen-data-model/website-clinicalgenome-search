<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\GeneLib;

class Dosage extends JsonResource
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
            'symbol' => $this->symbol,
            'hgnc_id' => $this->hgnc_id,
            'name' => $this->name,
            'haplo_assertion' => GeneLib::haploAssertionString($this->has_dosage_haplo),
            'triplo_assertion' => GeneLib::triploAssertionString($this->has_dosage_triplo),
            'report' => env('CG_URL_CURATIONS_DOSAGE', '#') . $this->symbol . '&subject=',
            'date' => $this->displayDate($this->dosage_report_date)
        ];
    }
}
