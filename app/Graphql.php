<?php

namespace App;

use Alexaandrov\GraphQL\Facades\Client as Genegraph;
use Illuminate\Support\Facades\Log;

use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;

use App\GeneLib;

use Exception;

use Carbon\Carbon;

/**
 * 
 * @category   Library
 * @package    Search
 * @author     P. Weller <pweller1@geisinger.edu>
 * @author     S. Goehringer <scottg@creationproject.com>
 * @copyright  2019 ClinGen
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/PackageName
 * @see        NetOther, Net_Sample::Net_Sample()
 * @since      Class available since Release 1.0.0
 * 
 * */
class Graphql
{    
	
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
		
		if ($curated === true)
		{		
			$query = '{
					genes(' 
					. self::optionList($page, $pagesize, $sort, $direction, $search, 'ALL')
					. ') {
						count
						gene_list {
							label
							hgnc_id
							iri
							chromosome_band
							last_curated_date
							alternative_label
							curation_activities
							dosage_curation {
								triplosensitivity_assertion { score }
								haploinsufficiency_assertion { score }
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
							chromosome_band
							last_curated_date
							curation_activities
						}
					}
				}';
		}

		try {
			Log::info("Begin Genegraph genelist call: " . Carbon::now());
			$response = Genegraph::fetch($query);
			Log::info("End Genegraph genelist call: " . Carbon::now());
			
		} catch (RequestException $exception) {	// guzzle exceptions
    
			$response = $exception->getResponse();
			if (is_null($response))				// empty reply from server
			{
				//GeneLib::putError($errors);
				
				// for now, just return an empty list
				return $collection;
			}
			
			$code = $response->getStatusCode();
			$reason = $response->getReasonPhrase();
			$errors = json_decode($exception->getResponse()->getBody()->getContents(), true);
			
			GeneLib::putError($errors);
			
			return null;
			
		} catch (Exception $exception) {		// everything else
			
			$response = $exception->getResponse();
			$code = $response->getStatusCode();
			$reason = $response->getReasonPhrase();
			$errors = json_decode($exception->getResponse()->getBody()->getContents(), true);
			
			GeneLib::putError($errors);
			
			return null;
			
		};
		
		// add each gene to the collection
		foreach($response->genes->gene_list as $record)
			$collection->push(new Nodal((array) $record));
	
