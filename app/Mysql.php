<?php

namespace App;

use Illuminate\Support\Facades\Log;

use Auth;

use App\Traits\Query;

use App\Drug;
use App\Disease;
use App\Term;
use App\Gene;
use App\Validity;
use App\Sensitivity;
use App\Actionability;
use App\Precuration;
use App\Nodal;

use Carbon\Carbon;

/**
 *
 * @category   Library
 * @package    Search
 * @author     P. Weller <pweller1@geisinger.edu>
 * @author     S. Goehringer <scottg@creationproject.com>
 * @copyright  2020 ClinGen
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/PackageName
 * @see        NetOther, Net_Sample::Net_Sample()
 * @since      Class available since Release 1.0.0
 *
 * */
class Mysql
{
	/**
     * This class is designed to be used statically.
     */


    /**
     * Get gene list with curation flags and last update
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function geneList($args, $curated = false, $page = 0, $pagesize = 20000)
    {

		// break out the args
		foreach ($args as $key => $value)
			$$key = $value;

		if ($curated === true)
		{
			$collection = collect();

			$gene_list = Gene::whereNotNull('activity')->get(['name as label', 'hgnc_id', 'date_last_curated as last_curated_date', 'activity', 'acmg59', 'disease']);

			// if logged in, get all followed genes
			if (Auth::guard('api')->check())
			{
				$user = Auth::guard('api')->user();
				$followed = $user->genes->pluck('hgnc_id')->toArray();
			}
			else
			{
				$followed = [];
			}

			// create node list and add pharma and variant curation indicators to the current gene list
			foreach($gene_list as $record)
			{
				$node = new Nodal($record->attributesToArray());
				$node->followed = in_array($node->hgnc_id, $followed);
				$node->acmg59 = ($node->acmg59 != 0);
				$node->curation_activities = $record->curation;
				$collection->push($node);
				//dd($node);
			}
		}
		else
		{
			// initialize the collection
			if (empty($search))
			{
				$collection = Gene::all(['name as symbol', 'location', 'description as name', 'hgnc_id', 'date_last_curated as last_curated_date',
                                         'activity as curation_activities', 'locus_group', 'chr', 'start37', 'stop37', 'start38', 'stop38']);
			}
			else
			{
                $usearch = strtoupper($search);

				$collection = Gene::where('name', 'like', '%' . $search . '%')
                    ->orWhere('alias_symbol', 'like', '%' . $usearch . '%')
                    ->orWhere('prev_symbol', 'like', '%' . $usearch . '%')
                    ->orderByRaw('CHAR_LENGTH(name)')
                    //->offset($page * $pagesize)
                    //->take($pagesize)
                    ->get(['name as symbol', 'description as name', 'hgnc_id', 'location', 'chr', 'start37', 'stop37', 'start38', 'stop38', 'date_last_curated as last_curated_date', 'activity as curation_activities', 'locus_type', 'locus_group']);

				// manipulate the return order per Erin
				/*if ($search !== null && $search != "")
				{
					//$match = $collection->where('symbol', $search)->first();
					$search = strtolower($search);
					$match = $collection->first(function ($item) use ($search) {
						return strtolower($item->symbol) == $search;
					});

					if ($match !== null)
					{
						//$collection = $collection->where('symbol', '!=', $search)->prepend($match);
						$collection = $collection->filter(function ($item) use ($search) {
							return strtolower($item->symbol) != $search;
						})->prepend($match);
					}
				}*/
			}
		}

		$ncurated = $collection->where('last_curated_date', '!=', null)->count();

		if ($curated)
		{
			$naction = $collection->where('has_actionability', true)->count();
			$nvalid = $collection->where('has_validity', true)->count();
			$ndosage = $collection->where('has_dosage', true)->count();
			$npharma = $collection->where('has_pharma', true)->count();
			$nvariant = $collection->where('has_variant', true)->count();
		}
		else
		{
			// right now we only use these counts on the curated page.  Probably should get triggered
			// by a call option so as not to bury things to deep.
			$naction = 0;
			$nvalid = 0;
			$ndosage = 0;
			$npharma = 0;
			$nvariant = 0;
		}

        //dd($collection->skip($page)->take($pagesize));
		return (object) ['count' => $collection->count(), 'collection' => $collection,
						'naction' => $naction, 'nvalid' => $nvalid, 'ndosage' => $ndosage,
						'npharma' => $npharma, 'nvariant' => $nvariant, 'ncurated' => $ncurated];
	}


    /**
     * Get gene list of acmg entries
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function acmgList($args, $curated = false, $page = 0, $pagesize = 20000)
    {

		// break out the args
		foreach ($args as $key => $value)
			$$key = $value;

		//$collection = Acmg::with('gene')->with('disease')->groupBy('gene_symbol')->get();
        $collection = collect();

        // get the list of gene relative to SF 3.2
        $genes = Gene::with('curations')->acmg59()->get();

        $diseases = [];

        //$parents = [];

        //$disease_index = 1;

        // build parent gene nodes
        foreach ($genes as $gene)
        {
            $activity = [];
            if ($gene->activity['dosage'])
                $activity[] = 'GENE_DOSAGE';
            if ($gene->activity['validity'])
                $activity[] = 'GENE_VALIDITY';
            if ($gene->activity['varpath'])
                $activity[] = 'VAR_PATH';
            if ($gene->activity['actionability'])
                $activity[] = 'ACTIONABILITY';

            $curations = $gene->curations->whereNotNull('disease_id')
                        ->whereIn('status', [Curation::STATUS_ACTIVE, Curation::STATUS_ACTIVE_REVIEW]);

            if ($filter == 'preferred_only')
            {
                $curations = $curations->filter(function ($item) {
                    return (($item->type != Curation::TYPE_ACTIONABILITY) ||
                            ($item->type == Curation::TYPE_ACTIONABILITY && $item->conditions[0] == $item->evidence_details[0]['curie']));
                });
            }

            $node = new Nodal([ 'gene_label' => $gene->name,
                                'gene_hgnc_id' => $gene->hgnc_id,
                                'disease_label' => null,
                                'disease_mondo' => null,
                                'disease_count' => $curations->unique('disease_id')->count(),
                                'curation' => ($gene->hasActivity('dosage') ? 'D' : '') .
                                                ($gene->hasActivity('actionability') ? 'A' : '') .
                                                ($gene->hasActivity('validity') ? 'V' : '') .
                                                ($gene->hasActivity('varpath') ? 'R' : ''),
                                'curation_activities' => $activity,
                                'has_comment' => !empty($gene->notes),
                                'comments' => $gene->notes ?? '',
                                'reportable' => false,
                                'id' => $gene->id,
                                'pid' => 0,
                                'type' => 3
                                ]);

            $collection->push($node);

            $diseases = array_merge($diseases, $curations->unique('disease_id')->pluck('disease_id')->toArray());

        }


		$ngenes = $collection->unique('gene_hgnc_id')->count();
        $ndiseases = count(array_unique($diseases));

		return (object) ['count' => $collection->count(), 'collection' => $collection,
						'ngenes' => $ngenes, 'ndiseases' => $ndiseases];
	}

    /**
     * Get gene list of acmg entries
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function acmgListV2($args, $curated = false, $page = 0, $pagesize = 20000)
    {

		// break out the args
		foreach ($args as $key => $value)
			$$key = $value;

		//$collection = Acmg::with('gene')->with('disease')->get();

        // get the list of gene ids relative to SF 3.2
        $genes = Gene::acmg59()->get()->pluck('hgnc_id')->toArray();
        $comments = Gene::acmg59()->whereNotNull('notes')->get()->pluck('notes', 'hgnc_id')->toArray();

        // <i class="fas fa-comment-alt"></i>
        $collection = collect();

        // get all the validity assertions associated with these genes
        $validity_collection = Validity::whereIn('gene_hgnc_id', $genes)->groupBy(['gene_hgnc_id', 'disease_mondo'])->get();
        foreach($validity_collection as $record)
        {
                $node = new Nodal([ 'gene_label' => $record->gene_label,
                                    'gene_hgnc_id' => $record->gene_hgnc_id,
                                    'disease_label' => $record->disease_label,
                                    'disease_mondo' => $record->disease_mondo,
                                    'curation' => 'V',
                                    'curation_activities' => ['GENE_VALIDITY'],
                                    'has_comment' => isset($comments[$record->gene_hgnc_id]),
                                    'comments' => $comments[$record->gene_hgnc_id] ?? null,
                                    'reportable' => false,
                                    'type' => 3
                                ]);
                $collection->push($node);
        }

        // for dosage, we need to split the haplo and triplo diseases into separate rows
        $dosage_collection = Sensitivity::whereIn('gene_hgnc_id', $genes)->get();
        foreach($dosage_collection as $record)
        {
            if ($record->haplo_disease_mondo != null)
            {
                $node = new Nodal([ 'gene_label' => $record->gene_label,
                                    'gene_hgnc_id' => $record->gene_hgnc_id,
                                    'disease_label' => $record->haplo_disease_label,
                                    'disease_mondo' => $record->haplo_disease_mondo,
                                    'curation' => 'D',
                                    'curation_activities' => ['GENE_DOSAGE'],
                                    'has_comment' => isset($comments[$record->gene_hgnc_id]),
                                    'comments' => $comments[$record->gene_hgnc_id] ?? null,
                                    'reportable' => false,
                                    'type' => 3
                                ]);
                $collection->push($node);
            }

            if ($record->triplo_disease_mondo != null)
            {
                $node = new Nodal([ 'gene_label' => $record->gene_label,
                                    'gene_hgnc_id' => $record->gene_hgnc_id,
                                    'disease_label' => $record->triplo_disease_label,
                                    'disease_mondo' => $record->triplo_disease_mondo,
                                    'curation' => 'D',
                                    'curation_activities' => ['GENE_DOSAGE'],
                                    'has_comment' => isset($comments[$record->gene_hgnc_id]),
                                    'comments' => $comments[$record->gene_hgnc_id] ?? null,
                                    'reportable' => false,
                                    'type' => 3

                                ]);
                $collection->push($node);
            }
        }

        // actionability
        $actionability_collection = Actionability::whereIn('gene_hgnc_id', $genes)->groupBy(['gene_hgnc_id', 'disease_mondo'])->get();
        foreach($actionability_collection as $record)
        {
            $node = new Nodal([ 'gene_label' => $record->gene_label,
                                'gene_hgnc_id' => $record->gene_hgnc_id,
                                'disease_label' => $record->disease_label,
                                'disease_mondo' => $record->disease_mondo,
                                'curation' => 'A',
                                'curation_activities' => ['ACTIONABILITY'],
                                'has_comment' => isset($comments[$record->gene_hgnc_id]),
                                'comments' => $comments[$record->gene_hgnc_id] ?? null,
                                'reportable' => true,
                                'type' => 3
                            ]);
            $collection->push($node);
        }

        // Variant Pathogenicity
        $variant_collection = Variantpath::whereIn('gene_hgnc_id', $genes)->groupBy(['gene_hgnc_id', 'disease_mondo'])->get();
        foreach($variant_collection as $record)
        {
            $node = new Nodal([ 'gene_label' => $record->gene_label,
                                'gene_hgnc_id' => $record->gene_hgnc_id,
                                'disease_label' => $record->disease_label,
                                'disease_mondo' => $record->disease_mondo,
                                'curation' => 'R',
                                'curation_activities' => ['VAR_PATH'],
                                'has_comment' => isset($comments[$record->gene_hgnc_id]),
                                'comments' => $comments[$record->gene_hgnc_id] ?? null,
                                'reportable' => false,
                                'type' => 3
                            ]);
            $collection->push($node);
        }

		$ngenes = $collection->unique('gene_hgnc_id')->count();
		$ndiseases = $collection->unique('disease_mondo')->count();

		return (object) ['count' => $collection->count(), 'collection' => $collection,
						'ngenes' => $ngenes, 'ndiseases' => $ndiseases];
	}


    /**
     * Get listing of all affiliates
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function affiliateList($args, $page = 0, $pagesize = 20)
    {
		// break out the args
		foreach ($args as $key => $value)
			$key = $value;

		// initialize the collection
		$collection = collect();

        $total_curations = 0;
        $total_panels = 0;

        $panels = Panel::has('curations')->with('curations')->get();

        foreach($panels as $panel)
        {
            $data = [
                'agent' => $panel->affiliate_id,
                'label' => $panel->title,
                'curie' => $panel->affiliate_id,
                'affiliate_id' => $panel->affiliate_id,
                'total_all_curations' => $panel->curations->count(),
			    'total_secondary_curations' => $panel->curations()->wherePivot('level', 2)->count(),
                'total_approver_curations' => $panel->curations()->wherePivot('level', 1)->count(),
                'count' => $panel->curations->count()
            ];

            if ($data['total_approver_curations'] > 0)
            {
                $node = new Nodal($data);
                $collection->push($node);
                $total_curations += $node->total_all_curations;
                $total_panels++;
            }

        }

		$collection = $collection->sortBy('label');

		return (object) ['count' => $total_panels, 'collection' => $collection,
						'ncurations' => $total_curations];
	}


    /**
     * Get details for an affiliate
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function affiliateDetail($args, $page = 0, $pagesize = 20)
    {
		// break out the args
		foreach ($args as $key => $value)
			$$key = $value;

		// the affiliate ID is expected to be numeric, handle gracefully if not
		if (!ctype_digit($affiliate))
			$affiliate = "0";

        $panel = Panel::affiliate($affiliate)->first();

        if ($panel == null)
            die($affiliate);

		// initialize the collection
		$collection = collect();

		$records = $panel->curations()->get();

		if ($records->isEmpty())
			return $response;

		// add each gene to the collection
		foreach($records as $record)
		{
            //dd($record);
			if ($record->gene_id === null || $record->disease_id === null)
				continue;

            // blacklist bad assertions
            /*if ($record->curie == "CGGV:assertion_815e0f84-b530-4fd2-81a9-02e02bf352ee-2020-12-18T170000.000Z")
                   continue;*/

            $nodal = new Nodal([
                        'label' => $record->gene_details['label'],
                        'hgnc_id' => $record->gene_hgnc_id,
                        'ep' => $panel->title ?? ($record->affiliate_details['label'] ?? ''),
                        'affiliate_id' => $panel->affiliate_id ?? null,
                        'disease' => $record->condition_details['label'],
                        'mondo' => $record->condition_details['curie'],
                        'moi' => $record->scores['moi_hp'],
                        'sop' => $record->sop_version,
                        'contributor_type' => ($panel->id == $record->panel_id ? "Primary" : "Secondary"),
                        'classification' => $record->scores['classification'],
                        'perm_id' => $record->source_uuid,
                        'animal_model_only' => $record->animal_model_only,
                        'report_id' => $record->document ?? null,
                        'released' => $record->events['report_date'],
                        'date' => $record->events['report_date']
            ]);

            $id = substr($record->source_uuid, 15, 36);

            // gg's build the lumping and splitting properties
			if ($include_lump_split ?? false)
			{
				// create additional entries for lumping and splitting
				$nodal->las_included = [];
				$nodal->las_excluded = [];
				$nodal->las_rationale = [];
				$nodal->las_curation = '';
				$nodal->las_date = null;

				if ($nodal->report_id !== null)
				{
					$map = Precuration::gdmid($nodal->report_id)->first();
					if ($map !== null)
					{
						$nodal->las_included = $map->omim_phenotypes['included'] ?? [];
						$nodal->las_excluded = $map->omim_phenotypes['excluded'] ?? [];
						$nodal->las_rationale =$map->rationale;
						$nodal->las_curation = $map->curation_type['description'] ?? '';

						// the dates aren't always populated in the gene tracker, so we may need to restrict them.
						$prec_date = $map->disease_date;
						if ($prec_date !== null)
						{
							$dd = Carbon::parse($prec_date);
							$rd = Carbon::parse($nodal->date);
							$nodal->las_date = ($dd->gt($rd) ? $nodal->date : $prec_date);
						}
						else
						{
							$nodal->las_date = $nodal->date;
						}
					}

				}
			}

			$collection->push($nodal);
		}

		$ngenes = $collection->unique('symbol')->count();
		$npanels = $collection->unique('ep')->count();

		return (object) ['count' => $collection->count(),
						'collection' => $collection,
                        'label' => $panel->title,
						'ngenes' => $ngenes,
						'npanels' => $npanels
						];
	}


    /**
     * Get listing of all genes with validity assertions.
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function validityList($args, $page = 0, $pagesize = 20)
    {
		// break out the args
		foreach ($args as $key => $value)
			$$key = $value;


		$search = null;

		// initialize the collection
		$collection = collect();

        $records = Curation::validity()->active()->with('panel')->get();


		if ($records->isEmpty())
			return $response;

		// add each gene to the collection
		foreach($records as $record)
		{
			if ($record->gene_id === null || $record->disease_id === null)
				continue;	// TODO:  Log as gg error

            // blacklist bad assertions
            /*if ($record->curie == "CGGV:assertion_815e0f84-b530-4fd2-81a9-02e02bf352ee-2020-12-18T170000.000Z")
                   continue;*/

            $nodal = new Nodal([
                        'label' => $record->gene_details['label'],
                        'hgnc_id' => $record->gene_hgnc_id,
                        'ep' => $record->panel->title ?? ($record->affiliate_details['label'] ?? ''),
                        'affiliate_id' => $record->panel->affiliate_id ?? null,
                        'disease' => $record->condition_details['label'],
                        'mondo' => $record->condition_details['curie'],
                        'moi' => $record->scores['moi_hp'],
                        'sop' => $record->sop_version,
                        'classification' => $record->scores['classification'],
                        'perm_id' => $record->source_uuid,
                        'animal_model_only' => $record->animal_model_only,
                        'report_id' => $record->document ?? null,
                        'released' => $record->events['report_date'],
                        'date' => $record->events['report_date']
            ]);

            $id = substr($record->source_uuid, 15, 36);

            // gg's build the lumping and splitting properties
			if ($include_lump_split ?? false)
			{
				// create additional entries for lumping and splitting
				$nodal->las_included = [];
				$nodal->las_excluded = [];
				$nodal->las_rationale = [];
				$nodal->las_curation = '';
				$nodal->las_date = null;

				if ($nodal->report_id !== null)
				{
					$map = Precuration::gdmid($nodal->report_id)->first();
					if ($map !== null)
					{
						$nodal->las_included = $map->omim_phenotypes['included'] ?? [];
						$nodal->las_excluded = $map->omim_phenotypes['excluded'] ?? [];
						$nodal->las_rationale =$map->rationale;
						$nodal->las_curation = $map->curation_type['description'] ?? '';

						// the dates aren't always populated in the gene tracker, so we may need to restrict them.
						$prec_date = $map->disease_date;
						if ($prec_date !== null)
						{
							$dd = Carbon::parse($prec_date);
							$rd = Carbon::parse($nodal->date);
							$nodal->las_date = ($dd->gt($rd) ? $nodal->date : $prec_date);
						}
						else
						{
							$nodal->las_date = $nodal->date;
						}
					}

				}
			}

			$collection->push($nodal);
		}

		$ngenes = $collection->unique('symbol')->count();
		$npanels = $collection->unique('ep')->count();

		return (object) ['count' => $collection->count(),
						'collection' => $collection,
						'ngenes' => $ngenes,
						'npanels' => $npanels
						];
	}


    /**
     * Get listing of all genes with dosage sensitivity.
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function dosageList($args, $page = 0, $pagesize = 20)
    {
		// break out the args
		foreach ($args as $key => $value)
			$$key = $value;

		// initialize the collection
		$collection = collect();

		$search = null;
		$hapcounters = 0;
		$tripcounters = 0;

        // DSCWG wants their listings grouped by gene, so thats what we'll do here
        $records = Gene::where('activity->dosage', true)->with(['curations' => function ($query) {
                                            $query->where('type', Curation::TYPE_DOSAGE_SENSITIVITY)
                                                    ->whereIn('status', [Curation::STATUS_ACTIVE]);
                    }])->get();

		// add each gene to the collection
		foreach($records as $gene)
        {
            // isolate the haplo and triplo assertions
            $haplo = $gene->curations->where('context', 'haploinsufficiency_assertion')->first();
            $triplo = $gene->curations->where('context', 'triplosensitivity_assertion')->first();

			$node = new Nodal([
                'type' => 0,
                'label' => $gene->name,
                'hgnc_id' => $gene->hgnc_id,
                'locus' => $gene->locus_group,
                'chromosome_band' => $gene->location,
                'grch37' => $gene->grch37 ?? null,
                'grch38' => $gene->grch38 ?? null,
                'pli' => $gene->pli,
                'hi' => $gene->hi,
                'haplo_assertion' => $haplo->scores['classification'] ?? ($haplo->scores['haploinsufficiency']['value'] ?? ($haplo->scores['haploinsufficiency_assertion'] ?? null ) ),
                'triplo_assertion' => $triplo->scores['classification'] ?? ($triplo->scores['triplosensitivity']['value'] ?? ($triplo->scores['triplosensitivity_assertion'] ?? null ) ),
                'omimlink' => $gene->display_omim,
                'morbid' => $gene->morbid,
                'plof' => $gene->plof,
                'dosage_report_date' => (isset($haplo) ?? $haplo->source == "genegraph" ?
                        $haplo->events['report_date'] ?? ($triplo->events['report_date'] ?? null) :
                        $haplo->events['resolved'] ?? ($triplo->events['resolved'] ?? null)),
                'resolved_date' => (isset($haplo) && $haplo->source == "genegraph" ?
                        $haplo->events['report_date'] ?? ($triplo->events['report_date'] ?? null) :
                        $haplo->events['resolved'] ?? ($triplo->events['resolved'] ?? null)),
                'haplo_disease' => (isset($haplo) && $haplo->source == "genegraph" ?
                        $haplo->condition_details['haploinsufficiency_assertion']['label'] ?? null :
                        $haplo->condition_details['disease_phenotype_name'] ?? null),
                'haplo_disease_id' => (isset($haplo) && $haplo->source == "genegraph" ?
                        $haplo->conditions[0] ?? null :
                        $haplo->condition_details['disease_id'] ?? null),
                'triplo_disease' => (isset($triplo) && $triplo->source == "genegraph" ?
                        $triplo->condition_details['triplosensitivity_assertion']['label'] ?? null :
                        $triplo->condition_details['disease_phenotype_name'] ?? null),
                'triplo_disease_id' => (isset($triplo) && $triplo->source == "genegraph" ?
                        $triplo->conditions[0] ?? null :
                        $triplo->condition_details['disease_id'] ?? null),
                'haplo_mondo' => (isset($haplo) && $haplo->source == "genegraph" ?
                        $haplo->conditions[0] ?? null :
                        $haplo->condition_details['disease_id'] ?? null),
                'triplo_mondo' => (isset($triplo) && $triplo->source == "genegraph" ?
                        $triplo->conditions[0] ?? null :
                        $triplo->condition_details['disease_id'] ?? null),
            ]);

            // do some processing on the history prop
            if ($gene->history !== null)
            {
                //dd($gene->history);
                foreach ($gene->history as $item)
                {
                    //dd($item["what"]);
                    if ($item['what'] == 'Triplosensitivity Score')
                        $node->triplo_history = $item['what'] . ' changed from ' . $item['from']
                                                . ' to ' . $item['to'] . ' on ' . $item['when'];
                    else if ($item['what'] == 'Haploinsufficiency Score')
                        $node->haplo_history = $item['what'] . ' changed from ' . $item['from']
                                                . ' to ' . $item['to'] . ' on ' . $item['when'];
                }
            }

            if ($node->haplo_assertion !== null)
				$tripcounters++;

            if ($node->triplo_assertion !== null)
				$hapcounters++;

            // temp fix for the NYE issue
            if ($node->haplo_assertion === null)
                $node->haplo_assertion = -5;
            if ($node->triplo_assertion === null)
                $node->triplo_assertion = -5;

			$collection->push($node);
		}

		return (object) ['count' => $collection->count(), 'collection' => $collection,
						'ngenes' => $records->count(), 'nregions' => 0,
						'ncurations' => $tripcounters + $hapcounters];
	}


    /**
     * Get a list of regions
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function regionList($args, $page = 0, $pagesize = 20)
    {
         // break out the args
         foreach ($args as $key => $value)
              $$key = $value;

          $collection = Region::active()->get();

          $nhaplo = $collection->where('haplo', '!=', 'unknown')->count();
          $ntriplo = $collection->where('triplo', '!=', 'unknown')->count();

          return (object) ['count' => $collection->count(), 'collection' => $collection,
               'nhaplo' => $nhaplo, 'ntriplo' => $ntriplo, 'ncurations' => $nhaplo + $ntriplo];
    }


     /**
     * Get a list of recurrent CNVs
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function cnvList($args, $page = 0, $pagesize = 20)
    {
         // break out the args
         foreach ($args as $key => $value)
              $$key = $value;

          $collection = collect();

          $regions = Region::active()->whereJsonContains('tags', 'Recurrent')->get();

          if (empty($regions))
               return $collection;

          foreach ($regions as $region)
          {
               // skip over any won't fixes
               //if ($issue->fields->resolution->name == "Won't Fix")
                //    continue;

               $node = new Nodal([
                    'key' => $region->issue,
                    'summary' => $region->name,
                    'grch37' => $region->grch37,
                    'grch38' => $region->grch38,
                    'triplo_score' => $region->scores['triplosensitivity'] ?? 'null',
                    'haplo_score' => $region->scores['haploinsufficiency'] ?? 'null',
                    'jira_report_date' => $region->events['resolved'] ?? ''
               ]);

               // for 30 and 40, Jira also sends text
                if ($node->triplo_score == "30: Gene associated with autosomal recessive phenotype")
                    $node->triplo_score = 30;
                else if ($node->triplo_score == "40: Dosage sensitivity unlikely")
                    $node->triplo_score = 40;
                else if ($node->triplo_score == "Not yet evaluated")
                    $node->triplo_score = -5;

                if ($node->haplo_score == "30: Gene associated with autosomal recessive phenotype")
                    $node->haplo_score = 30;
                else if ($node->haplo_score == "40: Dosage sensitivity unlikely")
                    $node->haplo_score = 40;
                else if ($node->haplo_score == "Not yet evaluated")
                    $node->haplo_score = -5;

               $collection->push($node);
          }

          $nhaplo = $collection->where('haplo_score', '>', 0)->count();
          $ntriplo = $collection->where('triplo_score', '>', 0)->count();

          return (object) ['count' => $collection->count(), 'collection' => $collection,
               'nhaplo' => $nhaplo, 'ntriplo' => $ntriplo];
    }


	/**
     * Suggester for Drug names
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function drugLook($args, $page = 0, $pagesize = 20)
    {
		// break out the args
		foreach ($args as $key => $value)
			$$key = $value;

		$collection = collect();

		// remove any possible occurence of'%' from search term
		$search = str_replace('%', '', $id);

		$response = Drug::where('label', 'like', '%' . $search . '%')->take(10)->get();

		if (empty($response))
			return $response;

		$array = [];
		foreach($response as $record)
		{
			$ctag = ($record->has_curations ? '        CURATED' : '');
			$short = $record->curie;
			$array[] = ['label' => $record->label . '  (RXNORM:' . $short . ')'
							. $ctag,
						'url' => route('drug-show', $short)];
		}

		return json_encode($array);
	}


    /**
     * Suggester for Drug names
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function drugLook2($args, $page = 0, $pagesize = 20)
    {
		// break out the args
		foreach ($args as $key => $value)
			$$key = $value;

        $array = [];
        if (stripos($search, 'RXNORM') === 0)
        {
            // strip out the numeric value
            $search = substr($search, 7);

            $records = Drug::query()->where('curie', 'like', $search . '%')
                            ->orderByRaw('CHAR_LENGTH(curie)')->take(10)->get();

            foreach($records as $record)
            {
                $c = $record->curation_activities;
                $array[] = ['label' => 'RXNORM:' . $record->curie,
                            'alias' => '',
                            'hgnc' => $record->label,
                            'url' => route('drug-show', $record->curie),
                            'curated' => (bool) count(array_filter($record->curation_activities))];
            }
        }
        else
        {
            $records = Drug::where('label', 'like', '%' . $search . '%')
                        ->orderByRaw('CHAR_LENGTH(label)')
                        //->orderBy('synonyms')
                        //->orderBy('weight', 'desc')
                        ->take(10)->get();

            if ($records->isEmpty() && stripos('RXNOR', $search) === 0)
            {
                // TODO:  change this around to avoid duplication
                $search = substr($search, 7);

                $records = Drug::query()->where('curie', 'like', $search . '%')
                                ->orderByRaw('CHAR_LENGTH(curie)')->take(10)->get();

                foreach($records as $record)
                {
                    $c = $record->curation_activities;
                    $array[] = ['label' => 'RXNORM:' . $record->curie,
                                'alias' => '',
                                'hgnc' => $record->label,
                                'url' => route('drug-show', $record->curie),
                                'curated' => (bool) count(array_filter($record->curation_activities))];
                }

            }
            else
            {
                foreach($records as $record)
                {
                    /*switch ($record->type)
                    {
                        case 2:
                            $ctag = "(previous of " . $record->alias . ")";
                            break;
                        case 3:
                            $ctag = "(alias of " . $record->alias . ")";
                            break;
                        default:
                            $ctag = '';
                    }*/
                    // $ctag .= (empty($record->curated) ? '' : ' CURATED');
                    //$array[] = ['label' => $record->name . '  (' . $record->value . ')'
                    //                . $ctag,
                    $array[] = ['label' => $record->label,
                                'alias' => '',
                                'hgnc' => 'RXNORM:' . $record->curie,
                                'url' => route('drug-show', $record->curie),
                                'curated' => (bool) count(array_filter($record->curation_activities))];
                }
            }
        }
		return json_encode($array);
	}


	/**
     * Suggester for Condition names
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function conditionLook($args, $page = 0, $pagesize = 20)
    {
		// break out the args
		foreach ($args as $key => $value)
			$$key = $value;

		//$collection = collect();

        if (stripos($search, 'MONDO:') === 0)
            $records = Disease::query()->where('curie', 'like', $search . '%')
                            ->take(10)->orderByRaw('CHAR_LENGTH(curie)')->get();
        else
            $records = Disease::query()->where('label', 'like', '%' . $search . '%')
                            ->take(10)->orderByRaw('CHAR_LENGTH(name)')->get();
		$array = [];
		foreach($records as $record)
		{
			$ctag = (empty($record->curation_activities) ? '' : ' CURATED');
			$array[] = ['label' => $record->label . '  (' . $record->curie . ')'
							. $ctag,
						'url' => route('condition-show', $record->curie)];
		}
		return json_encode($array);
	}


    /**
     * Suggester for Condition names
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function conditionLook2($args, $page = 0, $pagesize = 20)
    {
		// break out the args
		foreach ($args as $key => $value)
			$$key = $value;

        $array = [];

        // do some cleanup
        $search = trim($search);

        $parts = explode(':', $search);

        if (!isset($parts[1]))
        {
            $records = Term::where('name', 'like', '%' . $search . '%')
                        ->whereIn('type', [11, 12, 13, 14])
                        ->orderByRaw('CHAR_LENGTH(name)')
                        ->orderBy('alias')
                        ->orderBy('weight', 'desc')
                        ->take(10)->get();
            foreach($records as $record)
            {
                switch ($record->type)
                {
                    case 12:
                        $ctag = "(MONDO synonym)";
                        break;
                    case 13:
                        $ctag = "(Orphanet match)";
                        break;
                    case 14:
                        $ctag = "(OMIM match)";
                        break;
                    default:
                        $ctag = '';
                }
                // $ctag .= (empty($record->curated) ? '' : ' CURATED');
                //$array[] = ['label' => $record->name . '  (' . $record->value . ')'
                //                . $ctag,
                $array[] = ['label' => $record->name,
                            'alias' => $ctag,
                            'hgnc' => $record->value,
                            'url' => route('condition-show', $record->value),
                            'curated' => !empty($record->curated)];
            }


            /*$records = Disease::where('label', 'like', '%' . $search . '%')
                        ->orderByRaw('CHAR_LENGTH(label)')
                        //->orderBy('synonyms')
                        //->orderBy('weight', 'desc')
                        ->take(10)->get();
            foreach($records as $record)
            {
                /*switch ($record->type)
                {
                    case 2:
                        $ctag = "(previous of " . $record->alias . ")";
                        break;
                    case 3:
                        $ctag = "(alias of " . $record->alias . ")";
                        break;
                    default:
                        $ctag = '';
                }*/
                // $ctag .= (empty($record->curated) ? '' : ' CURATED');
                //$array[] = ['label' => $record->name . '  (' . $record->value . ')'
                //                . $ctag,
                /*$array[] = ['label' => $record->label,
                            'alias' => '',
                            'hgnc' => $record->curie,
                            'url' => route('condition-show', $record->curie),
                            'curated' => !empty($record->curation_activities)];

            }*/
        }
        else
        {
            $id = $parts[1];

            switch (strtoupper($parts[0]))
            {
                case 'OMIM':
                    $records = Disease::query()->where('omim', 'like', $parts[1] . '%')
                            ->orderByRaw('CHAR_LENGTH(curie)')->take(10)->get();

                    foreach($records as $record)
                    {
                        $array[] = ['label' => 'OMIM:' . $record->omim,
                                    'alias' => '(' . $record->curie . ')',
                                    'hgnc' => $record->label,
                                    'url' => route('condition-show', $record->curie),
                                    'curated' => !empty($record->curation_activities)];
                    }
                    break;
                case 'DOID':
                    $records = Disease::query()->where('do_id', 'like', $parts[1] . '%')
                            ->orderByRaw('CHAR_LENGTH(curie)')->take(10)->get();

                    foreach($records as $record)
                    {
                        $array[] = ['label' => 'DOID:' . $record->do_id,
                                    'alias' => '(' . $record->curie . ')',
                                    'hgnc' => $record->label,
                                    'url' => route('condition-show', $record->curie),
                                    'curated' => !empty($record->curation_activities)];
                    }
                    break;
                case 'ORPHANET':
                case 'ORPHA':
                    $records = Disease::query()->where('orpha_id', 'like', $parts[1] . '%')
                            ->orderByRaw('CHAR_LENGTH(curie)')->take(10)->get();

                    foreach($records as $record)
                    {
                        $array[] = ['label' => 'ORPHANET:' . $record->orpha_id,
                                    'alias' => '(' . $record->curie . ')',
                                    'hgnc' => $record->label,
                                    'url' => route('condition-show', $record->curie),
                                    'curated' => !empty($record->curation_activities)];
                    }
                    break;
                case 'GARD':
                    $records = Disease::query()->where('gard_id', 'like', $parts[1] . '%')
                            ->orderByRaw('CHAR_LENGTH(curie)')->take(10)->get();

                    foreach($records as $record)
                    {
                        $array[] = ['label' => 'GARD:' . $record->gard_id,
                                    'alias' => '(' . $record->curie . ')',
                                    'hgnc' => $record->label,
                                    'url' => route('condition-show', $record->curie),
                                    'curated' => !empty($record->curation_activities)];
                    }
                    break;
                case 'MONDO':
                    $records = Disease::query()->where('curie', 'like', $search . '%')
                            ->orderByRaw('CHAR_LENGTH(curie)')->take(10)->get();

                    foreach($records as $record)
                    {
                        $array[] = ['label' => $record->curie,
                                    'alias' => '',
                                    'hgnc' => $record->label,
                                    'url' => route('condition-show', $record->curie),
                                    'curated' => !empty($record->curation_activities)];
                    }
                    break;
                case 'MEDGEN':
                case 'UMLS':
                    $records = Disease::query()->where('umls_id', 'like', $parts[1] . '%')
                            ->orderByRaw('CHAR_LENGTH(curie)')->take(10)->get();

                    foreach($records as $record)
                    {
                        $array[] = ['label' => 'UMLS:' . $record->umls_id,
                                    'alias' => '(' . $record->curie . ')',
                                    'hgnc' => $record->label,
                                    'url' => route('condition-show', $record->curie),
                                    'curated' => !empty($record->curation_activities)];
                    }
                    break;
                default:
                    $check = null;

            }

        }
        /*$array = [];
        if (stripos($search, 'MONDO') === 0)
        {
            case 'OMIM':
                $records = Disease::query()->where('omim', 'like', $pair[1] . '%')
                            ->orderByRaw('CHAR_LENGTH(curie)')->take(10)->get();
                break;
            case 'DOID':
                $records = Disease::query()->where('do-id', 'like', $pair[1] . '%')
                            ->orderByRaw('CHAR_LENGTH(curie)')->take(10)->get();
                break;
            case 'ORPHANET':
                $records = Disease::query()->where('orpha_id', 'like', $pair[1] . '%')
                            ->orderByRaw('CHAR_LENGTH(curie)')->take(10)->get();
                break;
            case 'GARD':
                $records = Disease::query()->where('gard_id', 'like', $pair[1] . '%')
                            ->orderByRaw('CHAR_LENGTH(curie)')->take(10)->get();
                break;
            case 'MONDO':
                $records = Disease::query()->where('curie', 'like', $search . '%')
                            ->orderByRaw('CHAR_LENGTH(curie)')->take(10)->get();
                break;
            case 'MEDGEN':
            case 'UMLS':
                $records = Disease::query()->where('umls_id', 'like', $pair[1] . '%')
                            ->orderByRaw('CHAR_LENGTH(curie)')->take(10)->get();
                break;
        }

        if ($is_symbol)             //(stripos($search, 'MONDO') === 0)
        {
            //$records = Disease::query()->where('curie', 'like', $search . '%')
             //               ->orderByRaw('CHAR_LENGTH(curie)')->take(10)->get();

            foreach($records as $record)
            {
                $array[] = ['label' => $search,         //$record->curie,
                            'alias' => '',
                            'hgnc' => $record->label,
                            'url' => route('condition-show', $record->curie),
                            'curated' => !empty($record->curation_activities)];
            }
        }
        else
        {
            $records = Disease::where('label', 'like', '%' . $search . '%')
                        ->orderByRaw('CHAR_LENGTH(label)')
                        //->orderBy('synonyms')
                        //->orderBy('weight', 'desc')
                        ->take(10)->get();
            foreach($records as $record)
            {
                /*switch ($record->type)
                {
                    case 2:
                        $ctag = "(previous of " . $record->alias . ")";
                        break;
                    case 3:
                        $ctag = "(alias of " . $record->alias . ")";
                        break;
                    default:
                        $ctag = '';
                }*/
                // $ctag .= (empty($record->curated) ? '' : ' CURATED');
                //$array[] = ['label' => $record->name . '  (' . $record->value . ')'
                //                . $ctag,
                /*$array[] = ['label' => $record->label,
                            'alias' => '',
                            'hgnc' => $record->curie,
                            'url' => route('condition-show', $record->curie),
                            'curated' => !empty($record->curation_activities)];
            }
        } */

		return json_encode($array);
	}


	/**
     * Get listing of all conditions
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function conditionList($args, $curated = false, $page = 0, $pagesize = 20)
    {
		// break out the args
		foreach ($args as $key => $value)
			$$key = $value;

		// manipulate the return order per Erin
		if (empty($search))
		{
			$collection = Disease::filter()->orderBy('curie')->get();
		}
		else
		{
			$collection = Disease::where('label', 'like', '%' . $search . '%')
                    ->orWhere('synonyms', 'like', '%' . $search . '%')
                    ->orWhere('orpha_label', 'like', '%' . $search . '%')
                    ->orderByRaw('CHAR_LENGTH(label)')
                    ->get();

			//$match = $collection->where('symbol', $search)->first();
			/*$search = strtolower($search);
			$match = $collection->first(function ($item) use ($search) {
				return strtolower($item->symbol) == $search;
			});

			if ($match !== null)
			{
				//$collection = $collection->where('symbol', '!=', $search)->prepend($match);
				$collection = $collection->filter(function ($item) use ($search) {
					return strtolower($item->symbol) != $search;
				})->prepend($match);
			}*/
		}

		$ncurated = $collection->where('last_curated_date', '!=', null)->count();

		if ($curated) {
			$naction = $collection->where('has_actionability', true)->count();
			$nvalid = $collection->where('has_validity', true)->count();
			$ndosage = $collection->where('has_dosage', true)->count();
		} else {
			// right now we only use these counts on the curated page.  Probably should get triggered
			// by a call option so as not to bury things to deep.
			$naction = 0;
			$nvalid = 0;
			$ndosage = 0;
		}

		return (object) ['count' => $collection->count(), 'collection' => $collection,
						'ncurated' => $ncurated, 'naction' => $naction, 'nvalid' => $nvalid, 'ndosage' => $ndosage];
	}


    /**
     * Suggester for Disease names
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function conditionFind($args, $page = 0, $pagesize = 20)
    {
		// break out the args
		foreach ($args as $key => $value)
			$$key = $value;

		$collection = collect();

		/*if ($search == '*')
		{
			$array = [['label' => 'All Diseases (*)',
						'short' => 'All Diseases',
						'curated' => 0,
                        'alias' => '',
						'hgnc' => '*'
						]];
			return json_encode($array);
		}

		if (strpos('@', $search) === 0)
		{
			$array = [['label' => 'All Validity',
						'short' => 'All Validity',
						'curated' => 0,
                        'alias' => '',
						'hgnc' => '@AllValidity'
					],
					['label' => 'All Dosage',
						'short' => 'All Dosage',
						'curated' => 0,
                        'alias' => '',
						'hgnc' => '@AllDosage'
					],
					['label' => 'All Actionability',
						'short' => 'All Actionability',
						'curated' => 0,
                        'alias' => '',
						'hgnc' => '@AllActionability'
					],
					['label' => 'All Variant Pathogenicity',
						'short' => 'All Variant Pathogenicity',
						'curated' => 0,
                        'alias' => '',
						'hgnc' => '@AllVariant'
					]
				];

			return json_encode($array);
		}*/

        $array = [];

        // do some cleanup
        $search = trim($search);

        $parts = explode(':', $search);

        if (!isset($parts[1]))
        {
            $records = Term::where('name', 'like', '%' . $search . '%')
                        ->whereIn('type', [11, 12, 13, 14])
                        ->orderByRaw('CHAR_LENGTH(name)')
                        ->orderBy('alias')
                        ->orderBy('weight', 'desc')
                        ->take(10)->get();
            foreach($records as $record)
            {
                switch ($record->type)
                {
                    case 12:
                        $ctag = "(MONDO synonym)";
                        break;
                    case 13:
                        $ctag = "(Orphanet match)";
                        break;
                    case 14:
                        $ctag = "(OMIM match)";
                        break;
                    default:
                        $ctag = '';
                }
                // $ctag .= (empty($record->curated) ? '' : ' CURATED');
                //$array[] = ['label' => $record->name . '  (' . $record->value . ')'
                //                . $ctag,
                $array[] = ['label' => $record->name,
                            'alias' => $ctag,
                            'hgnc' => $record->value,
                            'url' => route('condition-show', $record->value),
                            'curated' => !empty($record->curated)];
            }
        }
        else
        {
            $id = $parts[1];

            switch (strtoupper($parts[0]))
            {
                case 'OMIM':
                    $records = Disease::query()->where('omim', 'like', $parts[1] . '%')
                            ->orderByRaw('CHAR_LENGTH(curie)')->take(10)->get();

                    foreach($records as $record)
                    {
                        $array[] = ['hgnc' => 'OMIM:' . $record->omim,
                                    'alias' => '(' . $record->curie . ')',
                                    'label' => $record->label,
                                    'url' => route('condition-show', $record->curie),
                                    'curated' => !empty($record->curation_activities)];
                    }
                    break;
                case 'DOID':
                    $records = Disease::query()->where('do_id', 'like', $parts[1] . '%')
                            ->orderByRaw('CHAR_LENGTH(curie)')->take(10)->get();

                    foreach($records as $record)
                    {
                        $array[] = ['hgnc' => 'DOID:' . $record->do_id,
                                    'alias' => '(' . $record->curie . ')',
                                    'label' => $record->label,
                                    'url' => route('condition-show', $record->curie),
                                    'curated' => !empty($record->curation_activities)];
                    }
                    break;
                case 'ORPHANET':
                case 'ORPHA':
                    $records = Disease::query()->where('orpha_id', 'like', $parts[1] . '%')
                            ->orderByRaw('CHAR_LENGTH(curie)')->take(10)->get();

                    foreach($records as $record)
                    {
                        $array[] = ['hgnc' => 'ORPHANET:' . $record->orpha_id,
                                    'alias' => '(' . $record->curie . ')',
                                    'label' => $record->label,
                                    'url' => route('condition-show', $record->curie),
                                    'curated' => !empty($record->curation_activities)];
                    }
                    break;
                case 'GARD':
                    $records = Disease::query()->where('gard_id', 'like', $parts[1] . '%')
                            ->orderByRaw('CHAR_LENGTH(curie)')->take(10)->get();

                    foreach($records as $record)
                    {
                        $array[] = ['hgnc' => 'GARD:' . $record->gard_id,
                                    'alias' => '(' . $record->curie . ')',
                                    'label' => $record->label,
                                    'url' => route('condition-show', $record->curie),
                                    'curated' => !empty($record->curation_activities)];
                    }
                    break;
                case 'MONDO':
                    $records = Disease::query()->where('curie', 'like', $search . '%')
                            ->orderByRaw('CHAR_LENGTH(curie)')->take(10)->get();

                    foreach($records as $record)
                    {
                        $array[] = ['hgnc' => $record->curie,
                                    'alias' => '',
                                    'label' => $record->label,
                                    'url' => route('condition-show', $record->curie),
                                    'curated' => !empty($record->curation_activities)];
                    }
                    break;
                case 'MEDGEN':
                case 'UMLS':
                    $records = Disease::query()->where('umls_id', 'like', $parts[1] . '%')
                            ->orderByRaw('CHAR_LENGTH(curie)')->take(10)->get();

                    foreach($records as $record)
                    {
                        $array[] = ['hgnc' => 'UMLS:' . $record->umls_id,
                                    'alias' => '(' . $record->curie . ')',
                                    'label' => $record->label,
                                    'url' => route('condition-show', $record->curie),
                                    'curated' => !empty($record->curation_activities)];
                    }
                    break;
                default:
                    $check = null;

            }

        }

		/*
		foreach($response->suggest as $record)
		{
			$ctag = (empty($record->curations) ? '' : '        CURATED');
			$array[] = ['label' => $record->text . '  (' . $record->alternative_curie . ')'
							. $ctag,
						'short' => $record->text,
						'curated' => !empty($record->curations),
						'hgncid' => $record->alternative_curie
						];
		}*/

		return json_encode($array);
	}


	/**
     * Get listing of all drugs
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function drugList($args, $page = 0, $pagesize = 2000)
    {
		// break out the args
		foreach ($args as $key => $value)
			$$key = $value;

		// initialize the collection
		if (empty($search))
		{
			$collection = Drug::all();
		}
		else
		{
			$collection = Drug::where('label', 'like', '%' . $search . '%')
                            ->orderByRaw('CHAR_LENGTH(label)')
                            ->get();

			//$match = $collection->where('symbol', $search)->first();
			/*$search = strtolower($search);
			$match = $collection->first(function ($item) use ($search) {
				return strtolower($item->symbol) == $search;
			});

			if ($match !== null)
			{
				//$collection = $collection->where('symbol', '!=', $search)->prepend($match);
				$collection = $collection->filter(function ($item) use ($search) {
					return strtolower($item->symbol) != $search;
				})->prepend($match);
			}*/
		}

		return (object) ['count' => $collection->count(), 'collection' => $collection];
	}


	/**
     * Get detail for a drug
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function drugDetail($args, $page = 0, $pagesize = 2000)
    {
		// break out the args
		foreach ($args as $key => $value)
			$$key = $value;

		// initialize the collection
		$record = Drug::curie($drug)->first();

		return $record;
	}


	/**
     * Suggester for Gene names
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function geneLook($args, $page = 0, $pagesize = 20)
    {
		// break out the args
		foreach ($args as $key => $value)
			$$key = $value;

		//$collection = collect();
        if (stripos($search, 'HGNC:') === 0)
            $records = Gene::query()->where('hgnc_id', 'like', $search . '%')
                            ->take(10)->orderByRaw('CHAR_LENGTH(hgnc_id)')->get();
        else
            $records = Gene::query()->where('name', 'like', '%' . $search . '%')
                            ->orWhere('alias_symbol', 'like', '%' . $search . '%')
                            ->orWhere('prev_symbol', 'like', '%' . $search . '%')
                            ->take(10)->orderByRaw('CHAR_LENGTH(name)')->get();


		$array = [];
		foreach($records as $record)
		{
			/*$ctag = (empty($record->curations) ? '' : '        CURATED');
			$array[] = ['label' => $record->text . '  (' . $record->alternative_curie . ')'
							. $ctag,
						'url' => route('gene-show', $record->hgnc_id)];*/
            $ctag = (empty($record->activity) ? '' : ' CURATED');
            $array[] = ['label' => $record->name . '  (' . $record->hgnc_id . ')'
                            . $ctag,
                        'url' => route('gene-show', $record->hgnc_id)];
		}

		//return (object) ['count' => count($collection), 'collection' => $collection];
        //dd($array);
		return json_encode($array);
	}


    /**
     * Suggester for Gene names
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function geneLook2($args, $page = 0, $pagesize = 20)
    {
		// break out the args
		foreach ($args as $key => $value)
			$$key = $value;

        $array = [];

        // do some cleanup
        $search = trim($search);

        $parts = explode(':', $search);

        //if (stripos($search, 'HGNC') === 0)
        if (isset($parts[1]))
        {
            $id = $parts[1];

            switch(strtoupper($parts[0]))
            {
                case 'CCID':
                    $records = Slug::query()->where('alias', 'like', $search . '%')
                                    ->take(10)->orderByRaw('CHAR_LENGTH(alias)')->get();
                    foreach($records as $record)
                    {
                        $array[] = ['label' => $record->alias,
                                    'alias' => ($record->subtype == 1 ? 'Gene-Disease Validity' : 'Dosage Sensitivity'),
                                    'hgnc' => $record->target,
                                    'url' => route('validity-show', $search),
                                    'curated' => true];
                    }
                    break;
                case 'MIM':
                case 'OMIM':
                    /*$records = Gene::query()->where('omim_id', 'like', '%' . $id . '%')->with('gene')->
                                ->take(10)->orderByRaw('CHAR_LENGTH(omim_id)')->get();
                    foreach($records as $record)
                    {
                        $array[] = ['label' => $record->omim_id,
                                    'alias' => '',
                                    'hgnc' => $record->name,
                                    'url' => route('gene-show', $record->hgnc_id),
                                    'curated' => !empty($record->activity)];
                    }
                    break;*/
                    $records = Mim::query()->where('mim', 'like', $id . '%')
                                ->take(10)->orderByRaw('CHAR_LENGTH(mim)')->get();
                    foreach($records as $record)
                    {
                        $array[] = ['label' => $record->mim,
                                    'alias' => ($record->type == 1 ? 'Phenotype' : 'Gene'),
                                    'hgnc' => $record->gene->hgnc_id,
                                    'url' => route('gene-show', $search),
                                    'curated' => !empty($record->gene->activity)];
                    }
                    break;
                case 'ENSEMBL':
                case 'NCBI':
                    $records = Gene::query()->where('ensembl_gene_id', 'like', $id . '%')
                                ->take(10)->orderByRaw('CHAR_LENGTH(ensembl_gene_id)')->get();
                    foreach($records as $record)
                    {
                        $array[] = ['label' => $record->ensembl_gene_id,
                                    'alias' => '',
                                    'hgnc' => $record->name,
                                    'url' => route('gene-show', $record->hgnc_id),
                                    'curated' => !empty($record->activity)];
                    }
                    break;
                case 'ENTREZ':
                    $records = Gene::query()->where('entrez_id', 'like', $id . '%')
                                ->take(10)->orderByRaw('CHAR_LENGTH(entrez_id)')->get();
                    foreach($records as $record)
                    {
                        $array[] = ['label' => $record->entrez_id,
                                    'alias' => '',
                                    'hgnc' => $record->name,
                                    'url' => route('gene-show', $record->hgnc_id),
                                    'curated' => !empty($record->activity)];
                    }
                    break;
                case 'HGNC':
                    $records = Gene::query()->where('hgnc_id', 'like', $search . '%')
                                ->take(10)->orderByRaw('CHAR_LENGTH(hgnc_id)')->get();
                    foreach($records as $record)
                    {
                        $array[] = ['label' => $record->hgnc_id,
                                    'alias' => '',
                                    'hgnc' => $record->name,
                                    'url' => route('gene-show', $record->hgnc_id),
                                    'curated' => !empty($record->activity)];
                    }
                    break;
                case 'UCSC':
                    $records = Gene::query()->where('ucsc_id', 'like', $id . '%')
                                ->take(10)->orderByRaw('CHAR_LENGTH(ucsc_id)')->get();
                    foreach($records as $record)
                    {
                        $array[] = ['label' => $record->ucsc_id,
                                    'alias' => '',
                                    'hgnc' => $record->name,
                                    'url' => route('gene-show', $record->hgnc_id),
                                    'curated' => !empty($record->activity)];
                    }
                    break;
                case 'UNIPROT':
                    $records = Gene::query()->where('uniprot_id', 'like', $id . '%')
                                ->take(10)->orderByRaw('CHAR_LENGTH(uniprot_id)')->get();
                    foreach($records as $record)
                    {
                        $array[] = ['label' => $record->uniprot_id,
                                    'alias' => '',
                                    'hgnc' => $record->name,
                                    'url' => route('gene-show', $record->hgnc_id),
                                    'curated' => !empty($record->activity)];
                    }
                    break;
                default:
                    $check = null;
            }
        }
        else
        {
            $records = Term::where('name', 'like', '%' . $search . '%')
                        ->whereIn('type', [1, 2, 3])
                        ->orderByRaw('CHAR_LENGTH(name)')
                        ->orderBy('alias')
                        ->orderBy('weight', 'desc')
                        ->take(10)->get();
            foreach($records as $record)
            {
                switch ($record->type)
                {
                    case 2:
                        $ctag = "(previous of " . $record->alias . ")";
                        break;
                    case 3:
                        $ctag = "(alias of " . $record->alias . ")";
                        break;
                    default:
                        $ctag = '';
                }
                // $ctag .= (empty($record->curated) ? '' : ' CURATED');
                //$array[] = ['label' => $record->name . '  (' . $record->value . ')'
                //                . $ctag,
                $array[] = ['label' => $record->name,
                            'alias' => $ctag,
                            'hgnc' => $record->value,
                            'url' => route('gene-show', $record->value),
                            'curated' => !empty($record->curated)];
            }
        }
		return json_encode($array);
	}

    /**
     * Suggester for Gene names
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function geneLookByName($args, $page = 0, $pagesize = 20)
    {
        foreach ($args as $key => $value)
            $$key = $value;

        $array = [];

        // do some cleanup
        $search = trim($search);

        $parts = explode(' ', $search);

        $records = Gene::where( function($gene) use ($parts) {
            foreach ($parts as $part) {
                $gene->orWhere('description', 'LIKE', '%' . $part . '%');
            }
        })->get();

        foreach ($records as $record) {
            $array[] = ['label' => $record->description,
                'alias' => '',
                'hgnc' => $record->name,
                'url' => route('gene-show', $record->hgnc_id),
                'curated' => !empty($record->activity)];
        }


        return json_encode($array);
    }


    /**
     * Suggester for Gene names used by the dashboard
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function geneFind($args, $page = 0, $pagesize = 20)
    {
		// break out the args
		foreach ($args as $key => $value)
			$$key = $value;

		if ($search == '*')
		{
			$array = [['label' => 'All Genes (*)',
						'short' => 'All Genes',
						'curated' => 2,
						'hgncid' => '*'
						]];
			return json_encode($array);
		}

		if (strpos('@', $search) === 0)
		{
			$array = [['label' => 'All Validity',
						'short' => 'All Validity',
						'curated' => 2,
						'hgncid' => '@AllValidity'
					],
					['label' => 'All Dosage',
						'short' => 'All Dosage',
						'curated' => 2,
						'hgncid' => '@AllDosage'
					],
					['label' => 'All Actionability',
						'short' => 'All Actionability',
						'curated' => 2,
						'hgncid' => '@AllActionability'
					],
					['label' => 'All Variant Pathogenicity',
						'short' => 'All Variant Pathogenicity',
						'curated' => 2,
						'hgncid' => '@AllVariant'
					],
					['label' => 'ACMG SF 3.2 Genes',
						'short' => '@ACMG59',
						'curated' => 2,
						'hgncid' => '@ACMG59'
					]
				];

			return json_encode($array);
		}

        if (stripos($search, 'HGNC:') === 0)
            $records = Gene::query()->where('hgnc_id', 'like', $search . '%')
                            ->take(10)->orderByRaw('CHAR_LENGTH(hgnc_id)')->get();
        else
            $records = Gene::query()->where('name', 'like', '%' . $search . '%')
                            ->orWhere('alias_symbol', 'like', '%' . $search . '%')
                            ->orWhere('prev_symbol', 'like', '%' . $search . '%')
                            ->take(10)->orderByRaw('CHAR_LENGTH(name)')->get();


		$array = [];
		foreach($records as $record)
		{
			/*$ctag = (empty($record->curations) ? '' : '        CURATED');
			$array[] = ['label' => $record->text . '  (' . $record->alternative_curie . ')'
							. $ctag,
						'url' => route('gene-show', $record->hgnc_id)];*/
            $ctag = (empty($record->activity) ? '' : ' CURATED');
            $array[] = ['label' => $record->name . '  (' . $record->hgnc_id . ')'
                            . $ctag,
                        //'url' => route('gene-show', $record->hgnc_id),
                        'short' => $record->name,
						'curated' => !empty($record->activity),
						'hgncid' => $record->hgnc_id

                    ];
		}

		return json_encode($array);
	}


    /**
	 * Get gene list with curation flags and last update
	 *
	 * @return Illuminate\Database\Eloquent\Collection
	 */
	static function geneListForExportReport($args, $curated = true, $page = 0, $pagesize = 20000)
	{
		// break out the args
		foreach ($args as $key => $value)
			$$key = $value;

		// initialize the collection
		$collection = collect();

        $gene_list = Gene::whereNotNull('activity')->with('curations')->get(['name as label', 'hgnc_id', 'date_last_curated as last_curated_date', 'activity', 'acmg59', 'disease']);

        foreach ($gene_list as $gene)
        {
            dd($gene);
            $return[] = [
                'gene_symbol' => $gene->label,
                'hgnc_id' => $gene->hgnc_id,
                'gene_url' => route('gene-show', ['id' => $gene->hgnc_id]),
                'disease_label' => $genetic_condition->disease->label,
                'mondo_id' => $genetic_condition->disease->curie,
                'disease_url' => route('condition-show', ['id' => $genetic_condition->disease->curie]),
                'mois' => $mois,
                'haploinsufficiency_assertion' => $haploinsufficiency_assertion,
                'triplosensitivity_assertion' => $triplosensitivity_assertion,
                'dosage_report' => $dosage_report,
                'dosage_group' => $dosage_group,
                'gene_validity_assertion_classifications' => $gene_validity_assertions_classifications,
                'gene_validity_assertion_reports' => $gene_validity_assertion_reports,
                'gene_validity_gceps' => $gene_validity_gceps,
                'actionability_assertion_classifications' => $actionability_assertion_classifications,
                'actionability_assertion_reports' => $actionability_assertions_reports,
                'actionability_groups' => $actionability_groups,

            ];
        }
    }
}
