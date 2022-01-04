<?php

namespace App;

use Illuminate\Support\Facades\Log;

use Auth;

use App\Traits\Query;

use App\Drug;
use App\Disease;
use App\Term;

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
				$collection = Gene::where('name', 'like', '%' . $search . '%')
                    ->orderByRaw('CHAR_LENGTH(name)')
                    //->offset($page * $pagesize)
                    //->take($pagesize)
                    ->get(['name as symbol', 'description as name', 'hgnc_id', 'date_last_curated as last_curated_date', 'activity as curation_activities', 'locus_type']);

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

        $pair = explode(':', $search);

        $is_symbol = count($pair) > 1;

        switch (strtoupper($pair[0]))
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
                $array[] = ['label' => $record->label,
                            'alias' => '',
                            'hgnc' => $record->curie,
                            'url' => route('condition-show', $record->curie),
                            'curated' => !empty($record->curation_activities)];
            }
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
			$collection = Disease::where('label', 'like', '%' . $search . '%')
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

        $pair = explode(':', $search);

        $is_symbol = count($pair) > 1;

        switch (strtoupper($pair[0]))
        {
            case 'HGNC':
                $records = Gene::query()->where('hgnc_id', 'like', $search . '%')
                                        ->take(10)->orderByRaw('CHAR_LENGTH(hgnc_id)')->get();
                break;
            case 'OMIM':
                $records = Gene::query()->where('omim_id', 'like', '%' . $pair[1] . '%')
                                        ->take(10)->orderByRaw('CHAR_LENGTH(hgnc_id)')->get();

                break;
            case 'ENSEMBL':
            case 'NCBI':
                $records = Gene::query()->where('ensembl_gene_id', 'like', $pair[1] . '%')
                                        ->take(10)->orderByRaw('CHAR_LENGTH(hgnc_id)')->get();
                break;
            case 'ENTREZ':
                $records = Gene::query()->where('entrez_id', 'like', $pair[1] . '%')
                                        ->take(10)->orderByRaw('CHAR_LENGTH(hgnc_id)')->get();
                break;
            case 'UCSC':
                $records = Gene::query()->where('ucsc_id', 'like', $pair[1] . '%')
                                        ->take(10)->orderByRaw('CHAR_LENGTH(hgnc_id)')->get();
                break;
            case 'UNIPROT':
                $records = Gene::query()->where('uniprot_id', 'like', $pair[1] . '%')
                                        ->take(10)->orderByRaw('CHAR_LENGTH(hgnc_id)')->get();
                break;
            default:
        }

        if ($is_symbol)     // (strpos($search, ':') === 0)
        {
            //$records = Gene::query()->where('hgnc_id', 'like', $search . '%')
            //                ->take(10)->orderByRaw('CHAR_LENGTH(hgnc_id)')->get();

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
                $array[] = ['label' => $search, //$record->hgnc_id,
                            'alias' => '',
                            'hgnc' => $record->name,
                            'url' => route('gene-show', $record->hgnc_id),
                            'curated' => !empty($record->activity)];
            }
        }
        else
        {
            $records = Term::where('name', 'like', '%' . $search . '%')
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
}