		return (object) ['count' => $response->genes->count, 'collection' => $collection];
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
							score
							dosage_classification {
								label
								ordinal
								enum_value
							  }
					  
						}
						haploinsufficiency_assertion {
							score
							dosage_classification {
								label
								ordinal
								enum_value
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
						  score
						  curie
						}
					}
				}
			}';

		try {
		
			Log::info("Begin Genegraph genedetail call: " . Carbon::now());
			$response = Genegraph::fetch($query);
			Log::info("End Genegraph genedetail call: " . Carbon::now());
			
		} catch (RequestException $exception) {	// guzzle exceptions
    
			$response = $exception->getResponse();
			if (is_null($response))				// empty reply from server
			{
				//GeneLib::putError($errors);
				
				// for now, just return an empty list
				return $collection;
			}
			
			$code = $response->getStatusCode();
			$reason = $response->getReasonPhrase();
			$errors = json_decode($exception->getResponse()->getBody()->getContents(), true);
			
			GeneLib::putError($errors);
			
			return null;
			
		} catch (Exception $exception) {		// everything else
			
			$response = $exception->getResponse();
			$code = $response->getStatusCode();
			$reason = $response->getReasonPhrase();
			$errors = json_decode($exception->getResponse()->getBody()->getContents(), true);
			
			GeneLib::putError($errors);
			
			return null;
			
		};

		$node = new Nodal((array) $response->gene);

		// add additional information from local db
		$localgene = Gene::where('hgnc_id', $gene)->first();
		if ($localgene !== null)
		{
			$node->alias_symbols = $localgene->display_aliases;
			$node->prev_symbols = $localgene->display_previous;
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
	//dd($node);	

		return $node;	
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
		
		try {
		
			Log::info("Begin Genegraph actionabilitylist call: " . Carbon::now());
			$response = Genegraph::fetch($query);
			Log::info("End Genegraph actionabilitylist call: " . Carbon::now());
			
		} catch (RequestException $exception) {	// guzzle exceptions
    
			$response = $exception->getResponse();
			$code = $response->getStatusCode();
			$reason = $response->getReasonPhrase();
			$errors = json_decode($exception->getResponse()->getBody()->getContents(), true);
			
			GeneLib::putError($errors);
			
			return null;
			
		} catch (Exception $exception) {		// everything else
			
			$response = $exception->getResponse();
			$code = $response->getStatusCode();
			$reason = $response->getReasonPhrase();
			$errors = json_decode($exception->getResponse()->getBody()->getContents(), true);
			
			GeneLib::putError($errors);
			
			return null;
			
		};
				
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
								score
							}
							haploinsufficiency_assertion {
								score
							}
						}
					}
				}
			}';

		try {
		
			Log::info("Begin Genegraph dosagelist call: " . Carbon::now());
			$response = Genegraph::fetch($query);
			Log::info("End Genegraph dosagelist call: " . Carbon::now());
			
		} catch (RequestException $exception) {	// guzzle exceptions
    
			$response = $exception->getResponse();
			if (is_null($response))				// empty reply from server
			{
				//GeneLib::putError($errors);
				
				// for now, just return an empty list
				return $collection;
			}
			
			$code = $response->getStatusCode();
			$reason = $response->getReasonPhrase();
			$errors = json_decode($exception->getResponse()->getBody()->getContents(), true);
			
			GeneLib::putError($errors);
			
			return null;
			
		} catch (Exception $exception) {		// everything else
			
			$response = $exception->getResponse();
			$code = $response->getStatusCode();
			$reason = $response->getReasonPhrase();
			$errors = json_decode($exception->getResponse()->getBody()->getContents(), true);
			
			GeneLib::putError($errors);
			
			return null;
			
		};

		// add each gene to the collection
		foreach($response->genes->gene_list as $record)
		{
			$node = new Nodal((array) $record);

			$gene = Gene::where('hgnc_id', $node->hgnc_id)->first();

			if ($gene !== null)
			{
				$node->hi = $gene->hi;
				$node->pli = $gene->pli;
				$collection->push($node);
			}
			//$collection->push(new Nodal((array) $record));
		}
	
		return (object) ['count' => $response->genes->count, 'collection' => $collection];
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
						triplosensitivity_assertion { score }
						haploinsufficiency_assertion { score }
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
						  score
						  curie
						}
					}
				}
			}';

		try {
		
			Log::info("Begin Genegraph dosagedetail call: " . Carbon::now());
			$response = Genegraph::fetch($query);
			Log::info("End Genegraph dosagedetail call: " . Carbon::now());
			
		} catch (RequestException $exception) {	// guzzle exceptions
    
			$response = $exception->getResponse();
			if (is_null($response))				// empty reply from server
			{
				//GeneLib::putError($errors);
				
				// for now, just return an empty list
				return $collection;
			}
			
			$code = $response->getStatusCode();
			$reason = $response->getReasonPhrase();
			$errors = json_decode($exception->getResponse()->getBody()->getContents(), true);
			
			GeneLib::putError($errors);
			
			return null;
			
		} catch (Exception $exception) {		// everything else
			
			$response = $exception->getResponse();
			$code = $response->getStatusCode();
			$reason = $response->getReasonPhrase();
			$errors = json_decode($exception->getResponse()->getBody()->getContents(), true);
			
			GeneLib::putError($errors);
			
			return null;
			
		};

		$node = new Nodal((array) $response->gene);

		// add additional information from local db
		$localgene = Gene::where('hgnc_id', $gene)->first();
		if ($localgene !== null)
		{
			$node->alias_symbols = $localgene->display_aliases;
			$node->prev_symbols = $localgene->display_previous;
			$node->hi = round($localgene->hi, 2);
			$node->pli = round($localgene->pli, 2);
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
	//dd($node);	

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
			
		/*$query = '{
				diseases('
					. self::optionList($page, $pagesize, $sort, $direction, $search, "ALL")
				. ') {
					disease_list {
						iri
						curie
						label
						last_curated_date
						curation_activities
					}
					count
				}
			}';*/

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
							curie
						}
						specified_by {
							label
							curie
						}
						attributed_to {
							label
							curie
						}
					}
				}
			}';

		try {
		
			Log::info("Begin Genegraph validitylist call: " . Carbon::now());
			$response = Genegraph::fetch($query);
			Log::info("End Genegraph validitylist call: " . Carbon::now());
			
		} catch (RequestException $exception) {	// guzzle exceptions
    
			$response = $exception->getResponse();
			if (is_null($response))				// empty reply from server
			{
				//GeneLib::putError($errors);
				
				// for now, just return an empty list
				return $collection;
			}
			
			$code = $response->getStatusCode();
			$reason = $response->getReasonPhrase();
			$errors = json_decode($exception->getResponse()->getBody()->getContents(), true);
			
			GeneLib::putError($errors);
			
			return null;
			
		} catch (Exception $exception) {		// everything else
			
			$response = $exception->getResponse();
			$code = $response->getStatusCode();
			$reason = $response->getReasonPhrase();
			$errors = json_decode($exception->getResponse()->getBody()->getContents(), true);
			
			GeneLib::putError($errors);
			
			return null;
			
		};

		// add each gene to the collection
		foreach($response->gene_validity_assertions->curation_list as $record)
			$collection->push(new Nodal((array) $record));
	
		return (object) ['count' => $response->gene_validity_assertions->count, 'collection' => $collection];
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

		try {
	
			Log::info("Begin Genegraph validitydetail call: " . Carbon::now());
			$response = Genegraph::fetch($query);
			Log::info("End Genegraph validitydetail call: " . Carbon::now());
			
		} catch (RequestException $exception) {	// guzzle exceptions
	
			$response = $exception->getResponse();
			if (is_null($response))				// empty reply from server
			{
				//GeneLib::putError($errors);
				
				// for now, just return an empty list
				return $collection;
			}
			
			$code = $response->getStatusCode();
			$reason = $response->getReasonPhrase();
			$errors = json_decode($exception->getResponse()->getBody()->getContents(), true);
			
			GeneLib::putError($errors);
			
			return null;
			
		} catch (Exception $exception) {		// everything else
			
			$response = $exception->getResponse();
			$code = $response->getStatusCode();
			$reason = $response->getReasonPhrase();
			$errors = json_decode($exception->getResponse()->getBody()->getContents(), true);
			
			GeneLib::putError($errors);
			
			return null;
			
		};

		$node = new Nodal((array) $response->gene_validity_assertion);
		$node->json = json_decode($node->legacy_json, false);
		$node->score_data = $node->json->scoreJson ?? $node->json;
