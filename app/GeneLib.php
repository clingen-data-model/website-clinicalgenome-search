<?php

namespace App;

use Jenssegers\Model\Model;

use App\Neo4j;
use App\Graphql;


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
class GeneLib extends Model
{	 
	/**
     * This class is designed to be used statically.  It is a non-persistant model
     * with no corresponding table in the database
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
          'ASSOCIATED_WITH_AUTOSOMAL_RECESSIVE_PHENOTYPE' => 'Gene Associated with Autosomal Recessive Phenotype',
          'MINIMAL_EVIDENCE' => 'Minimal Evidence for ####',
          'MODERATE_EVIDENCE' => 'Moderate Evidence for ####',
          'NO_EVIDENCE' => 'No Evidence for ####',
          'SUFFICIENT_EVIDENCE' =>'Sufficient Evidence for ####',
          'DOSAGE_SENSITIVITY_UNLIKELY' => 'Dosage Sensitivity Unlikely'
     ];

     protected static $curated_assertion_strings = [
          'ASSOCIATED_WITH_AUTOSOMAL_RECESSIVE_PHENOTYPE' => 'Associated with Autosomal Recessive Phenotype',
          'MINIMAL_EVIDENCE' => 'Minimal Evidence',
          'MODERATE_EVIDENCE' => 'Moderate Evidence',
          'NO_EVIDENCE' => 'No Evidence',
          'SUFFICIENT_EVIDENCE' =>'Sufficient Evidence',
          'DOSAGE_SENSITIVITY_UNLIKELY' => 'Dosage Sensitivity Unlikely'
     ];

     protected static $validity_classification_strings = [
          'gene associated with autosomal recessive phenotype' => 'Autosomal Recessive',
          'no evidence' => 'No Reported Evidence',
          'sufficient evidence' => 'Sufficient',
          'limited evidence' => 'Limited',
          'disputing' => 'Disputing',
          'definitive evidence' => 'Definitive',
          'minimal evidence' => 'Minimal',
          'met' => 'Met',
          'strong evidence' => 'Strong',
          'refuting evidence' => 'Refuted',
          'moderate evidence' => 'Moderate'
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

     protected static $validity_moi_strings = [
          'Autosomal recessive inheritance' => 'Autosomal Recessive',
          'Autosomal dominant inheritance' => 'Autosomal Dominant',
          'X-linked inheritance' => 'X-Linked',
          'Mode of inheritance' => 'Other',
          'X-linked recessive inheritance' => 'X-Linked Recessive',
          'Semidominant mode of inheritance' => 'Semidomimant'
     ];

     protected static $validity_criteria_strings = [
          'ClinGen Dosage Sensitivity Evaluation Guideline' => 'Eval',
          'ClinGen Gene Validity Evaluation Guideline' => 'Eval',
          'ACMG Variant Pathogenicity Interpretation Guidelines (2015, v1)' => 'ACMG',
          'ACMG PVS1 criterion' => 'ACMG',
          'ACMG PM2 criterion' => 'ACMG',
          'variant pathogenicity criterion scoring rule set (2015 ACMG Guidelines, v1)' => 'ACMG',
          'ClinGen Gene Validity Evaluation Criteria SOP6' => 'SOP6',
          'ClinGen Gene Validity Evaluation Criteria SOP5' => 'SOP5',
          'ClinGen Gene Validity Evaluation Criteria SOP4' => 'SOP4',
          'ClinGen Gene Validity Evaluation Criteria SOP7' => 'SOP7',
          'ClinGen Gene Validity Evaluation Criteria SOPX' => 'SOPX'
     ];

	protected static $dosage_score_assertion_strings = [
          '0' => 'No Evidence for ####',
          '1' => 'Minimal Evidence for ####',
          '2' => 'Moderate Evidence for ####',
          '3' =>'Sufficient Evidence for ####',
          '30' => 'Gene Associated with Autosomal Recessive Phenotype',
          '40' => 'Dosage Sensitivity Unlikely'
     ];

     protected static $curated_score_assertion_strings = [
          '0' => 'No Evidence',
          '1' => 'Minimal Evidence',
          '2' => 'Moderate Evidence',
          '3' =>'Sufficient Evidence',
          '30' => 'Associated with Autosomal Recessive Phenotype',
          '40' => 'Dosage Sensitivity Unlikely'
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
     * Get a list of all the curated genes
     * 
     * (Neo4j, Genegraph)
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
     static function geneLook($args)
     {
		if (is_null($args) || !is_array($args))
			return collect([]);

		// Gene data is currently in neo4j
		//$response = Neo4j::geneList($args);
		
		// Gene listing using Graphql
		$response = Graphql::geneLook($args);

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
          //$response = Neo4j::affiliateList($args);
          
          // The affiliate and curation data is currently in graphql
		$response = Graphql::affiliateList($args);

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
          //$response = Neo4j::affiliateDetail($args);
          
          // The affiliate and curation data is currently in neo4j
		$response = Graphql::affiliateDetail($args);

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
          
          // The gene validity data is currently in graphql...
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
          if ($response === null)
               return null;

          // ... but a lot is still in Jira
          $supplement = Jira::dosageDetail($args);

          if ($supplement !== null)
          {
               // combine the two
               foreach(['summary', 'genetype', 'GRCh37_position', 'GRCh38_position',
               'triplo_score', 'haplo_score', 'cytoband' ] as $field)
               {
                    $response->$field = $supplement->$field;
               }
          }

          return $response;
     }
     

     /**
     * Get list of recurrent CNVs
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function cnvList($args)
    {
         if (is_null($args) || !is_array($args))
              return collect([]);

         // regions are still only found in Jira
         $response = Jira::cnvList($args);

         return $response;
    }


    /**
     * Get list of ACMG59 Genes
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function acmg59List($args)
    {
         if (is_null($args) || !is_array($args))
              return collect([]);

          // build data structure from the original ini in the database
          $records = Acmg59::all();

         // now pull the updates from jira
         $local = Jira::acmg59List($args);
         // combine
         $c = $local->collection;
         $records = $records->map( function ($record) use ($c)
         {
              //dd($c);
              $node = $c->where('label', $record->gene)->first();

              if ($node !== null)
               {
                    $record->geneomim = $node->omim;
                    $record->gain = $node->haplo_score;
                    $record->loss = $node->triplo_score;
                    $record->key = $node->key;
               }

               return $record;
         });


         $local->collection = $records;

         return $local;
    }


    /**
     * Get list dosage regions
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function regionList($args)
    {
         if (is_null($args) || !is_array($args))
              return collect([]);

         // pull all the regions from jira
         $local = Jira::regionList($args);

         // combine
         /*$c = $local->collection;
         $records = $records->map( function ($record) use ($c)
         {
              //dd($c);
              $node = $c->where('label', $record->gene)->first();

              if ($node !== null)
               {
                    $record->geneomim = $node->omim;
                    $record->gain = $node->haplo_score;
                    $record->loss = $node->triplo_score;
                    $record->key = $node->key;
               }

               return $record;
         });


         $local->collection = $records;*/

