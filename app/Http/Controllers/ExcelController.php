<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Maatwebsite\Excel\Facades\Excel as Gexcel;

use Auth;
use Image;
use Storage;

use App\Imports\Excel;
use App\Exports\MollyExport;
use App\Gene;
use App\Region;

class ExcelController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home')
			->with('user', Auth::user());
    }


    /**
     * Process and normalize an Excel Spreadsheet.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function process($file = null)
    {
		if (empty($file))
			$file = "/home/pweller/Projects/website-clinicalgenome-search/data/ADMI_CNV_Molly.xlsx";

		$worksheets = (new Excel)->toArray($file);

        $newsheet = [];

        foreach ($worksheets[0] as $row)
        {
            $region = $row[0] . ':' . $row[13] . '-' . $row[14];
            $type = 'GRCh38';

            $genes = Gene::searchList(['type' => $type,
                        "region" => $region,
                        'option' => 1 ]);

            $row[] = implode(', ', $genes->collection->pluck('name')->toArray());

            //$row[] = $genes->collection->
            $newsheet[] = $row;
        }

        $this->output($newsheet);
    }


    /**
     * Write the new spreadsheet.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function output($file = null)
    {
        Gexcel::store(new MollyExport($file), 'molly.xlsx');

        //return Gexcel::download(new MollyExport($file), 'molly.xlsx');

        // return (new MollyExport($file))->download('molly.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

}