//dd($node);
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

		try {
	
			Log::info("Begin Genegraph actionabilitylist call: " . Carbon::now());
			$response = Genegraph::fetch($query);
			Log::info("End Genegraph actionabilitylist call: " . Carbon::now());
			
		} catch (RequestException $exception) {	// guzzle exceptions
	
			$response = $exception->getResponse();
			$code = $response->getStatusCode();
			$reason = $response->getReasonPhrase();
			$errors = json_decode($exception->getResponse()->getBody()->getContents(), true);
			
			GeneLib::putError($errors);
			
			return null;
			
		} catch (Exception $exception) {		// everything else
			
			$response = $exception->getResponse();
			$code = $response->getStatusCode();
			$reason = $response->getReasonPhrase();
			$errors = json_decode($exception->getResponse()->getBody()->getContents(), true);
			
			GeneLib::putError($errors);
			
			return null;
			
		};
				
		// add each gene to the collection
		foreach($response->affiliations->agent_list as $record)
		{
			$node = new Nodal((array) $record);

			$collection->push(new Nodal((array) $record));
		}
	//dd($collection);
		return (object) ['count' => $response->affiliations->count, 'collection' => $collection];
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

		try {
	
			Log::info("Begin Genegraph affiliatedetail call: " . Carbon::now());
			$response = Genegraph::fetch($query);
			Log::info("End Genegraph affiliatedetail call: " . Carbon::now());
			
		} catch (RequestException $exception) {	// guzzle exceptions
	
			$response = $exception->getResponse();
			if (is_null($response))				// empty reply from server
			{
				//GeneLib::putError($errors);
				
				// for now, just return an empty list
				return $collection;
			}
			
			$code = $response->getStatusCode();
			$reason = $response->getReasonPhrase();
			$errors = json_decode($exception->getResponse()->getBody()->getContents(), true);
			
			GeneLib::putError($errors);
			
			return null;
			
		} catch (Exception $exception) {		// everything else
			
			$response = $exception->getResponse();
			$code = $response->getStatusCode();
			$reason = $response->getReasonPhrase();
			$errors = json_decode($exception->getResponse()->getBody()->getContents(), true);
			
			GeneLib::putError($errors);
			
			return null;
			
		};

		//$node = new Nodal((array) $response->affiliation);
		//$node->json = json_decode($node->legacy_json, false);
		//$node->score_data = $node->json->scoreJson ?? $node->json;

		// add each gene to the collection
		foreach($response->affiliation->gene_validity_assertions->curation_list as $record)
			$collection->push(new Nodal((array) $record));
	
		return (object) ['count' => $response->affiliation->gene_validity_assertions->count, 'collection' => $collection, 'label' => $response->affiliation->label];
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

			/*$query = '{
				disease('
				. 'iri: "' . $condition
				. '") {
					label
					iri
					curation_activities
					genetic_conditions {
						gene {
							hgnc_id
							label
						}
						gene_validity_assertions {
							mode_of_inheritance
							report_date
							classification
							curie
						}
						actionability_curations {
							report_date
							source
						}
						gene_dosage_assertions {
							report_date
							score
							curie
						}
					}
				}
			}';*/

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
						score
						curie
						}
				  	}
				}
			}';
		
		try {
		
			Log::info("Begin Genegraph conditiondetail call: " . Carbon::now());
			$response = Genegraph::fetch($query);
			Log::info("End Genegraph conditiondetail call: " . Carbon::now());
			
		} catch (RequestException $exception) {	// guzzle exceptions
    
			$response = $exception->getResponse();
			if (is_null($response))				// empty reply from server
			{
				//GeneLib::putError($errors);
				
				// for now, just return an empty list
				return $collection;
			}
			
			$code = $response->getStatusCode();
			$reason = $response->getReasonPhrase();
			$errors = json_decode($exception->getResponse()->getBody()->getContents(), true);
			
			GeneLib::putError($errors);
			
			return null;
			
		} catch (Exception $exception) {		// everything else
			
			$response = $exception->getResponse();
			$code = $response->getStatusCode();
			$reason = $response->getReasonPhrase();
			$errors = json_decode($exception->getResponse()->getBody()->getContents(), true);
			
			GeneLib::putError($errors);
			
			return null;
			
		};
		
		$node = new Nodal((array) $response->disease);

		return $node;	
			
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
						iri
						curie
						label
						description
						last_curated_date
						curation_activities
					}
				}
			}';
			  
		try {
		
			Log::info("Begin Genegraph conditionlist call: " . Carbon::now());
			$response = Genegraph::fetch($query);
			Log::info("End Genegraph conditionlist call: " . Carbon::now());
			
		} catch (RequestException $exception) {	// guzzle exceptions
    
			$response = $exception->getResponse();
			if (is_null($response))				// empty reply from server
			{
				//GeneLib::putError($errors);
				
				// for now, just return an empty list
				return $collection;
			}
			
			$code = $response->getStatusCode();
			$reason = $response->getReasonPhrase();
			$errors = json_decode($exception->getResponse()->getBody()->getContents(), true);
			
			GeneLib::putError($errors);
			
			return null;
			
		} catch (Exception $exception) {		// everything else
			
			$response = $exception->getResponse();
			$code = $response->getStatusCode();
			$reason = $response->getReasonPhrase();
			$errors = json_decode($exception->getResponse()->getBody()->getContents(), true);
			
			GeneLib::putError($errors);
			
			return null;
			
		};

		// add each gene to the collection
		foreach($response->diseases->disease_list as $record)
			$collection->push(new Nodal((array) $record));
	
		return (object) ['count' => $response->diseases->count, 'collection' => $collection];
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
	
		try {
			Log::info("Begin Genegraph druglist call: " . Carbon::now());
			$response = Genegraph::fetch($query);
			Log::info("End Genegraph druglist call: " . Carbon::now());
			
		} catch (RequestException $exception) {	// guzzle exceptions
    
			$response = $exception->getResponse();
			if (is_null($response))				// empty reply from server
			{
				//GeneLib::putError($errors);
				
				// for now, just return an empty list
				return $collection;
			}
			
			$code = $response->getStatusCode();
			$reason = $response->getReasonPhrase();
			$errors = json_decode($exception->getResponse()->getBody()->getContents(), true);
			
			GeneLib::putError($errors);
			
			return null;
			
		} catch (Exception $exception) {		// everything else
			
			$response = $exception->getResponse();
			$code = $response->getStatusCode();
			$reason = $response->getReasonPhrase();
			$errors = json_decode($exception->getResponse()->getBody()->getContents(), true);
			
			GeneLib::putError($errors);
			
			return null;
			
		};
		
		// add each gene to the collection
		foreach($response->drugs->drug_list as $record)
			$collection->push(new Nodal((array) $record));
	
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
	
		try {
			Log::info("Begin Genegraph drugdetail call: " . Carbon::now());
			$response = Genegraph::fetch($query);
			Log::info("End Genegraph drugdetail call: " . Carbon::now());
			
		} catch (RequestException $exception) {	// guzzle exceptions
    
			$response = $exception->getResponse();
			if (is_null($response))				// empty reply from server
			{
				//GeneLib::putError($errors);
				
				// for now, just return an empty list
				return $collection;
			}
			
			$code = $response->getStatusCode();
			$reason = $response->getReasonPhrase();
			$errors = json_decode($exception->getResponse()->getBody()->getContents(), true);
			
			GeneLib::putError($errors);
			
			return null;
			
		} catch (Exception $exception) {		// everything else
			
			$response = $exception->getResponse();
			$code = $response->getStatusCode();
			$reason = $response->getReasonPhrase();
			$errors = json_decode($exception->getResponse()->getBody()->getContents(), true);
			
			GeneLib::putError($errors);
			
			return null;
			
		};
		
		$node = new Nodal((array) $response->drug);
	
		return $node;
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
