<?php

namespace App;

use Illuminate\Support\Facades\Log;

use App\Traits\Query;

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
class Graphql
{
	use Query;

	protected static $prefix = "https://search.clinicalgenome.org/kb/agents/";

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

		// initialize the collection
		$collection = collect();

		// set up query for either all genes or just curated ones
		if ($curated === true)
		{
			// note:  we don't currently use last_curated_date
			$query = '{
					genes('
					. self::optionList($page, $pagesize, $sort, $direction, $search, 'ALL')
					. ') {
						count
						gene_list {
							label
							hgnc_id
							last_curated_date
							curation_activities
							dosage_curation {
								triplosensitivity_assertion {
									dosage_classification {
										ordinal
									  }
								}
								haploinsufficiency_assertion {
									dosage_classification {
										ordinal
									  }
								}
							}
						}
					}
				}';
		}
		else
		{
			$query = '{
					genes('
					. self::optionList($page, $pagesize, $sort, $direction, $search, $curated)
					. ') {
						count
						gene_list {
							label
							alternative_label
							hgnc_id
							last_curated_date
							curation_activities
						}
					}
				}';
		}

		// query genegraph
		$response = self::query($query, __METHOD__);

		if (empty($response))
			return $response;

		// add each gene to the collection
		foreach($response->genes->gene_list as $record)
			$collection->push(new Nodal((array) $record));

		if ($curated)
		{
			$naction = $collection->where('has_actionability', true)->count();
			$nvalid = $collection->where('has_validity', true)->count();
			$ndosage = $collection->where('has_dosage', true)->count();
			//$ndosage = $collection->whereNotNull('dosage_curation')->count();
		}
		else
		{
			// right now we only use these counts on the curated page.  Probably should get triggered
			// by a call option so as not to bury things to deep.
			$naction = 0;
			$nvalid = 0;
			$ndosage = 0;
		}

		return (object) ['count' => $response->genes->count, 'collection' => $collection,
						'naction' => $naction, 'nvalid' => $nvalid, 'ndosage' => $ndosage];
	}


	/**
     * Get details of a specific gene
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function geneDetail($args, $page = 0, $pagesize = 20)
    {
		// break out the args
		foreach ($args as $key => $value)
			$$key = $value;

		// set up query for gene details
		$query = '{
				gene('
				. 'iri: "' . $gene
				. '") {
					label
					alternative_label
					hgnc_id
					chromosome_band
					curation_activities
					last_curated_date:
					dosage_curation {
						curie
						report_date
						triplosensitivity_assertion {
							dosage_classification {
								ordinal
							  }

						}
						haploinsufficiency_assertion {
							dosage_classification {
								ordinal
							  }

						}
					}
					genetic_conditions {
						disease {
						  label
						  iri
						}
						gene_validity_assertions {
						  mode_of_inheritance {
							  label
							  curie
						  }
						  report_date
						  classification {
							  label
							  curie
						  }
						  curie
						}
						actionability_curations {
						  report_date
						  source
						}
						gene_dosage_assertions {
						  report_date
						  assertion_type
						  dosage_classification {
							ordinal
							}
						  curie
						}
					}
				}
			}';

		// query genegraph
		$response = self::query($query,  __METHOD__);

		if (empty($response))
			return $response;

		$node = new Nodal((array) $response->gene);

		// add additional information from local db
		$localgene = Gene::where('hgnc_id', $gene)->first();
		if ($localgene !== null)
		{
			$node->alias_symbols = $localgene->display_aliases;
			$node->prev_symbols = $localgene->display_previous;
			$node->hi = round($localgene->hi, 2);
			$node->pli = round($localgene->pli, 2);
			$node->plof = round($localgene->plof, 2);
			$node->locus_type = $localgene->locus_type;
			$node->locus_group = $localgene->locus_group;
			$node->ensembl_id = $localgene->ensembl_gene_id;
			$node->entrez_id = $localgene->entrez_id;
			$node->omim_id = $localgene->omim_id;
			$node->ucsc_id = $localgene->ucsc_id;
			$node->uniprot_id = $localgene->uniprot_id;
			$node->function = $localgene->function;
		}

		// currently, there is no easy way to track what needs dosage_curation entries belong in
		// the catch all, so we need to process the genetic conditions and add some flags.
		$dosage_curation_map = ["haploinsufficiency_assertion" => true, "triplosensitivity_assertion" => true];

		if (empty($node->dosage_curation->triplosensitivity_assertion))
			unset($dosage_curation_map["triplosensitivity_assertion"]);

		if (empty($node->dosage_curation->haploinsufficiency_assertion))
			unset($dosage_curation_map["haploinsufficiency_assertion"]);

		if (!empty($node->genetic_conditions))
		{
			foreach($node->genetic_conditions as $condition)
			{
				foreach($condition->gene_dosage_assertions as $dosage)
				{
					switch ($dosage->assertion_type)
					{
						case "HAPLOINSUFFICIENCY_ASSERTION":
							unset($dosage_curation_map["haploinsufficiency_assertion"]);
							break;
						case "TRIPLOSENSITIVITY_ASSERTION":
							unset($dosage_curation_map["triplosensitivity_assertion"]);
							break;
					}
				}
			}
		}
		$by_activity = ['gene_validity' => [], 'dosage_curation' => [], 'actionability' => []];
			if (!empty($node->genetic_conditions))
			{
				//dd($node->genetic_conditions);
				$i = -1;
				foreach($node->genetic_conditions as $genetic_condition) {
					$i++;
					$ii = -1;
					foreach ($genetic_condition->gene_validity_assertions as $gene_validity_assertion) {
						$ii++;
						$curie = explode("/", $genetic_condition->disease->iri);
						$by_activity['gene_validity'][end($curie)][$ii]['disease'] = $genetic_condition->disease;
						$by_activity['gene_validity'][end($curie)][$ii]['curation'] = $gene_validity_assertion;
					}
					$ii = -1;
					foreach ($genetic_condition->gene_dosage_assertions as $gene_dosage_assertion) {
						$ii++;
						$curie = explode("/", $genetic_condition->disease->iri);
						$by_activity['dosage_curation'][end($curie)][$ii]['disease'] = $genetic_condition->disease;
						$by_activity['dosage_curation'][end($curie)][$ii]['curation'] = $gene_dosage_assertion;
					}
					$ii = -1;
					foreach ($genetic_condition->actionability_curations as $actionability_curation) {
						$ii++;
						$curie = explode("/", $genetic_condition->disease->iri);
						$by_activity['actionability'][end($curie)][$ii]['disease'] = $genetic_condition->disease;
						$by_activity['actionability'][end($curie)][$ii]['curation'] = $actionability_curation;
					}
					//$i++;
					//$curations_by_activity[$i]	=	$by_activity;
				}
				$ii++;
				if($node->dosage_curation){
					$by_activity['dosage_curation']['null'][$ii]['curation'] = $node->dosage_curation;
				}


			} elseif ($node->dosage_curation) {
				$by_activity 							= [];
				$by_activity['dosage_curation']['null'][0]['curation'] = $node->dosage_curation;
			}
			//dd($by_activity);
			$curations_by_activity = json_decode(json_encode($by_activity));
			//dd($curations_by_activity);
			$node->curations_by_activity = $curations_by_activity;


		$node->dosage_curation_map = $dosage_curation_map;

		//dd($node);
		return $node;
	}


	/**
     * Get details of a specific gene by activity
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function geneActivityDetail($args, $page = 0, $pagesize = 20)
    {
		// break out the args
		foreach ($args as $key => $value)
			$$key = $value;

		// set up query for gene details
		$query = '{
				gene('
				. 'iri: "' . $gene
				. '") {
					label
					alternative_label
					hgnc_id
					chromosome_band
					curation_activities
					last_curated_date:
					dosage_curation {
						curie
						report_date
						triplosensitivity_assertion {
							dosage_classification {
								ordinal
							  }

						}
						haploinsufficiency_assertion {
							dosage_classification {
								ordinal
							  }

						}
					}
					genetic_conditions {
						disease {
						  label
						  iri
						}
						gene_validity_assertions {
						  mode_of_inheritance {
							  label
							  curie
						  }
						  report_date
						  classification {
							  label
							  curie
						  }
						  curie
						}
						actionability_curations {
						  report_date
						  source
						}
						gene_dosage_assertions {
						  report_date
						  assertion_type
						  dosage_classification {
							ordinal
							}
						  curie
						}
					}
				}
			}';

		// query genegraph
		$response = self::query($query,  __METHOD__);

		if (empty($response))
			return $response;

		$node = new Nodal((array) $response->gene);

		// add additional information from local db
		$localgene = Gene::where('hgnc_id', $gene)->first();
		if ($localgene !== null)
		{
			$node->alias_symbols = $localgene->display_aliases;
			$node->prev_symbols = $localgene->display_previous;
			$node->hi = round($localgene->hi, 2);
			$node->pli = round($localgene->pli, 2);
			$node->plof = round($localgene->plof, 2);
			$node->locus_type = $localgene->locus_type;
			$node->locus_group = $localgene->locus_group;
			$node->ensembl_id = $localgene->ensembl_gene_id;
			$node->entrez_id = $localgene->entrez_id;
			$node->omim_id = $localgene->omim_id;
			$node->ucsc_id = $localgene->ucsc_id;
			$node->uniprot_id = $localgene->uniprot_id;
			$node->function = $localgene->function;
		}

		// currently, there is no easy way to track what needs dosage_curation entries belong in
		// the catch all, so we need to process the genetic conditions and add some flags.
		$dosage_curation_map = ["haploinsufficiency_assertion" => true, "triplosensitivity_assertion" => true];

		if (empty($node->dosage_curation->triplosensitivity_assertion))
			unset($dosage_curation_map["triplosensitivity_assertion"]);

		if (empty($node->dosage_curation->haploinsufficiency_assertion))
			unset($dosage_curation_map["haploinsufficiency_assertion"]);

		if (!empty($node->genetic_conditions))
		{
			foreach($node->genetic_conditions as $condition)
			{
				foreach($condition->gene_dosage_assertions as $dosage)
				{
					switch ($dosage->assertion_type)
					{
						case "HAPLOINSUFFICIENCY_ASSERTION":
							unset($dosage_curation_map["haploinsufficiency_assertion"]);
							break;
						case "TRIPLOSENSITIVITY_ASSERTION":
							unset($dosage_curation_map["triplosensitivity_assertion"]);
							break;
					}
				}
			}
		}
		//dd($node);
		$by_activity = ['gene_validity' => [], 'dosage_curation' => [], 'actionability' => []];
			if (!empty($node->genetic_conditions))
			{
				//dd($node->genetic_conditions);
				$i = -1;
				foreach($node->genetic_conditions as $genetic_condition) {
					$i++;
					$ii = -1;
					foreach ($genetic_condition->gene_validity_assertions as $gene_validity_assertion) {
						$ii++;
						$curie = explode("/", $genetic_condition->disease->iri);
						$by_activity['gene_validity'][end($curie)][$ii]['disease'] = $genetic_condition->disease;
						$by_activity['gene_validity'][end($curie)][$ii]['curation'] = $gene_validity_assertion;
					}
					$ii = -1;
					foreach ($genetic_condition->gene_dosage_assertions as $gene_dosage_assertion) {
						$ii++;
						$curie = explode("/", $genetic_condition->disease->iri);
						$by_activity['dosage_curation'][end($curie)][$ii]['disease'] = $genetic_condition->disease;
						$by_activity['dosage_curation'][end($curie)][$ii]['curation'] = $gene_dosage_assertion;
					}
					$ii = -1;
					foreach ($genetic_condition->actionability_curations as $actionability_curation) {
						$ii++;
						$curie = explode("/", $genetic_condition->disease->iri);
						$by_activity['actionability'][end($curie)][$ii]['disease'] = $genetic_condition->disease;
						$by_activity['actionability'][end($curie)][$ii]['curation'] = $actionability_curation;
					}
					//$i++;
					//$curations_by_activity[$i]	=	$by_activity;
				}
				$ii++;
				if($node->dosage_curation){
					$by_activity['dosage_curation']['null'][$ii]['curation'] = $node->dosage_curation;
				}


			} elseif ($node->dosage_curation) {
				$by_activity 							= [];
				$by_activity['dosage_curation']['null'][0]['curation'] = $node->dosage_curation;
			}
			//dd($by_activity);
			$curations_by_activity = json_decode(json_encode($by_activity));
			//dd($curations_by_activity);
			$node->curations_by_activity = $curations_by_activity;


		$node->dosage_curation_map = $dosage_curation_map;

		//dd($node);
		return $node;
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

		$query = '{
				suggest(contexts: ALL, suggest: DRUG, text: "'
				. $search . '") {
						curie
						curations
						highlighted
						iri
						text
						type
						weight
					}
				}
			}';

		// query genegraph
		$response = self::query($query,  __METHOD__);

		if (empty($response))
			return $response;

		// add each gene to the collection
		/*foreach($response->suggest as $record)
		{
			$node = new Nodal((array) $record);
			$node->label = $record->highlighted . '  (' . $record->curie . ')';
			$node->href = route('drug-show', $record->curie);

			$collection->push($node);
		}*/

		$array = [];
		foreach($response->suggest as $record)
		{
			$ctag = (empty($record->curations) ? '' : '        CURATED');
			$short = "RXNORM:" . basename($record->curie);
			$array[] = ['label' => $record->text . '  (' . $short . ')'
							. $ctag,
						'url' => route('drug-show', $short)];
		}


		//return (object) ['count' => count($collection), 'collection' => $collection];
		return json_encode($array);
	}


    /**
     * Get actionability details for a specific gene
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function actionabilityList($args, $page = 0, $pagesize = 20)
    {
		// break out the args
		foreach ($args as $key => $value)
			$$key = $value;

		$query = '{
			gene(iri: "' . $iri . '") {
				label
				conditions {
					iri
					label
					actionability_curations {
						report_date
						source
					}
				}
			  }
			}';

		// query genegraph
		$response = self::query($query,  __METHOD__);

		if (empty($response))
			return $response;

		$node = new Nodal((array) $response->gene);

		return $node;
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

		$query = '{
				genes('
				. self::optionList($page, $pagesize, $sort, $direction, $search, "GENE_DOSAGE")
				. ') {
					count
					gene_list {
						label
						hgnc_id
						chromosome_band
						dosage_curation {
							report_date
							triplosensitivity_assertion {
								dosage_classification {
									ordinal
								}
							}
							haploinsufficiency_assertion {
								dosage_classification {
									ordinal
								}
							}
						}
					}
				}
			}';

		// query genegraph
		$response = self::query($query,  __METHOD__);

		if (empty($response))
			return $response;

		// add each gene to the collection
		foreach($response->genes->gene_list as $record)
		{
			$node = new Nodal((array) $record);

			// query local db for additional information
			$gene = Gene::where('hgnc_id', $node->hgnc_id)->first();

			if ($gene !== null)
			{
				$node->hi = $gene->hi;
				$node->pli = $gene->pli;
				$node->plof = $gene->plof;
				$node->omimlink = $gene->display_omim;
				$node->morbid = $gene->morbid;
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
			}

			$node->type = 0;

			$collection->push($node);
		}

		$nhaplo = $collection->where('has_dosage_haplo', '!=', 0)->count();
		$ntriplo = $collection->where('has_dosage_triplo', '!=', 0)->count();

		return (object) ['count' => $response->genes->count, 'collection' => $collection,
						'nhaplo' => $nhaplo, 'ntriplo' => $ntriplo];
	}


	/**
     * Get details of a specific gene
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function dosageDetail($args, $page = 0, $pagesize = 20)
    {
		// break out the args
		foreach ($args as $key => $value)
			$$key = $value;

		$query = '{
				gene('
				. 'iri: "' . $gene
				. '") {
					label
					alternative_label
					hgnc_id
					chromosome_band
					curation_activities
					dosage_curation {
						curie
						report_date
						triplosensitivity_assertion {
							dosage_classification {
								ordinal
							}
							score
						}
						haploinsufficiency_assertion {
							dosage_classification {
								ordinal
							}
							score
						}
					}
					genetic_conditions {
						disease {
						  label
						  iri
						}
						gene_validity_assertions {
							mode_of_inheritance {
								label
								curie
							}
						  report_date
						  classification {
								label
								curie
							}
						  curie
						}
						actionability_curations {
						  report_date
						  source
						}
						gene_dosage_assertions {
						  report_date
						  assertion_type
						  dosage_classification {
							ordinal
							}
						  score
						  curie
						}
					}
				}
			}';

		// query genegraph
		$response = self::query($query,  __METHOD__);

		if (empty($response))
			return $response;

		$node = new Nodal((array) $response->gene);

		// add additional information from local db
		$localgene = Gene::where('hgnc_id', $gene)->first();
		if ($localgene !== null)
		{
			$node->alias_symbols = $localgene->display_aliases;
			$node->prev_symbols = $localgene->display_previous;
			$node->hi = round($localgene->hi, 2);
			$node->pli = round($localgene->pli, 2);
			$node->plof = round($localgene->plof, 2);
			$node->locus_type = $localgene->locus_type;
			$node->locus_group = $localgene->locus_group;
			$node->ensembl_id = $localgene->ensembl_gene_id;
			$node->entrez_id = $localgene->entrez_id;
			$node->omim_id = $localgene->omim_id;
			$node->ucsc_id = $localgene->ucsc_id;
			$node->uniprot_id = $localgene->uniprot_id;
			$node->function = $localgene->function;
		}

		// currently, there is no easy way to track what needs dosage_curation entries belong in
		// the catch all, so we need to process the genetic conditions and add some flags.
		$dosage_curation_map = ["haploinsufficiency_assertion" => true, "triplosensitivity_assertion" => true];

		if (empty($node->dosage_curation->triplosensitivity_assertion))
			unset($dosage_curation_map["triplosensitivity_assertion"]);

		if (empty($node->dosage_curation->haploinsufficiency_assertion))
			unset($dosage_curation_map["haploinsufficiency_assertion"]);

		if (!empty($node->genetic_conditions))
		{
			foreach($node->genetic_conditions as $condition)
			{
				foreach($condition->gene_dosage_assertions as $dosage)
				{
					switch ($dosage->assertion_type)
					{
						case "HAPLOINSUFFICIENCY_ASSERTION":
							unset($dosage_curation_map["haploinsufficiency_assertion"]);
							break;
						case "TRIPLOSENSITIVITY_ASSERTION":
							unset($dosage_curation_map["triplosensitivity_assertion"]);
							break;
					}
				}
			}

		}

		$node->dosage_curation_map = $dosage_curation_map;

		return $node;
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

		// initialize the collection
		$collection = collect();

		$query = '{
				gene_validity_assertions('
					. self::optionList($page, $pagesize, $sort, $direction, $search)
				. ') {
					count
					curation_list {
						report_date
						curie
						disease {
							label
							curie
						}
						gene {
							label
							hgnc_id
						}
						mode_of_inheritance {
							label
							curie
						}
						classification {
							label
						}
						specified_by {
							label
						}
						attributed_to {
							label
						}
					}
				}
			}';

		// query genegraph
		$response = self::query($query,  __METHOD__);

		if (empty($response))
			return $response;

		// add each gene to the collection
		foreach($response->gene_validity_assertions->curation_list as $record)
			$collection->push(new Nodal((array) $record));

		$ngenes = $collection->unique('gene')->count();
		$npanels = $collection->unique('attributed_to')->count();

		return (object) ['count' => $response->gene_validity_assertions->count,
						'collection' => $collection,
						'ngenes' => $ngenes,
						'npanels' => $npanels
						];
	}


	/**
     * Get validity report for a specific gene-disease pair
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function validityDetail($args, $page = 0, $pagesize = 20)
    {
		// break out the args
		foreach ($args as $key => $value)
			$$key = $value;

		// special case where legacy perm value is passed
		if (is_numeric($perm))
			$perm = "CGGCIEX:assertion_" . $perm;

		$query = '{
			gene_validity_assertion('
			. 'iri: "' . $perm
			. '") {
				curie
				report_date
				gene {
					label
					hgnc_id
					curie
				}
				disease {
					label
					curie
				}
				mode_of_inheritance {
					label
					curie
				}
				attributed_to {
					label
					curie
				}
				classification {
					label
					curie
				}
				specified_by {
					label
					curie
				}
				legacy_json
			}
		}';

		// query genegraph
		$response = self::query($query,  __METHOD__);

		if (empty($response))
			return $response;

		$node = new Nodal((array) $response->gene_validity_assertion);
		$node->json = json_decode($node->legacy_json, false);
		$node->score_data = $node->json->scoreJson ?? $node->json;

		return $node;

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

		$query = '{
			affiliations (limit: null)
			{
				count
				agent_list {
					iri
					curie
					label
					gene_validity_assertions{
						count
					}
				}
			}
		}';

		// query genegraph
		$response = self::query($query,  __METHOD__);

		if (empty($response))
			return $response;

		$ncurations = 0;

		// add each gene to the collection
		foreach($response->affiliations->agent_list as $record)
		{
			$node = new Nodal((array) $record);
			$ncurations += $node->gene_validity_assertions->count;

			$collection->push(new Nodal((array) $record));
		}

		// genegraph currently provides no sort capablility
		$collection = $collection->sortBy('label');

		return (object) ['count' => $response->affiliations->count, 'collection' => $collection,
						'ncurations' => $ncurations];
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

		// initialize the collection
		$collection = collect();

		$query = '{
			affiliation('
				. 'iri: "CGAGENT:' . $affiliate
				. '") {
				curie
				iri
				label
				gene_validity_assertions(limit: null, sort: {field: GENE_LABEL, direction: ASC}) {
					count
					curation_list {
						curie
						iri
						label
						legacy_json
						gene {
							label
							hgnc_id
							curie
						}
						disease {
							label
							curie
						}
						mode_of_inheritance {
							label
							curie
						}
						attributed_to {
							label
							curie
						}
						classification {
							label
							curie
						}
						specified_by {
							label
							curie
						}
						report_date
					}
				}
			}
		}';

		// query genegraph
		$response = self::query($query,  __METHOD__);

		if (empty($response))
			return $response;

		// add each gene to the collection
		foreach($response->affiliation->gene_validity_assertions->curation_list as $record)
			$collection->push(new Nodal((array) $record));

		return (object) ['count' => $response->affiliation->gene_validity_assertions->count,
						 'collection' => $collection, 'label' => $response->affiliation->label];
	}


	/**
     * Get details of a conditions
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function conditionDetail($args, $page = 0, $pagesize = 20)
    {
		// break out the args
		foreach ($args as $key => $value)
			$$key = $value;

		// genegraph doesn't like when the mondo prefix is missing, handle gracefully

		if (strpos($condition, 'MONDO:') === false && strpos($condition, 'MONDO_') === false)
			$condition = 'MONDO:' . $condition;

		$query = '{
			disease('
			. 'iri: "' . $condition
			. '") {
				label
				iri
				curation_activities
				genetic_conditions {
					gene {
					label
					hgnc_id
					}
					gene_validity_assertions {
					mode_of_inheritance {
						label
						curie
					}
					report_date
					classification {
						label
						curie
					}
					curie
					}
					actionability_curations {
						report_date
						source
					}
					gene_dosage_assertions {
						report_date
						dosage_classification {
							ordinal
						}
						curie
					}
				}
			}
		}';

		// query genegraph
		$response = self::query($query,  __METHOD__);

		if (empty($response))
			return $response;

		$node = new Nodal((array) $response->disease);

		return $node;

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

		$query = '{
				suggest(contexts: ALL, suggest: DISEASE, text: "'
				. $search . '") {
						curie
						curations
						highlighted
						iri
						text
						type
						weight
					}
				}
			}';

		// query genegraph
		$response = self::query($query,  __METHOD__);

		if (empty($response))
			return $response;

		// add each gene to the collection
		/*foreach($response->suggest as $record)
		{
			$node = new Nodal((array) $record);
			$node->label = $record->highlighted . '  (' . $record->curie . ')';
			$node->href = route('condition-show', $record->curie);

			$collection->push($node);
		}*/

		$array = [];
		foreach($response->suggest as $record)
		{
			$ctag = (empty($record->curations) ? '' : '        CURATED');
			$array[] = ['label' => $record->text . '  (' . $record->curie . ')'
							. $ctag,
						'url' => route('condition-show', $record->curie)];
		}

		//return (object) ['count' => count($collection), 'collection' => $collection];
		return json_encode($array);
	}


	/**
     * Get listing of all conditions
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function conditionList($args, $page = 0, $pagesize = 20)
    {
		// break out the args
		foreach ($args as $key => $value)
			$$key = $value;

		// initialize the collection
		$collection = collect();


		$query = '{
				diseases('
				. self::optionList($page, $pagesize, $sort, $direction, $search, $curated)
				. ') {
					count
					disease_list {
						curie
						label
						description
						last_curated_date
						curation_activities
					}
				}
			}';

		// query genegraph
		$response = self::query($query,  __METHOD__);

		if (empty($response))
			return $response;

		// add each gene to the collection
		foreach($response->diseases->disease_list as $record)
			$collection->push(new Nodal((array) $record));

		$ncurated = $collection->where('last_curated_date', '!=', null)->count();

		return (object) ['count' => $response->diseases->count, 'collection' => $collection,
						'ncurated' => $ncurated];
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
		$collection = collect();

		$query = '{
				drugs('
				. self::optionList($page, $pagesize, $sort, $direction, $search)
				. ') {
					count
					drug_list {
						label
						curie
					}
				}
			}';

		// query genegraph
		$response = self::query($query,  __METHOD__);

		if (empty($response))
			return $response;

		// add each gene to the collection
		foreach($response->drugs->drug_list as $record)
			$collection->push(new Nodal((array) $record));

		//$collection = $collection->SortBy('label');

		return (object) ['count' => $response->drugs->count, 'collection' => $collection];
	}


	/**
     * Get details of a drug
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function drugDetail($args, $page = 0, $pagesize = 20)
    {
		// break out the args
		foreach ($args as $key => $value)
			$$key = $value;

		// remap drug id back to ontology format
		$drug = str_replace(':', '/', $drug);

		$query = '{
				drug(iri: "http://purl.bioontology.org/ontology/'
				. $drug
				. '") {
						label
						iri
						curie
						aliases
					}
				}
			}';

		// query genegraph
		$response = self::query($query,  __METHOD__);

		if (empty($response))
			return $response;

		$node = new Nodal((array) $response->drug);

		return $node;
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

		$query = '{
				suggest(contexts: ALL, suggest: GENE, text: "'
				. $search . '") {
						curations
						highlighted
						alternative_curie
						text
					}
				}
			}';

		// query genegraph
		$response = self::query($query,  __METHOD__);

		if (empty($response))
			return $response;

		// add each gene to the collection
		/*foreach($response->suggest as $record)
		{
			$node = new Nodal((array) $record);
			$node->label = $record->highlighted . '  (' . $record->alternative_curie . ')';
			$node->href = route('gene-show', $record->alternative_curie);

			$collection->push($node);
		}*/

		$array = [];
		foreach($response->suggest as $record)
		{
			$ctag = (empty($record->curations) ? '' : '        CURATED');
			$array[] = ['label' => $record->text . '  (' . $record->alternative_curie . ')'
							. $ctag,
						'url' => route('gene-show', $record->alternative_curie)];
		}

		//return (object) ['count' => count($collection), 'collection' => $collection];
		return json_encode($array);
	}


	/**
     * Build the option list for the GraphQL call
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function optionList($page = 0, $pagesize = null, $sort=null, $sortdir='ASC', $search = null, $curated = false)
    {
		$options = [];

		if (!is_null($pagesize))
			$options[] = 'limit: ' . $pagesize;
		else
			$options[] = 'limit: null';

		if (!empty($page))
			$options[] = 'offset: ' . $page; // ($page * $pagesize);

		if ($curated !== false)
			$options[] = 'curation_activity: ' . $curated;

		if (!empty($sort))
			$options[] = 'sort: {field: ' . $sort . ', direction: ' . strtoupper($sortdir) . '}';

		if (!empty($search))
			$options[] = 'text: "*' . $search . '*"';

		return implode(', ', $options);
	}
}
