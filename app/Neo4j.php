<?php

namespace App;

use Ahsan\Neo4j\Facade\Cypher;


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
class Neo4j
{
	protected static $prefix = "https://search.clinicalgenome.org/kb/agents/";
	 
	 
	/**
     * This class is designed to be used statically.  
     */
     
    
    /**
     * Get listing of all genes with optional match on term.
     * 
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function geneList($args, $page = 0, $pagesize = 20)
    {
		// break out the args
		foreach ($args as $key => $value)
			$$key = $value;
						
		$query = '
			MATCH (n:Gene) ' .
			(isset($term) ? 'WHERE (n.search_label contains ' . $term . ') ' : '') . '
			WITH n
			SKIP ' . ($page * $pagesize) . '
			LIMIT ' . $pagesize . '
			OPTIONAL MATCH (n)<-[:has_subject]-(assertions)
			WHERE (assertions:Assertion)
			WITH n, collect(assertions) AS assertions_collection
			RETURN n.hgnc_id as hgnc_id, n.symbol as symbol, n.name as name, 
				n.last_curated as last_curated, assertions_collection'; 

		try {
			
			$response = Cypher::run($query);
			
		} catch (Exception $exception) {
			
			// TODO - more comprehensive error recovery
			die("error found");
			
		};
		
		// if no records found, return null
		if ($response->size() == 0)
			return null;
				
		return $response;
	}
	
	/**
     * Get listing of all curated genes
     * 
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function geneListCurated($args, $page = 0, $pagesize = 20)
    {
		$query = '
			MATCH (n:Gene)
			WHERE (n)<-[:has_subject]-(:Assertion)
			WITH n
			SKIP ' . ($page * $pagesize) . '
			LIMIT ' . $pagesize . '
			OPTIONAL MATCH (n)<-[:has_subject]-(assertions)
			WHERE (assertions:Assertion)
			WITH n, collect(assertions) AS assertions_collection
			RETURN n.hgnc_id as hgnc_id, n.symbol as symbol, n.name as name, 
				n.last_curated as last_curated, assertions_collection'; 

		$response = Cypher::run($query);
		
		return $response;
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
			
		// get all attributes of the gene node
		if (empty($curations))
		{
			$query = '
				MATCH (g:Gene)
				WHERE (g.hgnc_id = "' . $gene . '")
				RETURN ID(g) as identity, g {.hgnc_id, .symbol, .name, .location,
					.locus_group, .locus_type, .prev_symbol as, .alias_symbol }
				LIMIT 1';
			
			//$response = Cypher::run($query);
			//dd($response);
		}
		else
		{
			$query = '
				MATCH (g)
				WHERE (g.hgnc_id = "' . $gene . '")
				RETURN ID(g) as identity, g {.symbol, .name, .hgnc_id, .location, .locus_group, .locus_type, .prev_symbol, .alias_symbol,
					gene_validity_interps: [(g)<-[:has_subject]-(gv:GeneDiseaseAssertion) | gv {.date, .uuid,
						condition: [(gv)-[:has_object|:equivalentClass*..2]->(c:DiseaseConcept) | c {.label, .iri}][0],
						significance: [(gv)-[:has_predicate]->(i:Interpretation) | i {.label, .iri}][0],
						replaced_by: [(gv)<-[:wasInvalidatedBy]-(r) | r.iri ]}],
					gene_dosage_interps: [(g)<-[:has_subject]-(gd:GeneDosageAssertion) | gd {.date,
						condition: [(gd)-[:has_object|:equivalentClass*..2]->(c:DiseaseConcept) | c {.label, .iri}][0],
						significance: [(gd)-[:has_predicate]->(i:Interpretation) | i {.label, .iri}]}][0],
					actionability_interps: [(g)<-[:has_subject]-(a:ActionabilityAssertion) | a {.date,
						condition: [(a)-[:has_object|:equivalentClass*..2]->(c:DiseaseConcept) | c {.label, .iri}][0]}]}';
		}
		$response = Cypher::run($query);
		$ident = $response->firstRecord()->value('identity');
		$details = $response->firstRecord()->value('g');
		
		// Get the definitive display list of diseases for this gene
		$query = '
			MATCH (g:`Gene`)
			WHERE (ID(g)  = ' . $ident . ')
			MATCH (g)<-[rel1:has_subject]-(node3:Assertion:Entity)
			MATCH (node3)-[rel2:has_object]->(d:RDFClass)
			WHERE ((d)<-[:has_related_phenotype]-(g)
				or (d)<-[:has_object]-(:GeneDiseaseAssertion)-[:has_subject]->(g)
				or not (g)-[:has_related_phenotype]->())
			RETURN DISTINCT(d)';
		$response = Cypher::run($query);
		foreach ($response->getRecords() as $record)
			$diseases[] = $record->value('d')->values();
		$details['diseases'] = $diseases ?? [];
		
		//dd($details);
		//return $response->firstRecord()->value('g');
		
		// get the report links for actionability
		if (!empty($action_scores))
		{
			$query = '
				MATCH (g)
				WHERE (ID(g)  = ' . $ident . ')
				MATCH (g)<-[rel1:has_subject]-(a:Assertion:Entity)
				WHERE (a:ActionabilityAssertion)
				RETURN a {.report, .date,
					disease: [(a)-[:has_object]->(d:RDFClass) | d.iri]}';
					/*,
				  interventions: [(a)<-[:was_generated_by]-(a2:ActionabilityInterventionAssertion)-[:has_object]->(i:Intervention) | a2 {label: i.label,
				  scores: [(a2)<-[:was_generated_by]-(a3:ActionabilityScore) |
							a3 {score: [(a3)-[:has_predicate]->(s) | s.iri ],
								strength: [(a3)-[:has_evidence_strength]->(s) | s.iri] } ]  }]}';*/
				
