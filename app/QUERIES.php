// Gene list curated
{
    genes(limit: null, curation_activity: ALL)
    {
        count
        gene_list {
            label
            hgnc_id
            iri
            chromosome_band
            last_curated_date
            alternative_label
            curation_activities
            dosage_curation {
                triplosensitivity_assertion { score }
                haploinsufficiency_assertion { score }
            }
        }
    }
}

// Gene list
{
    genes(limit: null)
    {
        count
        gene_list {
            label
            alternative_label
            hgnc_id
            chromosome_band
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
        dosage_curation {
            curie
            report_date
            triplosensitivity_assertion {
                score
                dosage_classification {
                    label
                    ordinal
                    enum_value
                    }
            
            }
            haploinsufficiency_assertion {
                score
                dosage_classification {
                    label
                    ordinal
                    enum_value
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
                score
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

// Dosage List
{
    genes(limit: null, curation_activity: GENE_DOSAGE)
    {
        count
        gene_list {
            label
            hgnc_id
            chromosome_band
            dosage_curation {
                report_date
                triplosensitivity_assertion {
                    score
                }
                haploinsufficiency_assertion {
                    score
                }
            }
        }
    }
}


// Dosage detail
{
    gene(iri: "HGNC:18149") {
        label
        alternative_label
        hgnc_id
        chromosome_band
        curation_activities
        dosage_curation {
            curie
            report_date
            triplosensitivity_assertion { score }
            haploinsufficiency_assertion { score }
        }
        genetic_conditions {
            disease {
                label
                iri
            }
            gene_validity_assertions {
                mode_of_inheritance
                report_date
                classification
                curie
            }
            actionability_curations {
                report_date
                source
            }
            gene_dosage_assertions {
                report_date
                assertion_type
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
                curie
            }
            specified_by {
                label
                curie
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
            iri
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
    disease('
    . 'iri: "' . $condition
    . '") {
        label
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
            score
            curie
            }
        }
    }
}


