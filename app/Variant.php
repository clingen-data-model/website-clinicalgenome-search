<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Display;

use Uuid;

/**
 *
 * @category   Model
 * @package    Search
 * @author     P. Weller <pweller1@geisinger.edu>
 * @copyright  2020 Geisinger
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/PackageName
 * @see        NetOther, Net_Sample::Net_Sample()
 * @since      Class available since Release 1.0.0
 *
 * */
class Variant extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Display;

    /**
     * The attributes that should be validity checked.
     *
     * @var array
     */
    public static $rules = [
		'ident' => 'alpha_dash|max:80|required',
		'iri' => 'striing|max:80|required',
		'variant_id' => 'string|nullable',
		'caid' => 'string|nullable',
        'condition' => 'json|nullable',
        'evidence_links' => 'json|nullable',
        'gene' => 'json|nullable',
        'guidelines' => 'json|nullable',
        'hgvs' => 'json|nullable',
		'type' => 'integer',
		'status' => 'integer'
	];

	/**
     * Map the json attributes to associative arrays.
     *
     * @var array
     */
	protected $casts = [
			'caid' => 'array',
            'condition' => 'array',
            'evidence_links' => 'array',
            'gene' => 'array',
            'guidelines' => 'array',
            'hgvs' => 'array',
		];

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = ['iri', 'variant_id', 'caid', 'condition', 'evidence_links',
					        'published_date', 'gene', 'guidelines', 'hgvs', 'type', 'status'
                         ];

	/**
     * Non-persistent storage model attributes.
     *
     * @var array
     */
     protected $appends = ['display_date', 'list_date', 'display_status'];

     public const TYPE_NONE = 0;
     public const TYPE_OBSOLETE = 1;

     /*
     * Type strings for display methods
     *
     * */
     protected $type_strings = [
	 		0 => 'Unknown',
	 		9 => 'Deleted'
	];

     public const STATUS_INITIALIZED = 0;

     /*
     * Status strings for display methods
     *
     * */
     protected $status_strings = [
	 		0 => 'Initialized',
	 		9 => 'Deleted'
	];


	/**
     * Automatically assign an ident on instantiation
     *
     * @param	array	$attributes
     * @return 	void
     */
     public function __construct(array $attributes = array())
     {
        $this->attributes['ident'] = (string) Uuid::generate(4);
        parent::__construct($attributes);
	}


	/**
     * Query scope by ident
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeIdent($query, $ident)
     {
		return $query->where('ident', $ident);
     }


    /**
     * Query scope by symbol name
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeIri($query, $curie)
    {
		return $query->where('iri', $curie);
    }


     /**
     * Query scope by symbol or condition name
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public static function sortByClassifications($symbol, $disease = false)
    {
        $classifications = [
            'Pathogenic' => 0,
            'Likely Pathogenic' => 0,
            'Uncertain Significance' => 0,
            'Likely Benign' => 0,
            'Benign' => 0
        ];

        if (!$disease)
        {
            $records = self::where('gene->label', $symbol)->get();
        }
        else
        {
            $records = self::where('condition->@id', $symbol)->get();
        }

        if (empty($records))
            return [$symbol => ['classifications' => $classifications,
                                'panels' => []]];

        $genelist = [];

        foreach ($records as $record)
        {
            $tag = ($disease ? $record->gene['label'] : $record->condition['label']);

            if (!isset($genelist[$tag]))
            {
                 // deal with some bad records coming from the erepo that contain no gene data
                 if ($disease && !isset($record->gene["NCBI_id"]))
                    continue;
                
                $genelist[$tag] = [ 'id' => ($disease ? $record->gene['NCBI_id'] : $record->condition['@id']),
                                    'obsolete' => ($record->type == self::TYPE_OBSOLETE),
                                    'classifications' => $classifications,
                                    'panels' => []];
            }

            $a =& $genelist[$tag]['classifications'];
            $b =& $genelist[$tag]['panels'];

            foreach ($record->guidelines as $guideline)
            {
                if (isset($a[$guideline["outcome"]["label"]]))
                    $a[$guideline["outcome"]["label"]]++;

                foreach($guideline['agents'] as $agent)
                {
                    if (!in_array($agent["affiliation"], array_column($b, 'affiliation')))
                        $b[] = ['affiliation' => $agent["affiliation"], 'id' => $agent['@id']] ;
                }
            }
            //$genelist[$record->gene['label']] = $a;
        }
        return $genelist;
    }


    /**
     * Map a variant pathogenicity record to a curation
     *
     */
    public static function parser($message, $packet)
    {
        $record = json_decode($message->payload);

        //dd($record);

        // process unpublish requests
        if ($record->statusPublishFlag == "Unpublish")
        {
            if (!isset($record->id))
                die("Cannot unplublish id");//echo "Unpublish request with no iri \n";
            else
            {
                $old_curations = Curation::variant()->where('document', basename($record->interpretation->id))
                                                  ->where('status', '!=', Curation::STATUS_ARCHIVE)
                                                  ->get();

                $old_curations->each(function ($item) {
                    $item->update(['status' => Curation::STATUS_ARCHIVE]);
                });
               
            }

            return;
        }

        if($record->statusPublishFlag != "Publish")
            dd($record);

            // save old ones to later archive
        $old_curations = Curation::variant()->where('document', basename($record->interpretation->id))
                                            ->where('status', '!=', Curation::STATUS_ARCHIVE)
                                            ->get();

        // it seems that some records are published without a condition...
        if (isset($record->interpretation->condition))
            $disease = Disease::curie($record->interpretation->condition[0]->disease[0]->id)->first();
        else
            $disease = null;

        $panel = Panel::allids(basename($record->interpretation->contribution[0]->agent->id))->first();

        // there is no nice way to extract the approval and publish dates.  We have to parse contributions
        foreach($record->interpretation->contribution as $contributor)
        {
            if ($contributor->contributionRole->label = "publisher")
                $approval_date = $contributor->contributionDate;

            if ($contributor->contributionRole->label = "approver")
                $publish_date = $contributor->contributionDate;
        }

        // the gene reference is deep within the evidence.  Find it
        foreach($record->interpretation->evidenceLine as $evidence)
        {
            foreach($evidence->evidenceItem as $item)
            {
                if (isset($item->variant->relatedContextualAllele))
                {
                    foreach($item->variant->relatedContextualAllele as $allele)
                    {
                        if (isset($allele->relatedGene))
                            $gene_info = $allele->relatedGene;
                    }
                }
            }
        }

        if (isset($gene_info->id))
            $gene = Gene::entrez(substr($gene_info->id, 9))->first();
        else
            $gene = Gene::name($gene_info->label)->first();


        // finally, we can build the model
        $data = [
            'type' => Curation::TYPE_VARIANT_PATHOGENICITY,
            'type_string' => 'Variant Pathogenicity',
            'subtype' => Curation::SUBTYPE_VARIANT_PATHOGENICITY,
            'subtype_string' => 'Variant Pathogenicity',
            'group_id' => 0,
            'sop_version' => $record->interpretation->assertionMethod->label,
            'curation_version' => null,
            'source' => 'variant_interpretation',
            'source_uuid' => $message->key,
            'source_timestamp' => $message->timestamp,
            'source_offset' => $message->offset,
            'packet_id' => $packet->id,
            'message_version' => null,
            'assertion_uuid' => basename($record->interpretation->id),
            'alternate_uuid' => null,
            'panel_id' => $panel->id ?? null,
            'affiliate_id' => basename($record->interpretation->contribution[0]->agent->id),
            'affiliate_details' => $record->interpretation->contribution[0]->agent,
            'gene_id' => $gene->id,
            'gene_hgnc_id' => $gene->hgnc_id,
            'gene_details' => $gene_info,
            'variant_iri' => $record->interpretation->variant,
            'variant_details' => null,
            'document' => basename($record->interpretation->id),
            'context' => null,
            'title' => null,
            'summary' => null,
            'description' => $record->interpretation->description,
            'comments' => null,
            'disease_id' => $disease->id ?? null,
            'conditions' => (isset($record->interpretation->condition) ? [$record->interpretation->condition[0]->disease[0]->id] : []),
            'condition_details' => [$record->interpretation->condition ?? null],
            'evidence' => null,
            'evidence_details' => $record->interpretation->evidenceLine,
            'assertions' => null,
            'scores' => ['classification' => $record->interpretation->statementOutcome->label ?? null],
            'score_details' => $record->interpretation->statementOutcome,
            'curators' => null,
            'published' => ($record->statusPublishFlag == "Publish"),
            'animal_model_only' => false,
            'events' => ['approval_date' => $approval_date ?? null,
                         'publish_date' => $publish_date ?? null
                        ],
            'url' => [],
            'version' => 1,
            'status' => Curation::STATUS_ACTIVE
        ];

        $curation = new Curation($data);

        // adjust the version number
        
        $curation->save();
        
        $old_curations->each(function ($item) {
            $item->update(['status' => Curation::STATUS_ARCHIVE]);
        });
    }
}
