// Gene list curated
{
    genes(limit: null, curation_activity: ALL)
    {
        count
        gene_list {
            label
            hgnc_id
            last_curated_date
            curation_activities
            dosage_curation {
                triplosensitivity_assertion {
                    dosage_classification {
                        ordinal
                        }
                }
                haploinsufficiency_assertion {
                    dosage_classification {
                        ordinal
                        }
                }
            }
        }
    }
}

// Gene detail
{
    gene(iri: "HGNC:18149")
    {
        label
        alternative_label
        hgnc_id
        chromosome_band
        curation_activities
        last_curated_date
        dosage_curation {
            curie
            report_date
            triplosensitivity_assertion {
                dosage_classification {
                    ordinal
                    }

            }
            haploinsufficiency_assertion {
                dosage_classification {
                    ordinal
                    }

            }
        }
        genetic_conditions {
            disease {
                label
                iri
            }
            gene_validity_assertions {
                mode_of_inheritance {
                    label
                    website_display_label
                    curie
                }
                report_date
                classification {
                    label
                    curie
                }
                curie
            }
            actionability_assertions {
                report_date
                source
                classification {
                    label
                    curie
                }
                attributed_to {
                    label
                    curie
                }
            }
            gene_dosage_assertions {
                report_date
                assertion_type
                dosage_classification {
                ordinal
                }
                curie
            }
        }
    }
}


// Actionability List (reports)
// may no longer be used.
{
    genes(limit: null, curation_activity: ACTIONABILITY) {
        gene_list{
            label
            hgnc_id
            genetic_conditions {
                disease {
                    label
                    curie
                }
                mode_of_inheritance {
                    label
                    website_display_label
                    curie
                    }
                actionability_assertions {
                    report_date
                    source
                    attributed_to {
                        label
                    }
                    classification {
                        label
                        curie
                    }
                }
            }
        }
    }
    statistics {
        actionability_tot_reports
        actionability_tot_updated_reports
        actionability_tot_gene_disease_pairs
        actionability_tot_adult_gene_disease_pairs
        actionability_tot_pediatric_gene_disease_pairs
        actionability_tot_adult_outcome_intervention_pairs
        actionability_tot_outcome_intervention_pairs
        actionability_tot_pediatric_outcome_intervention_pairs
        actionability_tot_adult_score_counts
        actionability_tot_pediatric_score_counts
        actionability_tot_adult_failed_early_rule_out
        actionability_tot_pediatric_failed_early_rule_out
    }
}


// Actionability List (non-reports)
{
	gene(iri: "HGNC:18149") {
        label
        conditions {
            iri
            label
            actionability_assertions {
                report_date
                source
            }
        }
    }
}


// Dosage List (reports)
{
    genes(limit: null, curation_activity: GENE_DOSAGE, sort: {field: GENE_LABEL, direction: ASC})
    {
        count
        gene_list {
            label
            hgnc_id
            dosage_curation {
                curie
                report_date
                triplosensitivity_assertion {
                    report_date
                    disease {
                        label
                        curie
                    }
                    dosage_classification {
                        ordinal
                    }
                }
                haploinsufficiency_assertion {
                    report_date
                    disease {
                        label
                        curie
                    }
                    dosage_classification {
                        ordinal
                    }
                }
            }
            genetic_conditions {
                disease {
                    label
                    curie
                }
                gene_dosage_assertions {
                    report_date
                    assertion_type
                    curie
                    dosage_classification {
                    ordinal
                    }
                }
            }
        }
    }
}

// Dosage List (non-reports)
{
    genes(limit: null, curation_activity: GENE_DOSAGE, sort: {field: GENE_LABEL, direction: ASC})
    {
        count
        gene_list {
            label
            hgnc_id
            chromosome_band
            dosage_curation {
                report_date
                triplosensitivity_assertion {
                    report_date
                    disease {
                        label
                        curie
                    }
                    dosage_classification {
                        ordinal
                    }
                }
                haploinsufficiency_assertion {
                    report_date
                    disease {
                        label
                        curie
                    }
                    dosage_classification {
                        ordinal
                    }
                }
            }
        }
    }
}


