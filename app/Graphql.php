<?php

namespace App;

use Alexaandrov\GraphQL\Facades\Client as Genegraph;
use Illuminate\Support\Facades\Log;


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
			
		//try {
		
			$response = Genegraph::fetch($query);
			
		/*} catch (Exception $exception) {
			
			// TODO - more comprehensive error recovery
			die("error found");
			
		};*/
				
		dd($response->gene);
		
		return $response;
	}
}
