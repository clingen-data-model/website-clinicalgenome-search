<?php

namespace App\Exports;

use App\GeneLib;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

use App\Http\Resources\FilegdsLS as GdsResource;

use Carbon\Carbon;

class ValidityExportLS implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $results = GeneLib::validityList(['page' => 0,
										'pagesize' => null,
										'sort' => 'GENE_LABEL',
										'direction' => 'ASC',
										'search' => '',
                                        'include_lump_split' => true,
                                        'curated' => true ]);

        return GdsResource::collection($results->collection);
    }

    public function headings(): array
    {
        return [
            ["CLINGEN GENE DISEASE VALIDITY CURATIONS W/ LUMPING & SPLITTING"],
            ["FILE CREATED: " . Carbon::now()->format('Y-m-d')],
            ["WEBPAGE: " . route('validity-index')],
            ["+++++++++++","++++++++++++++","+++++++++++++","++++++++++++++++++","+++++++++","+++++++++","++++++++++++++","+++++++++++++","+++++++++++++++++++", "+++++++++++++++++++", "+++++++++++++++++++", "+++++++++++++++++++", "+++++++++++++++++++", "+++++++++++++++++++", "+++++++++++++++++++", "+++++++++++++++++++", "+++++++++++++++++++"],
            ["GENE SYMBOL","GENE ID (HGNC)","DISEASE LABEL","DISEASE ID (MONDO)","MOI","SOP","CLASSIFICATION","ONLINE REPORT","CLASSIFICATION DATE","GCEP","INCLUDED MIMS","EXCLUDED MIMS","EVALUATION DATE","CURATION TYPE","RATIONALES","PMIDS","NOTES"],
            ["+++++++++++","++++++++++++++","+++++++++++++","++++++++++++++++++","+++++++++","+++++++++","++++++++++++++","+++++++++++++","+++++++++++++++++++", "+++++++++++++++++++", "+++++++++++++++++++", "+++++++++++++++++++", "+++++++++++++++++++", "+++++++++++++++++++", "+++++++++++++++++++", "+++++++++++++++++++", "+++++++++++++++++++"]
        ];
    }
}
