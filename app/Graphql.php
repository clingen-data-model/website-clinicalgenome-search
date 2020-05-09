<?php

namespace App;

use Alexaandrov\GraphQL\Facades\Client as Genegraph;
use Illuminate\Support\Facades\Log;

use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;

use App\GeneLib;

use Exception;

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
					gene_list(' 
					. self::optionList($page, $pagesize, 'ALL')
					. ') {
						label
						hgnc_id
						iri
						curation_activities
						dosage_curation {
							triplosensitivity_assertion { score }
							haploinsufficiency_assertion { score }
						}
					}
				}';
					
				
		}
		else
		{
			$query = '{
					gene_list('
					. self::optionList($page, $pagesize, $curated)
					. ') {
						label
						alternative_label
						hgnc_id
						last_curated_date
						curation_activities
					}
				}';
		}

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
				. 'hgnc_id: ' . $gene
				. ') {
					label
					hgnc_id
					iri
					curation_activities
					dosage_curation {
						triplosensitivity_assertion { score }
						haploinsufficiency_assertion { score }
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
		
		$node = new Nodal((array) $record);
		dd($node);
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
				gene_list(' 
				. self::optionList($page, $pagesize, "GENE_DOSAGE")
				. ') {
					label
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
				. self::optionList($page, $pagesize, "GENE_VALIDITY")
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
    static function optionList($page = 0, $pagesize = null, $curated = false)
    {
		$options = [];
		
		if (!is_null($pagesize))
			$options[] = 'limit: ' . $pagesize;
			
		if (!empty($page))
			$options[] = 'offset: ' . $page;
		
		if ($curated !== false)
			$options[] = 'curation_type: ' . $curated;
			
		return implode(', ', $options);
	}
}
