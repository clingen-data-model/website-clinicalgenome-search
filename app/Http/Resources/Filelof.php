<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\GeneLib;

class Filelof extends JsonResource
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
            'gene_symbol' => $this->symbol,
            'hgnc_id' => $this->hgnc_id,
            'haploinsufficiency' => GeneLib::haploAssertionString($this->has_dosage_haplo),
            'triplosensitivity' => GeneLib::triploAssertionString($this->has_dosage_triplo),
            'online_report' => env('CG_URL_CURATIONS_DOSAGE', '#') . $this->symbol . '&subject=',
            'date' => $this->dosage_report_date
        ];
    }
}
