<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Display;

use Uuid;
use Carbon\Carbon;

use App\Jirafield;

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
class Curation extends Model
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
            'type' => 'integer',
            'type_string' => 'string|nullable',
            'subtype' => 'integer',
            'subtype_string' => 'string|nullable',
            'group_id' => 'integer',
            'sop_version' => 'string|nullable',
            'source' => 'string|nullable',
            'source_uuid' => 'string|nullable',
            'assertion_uuid' => 'string|nullable',
            'alternate_uuid' => 'string|nullable',
            'affiliate_id' => 'string|nullable',
            'affiliate_details' => 'json|nullable',
            'gene_hgnc_id' => 'string|nullable',
            'gene_details' => 'json|nullable',
            'title' => 'string|nullable',
            'summary' => 'text|nullable',
            'description' => 'text|nullable',
            'comments' => 'text|nullable',
            'conditions' => 'json|nullable',
            'condition_details' => 'json|nullable',
            'evidence' => 'json|nullable',
            'evidence_details' => 'json|nullable',
            'scores' => 'json|nullable',
            'score_details' => 'json|nullable',
            'curators' => 'json|nullable',
            'published' => 'boolean',
            'animal_model_only' => 'boolean',
            'events' => 'json|nullable',
            'version' => 'integer',
            'status' => 'integer'
	];

	/**
     * Map the json attributes to associative arrays.
     *
     * @var array
     */
	protected $casts = [
                'gene_details' => 'array',
                'affiliate_details' => 'array',
                'condition_details' => 'array',
                'conditions' => 'array',
                'evidence_details' => 'array',
                'evidence' => 'array',
                'score_details' => 'array',
                'scores' => 'array',
                'curators' => 'array',
                'events' => 'array'

	];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = ['ident', 'type', 'type_string', 'subtype', 'subtype_string', 'group_id',
                            'sop_version', 'source', 'source_uuid', 'assertion_uuid', 'alternate_uuid',
                            'affiliate_id', 'affiliate_details', 'gene_hgnc_id', 'gene_details', 'title',
                            'summary', 'description', 'comments', 'conditions', 'condition_details',
                            'evidence', 'evidence_details', 'scores', 'score_details', 'curators',
                            'published', 'animal_model_only', 'events', 'version', 'status'
                         ];

	/**
     * Non-persistent storage model attributes.
     *
     * @var array
     */
    protected $appends = ['display_date', 'list_date', 'display_status'];

    public const TYPE_NONE = 0;
    public const TYPE_DOSAGE_SENSITIVITY = 1;
    public const TYPE_GENE_VALIDITY = 2;
    public const TYPE_VARIANT_PATHOGENICITY = 3;

    /*
     * Type strings for display methods
     *
     * */
    protected $type_strings = [
	 		0 => 'Unknown',
	 		1 => 'Dosage Sensitivity'
        ];

    public const STATUS_INITIALIZED = 0;
    public const STATUS_ACTIVE = 1;

    /*
     * Status strings for display methods
     *
     * */
    protected $status_strings = [
	 		0 => 'Initialized',
            1=> 'Active',
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
     * Query scope by type
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeType($query, $type)
    {
		return $query->where('type', $type);
    }


    /**
     * Query scope by source
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeSource($query, $str)
    {
		return $query->where('source', $str);
    }


     /**
     * Query scope by source ID
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeSid($query, $id)
    {
		return $query->where('source_uuid', $id);
    }


     /**
     * Query scope by subtype
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeSub($query, $sub)
    {
		return $query->where('subtype', $sub);
    }


     /**
     * Query scope by published
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopePublished($query)
    {
		return $query->where('published', true);
    }


    /**
     * Parse a gene disease validity record
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public static function parse_gene_validity($record)
    {
        //if (isset($record->iri) && $record->iri == "1e576e58-debf-49c6-a86d-c599806180ff")
        //if (isset($record->iri) && $record->iri == "992d2cd7-5305-4278-9601-3e59ac1a8770")
            //dd($record);

        //return;

        if ($record->sopVersion != "8" && $record->sopVersion != "6" && $record->sopVersion != "7")
            dd($record);

        // process unpublish requests
        if ($record->statusPublishFlag == "Unpublish")
        {
            if (!isset($record->iri))
                ;//echo "Unpublish request with no iri \n";
            else
            {
                $curation = self::type(self::TYPE_GENE_VALIDITY)->source('gene_validity')
                                    ->sid($record->iri)->published()->orderBy('id', 'desc')->first();

                if ($curation !== null)
                    $curation->update(['published' => false]);
                else
                   ;// echo "Unpublish request for iri " . $record->iri . " not found \n";
            }

            return;
        }

        $curation = self::type(self::TYPE_GENE_VALIDITY)->source('gene_validity')
                                    ->sid($record->iri ?? '**NO IRI**')->orderBy('id', 'desc')->first();

        // if no animal flag, make the calculation

        if ($curation === null)
        {
            //dd($record);
            $curation = new Curation([
                                'type' => self::TYPE_GENE_VALIDITY,
                                'type_string' => 'Gene-Disease Validity',
                                'subtype' => 0,
                                'subtype_string' => null,
                                'group_id' => 0,
                                'sop_version' => $record->sopVersion,
                                'source' => 'gene_validity',
                                'source_uuid' => $record->iri ?? null,
                                'assertion_uuid' => $record->report_id ?? null,
                                'alternate_uuid' => null,
                                'affiliate_id' => $record->affiliation->gcep_id ?? $record->affiliation->id,
                                'affiliate_details' => $record->affiliation,
                                'gene_hgnc_id' => $record->genes[0]->curie,
                                'gene_details' => $record->genes,
                                'title' => $record->title,
                                'summary' => null,
                                'description' => null,
                                'comments' => null,
                                'conditions' => $record->conditions[0]->curie,
                                'condition_details' => $record->conditions,
                                'evidence' => null,
                                'evidence_details' => null,
                                'scores' => ['FinalClassification' => $record->scoreJson->summary->FinalClassification],
                                'score_details' => $record->scoreJson,
                                'curators' => null,
                                'published' => ($record->statusPublishFlag == "Publish"),
                                'animal_model_only' => (isset($record->scoreJson->summary->AnimalModelOnly) ? $record->scoreJson->summary->AnimalModelOnly == "YES" : false),
                                'contributors' => $record->scoreJson->summary->contributors ?? null,
                                'events' => null,
                                'version' => 1,
                                'status' => self::STATUS_ACTIVE
                            ]);

            $curation->save();
        }
        else
        {
            echo "Updating existing curation " . $curation->id . " \n";
           // dd($record);
            $curation->update([
                                'type' => self::TYPE_GENE_VALIDITY,
                                'type_string' => 'Gene-Disease Validity',
                                'subtype' => 0,
                                'subtype_string' => null,
                                'group_id' => 0,
                                'sop_version' => $record->sopVersion,
                                'source' => 'gene_validity',
                                'source_uuid' => $record->iri ?? null,
                                'assertion_uuid' => $record->report_id ?? null,
                                'alternate_uuid' => null,
                                'affiliate_id' => $record->affiliation->gcep_id ?? $record->affiliation->id,
                                'affiliate_details' => $record->affiliation,
                                'gene_hgnc_id' => $record->genes[0]->curie,
                                'gene_details' => $record->genes,
                                'title' => $record->title,
                                'summary' => null,
                                'description' => null,
                                'comments' => null,
                                'conditions' => $record->conditions[0]->curie,
                                'condition_details' => $record->conditions,
                                'evidence' => null,
                                'evidence_details' => null,
                                'scores' => ['FinalClassification' => $record->scoreJson->summary->FinalClassification],
                                'score_details' => $record->scoreJson,
                                'curators' => null,
                                'published' => ($record->statusPublishFlag == "Publish"),
                                'animal_model_only' => (isset($record->scoreJson->summary->AnimalModelOnly) ? $record->scoreJson->summary->AnimalModelOnly == "YES" : false),
                                'contributors' => $record->scoreJson->summary->contributors ?? null,
                                'events' => null,
                                'version' => $curation->version + 1,
                                'status' => self::STATUS_ACTIVE
                            ]);

        }
    }

    /**
     * Parse a gene disease validity record raw record
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public static function parse_gene_validity_raw($record)
    {
        dd($record);
    }



}
