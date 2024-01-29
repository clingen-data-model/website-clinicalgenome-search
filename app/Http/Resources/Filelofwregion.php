<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\GeneLib;

class Filelofwregion extends JsonResource
{
    
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if ($this->type == 1)
        {
            $haplo = GeneLib::haploAssertionString($this->haplo);
            $triplo = GeneLib::triploAssertionString($this->triplo);
        }
        else
        {
            $haplo = GeneLib::haploAssertionString($this->has_dosage_haplo);
            $triplo = GeneLib::triploAssertionString($this->has_dosage_triplo);

        }
    
        return [
            'gene_symbol' => $this->symbol,
            'hgnc_id' => $this->hgnc_id,
            'grch37' => $this->grch37,
            'grch38' => $this->grch38,
            'haploinsufficiency' => $haplo,
            'triplosensitivity' => $triplo,
            'online_report' => env('CG_URL_CURATIONS_DOSAGE', '#') . $this->symbol . '&subject=',
            'date' => $this->dosage_report_date
        ];
    }
}
