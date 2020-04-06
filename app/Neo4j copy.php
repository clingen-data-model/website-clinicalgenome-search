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
			OPTIONAL MATCH (n)<-[:has_subject]-(assertions)
			WHERE (assertions:Assertion)
			WITH n, collect(assertions) AS assertions_collection
			RETURN n.hgnc_id as hgnc_id, n.symbol as symbol, n.name as name,
				n.last_curated as last_curated, assertions_collection ' .
			(!empty($sort) ? 'ORDER BY n.' . $sort . ' ' . $direction : '')  . '
			SKIP ' . ($page * $pagesize) . '
			LIMIT ' . $pagesize . '
			';


//dd($query);
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
					.locus_group, .entrez_id, .locus_type, .prev_symbol as, .alias_symbol }
				LIMIT 1';

			//$response = Cypher::run($query);
			//dd($response);
		}
		else
		{
			$query = '
				MATCH (g)
				WHERE (g.hgnc_id = "' . $gene . '")
				RETURN ID(g) as identity, g {.symbol, .name, .hgnc_id, .location, .locus_group, .entrez_id, .locus_type, .prev_symbol, .alias_symbol,
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
		//dd($response);


		// these don't seem to be in the controllers.  Views?

		$query = '
			MATCH (g)
			WHERE (ID(g) = ' . $ident . ')
			MATCH (g)<-[rel1:has_subject]-(result_dosage_assertions:GeneDosageAssertion:Assertion:Entity)
			RETURN ID(result_dosage_assertions) AS proof_of_life
			LIMIT 1';

		$response = Cypher::run($query);
		//dd($response);

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
			$key = $value;

		$query = '
			MATCH (n:Agent)
			OPTIONAL MATCH (n)--(m)
			RETURN n.iri as agent, n.label as label, count(m) as count
			ORDER BY agent';


		//TODO - Dropped the NOT((m)-[:wasInvalidatedBy]->()) due to error
		// Prob needs to be reviewed as it may be needed
		// $query = '
		// 	MATCH (n:Agent)
		// 	OPTIONAL MATCH (n)--(m)
		// 	NOT((m)-[:wasInvalidatedBy]->())
		// 	RETURN n.iri as agent, n.label as label, count(m) as count
		// 	ORDER BY agent';

			// .match("(n:Agent)")
      // .optional_match("(n)--(m)")
      // .with("{agent:n.iri,label:n.label,GeneDiseaseAssertions:collect(m) } as data")
      // .return("data")
      // .order("data.agent")

		try {

			$response = Cypher::run($query);
			//dd($response);
		} catch (Exception $exception) {
			// set up geneError();
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

		//TODO - Dropped the NOT((m)-[:wasInvalidatedBy]->()) due to error
		// Prob needs to be reviewed as it may be needed
		$query = '
			MATCH (n:Agent {iri: "' . $url . '"})
			OPTIONAL MATCH (n)--(m)
			WITH {agent:n.iri, label:n.label, curations:collect(m)} as data
			RETURN data';

		// $query = '
		// 	MATCH (n:Agent {iri: "' . $url . '"})
		// 	OPTIONAL MATCH (n)--(m)
		// 	WITH {agent:n.iri, label:n.label, curations:collect(m)} as data
		// 	NOT((m)-[:wasInvalidatedBy]->())
		// 	RETURN data';

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

		// Get the condition and all its assertions
		$query = '
			MATCH (n:Condition:RDFClass)
			WITH n
			SKIP ' . ($page * $pagesize) . '
			LIMIT ' . $pagesize . '
			OPTIONAL MATCH (n:Condition:RDFClass)<-[rel1:has_object]-(assertions:Assertion:Entity)
			WITH n, collect(assertions) AS assertions_collection
			RETURN ID(n) as identity, n.curie as curie, n.num_curations as num_curations, n.last_curated as last_curated, n.label as label, assertions_collection';

		try {

			$response = Cypher::run($query);

		} catch (Exception $exception) {

			// TODO - more comprehensive error recovery
			die("error found");

		};

		//dd($response);

		/*
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

		};*/

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
			MATCH (n:RDFClass)
			WHERE (n.curie = "' . $condition . '")
			RETURN n
			LIMIT 1;
		';

  /*
   * // this is getting genegraph stuff
  query ConditionsController__ConditionQuery($iri: String!) {
  condition(iri: $iri) {
    label
    gene {
      label
      hgnc_id
    }
    actionability_curations {
      report_date
      source
    }
    genetic_conditions {
      gene {
        label
        hgnc_id
      }
      actionability_curations {
        report_date
        source
      }
     }
    }
   }
		# get actionability stuff
		MATCH (condition2751)
		WHERE (ID(condition2751) = {ID_condition2751})
		MATCH (condition2751)<-[rel1:`has_object`]-(a:`Assertion`:`Entity`)
		WHERE (a:ActionabilityAssertion)
		RETURN a {.uuid, .report, .date, .file,
			gene: [(a)-[:has_subject]->(g:Gene) | g.uuid],
		  interventions: [(a)                             <-[:was_generated_by]-(a2:ActionabilityInterventionAssertion)-[:has_object]->(i:Intervention) | a2 {label: i.label,
		  scores: [(a2)<-[:was_generated_by]-(a3:ActionabilityScore) |
					a3 {score: [(a3)-[:has_predicate]->(s) | s.iri ],
							strength: [(a3)-[:has_evidence_strength]->(s) | s.iri] } ]      }]} | {:ID_condition2751=>2751}

		#get genedisease assertion
		MATCH (condition2751)
		WHERE (ID(condition2751) = {ID_condition2751})
		MATCH (condition2751)<-[rel1:`has_object`]-(n:`Assertion`:`Entity`)
		WHERE (n:GeneDiseaseAssertion)
		WITH n
		OPTIONAL MATCH (n)-[:`has_predicate`]->(interpretation)
		WHERE (interpretation:`Interpretation`)
		WITH
		n,
		collect(interpretation) AS interpretation_collection
		OPTIONAL MATCH (n)-[:`has_subject`]->(genes)
		WHERE (genes:`Gene`)
		WITH
		n,
		collect(genes) AS genes_collection,
		interpretation_collection
		RETURN
		n,
		[interpretation_collection,genes_collection] | {:ID_condition2751=>2751}

		# get genedosage stuff
		MATCH (condition2751)
		WHERE (ID(condition2751) = {ID_condition2751})
		MATCH (condition2751)<-[rel1:`has_object`]-(a:`Assertion`:`Entity`)
		WHERE (a:GeneDosageAssertion)
		WITH a
		OPTIONAL MATCH (a)-[:`has_predicate`]->(interpretation)
		WHERE (interpretation:`Interpretation`)
		WITH
		a,
		collect(interpretation) AS interpretation_collection
		OPTIONAL MATCH (a)-[:`has_subject`]->(genes)
		WHERE (genes:`Gene`)
		WITH
		a,
		collect(genes) AS genes_collection,
		interpretation_collection
		RETURN
		a,

		# get proof of life?
		MATCH (c:`Condition`:`RDFClass`)
		WHERE (ID(c) = {ID_c})
		MATCH (c)<-[rel1:`has_object`]-(node3:`Assertion`:`Entity`)
		MATCH (node3)-[rel2:`has_subject`]->(g:`Gene`)
		WHERE ((c)<-[:has_related_phenotype]-(g) or (c)<-[:has_object]-(:GeneDiseaseAssertion) or not (g)-[:has_related_phenotype]->(:DiseaseConcept))
		RETURN ID(g) AS proof_of_life LIMIT 1 | {:ID_c=>2751}

		## no idea!
		MATCH (c:`Condition`:`RDFClass`)
		WHERE (ID(c) = {ID_c})
		MATCH (c)<-[rel1:`has_object`]-(node3:`Assertion`:`Entity`)
		MATCH (node3)-[rel2:`has_subject`]->(g:`Gene`)
		WHERE ((c)<-[:has_related_phenotype]-(g) or (c)<-[:has_object]-(:GeneDiseaseAssertion) or not (g)-[:has_related_phenotype]->(:DiseaseConcept))
		RETURN DISTINCT(g) | {:ID_c=>2751}

		MATCH (condition2751)
		WHERE (ID(condition2751) = {ID_condition2751})
		MATCH (condition2751)<-[rel1:`has_object`]-(n:`Assertion`:`Entity`)
		WHERE (n:GeneDiseaseAssertion)
		WITH n
		OPTIONAL MATCH (n)-[:`has_predicate`]->(interpretation)
		WHERE (interpretation:`Interpretation`)
		WITH
		n,
		collect(interpretation) AS interpretation_collection
		OPTIONAL MATCH (n)-[:`has_subject`]->(genes)
		WHERE (genes:`Gene`)
		WITH
		n,
		collect(genes) AS genes_collection,
		interpretation_collection
		RETURN
		n,
		[interpretation_collection,genes_collection] | {:ID_condition2751=>2751}
D,
  MATCH (genediseaseassertion234482)
  WHERE (ID(genediseaseassertion234482) = {ID_genediseaseassertion234482})
  MATCH (genediseaseassertion234482)-[rel1:`has_object`]->(result_diseases:`RDFClass`)
  RETURN result_diseases
  ORDER BY result_diseases.iri
  LIMIT {limit_1} | {:limit_1=>1, :ID_genediseaseassertion234482=>234482}

	*/





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
			MATCH (n:Drug:RDFClass) ' .
			(isset($term) ? 'WHERE (n.search_label contains ' . $term . ') ' : '') . '
			WITH n
			SKIP ' . ($page * $pagesize) . '
			LIMIT ' . $pagesize . '
			RETURN n.label as label, n.curie as curie';

		try {

			$response = Cypher::run($query);

		} catch (Exception $exception) {

			// TODO - more comprehensive error recovery
			die("error found");

		};
		//dd($response);
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
			WHERE (n.curie = "' . $drug . '")
			WITH n
			RETURN n.label as label, n.curie as curie
			LIMIT 1';

		try {

			$response = Cypher::run($query);

		} catch (Exception $exception) {

			// TODO - more comprehensive error recovery
			die("error found");

		};

		// if no records found, return null
		if ($response->size() == 0)
			return null;

		return $response->firstRecord();
	}


	/**
     * Get list of a validity curations
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function validityList($args, $page = 0, $pagesize = 20)
    {
		// break out the args
		foreach ($args as $key => $value)
			$$key = $value;

		$query = '
				MATCH (a:GeneDiseaseAssertion:Assertion:Entity)
				WHERE (NOT((a)-[:wasInvalidatedBy]->()))
				RETURN a {.date, .perm_id, .score_string, .jsonMessageVersion, .score_string_gci, .score_string_sop5,
					genes: [(g:Gene)<-[:has_subject]-(a) | g {.symbol, .hgnc_id}],
					diseases: [(d:DiseaseConcept)-[:has_object|:equivalentClass*1..2]-(a) | d {.curie, .label}],
					interpretation: [(i:Interpretation)<-[:has_predicate]-(a) | i {.label}],
					agent: [(ag:Agent)<-[:wasAttributedto]-(a) | ag {.label}]}';

		try {

			$response = Cypher::run($query);

		} catch (Exception $exception) {

			// TODO - more comprehensive error recovery
			die("error found");

		};

		// if no records found, return null
		if ($response->size() == 0)
			return null;
		//dd($response);
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
			MATCH (a:`GeneDiseaseAssertion`:`Assertion`:`Entity`)
			WHERE (a.perm_id = "' . $perm . '")
			RETURN a {.date, .perm_id, .score_string, .jsonMessageVersion, .score_string_gci, .score_string_sop5,
					genes: [(g:Gene)<-[:has_subject]-(a) | g {.symbol, .hgnc_id}],
					diseases: [(d:DiseaseConcept)-[:has_object|:equivalentClass*1..2]-(a) | d {.curie, .label}],
					interpretation: [(i:Interpretation)<-[:has_predicate]-(a) | i {.label}],
					agent: [(ag:Agent)<-[:wasAttributedto]-(a) | ag {.label, .iri}]}';
		//try {

			$response = Cypher::run($query);
			dd($response);
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
		$details['symbol'] = $response->firstRecord();

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
		//dd($details);
		return $details;
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

		//@query = Gene.all(:g).where("(g)<-[:has_subject]-(:GeneDosageAssertion)").order('g.symbol')
		//@genes = @query.page(page).per(100)
		/* <% if a = g.dosage_assertions.select {|i| i.haplo_assertion?}.first %>
              <%= a.interpretation.first.label %>
            <% end %>
          </td>
          <td>
            <% if a = g.dosage_assertions.select {|i| i.triplo_assertion?}.first %>
              <%= a.interpretation.first.label %>
            <% end %>
          </td>
          <td>
            <a href="https://www.ncbi.nlm.nih.gov/projects/dbvar/clingen/clingen_gene.cgi?sym=<%= g.label %>&subject=" target="ncbi" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-new-window"></i> <strong>View Details</strong></a></td>
          <td>
            <%= mdy_date(g.dosage_assertions.first.date) %></td>

           WITH n, collect(assertions) AS assertions_collection
           *
           *
           *
			MATCH (genedosageassertion234635)
			WHERE (ID(genedosageassertion234635) = {ID_genedosageassertion234635})
			MATCH (genedosageassertion234635)-[rel1:`has_predicate`]->(result_interpretation:`Interpretation`:`RDFClass`)
			RETURN result_interpretation
			ORDER BY result_interpretation.iri
			LIMIT {limit_1} | {:limit_1=>1, :ID_genedosageassertion234635=>234635}

			OPTIONAL MATCH (n)<-[:has_subject]-(assertions)
			WHERE (assertions:Assertion)
			WITH n, collect(assertions) AS assertions_collection
			RETURN n.hgnc_id as hgnc_id, n.symbol as symbol, n.name as name,
				n.last_curated as last_curated, assertions_collection';
				*
			MATCH (n:Gene)
			WITH n
			SKIP ' . ($page * $pagesize) . '
			LIMIT ' . $pagesize . '
			OPTIONAL MATCH (n)<-[:has_subject]-(assertions)
			WHERE (assertions:GeneDosageAssertion)
			WITH n, collect(assertions) AS assertions_collection
			RETURN n.hgnc_id as hgnc_id, n.symbol as symbol,
				   n.last_curated as last_curated, assertions_collection';
        */

		$query = '
			MATCH (n:Gene)<-[:has_subject]-(assertions)
			WHERE (assertions:GeneDosageAssertion)
			WITH n, collect(assertions) AS assertions_collection
			SKIP ' . ($page * $pagesize) . '
			LIMIT ' . $pagesize . '
			RETURN n.hgnc_id as hgnc_id, n.symbol as symbol,
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

}
