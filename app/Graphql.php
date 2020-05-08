<?php

namespace App;

use Alexaandrov\GraphQL\Facades\Client as Genegraph;
use Illuminate\Support\Facades\Log;

use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;

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
    static function geneList($args, $page = 0, $pagesize = 20)
    {
		// break out the args
		foreach ($args as $key => $value)
			$$key = $value;
			
		// initialize the collection
		$collection = collect();
						
		$query = '{
					gene_list(limit: ' . $pagesize . ', curation_type: ALL) {
						label
						alternative_label
						hgnc_id
						last_curated_date
						curation_activities
					}
				}';
      
		try {
		
			$response = Genegraph::fetch($query);
			
		} catch (RequestException $exception) {	// guzzle exceptions
    
			$response = $exception->getResponse();
			$code = $response->getStatusCode();
			$reason = $response->getReasonPhrase();
			$errors = json_decode($exception->getResponse()->getBody()->getContents(), true);
			
			Nodal::putError($errors);
			
			return null;
			
		} catch (Exception $exception) {		// everything else
			
			$response = $exception->getResponse();
			$code = $response->getStatusCode();
			$reason = $response->getReasonPhrase();
			$errors = json_decode($exception->getResponse()->getBody()->getContents(), true);
			
			Nodal::putError($errors);
			
			return null;
			
		};
		
		// add each gene to the collection
		foreach($response->gene_list as $record)
			$collection->push(new Nodal((array) $record));
				
		return $collection;
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
			
			Nodal::putError($errors);
			
			return null;
			
		} catch (Exception $exception) {		// everything else
			
			$response = $exception->getResponse();
			$code = $response->getStatusCode();
			$reason = $response->getReasonPhrase();
			$errors = json_decode($exception->getResponse()->getBody()->getContents(), true);
			
			Nodal::putError($errors);
			
			return null;
			
		};
				
		$node = new Nodal((array) $response->gene);
		
		return $node;
	}
}
