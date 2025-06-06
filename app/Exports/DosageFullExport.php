<?php

namespace App\Exports;

use App\GeneLib;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

use App\Http\Resources\Filelofwregion as LofResource;

use Carbon\Carbon;

class DosageFullExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $results = GeneLib::dosageFullList([
            'page' => 0,
            'pagesize' => null,
            'sort' => 'GENE_LABEL',
            'direction' => 'ASC',
            'search' => '',
            'curated' => true
        ]);

        // query the regions
        $jresults = GeneLib::regionList([
            'page' =>  0,
            'pagesize' =>  "null",
            'sort' => 'GENE_LABEL',
            'direction' =>  'ASC',
            'search' =>  null,
            'curated' => false
        ]);

        // dd($jresults->collection->first());

        return LofResource::collection($results->collection->concat($jresults->collection));
    }

    public function headings(): array
    {
        return [
            ["CLINGEN DOSAGE SENSITIVITY CURATIONS (FULL)"],
            ["FILE CREATED: " . Carbon::now()->format('Y-m-d')],
            ["WEBPAGE: " . route('dosage-index')],
            ["+++++++++++", "+++++++++", "++++++", "++++++", "++++++++++++++++++", "+++++++++++++++++", "+++++++++++++", "++++"],
            ["GENE/REGION", "HGNC/ISCA", "GRCh37", "GRCh38", "HAPLOINSUFFICIENCY", "TRIPLOSENSITIVITY", "ONLINE REPORT", "DATE"],
            ["+++++++++++", "+++++++++", "++++++", "++++++", "++++++++++++++++++", "+++++++++++++++++", "+++++++++++++", "++++"]
        ];
    }
}
