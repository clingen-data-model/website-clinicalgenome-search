<?php

namespace App;

use Jenssegers\Model\Model;

use App\Nodal;
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
class GeneLib extends Model
{	 
	/**
     * This class is designed to be used statically.
     */
     
     /**
     * The attributes that should be validity checked.
     *
     * @var array
     */
    public static $rules = [];

    /**
     * The attributes that are mass assignable.  Remember to fill it
     * in when all the attributes are known.
     *
     * @var array
     */
	//protected $fillable = ['name', 'address1', 'address2', 'city', 'state',
	//					   'zip', 'contact', 'phone', 'status' ];

	/**
     * Non-persistent storage model attributes.
     *
     * @var array
     */
    protected $appends = [];
     
	/*
     * Dosage Assertion strings for display methods
     *
     * */
     protected static $dosage_assertion_strings = [
          'ASSOCIATED_WITH_AUTOSOMAL_RECESSIVE_PHENOTYPE' => 'Associated with Autosomal Recessive Phenotype',
          'MINIMAL_EVIDENCE' => ' Minimal Evidence',
          'MODERATE_EVIDENCE' => 'Moderate Evidence',
          'NO_EVIDENCE' => 'No Evidence',
          'SUFFICIENT_EVIDENCE' =>'Sufficient Evidence',
          'DOSAGE_SENSITIVITY_UNLIKELY' => 'Dosage Sensitivity Unlikely'
     ];
     
     protected static $validity_assertion_strings = [
          'AUTOSOMAL_RECESSIVE' => 'Autosomal Recessive',
          'AUTOSOMAL_DOMINANT' => 'Autosomal Dominant',
          'X_LINKED' => 'Autosomal Recessive',
          'SEMIDOMINANT' => 'Semidominant',
          'MITOCHONDRIAL' => 'Mitochondrial',
          'UNDETERMINED' => 'Undetermined',
          'DEFINITIVE' => 'Definitive',
          'LIMITED' => 'Limited',
          'MODERATE' => 'Moderate',
          'NO_KNOWN_DISEASE_RELATIONSHIP' => 'No Known Disease Relationship',
          'STRONG' => 'Strong',
          'DISPUTED' => 'Disputed',
          'REFUTED' => 'Refuted'
     ];
	
	
	/*----------------------Public Methods----------------------------*/
	

