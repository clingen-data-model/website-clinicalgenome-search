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
            'type' => $this->type,
            'symbol' => $this->symbol,
            'hgnc_id' => $this->hgnc_id,
            //'name' => $this->name,
            'location' => $this->chromosome_band ?? null,
            'grch37' => $this->grch37 ?? null,
            'grch38' => $this->grch38 ?? null,
            'pli' => is_null($this->pli) ? null : round($this->pli, 2),
            'hi' => is_null($this->hi) ? null : round($this->hi, 2),
            'haplo_assertion' => GeneLib::shortAssertionString(($this->haplo_assertion ?? $this->has_dosage_haplo)),
            'triplo_assertion' => GeneLib::shortAssertionString(($this->triplo_assertion ?? $this->has_dosage_triplo)),
            'omim' => isset($this->omimlink) ? 'Yes' : 'No',
            'omimlink' => $this->omimlink ?? null,
            'morbid' => isset($this->morbid) ? 'Yes' : 'No',
            'haplo_history' => $this->haplo_history ?? null,
            'hhr' => empty($this->haplo_history) ? 0 : 1,
            'triplo_history' => $this->triplo_history ?? null,
            'thr' => empty($this->triplo_history) ? 0 : 1,
            'plof' => is_null($this->plof) ? null : round($this->plof, 2),
            //'report' => env('CG_URL_CURATIONS_DOSAGE', '#') . $this->symbol . '&subject=',
            'date' => $this->displayDate($this->resolved_date ?? $this->dosage_report_date),
            'rawdate' => $this->resolved_date ?? $this->dosage_report_date
        ];
    }
}