				$response = Cypher::run($query);
		
				foreach ($response->getRecords() as $record)
					$actionability_report[] = $record->value('a');
					
				$details['actionability_report'] = $actionability_report ?? [];
		}
		
		
		// get the report link for validity
		if (!empty($validity))
		{
			$query = '
				MATCH (g)
				WHERE (ID(g) = ' . $ident . ')
				MATCH (g)<-[rel1:has_subject]-(n:Assertion:Entity)
				WHERE (n:GeneDiseaseAssertion) AND (NOT((n)-[:wasInvalidatedBy]->()))
				WITH n
				OPTIONAL MATCH (n)-[:has_predicate]->(interpretation)
				WHERE (interpretation:Interpretation)
				WITH n, collect(interpretation) AS interpretation_collection
				OPTIONAL MATCH (n)-[:has_object]->(diseases)
				WHERE (diseases:RDFClass)
				WITH n, collect(diseases) AS diseases_collection, interpretation_collection
				RETURN n, [interpretation_collection,diseases_collection]';
				
				$response = Cypher::run($query);

				foreach ($response->getRecords() as $record)
					$validity_report[] = ['node' => $record->value('n'), 'idc' =>  $record->value('[interpretation_collection,diseases_collection]')];
					
				$details['validity_report'] = $validity_report ?? [];
		}
		
		/*try {
			
			$response = Cypher::run($query);
			
		} catch (Exception $exception) {
			
			// TODO - more comprehensive error recovery
			die("error found");
			
		};
		
		// if no records found, return null
		if ($response->size() == 0)
			return null;*/
		return $details;
		
		// get the gene dosage details (evidence for setting Haplo and Triplo?)
		if (!empty($dosage))
		{
			$query = '
				MATCH (g)
				WHERE (ID(g) = ' . $ident . ')
				MATCH (g)<-[rel1:has_subject]-(a:Assertion:Entity)
				WHERE (a:GeneDosageAssertion)
				WITH a
				OPTIONAL MATCH (a)-[:has_predicate]->(interpretation)
				WHERE (interpretation:Interpretation)
				WITH a, collect(interpretation) AS interpretation_collection
				OPTIONAL MATCH (a)-[:has_object]->(diseases)
				WHERE (diseases:RDFClass)
				WITH a, collect(diseases) AS diseases_collection, interpretation_collection
				RETURN a, [interpretation_collection,diseases_collection]';
		}
		
		$response = Cypher::run($query);
		dd($response);	


		// these don't seem to be in the controllers.  Views?
	
		$query = '
			MATCH (g)
			WHERE (ID(g) = ' . $ident . ')
			MATCH (g)<-[rel1:has_subject]-(result_dosage_assertions:GeneDosageAssertion:Assertion:Entity)
			RETURN ID(result_dosage_assertions) AS proof_of_life 
			LIMIT 1';

		$response = Cypher::run($query);
		dd($response);

