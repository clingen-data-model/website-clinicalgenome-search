<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActionabilitySummary extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'docId ',
        'iri',
        'latestSearchDate',
        'lastUpdated',
        'lastAuthor',
        'context',
        'contextIri',
        'release',
        'releaseDate',
        'gene',
        'geneOmim',
        'disease',
        'omim',
        'status-overall',
        'status_stg1',
        'status_stg2',
        'status_scoring',
        'outcome',
        'outcomeScoringGroup',
        'intervention',
        'interventionScoringGroup',
        'scorer',
        'prelim_severity',
        'prelim_likelihood',
        'prelim_natureOfIntervention',
        'prelim_effectiveness',
        'final_severity',
        'final_likelihood',
        'final_natureOfIntervention',
        'final_effectiveness',
        'overall',
        'severity',
        'likelihood',
        'natureOfIntervention',
        'effectiveness',
        'overall',
        'consensusAssertion'
    ];
}
