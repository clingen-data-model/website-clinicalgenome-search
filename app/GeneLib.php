<?php

namespace App;

//use App\Traits\Display;

use App\Nodal;

// the various database access models
use App\Neo4j;
use App\Graphql;


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
class GeneLib
{
    //use Display;
    
    /**
     * This class is designed to be used statically.  
     */
	 
    
    /**
     * Get a list of actionability curations from Genegraph
     * 
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function actionabilityList($args)
    {
		// check args
		if (is_null($args) || !is_array($args))
			return collect([]);
			
		// Gene data is currently in neo4j
		$response = Graphql::actionabilityList($args);
			
		return $response;
	}
    
    
    /**
     * Get a list of all the curated genes
     * 
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function geneList($args)
    {
		if (is_null($args) || !is_array($args))
			return collect([]);
			
		// Gene data is currently in neo4j
		$response = Neo4j::geneList($args);

		// TODO:  error return?
		if ($response === null)
			return null;
		
		// morph the graphware structure to a collection
		foreach($response->getRecords() as $record)
		{
			$node = new Nodal(array_combine($record->keys(), $record->values()));
			
			// set some shortcuts for the views
			if (!empty($node->assertions_collection))
			{
				foreach($node->assertions_collection as $assertion)
				{
					if ($assertion->hasLabel('ActionabilityAssertion'))
						$node->hasActionability = true;
					if ($assertion->hasLabel('GeneDiseaseAssertion'))
						$node->hasValidity = true;
					if ($assertion->hasLabel('GeneDosageAssertion'))
						$node->hasDosage = true;
				}
			}
					
			$records[] = $node;
		}
		
		return collect($records);
	}
	
	
	/**
     * Get details of a particular gene
     * 
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function geneDetail($args)
    {
		if (is_null($args) || !is_array($args))
			return collect([]);
			
		// Most of the gene and curation data is currently in neo4j...
		$response = Neo4j::geneDetail($args);
		
		//...but actionability is now in genegraph
		//$actionability = Genegraph::actionabilityList($args);
		
		// null means no records found
		if ($response === null)
			return null;
		
		// morph the graphware structure to a collection
		$record = new Nodal($response);
		
		return $record;
	}
	
	
	/**
     * Get a list of all the affiliates and associated curation counts
     * 
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function affiliateList($args)
    {
		if (is_null($args) || !is_array($args))
			return collect([]);
			
		// The affiliate and curation data is currently in neo4j
		$response = Neo4j::affiliateList($args);
		
		// null means no records found
		if ($response === null)
			return null;
		
		// morph the graphware structure to a collection
		foreach($response->getRecords() as $record)
			$records[] = new Nodal(array_combine($record->keys(), $record->values()));
		
		return collect($records);
	}
	
	
	/**
     * Get details of a particular affiliate and associated curations
     * 
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function affiliateDetail($args)
    {
		if (is_null($args) || !is_array($args))
			return null;
			
		// The affiliate and curation data is currently in neo4j
		$response = Neo4j::affiliateDetail($args);

		// null means no records found
		if ($response === null)
			return null;
		
		// morph the graphware structure to a collection
		$record = new Nodal($response);
		
		return $record;
	}
	
	
	/**
     * Get a list of all gene validity assertions
     * 
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function validityList($args)
    {
		if (is_null($args) || !is_array($args))
			return collect([]);
			
		// Gene data is currently in neo4j
		$response = Neo4j::validityList($args);

		// TODO:  error return?
		if ($response === null)
			return null;
		
		// morph the graphware structure to a collection
		/*foreach($response->getRecords() as $record)
		{
			$node = new Nodal(array_combine($record->keys(), $record->values()));
			
			// set some shortcuts for the views
			if (!empty($node->assertions_collection))
			{
				foreach($node->assertions_collection as $assertion)
				{
					if ($assertion->hasLabel('ActionabilityAssertion'))
						$node->hasActionability = true;
					if ($assertion->hasLabel('GeneDiseaseAssertion'))
						$node->hasValidity = true;
					if ($assertion->hasLabel('GeneDosageAssertion'))
						$node->hasDosage = true;
				}
			}
					
			$records[] = $node;
		}*/
		
		dd($records);
		return collect($records);
	}
	
	
	/**
     * Get details of a gene validity assertion
     * 
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function validityDetail($args)
    {
		if (is_null($args) || !is_array($args))
			return collect([]);
			
		// The gene validity data is currently in neo4j...
		$response = Neo4j::validityDetail($args);
		
		// null means no records found
		if ($response === null)
			return null;
		
		// morph the graphware structure to a collection
		//TODO, make this into a better structure.
		$record = new Nodal($response);
		
		return $record;
	}
}
