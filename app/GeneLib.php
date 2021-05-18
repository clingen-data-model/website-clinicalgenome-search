<?php

namespace App;

use Jenssegers\Model\Model;

use App\Neo4j;
use App\Graphql;
use App\Jira;
use App\Region;
use App\Mysql;
use App\Health;


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

    protected static $short_dosage_assertion_strings = [
         '-5' => 'Not yet evaluated',
         '-1' => 'Pseudogene',
          '0' => 'No Evidence',
          '1' => 'Little Evidence',
          '2' => 'Emerging Evidence',
          '3' => 'Sufficient Evidence',
          '30' => 'Autosomal Recessive',
          '40' => 'Dosage Sensitivity Unlikely'
     ];

     protected static $dosage_assertion_strings = [
          'ASSOCIATED_WITH_AUTOSOMAL_RECESSIVE_PHENOTYPE' => 'Gene Associated with Autosomal Recessive Phenotype',
          'MINIMAL_EVIDENCE' => 'Little Evidence for ####',
          'MODERATE_EVIDENCE' => 'Emerging Evidence for ####',
          'NO_EVIDENCE' => 'No Evidence for ####',
          'SUFFICIENT_EVIDENCE' =>'Sufficient Evidence for ####',
          'DOSAGE_SENSITIVITY_UNLIKELY' => 'Dosage Sensitivity Unlikely'
     ];

     protected static $curated_assertion_strings = [
          'ASSOCIATED_WITH_AUTOSOMAL_RECESSIVE_PHENOTYPE' => 'Associated with Autosomal Recessive Phenotype',
          'MINIMAL_EVIDENCE' => 'Little Evidence',
          'MODERATE_EVIDENCE' => 'Emerging Evidence',
          'NO_EVIDENCE' => 'No Evidence',
          'SUFFICIENT_EVIDENCE' =>'Sufficient Evidence',
          'DOSAGE_SENSITIVITY_UNLIKELY' => 'Dosage Sensitivity Unlikely'
     ];

     protected static $validity_classification_strings = [
          'gene associated with autosomal recessive phenotype' => 'Autosomal Recessive',
          //'no evidence' => 'No Reported Evidence',
          'no evidence' => 'No Known Disease Relationship',
          'no known disease relationship' => 'No Known Disease Relationship',
          'sufficient evidence' => 'Sufficient',
          'limited evidence' => 'Limited',
          'disputing' => 'Disputed',
          'disputed' => 'Disputed',
          'definitive evidence' => 'Definitive',
          'minimal evidence' => 'Minimal',
          'met' => 'Met',
          'strong evidence' => 'Strong',
          'refuting evidence' => 'Refuted',
          'moderate evidence' => 'Moderate'
     ];

     protected static $validity_sort_value = [
               'Definitive' => 20,
               'Strong' => 19,
               'Moderate' => 18,
               'Supportive' => 17,
               'Limited' => 16,
               'Animal Model Only' => 15,
               'Disputed' => 14,
               'Refuted' => 13,
               'No Known Disease Relationship' => 12
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
          'Semidominant mode of inheritance' => 'Semidomimant',
          'Undetermined mode of inheritance' => 'Undetermined',
          'Undetermined' => 'Undetermined',
          'X-linked recessive inheritance' => 'X-Linked Recessive',
          'Mitochondrial inheritance' => 'Mitochondrial inheritance'
     ];

     protected static $validity_criteria_strings = [
          'ClinGen Dosage Sensitivity Evaluation Guideline' => 'Eval',
          'ClinGen Gene-Disease Validity Evaluation Guideline' => 'Eval',
          'ClinGen Gene Validity Evaluation Guideline' => 'Eval',
          'ACMG Variant Pathogenicity Interpretation Guidelines (2015, v1)' => 'ACMG',
          'ACMG PVS1 criterion' => 'ACMG',
          'ACMG PM2 criterion' => 'ACMG',
          'variant pathogenicity criterion scoring rule set (2015 ACMG Guidelines, v1)' => 'ACMG',
          'ClinGen Gene-Disease Validity Evaluation Criteria SOP6' => 'SOP6',
          'ClinGen Gene Validity Evaluation Criteria SOP6' => 'SOP6',
          'ClinGen Gene-Disease Validity Evaluation Criteria SOP5' => 'SOP5',
          'ClinGen Gene Validity Evaluation Criteria SOP5' => 'SOP5',
          'ClinGen Gene-Disease Validity Evaluation Criteria SOP4' => 'SOP4',
          'ClinGen Gene Validity Evaluation Criteria SOP4' => 'SOP4',
          'ClinGen Gene-Disease Validity Evaluation Criteria SOP7' => 'SOP7',
          'ClinGen Gene Validity Evaluation Criteria SOP7' => 'SOP7',
          'ClinGen Gene-Disease Validity Evaluation Criteria SOP8' => 'SOP8',
          'ClinGen Gene Validity Evaluation Criteria SOP8' => 'SOP8',
          'ClinGen Gene-Disease Validity Evaluation Criteria SOPX' => 'SOPX',
          'ClinGen Gene Validity Evaluation Criteria SOPX' => 'SOPX'
     ];

	protected static $dosage_score_assertion_strings = [
          '-5' => 'Not yet evaluated',
          '-1' => 'Pseudogene',
          '0' => 'No Evidence for ####',
          '1' => 'Little Evidence for ####',
          '2' => 'Emerging Evidence for ####',
          '3' =>'Sufficient Evidence for ####',
          '30' => 'Gene Associated with Autosomal Recessive Phenotype',
          '40' => 'Dosage Sensitivity Unlikely'
     ];

     protected static $curated_score_assertion_strings = [
          '-5' => 'Not yet evaluated',
          '-1' => 'Pseudogene',
          '0' => 'No Evidence',
          '1' => 'Little Evidence',
          '2' => 'Emerging Evidence',
          '3' =>'Sufficient Evidence',
          '30' => 'Associated with Autosomal Recessive Phenotype',
          '40' => 'Dosage Sensitivity Unlikely'
     ];

     protected static $actionability_assertion_strings = [
          'Definitive Actionability' => "Definitive Actionability",
          'Strong Actionability' => "Strong Actionability",
          'Moderate Actionability' => "Moderate Actionability",
          'Limited Actionability' => "Limited Actionability",
          'Insufficient Actionability' => "Insufficient Actionability",
          'Has Insufficient Evidence for Actionability Based on Early Rule-out' => "N/A - Insufficient evidence: early rule-out",
          'N/A - Insufficient evidence: early rule-out' => "N/A - Insufficient evidence: early rule-out",
          'Has Insufficient Evidence for Actionability Based on Expert Review' => "N/A - Insufficient evidence: expert review",
          'N/A - Insufficient evidence: expert review' => "N/A - Insufficient evidence: expert review",
          'No Actionability' => "No Actionability",
          'Assertion Pending' => "Assertion Pending",
     ];

     protected static $actionability_sort_value = [
          'Definitive Actionability' => 20,
          'Strong Actionability' => 19,
          'Moderate Actionability' => 18,
          'Limited Actionability' => 17,
          'Insufficient Actionability' => 16,
          'No Actionability' => 15,
          'Assertion Pending' => 14,
          'Has Insufficient Evidence for Actionability Based on Early Rule-out' => 13,
		'N/A - Insufficient evidence: expert review' => 12,
		'N/A - Insufficient evidence: early rule-out' => 11
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
          /*
          SELECT * FROM `genes` WHERE name like '%AR%' order by (name = 'AR') desc, length(name)
          */

          // Gene listing using Graphql
          if (!empty($args['forcegg']))
               return Graphql::geneList($args);

          $health = Health::where('service', 'GeneSearch')->first();

          if (empty($health->genegraph) || empty($args['curated']))
               $response = Mysql::geneList($args);
          else
               $response = Graphql::geneList($args);

          /*if ($args['curated'] || !empty($args['forcegg']))
               $response = Graphql::geneList($args);
          else
               $response = Mysql::geneList($args);*/

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

          if (!isset($args['gene']) || strpos($args['gene'], 'ISCA-') === 0)		// dosage pseudogene
		{
               $gene = self::geneNotCurated($args);
               $issue = Iscamap::issue($args['gene'])->first();
               if ($issue !== null)
               {
                    $gene->label = $issue->symbol;
                    $gene->hgnc_id = $issue->symbol;
               }

               return $gene;

          }

		//...but actionability is now in genegraph
          $response = Graphql::geneDetail($args);

          // This is a real ugly characteristic of genegraph that requires a really ugly workaround
          if ($response === null && self::getError() == "There was an error with the GraphQL response, no data key was found.")
          {
               // gene not found, create a dummy one and see if that worls
               $response = self::geneNotCurated($args);

               // add additional information from local db
               $localgene = Gene::where('hgnc_id', $args['gene'])->first();

               if ($localgene !== null)
               {
                    $response->label = $localgene->name;
                    $response->alternative_label = $localgene->description;
                    $response->hgnc_id = $localgene->hgnc_id;
                    $response->chromosome_band = $localgene->location;
                    $response->alias_symbols = $localgene->display_aliases;
                    $response->prev_symbols = $localgene->display_previous;
                    $response->hi = isset($localgene->hi) ? round($localgene->hi, 2) : null;
                    $response->pli = isset($localgene->pli) ? round($localgene->pli, 2) : null;
                    $response->plof = isset($localgene->plof) ? round($localgene->plof, 2) : null;
                    $response->locus_type = $localgene->locus_type;
                    $response->locus_group = $localgene->locus_group;
                    $response->ensembl_id = $localgene->ensembl_gene_id;
                    $response->entrez_id = $localgene->entrez_id;
                    $response->omim_id = $localgene->omim_id;
                    $response->ucsc_id = $localgene->ucsc_id;
                    $response->uniprot_id = $localgene->uniprot_id;
                    $response->function = $localgene->function;
               }
          }

		return $response;
     }


     /**
     * special case where we want to return a structure that triggers
     * the not curated message
     *
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function geneNotCurated($args)
    {
         if (is_null($args) || !is_array($args))
              return collect([]);

          $response = new Nodal([
                              "label" => $args['gene'],
                              "alternative_label" => '',
                              "hgnc_id" => null,
                              "chromosome_band" => "",
                              "curation_activities" => [],
                              "last_curated_date" => null,
                              "dosage_curation" => null,
                              "genetic_conditions" => [],
                              "alias_symbols" => "",
                              "prev_symbols" => "No previous names found",
                              "hi" => null,
                              "pli" => null,
                              "plof" => null,
                              "locus_type" => "pseudogene",
                              "locus_group" => "pseudogene",
                              "ensembl_id" => "",
                              "entrez_id" => null,
                              "omim_id" => null,
                              "ucsc_id" => null,
                              "uniprot_id" => null,
                              "function" => "",
                              "naction" => 0,
                              "nvalid" => 0,
                              "ndosage" => 0,
                              "pharma" => [],
                              "dosage_curation_map" => []
          ]);

         return $response;
    }


     /**
     * Get details of a particular gene
     *
     * (Neo4j)
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    /*static function geneActivityDetail($args)
    {
         if (is_null($args) || !is_array($args))
              return collect([]);

         // Most of the gene and curation data is currently in neo4j...
         //$response = Neo4j::geneDetail($args);

         //...but actionability is now in genegraph
         $response = Graphql::geneActivityDetail($args);

         return $response;
    }*/


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
     * Get a list of all the curated genes
     *
     * (Neo4j, Genegraph)
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function geneFind($args)
    {
         if (is_null($args) || !is_array($args))
              return collect([]);

         // Gene data is currently in neo4j
         //$response = Neo4j::geneList($args);

         // Gene listing using Graphql
         $response = Graphql::geneFind($args);

         return $response;
    }


     /**
     * Get a list of all genes and regions within the search params
     *
     * (Neo4j, GeneGraph)
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function geneRegionList($args)
    {
         if (is_null($args) || !is_array($args))
              return collect([]);

         // Gene data is locally populated from batch exports
         $response = Gene::searchList($args);

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
     * Get a list of all genes and regions within the search params
     *
     * (Neo4j, GeneGraph)
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function regionSearchList($args)
    {
         if (is_null($args) || !is_array($args))
              return collect([]);

         // Gene data is locally populated from batch exports
         $response = Region::searchList($args);

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
          if (!isset($args['gene']) || strpos($args['gene'], 'ISCA-') === 0)		// dosage psuedogene
		{
               $response = self::geneNotCurated($args);
               $expand = true;
          }
          else
          {
               // Much of the data is in graphql....
               $response = Graphql::dosageDetail($args);
               $expand = false;

               // This is a real ugly characteristic of genegraph that requires a really ugly workaround
               if ($response === null && self::getError() == "There was an error with the GraphQL response, no data key was found.")
               {
                    // gene not found, create a dummy one and see if that worls
                    $response = self::geneNotCurated($args);

                    // add additional information from local db
                    $localgene = Gene::where('hgnc_id', $args['gene'])->first();

                    if ($localgene !== null)
                    {
                         $response->label = $localgene->name;
                         $response->alternative_label = $localgene->description;
                         $response->hgnc_id = $localgene->hgnc_id;
                         $response->chromosome_band = $localgene->location;
                         $response->alias_symbols = $localgene->display_aliases;
                         $response->prev_symbols = $localgene->display_previous;
                         $response->hi = isset($localgene->hi) ? round($localgene->hi, 2) : null;
                         $response->pli = isset($localgene->pli) ? round($localgene->pli, 2) : null;
                         $response->plof = isset($localgene->plof) ? round($localgene->plof, 2) : null;
                         $response->locus_type = $localgene->locus_type;
                         $response->locus_group = $localgene->locus_group;
                         $response->ensembl_id = $localgene->ensembl_gene_id;
                         $response->entrez_id = $localgene->entrez_id;
                         $response->omim_id = $localgene->omim_id;
                         $response->ucsc_id = $localgene->ucsc_id;
                         $response->uniprot_id = $localgene->uniprot_id;
                         $response->function = $localgene->function;
                    }

               }
          }

          if ($response === null)
               return null;

          // ... but a lot is still in Jira
          $supplement = Jira::dosageDetail($args, $expand);

          if ($supplement !== null)
          {
               // combine the two
               foreach(['summary', 'genetype',
               'triplo_score', 'haplo_score', 'cytoband', 'key',
               'loss_comments', 'loss_pheno_omim', 'loss_pmids',
               'loss_pheno_ontology', 'loss_pheno_ontology_id', 'loss_pheno_name',
               'gain_comments', 'gain_pheno_omim', 'gain_pmids', 'gain_pheno_name',
               'resolution', 'issue_type', 'gain_pheno_ontology', 'gain_pheno_ontology_id',
               'GRCh37_seqid', 'GRCh38_seqid', 'issue_status', 'jira_status' ] as $field)
               {
                    // Prefer the NIH wording over the local Jira one.
                    if ($field == 'genetype' && !empty($response->locus_group))
                    {
                         $response->$field = $response->locus_group;
                         continue;
                    }
                    $response->$field = $supplement->$field;
               }

               // special case for psudogenes
               if ($supplement->genetype == "pseudo")
               {
                    //dd($supplement);
                    $response->label = $supplement->label;
                    $response->chromosome_band = $supplement->cytoband;
                    $response->grch37 = $supplement->grch37;
                    $response->grch38 = $supplement->grch38;
               }
          }

          if ($response->locus_type == 'pseudogene')
               $response->issue_status = "Not Reviewable";

          // need the titles from Omim
          $omim = null; //Omim::omimid($)->first();

          if ($omim !== null)
          {
               $response->omimtitle = $omim->titles;
          }

          return $response;
     }


     /**
     * Get details of a particular gene with dosage sensitivitiy
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function dosageRegionDetail($args)
    {
         if (is_null($args) || !is_array($args))
              return collect([]);

          $response = new Nodal([]);

         // ... but a lot is still in Jira
         $supplement = Jira::dosageRegionDetail($args);

         if ($supplement !== null)
         {
              // combine the two
              foreach(['summary', 'genetype', 'label', 'date',
              'triplo_score', 'haplo_score', 'cytoband', 'key',
              'loss_comments', 'loss_pheno_omim', 'loss_pmids',
              'loss_pheno_name', 'loss_pheno_ontology', 'loss_pheno_ontology_id',
              'gain_comments', 'gain_pheno_omim', 'gain_pmids',
              'gain_pheno_name', 'gain_pheno_ontology', 'gain_pheno_ontology_id',
              'grch37', 'grch38', 'chromosome_band',
              'resolution', 'issue_type', 'description',
              'GRCh37_seqid', 'GRCh38_seqid', 'issue_status', 'jira_status' ] as $field)
              {
                   if ($field == 'genetype' && !empty($response->locus_group))
                   {
                        $response->$field = $response->locus_group;
                        continue;
                   }
                   $response->$field = $supplement->$field;
              }
         }

         // need the titles from Omim
         $omim = null; //Omim::omimid($)->first();

         if ($omim !== null)
         {
              $response->omimtitle = $omim->titles;
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
          if (!empty($args['forcegg']))
               $response = Graphql::drugList($args);
          else      // Drug data is now local
               $response = Mysql::drugList($args);

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
		$response =Mysql::drugDetail($args);

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

		// Suggester listing using Graphql
         $response = Graphql::drugLook($args);

          // Suggester listing using Mysql
		//$response = Mysql::drugLook($args);

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

          // Gene data is currently in genegraph
          if (!empty($args['forcegg']))
               $response = Graphql::conditionList($args);
          else      // Gene data is currently local
		     $response = Mysql::conditionList($args);

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
    public static function shortAssertionString($str)
    {
         if ($str === null || $str === false || $str === 'unknown')
              return 'Not Yet Evaluated';

          return $str . ' (' . self::$short_dosage_assertion_strings[$str] . ')';
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

		 return str_replace('####', 'Haploinsufficiency', self::$dosage_score_assertion_strings[$str] ?? '');
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

		 return str_replace('####', 'Triplosensitivity', self::$dosage_score_assertion_strings[$str] ?? '');
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

          return self::$curated_score_assertion_strings[$str] ?? '';
          //return self::$curated_assertion_strings[$str] ?? '';
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

		 return self::$validity_moi_strings[$str] ?? '';
     }


     /**
     * Return a displayable validity assertion description
     *
     * @return string
     */
    public static function actionabilityAssertionString($str)
    {
         if (empty($str))
              return '';

          return self::$actionability_assertion_strings[$str] ?? '';
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

		 return self::$validity_assertion_strings[$str] ?? '';
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

		 return self::$validity_classification_strings[$str] ?? '';
     }


     /**
     * Return a displayable validity classification description
     *
     * @return string
     */
    public static function validitySortOrder($str)
    {
         if (empty($str))
              return 0;

          return self::$validity_sort_value[$str] ?? 0;
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

		 return self::$validity_criteria_strings[$str] ?? 'ERRR';
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


     /**
     * Return a displayable validity criteria description
     *
     * @return string
     */
    public static function conditionLastSynonym($record)
    {
         if (empty($record) || empty($record->synonyms))
              return null;

          return is_array($record->synonyms) ? $record->synonyms[0] : null;
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
