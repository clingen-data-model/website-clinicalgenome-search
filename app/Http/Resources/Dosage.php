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
        if ($this->type == 0)
        {
            $a = [
                'type' => $this->type,
                'symbol' => $this->label,
                'hgnc_id' => $this->hgnc_id,
                'symbol_id' => $this->hgnc_id,
                'locus' => $this->locus,
                'location' => $this->chromosome_band ?? null,
                'relationship' => null,
                'grch37' => $this->grch37 ?? null,
                'grch38' => $this->grch38 ?? null,
                'acmgsf' => $this->acmgsf ?? 0,
                'pli' => is_null($this->pli) ? null : round($this->pli, 2),
                'hi' => is_null($this->hi) ? null : round($this->hi, 2),
                //'haplo_assertion' => GeneLib::shortAssertionString(($this->haplo_assertion ?? $this->has_dosage_haplo)),
                //'triplo_assertion' => GeneLib::shortAssertionString(($this->triplo_assertion ?? $this->has_dosage_triplo)),
                'haplo_assertion' => $this->haplo_assertion ?? $this->has_dosage_haplo,
                'triplo_assertion' => $this->triplo_assertion ?? $this->has_dosage_triplo,
                'omim' => isset($this->omimlink) ? 'Yes' : 'No',
                'omimlink' => $this->omimlink ?? null,
                'morbid' => !empty($this->morbid) ? 'Yes' : 'No',
                'omimcombo' => (isset($this->omimlink) && !empty($this->morbid) ? 3 : (isset($this->omimlink) ? 1 : (!empty($this->morbid) ? 2 : 0))),
                'haplo_history' => $this->haplo_history ?? null,
                'hhr' => empty($this->haplo_history) ? 0 : 1,
                'triplo_history' => $this->triplo_history ?? null,
                'thr' => empty($this->triplo_history) ? 0 : 1,
                'plof' => is_null($this->plof) ? null : round($this->plof, 2),
                //'report' => env('CG_URL_CURATIONS_DOSAGE', '#') . $this->symbol . '&subject=',
                'date' => $this->displayDate($this->resolved_date ?? $this->dosage_report_date),
                'rawdate' => $this->resolved_date ?? $this->dosage_report_date,
                //
                'haplo_disease' => $this->haplo_disease ?? null,
                'haplo_disease_id' => $this->haplo_disease_id ?? null,
                'triplo_disease' => $this->triplo_disease ?? null,
                'triplo_disease_id' => $this->triplo_disease_id ?? null,
                'haplo_mondo' => $this->haplo_mondo ?? null,
                'triplo_mondo' => $this->triplo_mondo ?? null,
            ];
        }
        else
        {
            // temp fix for NYE
            $haplo_score = $this->scores['haploinsufficiency'] ?? null;
            if ($haplo_score == 'Not yet evaluated')
                $haplo_score = -5;
            $triplo_score = $this->scores['triplosensitivity'] ?? null;
            if ($triplo_score == 'Not yet evaluated')
                $triplo_score = -5;

            $a = [
                'type' => 1,
                'symbol' => $this->name,
                'hgnc_id' => $this->iri,
                'symbol_id' => $this->iri,
                'locus' => null,
                'location' => $this->cytoband ?? null,
                'relationship' => null,
                'grch37' => $this->grch37 ?? null,
                'grch38' => $this->grch38 ?? null,
                'pli' => is_null($this->pli) ? null : round($this->pli, 2),
                'hi' => is_null($this->hi) ? null : round($this->hi, 2),
                'haplo_assertion' => $haplo_score,
                'triplo_assertion' => $triplo_score,
                'omim' => !empty($this->metadata['omim']) ? 'Yes' : 'No',
                'omimlink' => $this->metadata['omim'] ?? null,
                'morbid' => !empty($this->morbid) ? 'Yes' : 'No',
                'omimcombo' => (isset($this->omimlink) && !empty($this->morbid) ? 3 : (isset($this->omimlink) ? 1 : (!empty($this->morbid) ? 2 : 0))),
                'haplo_history' => $this->events['haplo_score_change'] ?? null,
                'hhr' => empty($this->events['haplo_score_change']) ? 0 : 1,
                'triplo_history' => $this->events['triplo_score_change'] ?? null,
                'thr' => empty($this->events['triplo_score_change']) ? 0 : 1,
                'plof' => is_null($this->plof) ? null : round($this->plof, 2),
                //'report' => env('CG_URL_CURATIONS_DOSAGE', '#') . $this->symbol . '&subject=',
                'date' => $this->displayDate($this->events['resolved'] ?? 0),
                'rawdate' => $this->events['resolved'] ?? 0,
                //
                'haplo_disease' => $this->metadata['loss_pheno_name'] ?? null,
                'haplo_disease_id' => $this->metadata['loss_pheno_omim'] ?? null,
                'triplo_disease' => $this->metadata['gain_pheno_name'] ?? null,
                'triplo_disease_id' => $this->metadata['gain_pheno_omim'] ?? null,
                'haplo_mondo' => null,
                'triplo_mondo' => null,
            ];

            //dd($this->scores);
        }


        /*
        if ($this->type == 0)
        {
            $a['haplo_disease'] = $this->haplo_disease ?? null;
            $a['haplo_disease_id'] = $this->haplo_disease_id ?? null;
            $a['triplo_disease'] = $this->triplo_disease ?? null;
            $a['triplo_disease_id'] = $this->triplo_disease_id ?? null;
            $a['haplo_mondo'] = $this->haplo_mondo ?? null;
            $a['triplo_mondo'] = $this->triplo_mondo ?? null;
        }
        else
        {
            $a['haplo_disease'] = $this->loss_pheno_omim[0]['titles'] ?? null;
            $a['haplo_disease_id'] = $this->loss_pheno_omim[0]['id'] ?? null;
            $a['triplo_disease'] = $this->gain_pheno_omim[0]['titles'] ?? null;
            $a['triplo_disease_id'] = $this->gain_pheno_omim[0]['id'] ?? null;
            $a['haplo_mondo'] = null;
            $a['triplo_mondo'] = null;
        }
        */

        return $a;
    }
}
