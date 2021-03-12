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


// Gene list all
{
    genes(limit: null)
    {
        count
        gene_list {
            label
            alternative_label
            hgnc_id
            last_curated_date
            curation_activities
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
        last_curated_date:
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
            actionability_curations {
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


// Actionability List
{
    gene(iri: "HGNC:18149") {
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
    }
}


// Dosage List
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
                score
            }
            haploinsufficiency_assertion { 
                dosage_classification {
                    ordinal
                }
                score
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
            actionability_curations {
                report_date
                source
            }
            gene_dosage_assertions {
                report_date
                assertion_type
                dosage_classification {
                ordinal
                }
                score
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
    diseases(limit: null)
    {
        count
        disease_list {
            curie
            label
            description
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
                curie
            }
            report_date
            classification {
                label
                curie
            }
            curie
            }
            actionability_curations {
                report_date
                source
            }
            gene_dosage_assertions {
                report_date
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


// Drug list
{
    drugs(limit: null){
        count
        drug_list {
            label
            curie
        }
    }
}


{
    drug(iri: "http://purl.bioontology.org/ontology/'
    . $drug
    . '") {
            label
            iri
            curie
            aliases
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
