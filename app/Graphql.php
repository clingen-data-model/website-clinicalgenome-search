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
							last_curated_date
							curation_activities
						}
					}
				}';
		}
//dd($query);
		try {
			Log::info("Begin genelist" . Carbon::now());
			$response = Genegraph::fetch($query);
			Log::info("End genelist" . Carbon::now());
			
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
						triplosensitivity_assertion { score }
						haploinsufficiency_assertion { score }
					}
					genetic_conditions {
						disease {
						  label
						  iri
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
						  assertion_type
						  score
						  curie
						}
					}
				}
			}';

		try {
		
			$response = Genegraph::fetch($query);
			
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

			$node->dosage_curation_map = $dosage_curation_map;
		}
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
		
			$response = Genegraph::fetch($query);
			
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
		
			$response = Genegraph::fetch($query);
			
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
				gene_list(' 
				. self::optionList($page, $pagesize, $sort, $direction, $search, "GENE_VALIDITY")
				. ') {
					label
					last_curated_date
					genetic_conditions {
						mode_of_inheritance
						disease {
							label
						}
						gene_validity_curation {
							iri
							label
							report_date
						}
					}
				}
			}';
			  
	
		try {
		
			$response = Genegraph::fetch($query);
			
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
		foreach($response->gene_list as $record)
			$collection->push(new Nodal((array) $record));
		
		return $collection;
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

			$query = '{
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
			}';
		
		try {
		
			$response = Genegraph::fetch($query);
			
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
//dd($node);
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
						last_curated_date
						curation_activities
					}
				}
			}';

		try {
		
			$response = Genegraph::fetch($query);
			
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
		foreach($response->gene_list as $record)
			$collection->push(new Nodal((array) $record));
	
		return $collection;
	}
	
	
	/**
     * Get listing of all drugs
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function drugList($args, $page = 0, $pagesize = 20)
    {
		// break out the args
		foreach ($args as $key => $value)
			$$key = $value;
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