/*
	// these don't seem to be in the controllers.  Views?
Gene#dosage_assertions 
  MATCH (gene111556)
  WHERE (ID(gene111556) = {ID_gene111556})
  MATCH (gene111556)<-[rel1:`has_subject`]-(result_dosage_assertions:`GeneDosageAssertion`:`Assertion`:`Entity`)
  RETURN result_dosage_assertions | {:ID_gene111556=>111556}

Interpretation 
  MATCH (i:`Interpretation`:`RDFClass`)
  WHERE (i)-[:subClassOf]->(:Interpretation {iri: 'http://datamodel.clinicalgenome.org/terms/CG_000102'})
  RETURN i 
  
  GeneDosageAssertion#interpretation 
  MATCH (genedosageassertion236518)
  WHERE (ID(genedosageassertion236518) = {ID_genedosageassertion236518})
  MATCH (genedosageassertion236518)-[rel1:`has_predicate`]->(result_interpretation:`Interpretation`:`RDFClass`)
  RETURN result_interpretation
  ORDER BY result_interpretation.iri
  LIMIT {limit_1} | {:limit_1=>1, :ID_genedosageassertion236518=>236518}

GeneDosageAssertion#interpretation 
  MATCH (genedosageassertion236445)
  WHERE (ID(genedosageassertion236445) = {ID_genedosageassertion236445})
  MATCH (genedosageassertion236445)-[rel1:`has_predicate`]->(result_interpretation:`Interpretation`:`RDFClass`)
  RETURN result_interpretation
  ORDER BY result_interpretation.iri
  LIMIT {limit_1} | {:limit_1=>1, :ID_genedosageassertion236445=>236445}

GeneDosageAssertion#interpretation 
  MATCH (genedosageassertion236518)
  WHERE (ID(genedosageassertion236518) = {ID_genedosageassertion236518})
  MATCH (genedosageassertion236518)-[rel1:`has_predicate`]->(result_interpretation:`Interpretation`:`RDFClass`)
  RETURN result_interpretation
  ORDER BY result_interpretation.iri
  LIMIT {limit_1} | {:limit_1=>1, :ID_genedosageassertion236518=>236518}

Interpretation 
  MATCH (i:`Interpretation`:`RDFClass`)
  WHERE (i)-[:subClassOf]->(:Interpretation {iri: 'http://datamodel.clinicalgenome.org/terms/CG_000101'})
  RETURN i 

GeneDosageAssertion#interpretation 
  MATCH (genedosageassertion236518)
  WHERE (ID(genedosageassertion236518) = {ID_genedosageassertion236518})
  MATCH (genedosageassertion236518)-[rel1:`has_predicate`]->(result_interpretation:`Interpretation`:`RDFClass`)
  RETURN result_interpretation
  ORDER BY result_interpretation.iri
  LIMIT {limit_1} | {:limit_1=>1, :ID_genedosageassertion236518=>236518}

GeneDosageAssertion#interpretation 
  MATCH (genedosageassertion236445)
  WHERE (ID(genedosageassertion236445) = {ID_genedosageassertion236445})
  MATCH (genedosageassertion236445)-[rel1:`has_predicate`]->(result_interpretation:`Interpretation`:`RDFClass`)
  RETURN result_interpretation
  ORDER BY result_interpretation.iri
  LIMIT {limit_1} | {:limit_1=>1, :ID_genedosageassertion236445=>236445}

GeneDosageAssertion#interpretation 
  MATCH (genedosageassertion236445)
  WHERE (ID(genedosageassertion236445) = {ID_genedosageassertion236445})
  MATCH (genedosageassertion236445)-[rel1:`has_predicate`]->(result_interpretation:`Interpretation`:`RDFClass`)
  RETURN result_interpretation
  ORDER BY result_interpretation.iri
  LIMIT {limit_1} | {:limit_1=>1, :ID_genedosageassertion236445=>236445}

 Gene#assertions 
  MATCH (gene111556)
  WHERE (ID(gene111556) = {ID_gene111556})
  MATCH (gene111556)<-[rel1:`has_subject`]-(n:`Assertion`:`Entity`)
  WHERE 
    (n:GeneDiseaseAssertion) AND 
    (NOT((n)-[:wasInvalidatedBy]->()))
  WITH n
  OPTIONAL MATCH (n)-[:`has_predicate`]->(interpretation)
  WHERE (interpretation:`Interpretation`)
  WITH 
    n, 
    collect(interpretation) AS interpretation_collection
  OPTIONAL MATCH (n)-[:`has_object`]->(diseases)
  WHERE (diseases:`RDFClass`)
  WITH 
    n, 
    collect(diseases) AS diseases_collection, 
    interpretation_collection
  RETURN 
    n, 
    [interpretation_collection,diseases_collection] | {:ID_gene111556=>111556}

Gene#assertions#interpretation 
  MATCH (gene111556)
  WHERE (ID(gene111556) = {ID_gene111556})
  MATCH (gene111556)<-[rel1:`has_subject`]-(n:`Assertion`:`Entity`)
  WHERE 
    (n:GeneDiseaseAssertion) AND 
    (NOT((n)-[:wasInvalidatedBy]->()))
  MATCH (n)-[rel2:`has_predicate`]->(result_interpretation:`Interpretation`:`RDFClass`)
  RETURN result_interpretation
  ORDER BY result_interpretation.iri
  LIMIT {limit_1} | {:limit_1=>1, :ID_gene111556=>111556}
  
  Gene#assertions 
  MATCH (gene111556)
  WHERE (ID(gene111556) = {ID_gene111556})
  MATCH (gene111556)<-[rel1:`has_subject`]-(n:`Assertion`:`Entity`)
  WHERE 
    (n:GeneDiseaseAssertion) AND 
    (NOT((n)-[:wasInvalidatedBy]->()))
  WITH n
  OPTIONAL MATCH (n)-[:`has_predicate`]->(interpretation)
  WHERE (interpretation:`Interpretation`)
  WITH 
    n, 
    collect(interpretation) AS interpretation_collection
  OPTIONAL MATCH (n)-[:`has_object`]->(diseases)
  WHERE (diseases:`RDFClass`)
  WITH 
    n, 
    collect(diseases) AS diseases_collection, 
    interpretation_collection
  RETURN 
    n, 
    [interpretation_collection,diseases_collection] | {:ID_gene111556=>111556}
    
    GeneDosageAssertion#genes 
  MATCH (genedosageassertion236518)
  WHERE (ID(genedosageassertion236518) = {ID_genedosageassertion236518})
  MATCH (genedosageassertion236518)-[rel1:`has_subject`]->(result_genes:`Gene`)
  RETURN result_genes
  ORDER BY result_genes.uuid
  LIMIT {limit_1} | {:limit_1=>1, :ID_genedosageassertion236518=>236518}

Gene#assertions 
  MATCH (gene111556)
  WHERE (ID(gene111556) = {ID_gene111556})
  MATCH (gene111556)<-[rel1:`has_subject`]-(n:`Assertion`:`Entity`)
  WHERE 
    (n:GeneDiseaseAssertion) AND 
    (NOT((n)-[:wasInvalidatedBy]->()))
  WITH n
  OPTIONAL MATCH (n)-[:`has_predicate`]->(interpretation)
  WHERE (interpretation:`Interpretation`)
  WITH 
    n, 
    collect(interpretation) AS interpretation_collection
  OPTIONAL MATCH (n)-[:`has_object`]->(diseases)
  WHERE (diseases:`RDFClass`)
  WITH 
    n, 
    collect(diseases) AS diseases_collection, 
    interpretation_collection
  RETURN 
    n, 
    [interpretation_collection,diseases_collection] | {:ID_gene111556=>111556}

Gene#assertions#interpretation 
  MATCH (gene111556)
  WHERE (ID(gene111556) = {ID_gene111556})
  MATCH (gene111556)<-[rel1:`has_subject`]-(n:`Assertion`:`Entity`)
  WHERE 
    (n:GeneDiseaseAssertion) AND 
    (NOT((n)-[:wasInvalidatedBy]->()))
  MATCH (n)-[rel2:`has_predicate`]->(result_interpretation:`Interpretation`:`RDFClass`)
  RETURN result_interpretation
  ORDER BY result_interpretation.iri
  LIMIT {limit_1} | {:limit_1=>1, :ID_gene111556=>111556}

Gene#assertions 
  MATCH (gene111556)
  WHERE (ID(gene111556) = {ID_gene111556})
  MATCH (gene111556)<-[rel1:`has_subject`]-(n:`Assertion`:`Entity`)
  WHERE 
    (n:GeneDiseaseAssertion) AND 
    (NOT((n)-[:wasInvalidatedBy]->()))
  WITH n
  OPTIONAL MATCH (n)-[:`has_predicate`]->(interpretation)
  WHERE (interpretation:`Interpretation`)
  WITH 
    n, 
    collect(interpretation) AS interpretation_collection
  OPTIONAL MATCH (n)-[:`has_object`]->(diseases)
  WHERE (diseases:`RDFClass`)
  WITH 
    n, 
    collect(diseases) AS diseases_collection, 
    interpretation_collection
  RETURN 
    n, 
    [interpretation_collection,diseases_collection] | {:ID_gene111556=>111556}
    
    GeneDosageAssertion#interpretation 
  MATCH (genedosageassertion236518)
  WHERE (ID(genedosageassertion236518) = {ID_genedosageassertion236518})
  MATCH (genedosageassertion236518)-[rel1:`has_predicate`]->(result_interpretation:`Interpretation`:`RDFClass`)
  RETURN result_interpretation
  ORDER BY result_interpretation.iri
  LIMIT {limit_1} | {:limit_1=>1, :ID_genedosageassertion236518=>236518}

GeneDosageAssertion#interpretation 
  MATCH (genedosageassertion236445)
  WHERE (ID(genedosageassertion236445) = {ID_genedosageassertion236445})
  MATCH (genedosageassertion236445)-[rel1:`has_predicate`]->(result_interpretation:`Interpretation`:`RDFClass`)
  RETURN result_interpretation
  ORDER BY result_interpretation.iri
  LIMIT {limit_1} | {:limit_1=>1, :ID_genedosageassertion236445=>236445}

GeneDosageAssertion#interpretation 
  MATCH (genedosageassertion236518)
  WHERE (ID(genedosageassertion236518) = {ID_genedosageassertion236518})
  MATCH (genedosageassertion236518)-[rel1:`has_predicate`]->(result_interpretation:`Interpretation`:`RDFClass`)
  RETURN result_interpretation
  ORDER BY result_interpretation.iri
  LIMIT {limit_1} | {:limit_1=>1, :ID_genedosageassertion236518=>236518}

GeneDosageAssertion#interpretation 
  MATCH (genedosageassertion236518)
  WHERE (ID(genedosageassertion236518) = {ID_genedosageassertion236518})
  MATCH (genedosageassertion236518)-[rel1:`has_predicate`]->(result_interpretation:`Interpretation`:`RDFClass`)
  RETURN result_interpretation
  ORDER BY result_interpretation.iri
  LIMIT {limit_1} | {:limit_1=>1, :ID_genedosageassertion236518=>236518}
  
   GeneDosageAssertion#interpretation 
  MATCH (genedosageassertion236445)
  WHERE (ID(genedosageassertion236445) = {ID_genedosageassertion236445})
  MATCH (genedosageassertion236445)-[rel1:`has_predicate`]->(result_interpretation:`Interpretation`:`RDFClass`)
  RETURN result_interpretation
  ORDER BY result_interpretation.iri
  LIMIT {limit_1} | {:limit_1=>1, :ID_genedosageassertion236445=>236445}

GeneDosageAssertion#interpretation 
  MATCH (genedosageassertion236445)
  WHERE (ID(genedosageassertion236445) = {ID_genedosageassertion236445})
  MATCH (genedosageassertion236445)-[rel1:`has_predicate`]->(result_interpretation:`Interpretation`:`RDFClass`)
  RETURN result_interpretation
  ORDER BY result_interpretation.iri
  LIMIT {limit_1} | {:limit_1=>1, :ID_genedosageassertion236445=>236445}
*/

		//$response = Cypher::run($query);
		
		return $response;
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
			$$key = $value;
			
		$query = '
			MATCH (n:Agent)
			OPTIONAL MATCH (n)--(m)
			NOT((m)-[:wasInvalidatedBy]->())
			RETURN n.iri as agent, n.label as label, count(m) as count
			ORDER BY agent'; 

		try {
			
			$response = Cypher::run($query);
			
		} catch (Exception $exception) {
			
			// TODO - more comprehensive error recovery
			die("error found");
			
		};
		
		// if no records found, return null
		if ($response->size() == 0)
			return null;
			
		return $response;
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
			
		$url = "https://search.clinicalgenome.org/kb/agents/" . $affiliate;

		$query = '
			MATCH (n:Agent {iri: "' . $url . '"})
			OPTIONAL MATCH (n)--(m)
			WITH {agent:n.iri, label:n.label, curations:collect(m)} as data
			NOT((m)-[:wasInvalidatedBy]->())
			RETURN data'; 

		try {
			
			$response = Cypher::run($query);
			
		} catch (Exception $exception) {
			
			// TODO - more comprehensive error recovery
			die("error found");
			
		};
		
		// if no records found, return null
		if ($response->size() == 0)
			return null;
		
		return $response->firstRecord()->value('data');
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
			
		// first call appears to get the conditions
		$query = '
			MATCH (result_condition:Condition:RDFClass)
			RETURN result_condition
			SKIP ' . ($page * $pagesize) . '
			LIMIT ' . $pagesize . '
			RETURN result_condition';

		// this one seems to get the assertions for the previously matched conditions
		$query = '
			MATCH (previous:Condition:RDFClass)
			WHERE (ID(previous) IN {ID_previous})
			OPTIONAL MATCH (previous)<-[rel1:has_object]-(next:Assertion:Entity)
			RETURN 
			ID(previous), 
			collect(next) | {:ID_previous=>[2611, 2613, 2627, 2645, 2649, 2663, 2665, 2687, 2693, 2694, 2696, 2712, 2734, 2744, 2745, 2746, 2748, 2751, 2752, 2771]}';

		try {
			
			$response = Cypher::run($query);
			
		} catch (Exception $exception) {
			
			// TODO - more comprehensive error recovery
			die("error found");
			
		};
		
		// if no records found, return null
		if ($response->size() == 0)
			return null;
		
		return $response;
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
			
		// first call appears to get the conditions
		$query = '
			';

		try {
			
			$response = Cypher::run($query);
			
		} catch (Exception $exception) {
			
			// TODO - more comprehensive error recovery
			die("error found");
			
		};
		
		// if no records found, return null
		if ($response->size() == 0)
			return null;
		
		return $response;
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
			
		$query = '
			MATCH (n:Drug:RDFClass)
			SKIP ' . ($page * $pagesize) . '
			LIMIT ' . $pagesize . '
			RETURN n';

		try {
			
			$response = Cypher::run($query);
			
		} catch (Exception $exception) {
			
			// TODO - more comprehensive error recovery
			die("error found");
			
		};
		
		// if no records found, return null
		if ($response->size() == 0)
			return null;
		
		return $response;
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
			
		// first call appears to get the conditions
		$query = ' 
			MATCH (n:Drug:RDFClass)
			WHERE (n.curie = ' . $id . ')
			LIMIT 1
			RETURN n';

		try {
			
			$response = Cypher::run($query);
			
		} catch (Exception $exception) {
			
			// TODO - more comprehensive error recovery
			die("error found");
			
		};
		
		// if no records found, return null
		if ($response->size() == 0)
			return null;
		
		return $response;
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
			
		// first call appears to get the gene disease info 
		$query = ' 
			MATCH (n:`GeneDiseaseAssertion`:`Assertion`:`Entity`)
			WHERE (n.perm_id = "' . $perm . '")
			RETURN n, ID(n) as identity
			LIMIT 1';
		
		//try {
			
			$response = Cypher::run($query);
			
		/*} catch (Exception $exception) {
			
			// TODO - more comprehensive error recovery
			die("error found");
			
		};*/
		
		$ident = $response->firstRecord()->value('identity');
		$details['n'] = $response->firstRecord()->value('n');
			
		// this call gets the first gene symbol
		$query = '
			MATCH (g)
			WHERE (ID(g)  = ' . $ident . ')
			MATCH (g)-[rel1:has_subject]->(result_genes:Gene)
			RETURN result_genes
			ORDER BY result_genes.uuid
			LIMIT 1';
		
		//try {
			
			$response = Cypher::run($query);
			
		/*} catch (Exception $exception) {
			
			// TODO - more comprehensive error recovery
			die("error found");
			
		};*/
		//dd($response->firstRecord());
		$details['gene_symbol'] = $response->firstRecord();
		
		// this call gets the first disease name
		$query = '
			MATCH (g)
			WHERE (ID(g)  = ' . $ident . ')
			MATCH (g)-[rel1:has_object]->(result_diseases:RDFClass)
			RETURN result_diseases
			ORDER BY result_diseases.iri
			LIMIT 1';
		
		//try {
			
			$response = Cypher::run($query);
			
		/*} catch (Exception $exception) {
			
			// TODO - more comprehensive error recovery
			die("error found");
			
		};*/
		
		$details['disease_name'] = $response->firstRecord();
		
		// this call gets the curie
		$query = '
			MATCH (g)
			WHERE (ID(g)  = ' . $ident . ')
			MATCH (g)-[rel1:has_subject]->(result_genes:Gene)
			RETURN result_genes
			ORDER BY result_genes.uuid
			LIMIT 1';
		
		//try {
			
			$response = Cypher::run($query);
			
		/*} catch (Exception $exception) {
			
			// TODO - more comprehensive error recovery
			die("error found");
			
		};*/
		
		$details['gene_curie'] = $response->firstRecord();
		
		// this call gets the disease curie
		$query = '
			MATCH (g)
			WHERE (ID(g)  = ' . $ident . ')
			MATCH (g)-[rel1:has_object]->(result_diseases:RDFClass)
			RETURN result_diseases
			ORDER BY result_diseases.iri
			LIMIT 1';
		
		//try {
			
			$response = Cypher::run($query);
			
		/*} catch (Exception $exception) {
			
			// TODO - more comprehensive error recovery
			die("error found");
			
		};*/
		
		$details['disease_curie'] = $response->firstRecord();
		
		//CHECKPOINT 9
		
		return $details;
	}

}
