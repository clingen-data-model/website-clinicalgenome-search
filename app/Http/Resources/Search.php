<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\GeneLib;

class Search extends JsonResource
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
            'isca' => $this->issue,
            'hgnc_id' => $this->hgnc_id,
            'locus' => $this->locus,
            'location' => $this->location,
            'triplo_assertion' => (is_numeric($this->gain) ? GeneLib::shortAssertionString($this->gain) : $this->gain),
            'haplo_assertion' => (is_numeric($this->loss) ? GeneLib::shortAssertionString($this->loss) : $this->loss),
            'haplo_history' => null,
            'hhr' => 0,
            'triplo_history' => null,
            'thr' => 0,
            'omimlink' => $this->omim ?? null,
            'relationship' => $this->relationship,
            'pli' => is_null($this->pli) ? null : round($this->pli, 2),
            'hi' => is_null($this->hi) ? null : round($this->hi, 2),
            'morbid' => !empty($this->morbid) ? 'Yes' : 'No',
            'omimcombo' => (isset($this->omimlink) && !empty($this->morbid) ? 3 : (isset($this->omimlink) ? 1 : (!empty($this->morbid) ? 2 : 0))),
            'plof' => is_null($this->plof) ? null : round($this->plof, 2),
            'type' => $this->type,
            'workflow' => $this->workflow,
            'date' => $this->displayDate($this->dosage_report_date),
            'rawdate' => $this->dosage_report_date
        ];
    }
}
