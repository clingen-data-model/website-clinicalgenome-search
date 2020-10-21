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
            'GRCh37_position' => $this->GRCh37_position ?? null,
            'GRCh38_position' => $this->GRCh38_position ?? null,
            'pli' => is_null($this->pli) ? null : round($this->pli, 2),
            'hi' => is_null($this->hi) ? null : round($this->hi, 2),
            'haplo_assertion' => GeneLib::shortAssertionString(($this->haplo_assertion ?? $this->has_dosage_haplo)),
            'triplo_assertion' => GeneLib::shortAssertionString(($this->triplo_assertion ?? $this->has_dosage_triplo)),
            'omimlink' => $this->omimlink ?? null,
            'morbid' => $this->morbid ?? 0,
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
