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
                'variant_details' => 'array',
                'affiliate_details' => 'array',
                'condition_details' => 'array',
                'conditions' => 'array',
                'evidence_details' => 'array',
                'evidence' => 'array',
                'score_details' => 'array',
                'scores' => 'array',
                'curators' => 'array',
                'events' => 'array',
                'assertions' => 'array',
                'url' => 'array'

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
                            'published', 'animal_model_only', 'events', 'version', 'status',
                            'curation_version', 'panel_id', 'source_timestamp', 'source_offset', 'message_version',
                            'url', 'assertions', 'document', 'variant_iri', 'variant_details', 
                            'gene_id', 'disease_id', 'packet_id', 'context'
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
    public const TYPE_ACTIONABILITY = 4;

    /*
     * Type strings for display methods
     *
     * */
    protected $type_strings = [
	 		0 => 'Unknown',
	 		1 => 'Dosage Sensitivity'
        ];

    public const SUBTYPE_NONE = 0;
    public const SUBTYPE_ACTIONABILITY = 1;
    public const SUBTYPE_VALIDITY_GCI = 2;
    public const SUBTYPE_VALIDITY_GCE = 3;
    public const SUBTYPE_VALIDITY_GGP = 4;
    public const SUBTYPE_DOSAGE_DCI = 10;
    public const SUBTYPE_DOSAGE_GGP = 11;
    public const SUBTYPE_VARIANT_PATHOGENICITY = 20;

    /*
    * Status constants and strings for display methods
    *
    *
    */
    public const STATUS_INITIALIZED = 0;
    public const STATUS_ACTIVE = 1;
    public const STATUS_DEPRECATED = 2;
    public const STATUS_ARCHIVE = 3;
    public const STATUS_RETRACTED = 4;
    public const STATUS_PRELIMINARY = 5;
    public const STATUS_ACTIVE_REVIEW = 6;
    public const STATUS_DELETED = 9;
    public const STATUS_OPEN = 10;
    public const STATUS_PRIMARY_REVIEW = 11;
    public const STATUS_SECONDARY_REVIEW = 12;
    public const STATUS_GROUP_REVIEW = 14;
    public const STATUS_CLOSED = 20;
    public const STATUS_REOPENED = 30;


    /*
    * Status strings for display methods
    *
    * */
    protected $status_strings = [
	 		0 => 'Initialized',
            1 => 'Active',
            2 => 'Deprecated',
	 		9 => 'Deleted',
            // DCI Workflows
            10 => 'Open',
            11 => 'Under Primary Review',
            12 => 'Under Secondary Review',
            14 => 'Under Group Review',
            20 => 'Closed',
            30 => 'Reopened'
	];

    protected static $dci_status_keys = [
            'Open' => 10,
            'Under Primary Review' => 11,
            'Under Secondary Review' => 12,
            'Under Group Review' => 14,
            'Closed' => 20,
            'Reopened' => 30
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


    /*
     * The gene associated with this curation
     */
    public function gene()
    {
       return $this->belongsTo('App\Gene');
    }


    /*
     * The disease associated with this curation
     */
    public function disease()
    {
       return $this->belongsTo('App\Disease');
    }


    /*
     * The kafka message packet associated with this curation
     */
    public function packet()
    {
       return $this->belongsTo('App\Packet');
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
     * Query scope by type = dosage
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeDosage($query)
    {
		return $query->where('type', self::TYPE_DOSAGE_SENSITIVITY);
    }


    /**
     * Query scope by type = actionability
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeActionability($query)
    {
		return $query->where('type', self::TYPE_ACTIONABILITY);
    }


    /**
     * Query scope by type = validity
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeValidity($query)
    {
		return $query->where('type', self::TYPE_GENE_VALIDITY);
    }


    /**
     * Query scope by type = variant pathogenicity
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeVariant($query)
    {
		return $query->where('type', self::TYPE_VARIANT_PATHOGENICITY);
    }


    /**
     * Query scope by type = validity
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeActive($query)
    {
		return $query->whereIn('status', [self::STATUS_ACTIVE, self::STATUS_ACTIVE_REVIEW]);
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
     * Query scope by alternate ID
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeAid($query, $id)
    {
		return $query->where('alternate_uuid', $id);
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
     * Query scope by record status
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeStatus($query, $type)
    {
		return $query->where('status', $type);
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


    /**
     * Return enumerated value for curation activity workflow status
     *
     * @param string $str
     * @param integer $type
     * @return integer
     */
    public static function map_activity_status($str, $activity)
    {
        switch ($activity)
        {
            case self::TYPE_DOSAGE_SENSITIVITY:
                return self::$dci_status_keys[$str] ?? 0;
        }

        return 0;
    }



}
