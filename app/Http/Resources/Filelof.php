<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\GeneLib;
use Carbon\Carbon;

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
            'haploinsufficiency' => GeneLib::haploAssertionString($this->haplo_assertion),
            'triplosensitivity' => GeneLib::triploAssertionString($this->triplo_assertion),
            'online_report' => "https://search.clinicalgenome.org/kb/gene-dosage/" . $this->hgnc_id,
            'date' => (new Carbon($this->resolved_date))->toIso8601String()
        ];
    }
}