         return $local;
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
          //$response = Neo4j::drugList($args);
          
          // Drug data is now in graphql
		$response = Graphql::drugList($args);

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
          //$response = Neo4j::drugDetail($args);
          
          // Drug details are currently in neo4j
		$response =Graphql::drugDetail($args);

		return $response;
     }
     

     /**
     * Get a matched list of drugs
     * 
     * (Neo4j, Genegraph)
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
     static function drugLook($args)
     {
		if (is_null($args) || !is_array($args))
			return collect([]);

		// Gene data is currently in neo4j
		//$response = Neo4j::geneList($args);
		
		// Gene listing using Graphql
		$response = Graphql::drugLook($args);

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
     * Get a matched list of conditions
     * 
     * (Neo4j, Genegraph)
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
     static function conditionLook($args)
     {
		if (is_null($args) || !is_array($args))
			return collect([]);

		// Gene data is currently in neo4j
		//$response = Neo4j::geneList($args);
		
		// Gene listing using Graphql
		$response = Graphql::conditionLook($args);

		return $response;
     }
     

	
	
	/**
     * Return a displayable dosage assertion description
     * 
     * @return string
     */
     public static function haploAssertionString($str)
     {
          if ($str === null || $str === false)
               return '';

		 return str_replace('####', 'Haplosufficiency', self::$dosage_score_assertion_strings[$str] ?? 'ERROR');
     }
      

      /**
     * Return a displayable dosage assertion description
     * 
     * @return string
     */
     public static function triploAssertionString($str)
     {
          if ($str === null || $str === false)
               return '';

		 return str_replace('####', 'Triplosensitivity', self::$dosage_score_assertion_strings[$str] ?? 'ERROR');
     }


     /**
     * Return a displayable dosage assertion description
     * 
     * @return string
     */
     public static function dosageAssertionString($str)
     {
          if ($str === null || $str === false)
               return '';

          return self::$curated_score_assertion_strings[$str] ?? 'ERROR';
          //return self::$curated_assertion_strings[$str] ?? 'ERROR';
     }
      

     /**
     * Return a displayable moi assertion description
     * 
     * @return string
     */
     public static function validityMoiString($str)
     {
          if (empty($str))
               return '';

		 return self::$validity_moi_strings[$str] ?? 'ERROR';
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
     * Return a displayable validity classification description
     * 
     * @return string
     */
     public static function validityClassificationString($str)
     {
          if (empty($str))
               return '';

		 return self::$validity_classification_strings[$str] ?? 'ERROR';
     }


     /**
     * Return a displayable validity criteria description
     * 
     * @return string
     */
     public static function validityCriteriaString($str)
     {
          if (empty($str))
               return '';

		 return self::$validity_criteria_strings[$str] ?? 'ERROR';
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
