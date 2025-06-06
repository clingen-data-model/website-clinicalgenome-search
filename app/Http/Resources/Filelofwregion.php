<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\GeneLib;
use Carbon\Carbon;

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
        if (isset($this->iri))
        {
            $haplo = GeneLib::haploAssertionString($this->scores['haploinsufficiency']);
            $triplo = GeneLib::triploAssertionString($this->scores['triplosensitivity']);
            $date = (new Carbon($this->events['resolved']))->toIso8601String();
            $report = "https://search.clinicalgenome.org/kb/gene-dosage/region/" . $this->iri;
        }
        else
        {
            $haplo = GeneLib::haploAssertionString($this->has_dosage_haplo === false ? '-5' : $this->has_dosage_haplo);
            $triplo = GeneLib::triploAssertionString($this->has_dosage_triplo === false ? '-5' : $this->has_dosage_triplo);
            $date = (new Carbon($this->dosage_report_date))->toIso8601String();
            $report = "https://search.clinicalgenome.org/kb/gene-dosage/" . $this->hgnc_id;
        }
    
        return [
            'gene_symbol' => $this->symbol ?? $this->name,
            'hgnc_id' => $this->hgnc_id ?? $this->iri,
            'grch37' => $this->grch37 == 'chr:-' ? '' : $this->grch37,
            'grch38' => $this->grch38 == 'chr:-' ? '' : $this->grch38,
            'haploinsufficiency' => $haplo,
            'triplosensitivity' => $triplo,
            'online_report' => $report,
            'date' => $date
        ];
    }
}
