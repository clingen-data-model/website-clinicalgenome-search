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
            'symbol' => $this->label,
            'type' => $this->type,
            'build' => $this->build,
            'symbol_id' => $this->symbol_id,
            'locus_type' => $this->locus_type ?? null,
            'locus' => $this->locus ?? null,
            'location' => $this->location,
            'coordinates' => 'chr' . $this->chr . ':' . $this->start . '-' . $this->stop,
            'chr' => $this->chr,
            'start' => $this->start,
            'stop' => $this->stop,
            'seqid' => $this->seqid,
            'relationship' => $this->relationship,
            'is_par' => $this->is_par,
            'hi' => is_null($this->hi) ? null : round($this->hi, 2),
            'plof' => is_null($this->plof) ? null : round($this->plof, 2),
            'pli' => is_null($this->pli) ? null : round($this->pli, 2),
            'haplo_assertion' => $this->haplo,
            'triplo_assertion' => $this->triplo,
            'haplo_history' => $this->haplo_history,
            'hhr' => empty($this->haplo_history) ? 0 : 1,
            'triplo_history' => $this->triplo_history,
            'thr' => empty($this->triplo_history) ? 0 : 1,
            'omim' => isset($this->omim) ? 'Yes': 'No',
            'omimlink' => $this->omim ?? null,
            'morbid' => !empty($this->morbid) ? 'Yes' : 'No',
            'curation' => (($this->activity['dosage'] ?? false) ? 'D' : '') . (($this->activity['actionability'] ?? false) ? 'A' : '')
                             . (($this->activity['validity'] ?? false) ? 'V' : '')
                            . (($this->activity['varpath'] ?? false) ? 'R' : '') . (($this->activity['pharma'] ?? false) ? 'P' : ''),
            'has_actionability' => $this->activity['actionability'] ?? false,
            'has_validity' => $this->activity['validity'] ?? false,
            'has_dosage' => $this->activity['dosage'] ?? false,
            'has_pharma' => $this->activity['pharma'] ?? false,
            'has_variant' => $this->activity['varpath'] ?? false,
            'date_last_curated' => $this->nicedate,
            'rawdate' => $this->date_last_curated,
            'has_curations' => !empty($this->date_last_curated) ?? false,
            'status' => $this->status,
            'precuration' => $this->precuration
        ];
    }
}
