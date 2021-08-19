<?php

namespace App\Exports;

use App\GeneLib;
use Maatwebsite\Excel\Concerns\FromArray;
//use Maatwebsite\Excel\Concerns\WithHeadings;

use App\Http\Resources\Filegds as GdsResource;

use Carbon\Carbon;

class MollyExport implements FromArray
{
    protected $worksheet;

    public function __construct(array $worksheet)
    {
        $this->worksheet = $worksheet;
    }


    /**
    * @return \Illuminate\Support\Collection
    */
    public function array(): array
    {
        return $this->worksheet;
    }

}
