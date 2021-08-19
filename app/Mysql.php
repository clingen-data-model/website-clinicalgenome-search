<?php

namespace App;

use Illuminate\Support\Facades\Log;

use Auth;

use App\Traits\Query;

use App\Drug;
use App\Disease;

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
				$collection = Gene::all(['name as symbol', 'description as name', 'hgnc_id', 'date_last_curated as last_curated_date', 'activity as curation_activities', 'locus_type']);
			}
			else
			{
				// initialize the collection
				$collection = Gene::where('name', 'like', '%' . $search . '%')->get(['name as symbol', 'description as name', 'hgnc_id', 'date_last_curated as last_curated_date', 'activity as curation_activities', 'locus_type']);

				// manipulate the return order per Erin
				if ($search !== null && $search != "")
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
				}
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
		return (object) ['count' => $collection->count(), 'collection' => $collection,
						'naction' => $naction, 'nvalid' => $nvalid, 'ndosage' => $ndosage,
						'npharma' => $npharma, 'nvariant' => $nvariant, 'ncurated' => $ncurated];
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
     * Suggester for Condition names
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function conditionLook($args, $page = 0, $pagesize = 20)
    {
		// break out the args
		foreach ($args as $key => $value)
			$$key = $value;

		$collection = collect();

		$array = [];
		foreach($response->suggest as $record)
		{
			$ctag = (empty($record->curations) ? '' : '        CURATED');
			$array[] = ['label' => $record->text . '  (' . $record->curie . ')'
							. $ctag,
						'url' => route('condition-show', $record->curie)];
		}

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
			$collection = Disease::where('label', 'like', '%' . $search . '%')->get();

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
			$collection = Drug::where('label', 'like', '%' . $search . '%')->get();

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

		$collection = collect();

        $records = Gene::query()->where('name', 'like', '%5P%')
                            ->orWhere('alias_symbol', 'like', '%5P%')
                            ->orWhere('prev_symbol', 'like', '%5P%')
                            ->take(10)->orderByRaw('CHAR_LENGTH(name)')->get();

		$array = [];
		foreach($records as $record)
		{
			/*$ctag = (empty($record->curations) ? '' : '        CURATED');
			$array[] = ['label' => $record->text . '  (' . $record->alternative_curie . ')'
							. $ctag,
						'url' => route('gene-show', $record->hgnc_id)];*/
            $ctag = (empty($record->activiity) ? '' : '        CURATED');
            $array[] = ['label' => $record->name . '  (' . $record->hgnc_id . ')'
                            . $ctag,
                        'url' => route('gene-show', $record->hgnc_id)];
		}

		//return (object) ['count' => count($collection), 'collection' => $collection];
		return json_encode($array);
	}
}
