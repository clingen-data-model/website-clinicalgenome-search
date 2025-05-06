<?php

namespace App\Exports;

use App\GeneLib;
use App\Gene;
use App\Curation;
use App\Reportable;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

use App\Http\Resources\Fileacmgcurated;

use Carbon\Carbon;

class AcmgCuratedExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // grap the reportable table for faster access
        $reportables = Reportable::all();

        // get the list of gene relative to SF 3.3
        $genes = Gene::acmg59()
                        ->with('curations', function($query) {
                                $query->validity()->whereIn('status', [Curation::STATUS_ACTIVE, Curation::STATUS_ACTIVE_REVIEW]);
                            })
                        ->get();

        foreach ($genes as $gene)
        {
            foreach ($gene->curations as $curation)
            {
               
                $reportable = $reportables->where('gene_hgnc_id', $gene->hgnc_id)->where('disease_mondo_id', $curation->conditions[0] ?? null)->first();
                
                $return[] = [
                    'gene_symbol' => $gene->name,
                    'hgnc_id' => $gene->hgnc_id,
                    'disease_label' => isset($curation->conditions[0]) ? ($curation->disease->label ?? null) : null,
                    'mondo_id' => $curation->conditions[0] ?? null,
                    'mode_of_inheritance' => GeneLib::validityMoiAbvrString($curation->scores['moi'] ?? null),
                    'assertion' => $this->assertion($curation),
                    'reportability' => $reportable->reportable ?? $this->reportable($this->assertion($curation))

                ];
            }
        }

        return Fileacmgcurated::collection($return);
    }

    public function headings(): array
    {
        return [
            ["ClinGen Curation ACMG SF c3.3 Summary Report - FILE CREATED: " . Carbon::now()->format('Y-m-d')],
            ["README 1: ", " This file provides ClinGen summary curation information about ACMG SF v3.3."],
            ["Gene Symbol", "HGNC ID", "MONDO Disease Name", "MONDO ID", "MOI", "Gene-Disease Validity", "Report?"],
        ];
    }


     /**
     * Extract a readable assertion from the curation.
     *
     * @return \Illuminate\Http\Response
     */
    public function assertion($curation)
    {
       
        switch ($curation->type)
        {
            case Curation::TYPE_ACTIONABILITY:
                if ($curation->context == "Adult")
                    $value = "Adult:  " . $curation->assertions['assertion'];
                else
                    $value = "Pediatric:  " . $curation->assertions['assertion'];
                break;
            case Curation::TYPE_DOSAGE_SENSITIVITY:
                if ($curation->context == "haploinsufficiency_assertion")
                    $value = "Haploinsufficiency:  " . ($curation->scores['classification'] ?? 
                                ($curation->assertions['value'] ?? $curation->scores['haploinsufficiency_assertion']));
                else
                    $value = "Triplosensitivity:  " . ($curation->scores['classification'] ?? 
                                ($curation->assertions['value'] ?? $curation->scores['triplosensitivity_assertion']));
                    break;
            case Curation::TYPE_DOSAGE_SENSITIVITY_REGION:
                $value = "";
                break;
            case Curation::TYPE_GENE_VALIDITY:
                $value =  ucwords($curation->scores['classification']);
                break;
            case Curation::TYPE_VARIANT_PATHOGENICITY:
                $value =  $curation->scores['classification'];
                break;
            default:
                $value = "";
        }

		return ($value);
    }


    /**
     * For empty reportable values, use assertion to determine if pending or NA
     * 
     */
    protected function reportable($assertion)
    {
        switch (ucwords($assertion))
        {
            case 'Disputing':
            case 'Limited Evidence':
            case 'No Known Disease Relationship':
            case  'Refuting Evidence':
                return "NA";
            default:
                return "Reportabinility Pending";
        }

        return "Reportabinility Pending";
    }
}