// Dosage detail
{
    gene(iri: "HGNC:18149") {
    {
        label
        alternative_label
        hgnc_id
        chromosome_band
        curation_activities
        dosage_curation {
            curie
            report_date
            triplosensitivity_assertion {
                dosage_classification {
                    ordinal
                }
            }
            haploinsufficiency_assertion {
                dosage_classification {
                    ordinal
                }
            }
        }
        genetic_conditions {
            disease {
                label
                iri
            }
            gene_validity_assertions {
                mode_of_inheritance {
                    label
                    curie
                }
                report_date
                classification {
                    label
                    curie
                }
                curie
            }
            actionability_assertions {
                report_date
                source
            }
            gene_dosage_assertions {
                report_date
                assertion_type
                dosage_classification {
                ordinal
                }
                curie
            }
        }
    }
}

// Validity List
{
    gene_validity_assertions(limit: null)
    {
        count
        curation_list {
            report_date
            curie
            disease {
                label
                curie
            }
            gene {
                label
                hgnc_id
            }
            mode_of_inheritance {
                label
                curie
            }
            classification {
                label
            }
            specified_by {
                label
            }
            attributed_to {
                label
            }
        }
    }
}


// Validity detail
{
    gene_validity_assertion(iri: "HGNC:18149")
    {
        curie
        report_date
        gene {
            label
            hgnc_id
            curie
        }
        disease {
            label
            curie
        }
        mode_of_inheritance {
            label
            website_display_label
            curie
        }
        attributed_to {
            label
            curie
        }
        classification {
            label
            curie
        }
        specified_by {
            label
            curie
        }
        legacy_json
    }
}


// Condition list
{
    diseases(limit: null, curation_activity: ALL, sort: {field: GENE_LABEL, direction: ASC}) {
    {
        count
        disease_list {
            label
            curie
            description
            synonyms
            last_curated_date
            curation_activities
        }
    }
}


// Condition Detail
{
    disease(iri: "MONDO:0009734") {
        label
        iri
        curation_activities
        genetic_conditions {
            gene {
            label
            hgnc_id
            }
            gene_validity_assertions {
                mode_of_inheritance {
                    label
                    website_display_label
                    curie
                }
                report_date
                classification {
                    label
                    curie
                }
                curie
            }
            actionability_assertions {
                report_date
                source
                classification {
                    label
                    curie
                }
            }
            gene_dosage_assertions {
                report_date
                assertion_type
                dosage_classification {
                    ordinal
                }
                curie
            }
        }
    }
}


// Affiliate list
{
    affiliations (limit: null)
    {
        count
        agent_list {
            iri
            curie
            label
            gene_validity_assertions{
                count
            }
        }
    }
}


// Affiliate Detail
{
    affiliation(iri: "CGAGENT:007") {
        curie
        iri
        label
        gene_validity_assertions(limit: null, sort: {field: GENE_LABEL, direction: ASC}) {
            count
            curation_list {
                curie
                iri
                label
                legacy_json
                gene {
                    label
                    hgnc_id
                    curie
                }
                disease {
                    label
                    curie
                }
                mode_of_inheritance {
                    label
                    curie
                }
                attributed_to {
                    label
                    curie
                }
                classification {
                    label
                    curie
                }
                specified_by {
                    label
                    curie
                }
                report_date
            }
        }
    }
}


// Validity list
{
    gene_validity_assertions(limit: null){
        count
        curation_list {
            report_date
            curie
            disease {
                label
                curie
            }
            gene {
                label
                hgnc_id
            }
            mode_of_inheritance {
                label
                curie
            }
            classification {
                label
            }
            specified_by {
                label
            }
            attributed_to {
                label
            }
        }
    }
}


// Metrics
{
    genes(limit: null, curation_activity: ALL) {
        count
        gene_list {
            hgnc_id
            curation_activities
            dosage_curation {
                triplosensitivity_assertion {
                    dosage_classification {
                        ordinal
                    }
                }
                haploinsufficiency_assertion {
                    dosage_classification {
                        ordinal
                    }
                }
            }
            genetic_conditions {
                actionability_assertions {
                    report_date
                    source
                }
            }
        }
    }
}

{
    gene_validity_assertions(limit: null) {
        count
        curation_list {
            curie
            classification {
                label
            }
            specified_by {
                label
            }
            attributed_to {
                curie
                label
            }
        }
    }
}

{
    statistics {
        actionability_tot_reports
        actionability_tot_updated_reports
        actionability_tot_gene_disease_pairs
        actionability_tot_adult_gene_disease_pairs
        actionability_tot_pediatric_gene_disease_pairs
        actionability_tot_adult_outcome_intervention_pairs
        actionability_tot_outcome_intervention_pairs
        actionability_tot_pediatric_outcome_intervention_pairs
        actionability_tot_adult_score_counts
        actionability_tot_pediatric_score_counts
        actionability_tot_adult_failed_early_rule_out
        actionability_tot_pediatric_failed_early_rule_out
    }
}


// Gene Suggester
{
    suggest(contexts: ALL, suggest: GENE, text: "ABC") {
            curations
            highlighted
            alternative_curie
            text
        }
    }
}


// Drug Suggester
{
    suggest(contexts: ALL, suggest: DRUG, text: "ABC") {
            curie
            curations
            highlighted
            iri
            text
            type
            weight
        }
    }
}


// Disease Suggester
// no longer used
{
    suggest(contexts: ALL, suggest: DISEASE, text: "ABC") {
            curie
            curations
            highlighted
            iri
            text
            type
            weight
        }
    }
}