    /**
     * Get a list of actionability curations
     * 
     * (Genegraph)
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function actionabilityList($args)
    {
		// check args
		if (is_null($args) || !is_array($args))
			return collect([]);

		// Use graphql for data content
		$response = Graphql::actionabilityList($args);

		return $response;
	}


    /**
     * Get a list of all the curated genes
     * 
     * (Neo4j, Genegraph)
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function geneList($args)
    {
		if (is_null($args) || !is_array($args))
			return collect([]);

		// Gene data is currently in neo4j
		//$response = Neo4j::geneList($args);
		
		// Gene listing using Graphql
		$response = Graphql::geneList($args);

		return $response;
	}


	/**
     * Get details of a particular gene
     * 
     * (Neo4j)
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function geneDetail($args)
    {
		if (is_null($args) || !is_array($args))
			return collect([]);

		// Most of the gene and curation data is currently in neo4j...
          //$response = Neo4j::geneDetail($args);
          
		//...but actionability is now in genegraph
		$response = Graphql::geneDetail($args);

		return $response;
	}


	/**
     * Get a list of all the affiliates and associated curation counts
     * 
     * (Neo4j)
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function affiliateList($args)
    {
		if (is_null($args) || !is_array($args))
			return collect([]);

		// The affiliate and curation data is currently in neo4j
		$response = Neo4j::affiliateList($args);

		return $response;
	}


	/**
     * Get details of a particular affiliate and associated curations
     * 
     * (Neo4j)
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function affiliateDetail($args)
    {
		if (is_null($args) || !is_array($args))
			return null;

		// The affiliate and curation data is currently in neo4j
		$response = Neo4j::affiliateDetail($args);

		return $response;
	}


	/**
     * Get a list of all gene validity assertions
     * 
     * (Neo4j)
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function validityList($args)
    {
		if (is_null($args) || !is_array($args))
			return collect([]);

		// Gene data is currently in neo4j
		// $response = Neo4j::validityList($args);
	
		// Gene data using Graphql
		$response = Graphql::validityList($args);
//dd($response);
		return $response;
	}


	/**
     * Get details of a gene validity assertion
     * 
     * (Neo4j)
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function validityDetail($args)
    {
		if (is_null($args) || !is_array($args))
			return collect([]);

		// The gene validity data is currently in neo4j...
          //$response = Neo4j::validityDetail($args);
          
          // The gene validity data is currently in neo4j...
		$response = Graphql::validityDetail($args);
		
		return $response;
	}


	/**
     * Get a list of all the genes with dosage sensitivitiy
     * 
     * (Neo4j, GeneGraph)
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function dosageList($args)
    {
		if (is_null($args) || !is_array($args))
			return collect([]);

		// Gene data is currently in neo4j
		//$response = Neo4j::dosageList($args);
		
		// Gene data is currently in graphgq
		$response = Graphql::dosageList($args);
		
		return $response;
	}


	/**
     * Get details of a particular gene with dosage sensitivitiy
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function dosageDetail($args)
    {
          if (is_null($args) || !is_array($args))
               return collect([]);

          // Most of the gene and curation data is currently in neo4j...
          //$response = Neo4j::geneDetail($args);

          // Much of the data is in graphql....
          $response = Graphql::dosageDetail($args);

          // ... but a lot is still in Jira
          $supplement = Jira::dosageDetail($args);

          // combine the two
          foreach(['summary', 'genetype', 'GRCh37_position', 'GRCh38_position',
          'triplo_score', 'haplo_score', 'cytoband' ] as $field)
          {
               $response->$field = $supplement->$field;
          }


          return $response;
	}


	/**
     * Get a list of all the drugs
     * 
     * (Neo4j)
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function drugList($args)
    {
		if (is_null($args) || !is_array($args))
			return collect([]);

		// Drug data is currently in neo4j
		$response = Neo4j::drugList($args);

		return $response;
	}


	/**
     * Get details of a particular drug
     * 
     * (Neo4j)
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function drugDetail($args)
    {
		if (is_null($args) || !is_array($args))
			return collect([]);

		// Drug details are currently in neo4j
		$response = Neo4j::drugDetail($args);

		return $response;
	}


	/**
     * Get a list of all the conditions
     * 
     * (Neo4j)
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function conditionList($args)
    {
		if (is_null($args) || !is_array($args))
			return collect([]);

		// Gene data is currently in neo4j
          //$response = Neo4j::conditionList($args);
          
          // Gene data is currently in neo4j
		$response = Graphql::conditionList($args);

		return $response;
	}


	/**
     * Get details of a particular condition
     * 
     * (Neo4j)
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function conditionDetail($args)
    {
		if (is_null($args) || !is_array($args))
			return collect([]);

		// Condition data is all in Neo4j
          //$response = Neo4j::conditionDetail($args);
          
          $response = Graphql::conditionDetail($args);

		return $response;
	}
	
	
	/**
     * Return a displayable dosage assertion description
     * 
     * @return string
     */
     public static function haploAssertionString($str)
     {
          if (empty($str))
               return '';

		 return self::$dosage_assertion_strings[$str] ?? 'ERROR';
      }
      

      /**
     * Return a displayable dosage assertion description
     * 
     * @return string
     */
     public static function triploAssertionString($str)
     {
          if (empty($str))
               return '';

		 return self::$dosage_assertion_strings[$str] ?? 'ERROR';
      }


      /**
     * Return a displayable dosage assertion description
     * 
     * @return string
     */
     public static function dosageAssertionString($str)
     {
          if (empty($str))
               return '';

		 return self::$dosage_assertion_strings[$str] ?? 'ERROR';
      }
      

      /**
     * Return a displayable validity assertion description
     * 
     * @return string
     */
     public static function validityAssertionString($str)
     {
          if (empty($str))
               return '';

		 return self::$validity_assertion_strings[$str] ?? 'ERROR';
      }
      

     /**
     * Return a usable validity assertion identifier
     * 
     * @return string
     */
     public static function validityAssertionID($str)
     {
          return substr($str, strpos($str, ":assertion_") + 11)  ?? '';
	}
	 
	 
	 /*
     * Set a GraphLib error for use by controllers or views.  
     *
     * @param	string	$mondo
     * @return 	array
     */
    public static function putError($error = null)
    {
		if ($error === null)
			return session()->put('GeneLibError', false);
			
		session()->put('GeneLibError', $error);
	}
	
	
	/*
     * Get a GraphLib error structure.  TODO:  formatting
     *
     * @param	string	$mondo
     * @return 	array
     */
    public static function getError()
    {
		return session()->get('GeneLibError', false);
	}
}
