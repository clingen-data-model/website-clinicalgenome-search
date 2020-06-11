<?php

namespace App\Exports;

use App\GeneLib;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

use App\Http\Resources\Filelof as LofResource;

use Carbon\Carbon;

class DosageExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $results = GeneLib::dosageList(['page' => 0,
										'pagesize' => null,
										'sort' => 'GENE_LABEL',
										'direction' => 'ASC',
										'search' => '',
                                        'curated' => true ]);
                                                
        return LofResource::collection($results->collection);
    }
    
    public function headings(): array
    {
        return [
            ["CLINGEN DOSAGE SENSITIVITY CURATIONS"],
            ["FILE CREATED: " . Carbon::now()->format('Y-m-d')],
            ["WEBPAGE: " . route('dosage-index')],
            ["+++++++++++","+++++++","++++++++++++++++++","+++++++++++++++++","+++++++++++++","++++"],
            ["GENE SYMBOL", "HGNC ID", "HAPLOINSUFFICIENCY", "TRIPLOSENSITIVITY", "ONLINE REPORT","DATE"],
            ["+++++++++++","+++++++","++++++++++++++++++","+++++++++++++++++","+++++++++++++","++++"]
        ];
    }
}
