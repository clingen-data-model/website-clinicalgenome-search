<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Maatwebsite\Excel\Facades\Excel as Gexcel;

use Auth;

use App\Imports\Excel;
use App\Exports\ReportExport;
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
        $this->middleware('auth');
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

        dd($worksheets);

		// Valid variant values
		/*$variants = ["Mosaic frameshift", "Splice site, Frameshift", "Splice",
			"Splice, Frameshift", "Splice, Frameshift", "Splice, Missense",
			"Nonsense, Missense", "Frameshift, Missense", "Nonsense, Frameshift",
			"Frameshift, Splice site", "Stop-lost", "Whole gene deletion",
			"Microdeletion", "Splice, Deletion", "Splice site", "Frameshift",
			"Deletion", "Nonsense", "Loss of function", "Missense", "Nonsense,
			Splice site",
			"Frameshift, Nonsense", "Missense, Splice site", "Missense, Nonsense",
			"Mosaic missense"];*/

		// Valid variant values
		$variants = [ "Deletion", "Frameshift", "Nonsense", "Missense",
			"Splice", "In-frame indel", "Loss of function", "Initiation codon",
			"Intronic insertion", "Start-lost", "Stop-lost"
			];

		// Valid inheritance values
		$inheritance = ["Unknown", "Maternal", "De novo", "Inherited",
						"Paternal", "Bi-parental", "Mosaic",
						];

		// Valid chromosone values
		$chr = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10",
			    "11", "12", "13", "14", "15", "16", "17", "18", "19",
			    "20", "21", "22", "23", "x", "X", "y", "Y"];

		// Valid genome build castings
		$genome_build = [
				'17'=>'hg17', 'hg17'=>'hg17', 'ncbi35'=>'hg17',
				'18'=>'hg18', 'hg18'=>'hg18', 'ncbi36'=>'hg18',
				'19'=>'hg19', 'hg19'=>'hg19', 'grch37'=>'hg19', ''=>'', 'na'=>''
				];

		$var_type = ['Other', 'indels', 'pLOF'];

		// position mapping of #..# and #--#

		$line = 1;

		foreach($worksheets[0] as &$row)
		{
			$line++;
			echo "Processing line $line\n";

			// remove NA and spaces in select fields
			foreach (['chr', 'start', 'stop'] as $v)
				$row[$v] = preg_replace('/NA|N\/A| /', '', $row[$v]);

			//$original = $row['variant_type'];

			// remove white space from beginning and end of string
			foreach (['variant_type', 'inheritance'] as $v)
				$row[$v] = trim($row[$v]);

			// replace embedded newlines or slash or comma-space with just a comma
			//$row['variant_type'] = preg_replace('/[\n\/]|, /', ',', $row['variant_type']);

			// for dual variant types, split out into two fields
			if (strpos($row['variant_type'], ' / ') !== false)
			{
				$variants = explode( ' / ', $row['variant_type']);
				$row['variant_type'] = $variants[0];
				$row['variant_type2'] = $variants[1] ?? '';
			}

			// change to sentence case
			foreach (['variant_type', 'variant_type2'] as $v)
				if (isset($row[$v]))
					$row[$v] = ucfirst(strtolower($row[$v]));

			// expand comma to comma-space
			//$row['variant_type'] = preg_replace('/,/', ', ', $row['variant_type']);

			// gracefully deal with common typos and requested transforms
			/*switch ($row['variant_type'])
			{
				case 'Framesihft':
					$row['variant_type'] = 'Frameshift';
					break;
				case 'Nonsense, frameshift':
					$row['variant_type'] = 'Frameshift, Nonsense';
					break;
				case 'Nonsense, missense':
					$row['variant_type'] = 'Missense, Nonsense';
					break;
				case 'Splie site':
					$row['variant_type'] = 'Splice site';
					break;
				case 'Truncated':
					$row['variant_type'] = 'Nonsense';
					break;
				case 'Microdeletion':
					$row['variant_type'] = 'Microdeletion';
					break;
			}*/

            if (empty($row['remove']))
                $row['remove'] = 0;

			// for dual variant types, split out into two fields
			if (strpos($row['inheritance'], ' / ') !== false)
			{
				$inheritances = explode( ' / ', $row['inheritance']);
				$row['inheritance'] = $inheritances[0];
				$row['inheritance2'] = $inheritances[1] ?? '';
			}

			// change to sentence case
			foreach (['inheritance', 'inheritance2'] as $v)
				if (isset($row[$v]))
					$row[$v] = ucfirst(strtolower($row[$v]));

			// replace NA with Unknown
			$row['inheritance'] = preg_replace('/Na/', 'Unknown', $row['inheritance']);

			// gracefully deal with requested transforms
			/*switch ($row['inheritance'])
			{
				case '':
					$row['inheritance'] = 'Unknown';
					break;
				case 'Biparental':
					$row['inheritance'] = 'Bi-parental';
					break;
				case "Maternal\npaternal":
					$row['inheritance'] = 'Bi-parental';
					break;
				case 'De novo/maternal':
					$row['inheritance'] = 'De novo, maternal';
					break;
			}*/

			// map the individual gender to correct phrasing
			switch ($row['individual_gender'])
			{
				case 'f':
				case 'F':
					$row['individual_gender'] = "Female";
					break;
				case 'm':
				case 'M':
					$row['individual_gender'] = "Male";
					break;
				default:
					$row['individual_gender'] = "Unknown";
					break;
			}

			// change these fields to pure boolean
			$row['iddd'] =  ($row['iddd'] == ' ' ? false : (boolean) $row['iddd']);
            $row['autism'] = ($row['autism'] == ' ' ? false : (boolean) $row['autism']);
            $row['epilepsy'] = ($row['epilepsy'] == ' ' ?
										false : (boolean) $row['epilepsy']);
            $row['adhd'] = ($row['adhd'] == ' ' ? false : (boolean) $row['adhd']);
            $row['schizophrenia'] = ($row['schizophrenia'] == ' ' ?
										false : (boolean) $row['schizophrenia']);
            $row['bipolar_disorder'] = ($row['bipolar_disorder'] == ' ' ?
										false : (boolean) $row['bipolar_disorder']);
            $row['wgs'] = ($row['wgs'] == ' ' ? false : (boolean) $row['wgs']);
            $row['wes'] = ($row['wes'] == ' ' ? false : (boolean) $row['wes']);
            $row['genome_wide_cma'] = ($row['genome_wide_cma'] == ' ' ?
										false : (boolean) $row['genome_wide_cma']);
            $row['targeted_cnv_analysis'] = ($row['targeted_cnv_analysis'] == ' ' ?
										false : (boolean) $row['targeted_cnv_analysis']);
            $row['targeted_sequencing'] = ($row['targeted_sequencing'] == ' ' ?
										false : (boolean) $row['targeted_sequencing']);

			// normalize genome build
			$row['genome_build'] = "hg19"; //$genome_build[$row['genome_build']] ??
										//$row['genome_build'];

			// Remove NA or N/A for individual ID
			$row['individual_id'] = preg_replace('/^NA$|^N\/A$/', '',
													$row['individual_id']);

			// Map the primary sequence
			if ($row['wgs'])
				$row['primary_sequence'] = Curation::CURATE_SEQ_WGS;
			else if ($row['wes'])
				$row['primary_sequence'] = Curation::CURATE_SEQ_WES;
			else if ($row['genome_wide_cma'])
				$row['primary_sequence'] = Curation::CURATE_SEQ_CMA;
			else if ($row['targeted_cnv_analysis'])
				$row['primary_sequence'] = Curation::CURATE_SEQ_CNV;
			else if ($row['targeted_sequencing'])
				$row['primary_sequence'] = Curation::CURATE_SEQ_TAR;
			else
				$row['primary_sequence'] = Curation::CURATE_SEQ_NONE;

		// check position, start, and stop values for consistency
		// reference and alternative sequence?
		// Make PMID an integer value?
		// missense? ["PolyPhen-2", "SIFT", "Provean", "MutationTaster", "CADD Phred", "GERP++ Score"]

		}

		echo "Building validator\n";

		// validate the worksheet
		/*$validator = Validator::make($worksheets[0], [
             '*.gene' => 'required|string',
             '*.iddd' => 'boolean',
             '*.autism' => 'boolean',
             '*.epilepsy' => 'boolean',
             '*.adhd' => 'boolean',
             '*.schizophrenia' => 'boolean',
             '*.bipolar_disorder' => 'boolean',
             '*.wgs' => 'boolean',
             '*.wes' => 'boolean',
             '*.genome_wide_cma' => 'boolean',
             '*.targeted_cnv_analysis' => 'boolean',
             '*.targeted_sequencing' => 'boolean',
             '*.variant_type' => ['required', 'string', Rule::in($variants)],
             '*.inheritance' => ['required', 'string', Rule::in($inheritance)],
             '*.chr' => ['nullable', 'string', Rule::in($chr)],
             '*.start' => 'nullable|numeric',
             '*.stop' => 'nullable|numeric',
             '*.ref'=> 'nullable|regex:/^[AaCcGgTt]*$/',
             '*.alt' => 'nullable|regex:/^[AaCcGgTt]*$/',
             '*.size' => 'nullable|regex:/^[AaCcGgTt]*$/',
			 '*.genome_build' => ['nullable', 'string', Rule::in($genome_build)],
			 '*.coding_dna_change' => 'nullable|string',
			 '*.protein_change' => 'nullable|string',
			 '*.position' => 'nullable|string',
			 '*.additional_mutation' => 'nullable|string',
			 '*.reference' => 'nullable|string',
		//	 '*.individual_id' => 'nullable|string|numeric',
			 '*.additional_information' => 'nullable|string',
			 '*.condition' => 'nullable|string',
			 '*.pmid' => 'nullable|numeric',
			 '*.study_type' => 'nullable|string',
			 '*.total_cases' => [
							'nullable',
							'regex:/^[\d\s,]*$/',
						],
			 '*.comments' => 'nullable|string',
			 '*.exclude' => 'nullable|string',
			 '*.curator' => 'nullable|string',
			 '*.var_type' => ['nullable', 'string', Rule::in($var_type)],
			 '*.genes_with_multiple_idels_only' => 'nullable|string',
         ]); //->validate();*/

         // other columns = Old_Tier_March2018, FINAL_Tier_April_2019, No. indels (genes w/indels only)

         echo "Validate complete\n";

         // There may be too many errors to easily view in one session, so store
       /* if ($validator->fails())
        {
			echo "cp1 \n";
			$path = Auth::user()->id. '/' . $this->workspace->id .'/' .
						$file->ident . '.err';

			Storage::put($path, $validator->errors()->all());

			$file->update(['status', 3]);

            return 2;
        }*/

        echo "Normalizing Complete\n";
		// TODO:  feedback stats on new genes added and combined totals?

		// No errors, build the database objects
		return $this->store($file, $worksheets[0]);

    }


    /**
     * Store a worksheet.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function store($file, array $sheet)
    {
		$workspace = Project::find(1); //Auth::user()->current_project;

		// determine which columns don't have a fixed field
		$orphans = array_diff(array_keys($sheet[0]), Curation::columns());

		//dd($orphans);
        /*
            0 => "gene"
            1 => "refseq"
            21 => "size_bp"
            29 => ""
            31 => "condition"
            33 => "study_type_cohort_source"
            35 => "reviewers"
            38 => "age_dx"
            39 => "published_gene_alias"
            40 => "variants_id"
            41 => "single_plof_casegene"
            42 => "single_missense_casegene"
            43 => "data_for_may_2019_updated_literature_upto_jan_2019"
            44 => "curator"
            46 => "tier"
            47 => "denovo"
            48 => "inherited"
            49 => "unknown"
            50 => "psych_diagnosis"
            51 => "duplicates_removed_from_website"
            52 => "published_online"
            53 => "pmid_in_fjan20"
            54 => "rows"
        */


		$n = 0;

		foreach ($sheet as $row)
		{
			$n++;

			// deal with trailing rows in spreadsheet
			if (empty($row['gene']))
				continue;

			// locate or create a gene record
			$gene = Gene::name(trim($row['gene']))->first();

			if (empty($gene))
			{
				echo $row['gene'] . ": Gene Not Found! Row $n ";

				// look up for a match in previous names
				$gene = Gene::whereJsonContains('prev_symbol',  $row['gene'])->first();
				if (empty($gene))
				{
					// last chance, try the aliases
					$gene = Gene::whereJsonContains('alias_symbol',  $row['gene'])->first();
					if (empty($gene))
					{
						echo "\n";
						continue;
					}
				}

				echo ".....renamed/aliased to " . $gene->name . "\n";

			}

			// attach gene to workspace
			$gene->projects()->sync([$workspace->id], false);

			// create the attributes
			$curation = new Curation($row);
			$curation->user_id = 1;  //Auth::user()->id;
			$curation->project_id = $workspace->id;
			$curation->new_data = 1;
			$curation->update = 1;
			$curation->status = 1; // 2;

            // map the orphans and add some source info
			$other = array_intersect_key($row, array_flip($orphans));
            $other['source_file'] = basename($file);
            $other['source_row'] = $n;

            $curation->other = $other;

			$gene->curations()->save($curation);
			$gene->update(['status' => '2']);
		}

        return;
    }


    /**
     * Export the files for download.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function export($file = null)
    {
		Gexcel::store(new GenesExport, 'Full-LoF-Table-Data.csv');
		Gexcel::store(new MissenseExport, 'Full-Missense-Table-Data.csv');
		Gexcel::store(new CurationExport, 'DBD-Genes-Full-Data.csv');

		return;
	}
}
