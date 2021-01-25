<?php

namespace App\Exports;

use App\GeneLib;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

use App\Http\Resources\Filegds as GdsResource;

use Carbon\Carbon;

class ValidityExport implements FromCollection, WithHeadings
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
                                        'curated' => true ]);

        return GdsResource::collection($results->collection);
    }

    public function headings(): array
    {
        return [
            ["CLINGEN GENE DISEASE VALIDITY CURATIONS"],
            ["FILE CREATED: " . Carbon::now()->format('Y-m-d')],
            ["WEBPAGE: " . route('validity-index')],
            ["+++++++++++","++++++++++++++","+++++++++++++","++++++++++++++++++","+++++++++","+++++++++","++++++++++++++","+++++++++++++","+++++++++++++++++++", "+++++++++++++++++++"],
            ["GENE SYMBOL","GENE ID (HGNC)","DISEASE LABEL","DISEASE ID (MONDO)","MOI","SOP","CLASSIFICATION","ONLINE REPORT","CLASSIFICATION DATE","GCEP"],
            ["+++++++++++","++++++++++++++","+++++++++++++","++++++++++++++++++","+++++++++","+++++++++","++++++++++++++","+++++++++++++","+++++++++++++++++++", "+++++++++++++++++++"]
        ];
    }
}
