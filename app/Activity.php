<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Uuid;
use Str;

use App\Traits\Display;

use Carbon\Carbon;
use ReturnTypeWillChange;

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
class Activity extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Display;

    /**
     * Map the json attributes to associative arrays.
     *
     * @var array
     */
    protected $casts = [
        'workflow' => 'array',
        'references' => 'array',
        'affiliation' => 'array',
        'version' => 'array',
        'changes' => 'array',
        'notes' => 'array',
        'urls' => 'array',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ident', 'type', 'subtype', 'source', 'source_uuid', 'alternate_uuid',
        'workflow', 'workflow->unpublish_date', 'activity', 'activity_string', 'references', 'affiliation',
        'version', 'changes', 'notes', 'urls', 'status'
    ];

    /**
     * Non-persistent storage model attributes.
     *
     * @var array
     */
    protected $appends = [];

    public const TYPE_NONE = 0;
    public const TYPE_PUBLISH = 1;
    public const TYPE_UNPUBLISH = 2;
    public const TYPE_RETRACT = 3;

    public const SUBTYPE_NONE = 0;
    public const SUBTYPE_CURATION = 1;
    public const SUBTYPE_TEST = 2;

    public const ACTIVITY_NONE = 0;
    public const ACTIVITY_VALIDITY = 1;
    public const ACTIVITY_DOSAGE = 2;
    public const ACTIVITY_VARIANT = 3;
    public const ACTIVITY_ACTIONABILITY = 4;

    /*
    * Type strings for display methods
    *
    * */
    protected $type_strings = [
        0 => 'Unknown',
        1 => "Publish",
        2 => "Unpublish",
        3 => "Retract",
        9 => 'Deleted'
    ];

    public const STATUS_INITIALIZED = 0;
    public const STATUS_ACTIVE = 1;
    public const STATUS_ARCHIVE = 2;
    public const STATUS_UNPUBLISH = 3;
    public const STATUS_RETRACT = 4;

    /*
    * Status strings for display methods
    *
    * */
    protected $status_strings = [
        0 => 'Initialized',
        9 => 'Deleted'
    ];


    /*
    * Reason code descriptions
    *
    * */
    protected $reason_strings = [
        'NEW_CURATION' => 'The curation is published for the first time',
        'RECURATION_NEW_EVIDENCE' => 'Recurated due to the addition of new evidence',
        'RECURATION_COMMUNITY_REQUEST' => 'Recurated on request of the community',
        'RECURATION_ERROR_SCORE_CLASS' => 'Recurated due to a scoring error',
        'RECURATION_TIMING' => 'Recurated due to a periodic requirement',
        'RECURATION_DISCREP_RESOLUTION' => 'Recurated as part of discrepency resolution',
        'RECURATION_FRAMEWORK' => 'Recurated to conform to a newer SOP',
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
     * Query scope by source_uuid
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function scopeSid($query, $id)
    {
        return $query->where('source_uuid', $id);
    }


    /**
     * Query scope by version
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function scopeVersion($query, $version)
    {
        return $query->whereJsonContains('version->internal', $version);
    }


    /**
     * Query scope by published status
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function scopePublished($query)
    {
        return $query->where('type', self::TYPE_PUBLISH);
    }


    /**
     * Query scope by active status
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function scopeDisplayable($query)
    {
        return $query->whereIn('status', [self::STATUS_ACTIVE, self::STATUS_ARCHIVE, self::STATUS_UNPUBLISH]);
    }


    public function display_reason($reason)
    {
        if (empty($reason))
            return '';

        return $this->reason_strings[$reason] ?? '';
    }


    public static function parser($message, $packet = null)
    {
        $record = json_decode($message->payload);


        if ($record->event_subtype == "TEST")
            return;

        switch ($record->event_type)
        {
            case 'PUBLISH':
                $type = self::TYPE_PUBLISH;
                break;
            case 'UNPUBLISH':
                $type = self::TYPE_UNPUBLISH;
                $status = self::STATUS_UNPUBLISH;
                //$record->references->source_uuid = $record->references->alternate_uuid; //handle bug in current validity data
                break;
            case 'RETRACT':
                $type = self::TYPE_RETRACT;
                $status = self::STATUS_RETRACT;
                break;
            default:
                $type = self::TYPE_NONE;
        }

        switch ($record->event_subtype)
        {
            case 'CURATION':
                $subtype = self::SUBTYPE_CURATION;
                break;
            default:
                $subtype = self::SUBTYPE_NONE;
        }


        switch ($record->activity)
        {
            case 'VALIDITY':
                $activity = self::ACTIVITY_VALIDITY;
                $source_prefix = 'CGGV:assertion_';                                 // handle bug in current validity data
                $activity_string = 'GENE_DISEASE_VALIDITY';
                //$record->version->reasons = array($record->version->reasons);       // handle bug in current validity data
                break;
            case 'ACTIONABILITY':
                $activity = self::ACTIVITY_ACTIONABILITY;
                $source_prefix = '';
                $activity_string = $record->activity;
                break;
            case 'DOSAGE':
                $activity = self::ACTIVITY_DOSAGE;
                $source_prefix = '';
                $activity_string = $record->activity;
                break;
            case 'VARIANT':
                $activity = self::ACTIVITY_VARIANT;
                $source_prefix = '';
                $activity_string = $record->activity;
                break;
            default:
                $activity = self::ACTIVITY_NONE;
                $source_prefix = '';
                $activity_string = 'UNKNOWN';
        }

        $iri = $record->references->alternate_uuid ?? null;

        if ($iri === null)
            return;

        // if unpublish, process the affected record now
        if ($type == self::TYPE_UNPUBLISH  || $type == self::TYPE_RETRACT)
        {
            $a = self::sid($source_prefix . basename($record->references->source_uuid))
                            ->version($record->version->internal)
                            ->where('status', self::STATUS_ACTIVE)
                            ->first();
            if ($a !== null)
            {
                $workflow = $a->workflow;
                $workflow['unpublish_date'] = $record->workflow->unpublish_date;
                $a->update(['status' => $status,
                            'workflow' => $workflow]);
            }
            else
                dd($record);
        }
        
        // save old ones to later archive
        $old_activity = self::sid($source_prefix . basename($record->references->source_uuid))
                                ->where('status', self::STATUS_ACTIVE)
                                ->get();

        $record = new Activity([
            'type' => $type,
            'subtype' => $subtype,
            'workflow' => $record->workflow,
            'source' => $record->source,
            'activity' => $activity,
            'activity_string' => $activity_string,
            'source_uuid' => isset($record->references->source_uuid) ? $source_prefix . basename($record->references->source_uuid) : null,
            'alternate_uuid' => $record->references->alternate_uuid ?? null,
            'references' => $record->references,
            'affiliation' => $record->affiliation,
            'version' => $record->version,
            'changes' => $record->changes ?? [],
            'notes' => $record->notes ?? null,
            'urls' => $record->urls ?? null,
            'status' => self::STATUS_ACTIVE
        ]);

        $record->save();
 
        // archive the older curations
        $old_activity->each(function ($item) {
             $item->update(['status' => Activity::STATUS_ARCHIVE]);
        });
     }
 

    public static function initialize()
    {
        /*$stream = Stream::where('name', 'gene-precuration')->first();

        if ($stream === null)
            return;

        
        $new = new Stream([
            'type' => 1,
            'name' => 'all-curation-events',
            'description' => 'All Curation Events Kafka Stream',
            'endpoint' => $stream->endpoint,
            'username' => $stream->username,
            'password' => $stream->password,
            'topic' => 'all-curation-events',
            'offset' => 0,
            'parser' => 'App\Activity::parser',
            'status' => 1
        ]);

        $new->save();*/

        
        $record = new Activity([
            'type' => self::TYPE_PUBLISH,
            'subtype' => self::SUBTYPE_CURATION,
            'workflow' => [
                'classification_date' => '2022-05-24T07:02:34-05:00',
                'publish_date' => '2024-04-02T07:02:34-05:00',
                'original_publis_date' => '2019-11-19T12:34:02-05:00'
            ],
            'source' => 'GENEGRAPH',
            'source_uuid' => 'CGGV:assertion_375ffa04-a8d9-427e-8948-f913bc8ff68d-2022-05-24T214158',
            'alternate_uuid' => '6e14e6fb-aef7-4c97-9e06-a60e6ffcf64b',
            'activity' => self::ACTIVITY_VALIDITY,
            'activity_string' => 'GENE_DISEASE_VALIDITY',
            'references' => [
                'source_uuid' => 'CGGV:assertion_375ffa04-a8d9-427e-8948-f913bc8ff68d-2022-05-24T214158',
                'dx_location' => 'gene-validity-raw',
                'alternate_uuid' => '6e14e6fb-aef7-4c97-9e06-a60e6ffcf64b',
                'additional_properties' =>  []
            ],
            'affiliation' => [
                'affiliate_id' => '40072',
                'affiliate_name' => 'Retinal Gene Curation Expert Panel'
            ],
            'version' => [
                'display' => '4.0',
                'internal' =>  '4.0.0',
                'reasons' => ['RECURATION_NEW_EVIDENCE'],
                'description' => 'New evidence published in PMID 123456 providing justification for reassessing this gene disease',
                'additional_properties' => []
            ],
            'changes' => [ [
                'change_code' => 'CLASSIFICATION_CHANGE',
                'attribute' =>  'classification',
                'from' => 'Supportive',
                'to' => 'Definitive'
            ]
            ],
            'notes' => [
                'public' => 'Despite the additional evidence, the classification remained the same',
                'private' => 'Although the scores did not change, new evidence was evaluated, thus dictating the new version'
            ],
            'urls' => [
                'source' =>  ''
            ],
            'status' => self::STATUS_ACTIVE
        ]);
        $record->save();

        $record = new Activity([
            'type' => self::TYPE_PUBLISH,
            'subtype' => self::SUBTYPE_CURATION,
            'workflow' => [
                'classification_date' => '2022-05-02T07:02:34-05:00',
                'publish_date' => '2024-04-02T07:02:34-05:00',
                'original_publis_date' => '2019-11-19T12:34:02-05:00'
            ],
            'source' => 'GENEGRAPH',
            'activity' => self::ACTIVITY_VALIDITY,
            'activity_string' => 'GENE_DISEASE_VALIDITY',
            'source_uuid' => 'CGGV:assertion_375ffa04-a8d9-427e-8948-f913bc8ff68d-2022-05-24T214158',
            'alternate_uuid' => '6e14e6fb-aef7-4c97-9e06-a60e6ffcf64b',
            'references' => [
                'source_uuid' => 'CGGV:assertion_375ffa04-a8d9-427e-8948-f913bc8ff68d-2022-05-24T214158',
                'dx_location' => 'gene-validity-raw',
                'alternate_uuid' => '6e14e6fb-aef7-4c97-9e06-a60e6ffcf64b',
                'additional_properties' =>  []
            ],
            'affiliation' => [
                'affiliate_id' => '40072',
                'affiliate_name' => 'Retinal Gene Curation Expert Panel'
            ],
            'version' => [
                'display' => '3.0',
                'internal' =>  '3.0.0',
                'reasons' => ['RECURATION_TIMING'],
                'description' => 'New evidence published in PMID 123456 providing justification for reassessing this gene disease',
                'additional_properties' => []
            ],
            'changes' => [
                [
                'change_code' => 'SOP_CHANGE',
                'attribute' =>  'sop',
                'from' => 'SOP7',
                'to' => 'SOP10'
                ]
            ],
            'notes' => [
                'public' => 'Recuration due to periodic requirement.  The classification remains unchanged.',
                'private' => ''
            ],
            'urls' => [
                'source' =>  ''
            ],
            'status' => self::STATUS_ARCHIVE
        ]);
        $record->save();

        $record = new Activity([
            'type' => self::TYPE_PUBLISH,
            'subtype' => self::SUBTYPE_CURATION,
            'workflow' => [
                'classification_date' => '2022-05-22T07:02:34-05:00',
                'publish_date' => '2024-04-02T07:02:34-05:00',
                'original_publis_date' => '2019-11-19T12:34:02-05:00'
            ],
            'source' => 'GENEGRAPH',
            'activity' => self::ACTIVITY_VALIDITY,
            'activity_string' => 'GENE_DISEASE_VALIDITY',
            'source_uuid' => 'CGGV:assertion_375ffa04-a8d9-427e-8948-f913bc8ff68d-2022-05-24T214158',
            'alternate_uuid' => '6e14e6fb-aef7-4c97-9e06-a60e6ffcf64b',
            'references' => [
                'source_uuid' => 'CGGV:assertion_375ffa04-a8d9-427e-8948-f913bc8ff68d-2022-05-24T214158',
                'dx_location' => 'gene-validity-raw',
                'alternate_uuid' => '6e14e6fb-aef7-4c97-9e06-a60e6ffcf64b',
                'additional_properties' =>  []
            ],
            'affiliation' => [
                'affiliate_id' => '40072',
                'affiliate_name' => 'Retinal Gene Curation Expert Panel'
            ],
            'version' => [
                'display' => '2.0',
                'internal' =>  '2.0.0',
                'reasons' => ['RECURATION_FRAMEWORK'],
                'description' => 'New evidence published in PMID 123456 providing justification for reassessing this gene disease',
                'additional_properties' => []
            ],
            'changes' => [
                [
                'change_code' => 'EXPERT_PANEL_CHANGE',
                'attribute' =>  'affiliate',
                'from' => '',
                'to' => 'Retina'
                ]
            ],
            'notes' => [
                'public' => 'Recurated to SOP8.  Classification is unchanged',
                'private' => ''
            ],
            'urls' => [
                'source' =>  ''
            ],
            'status' => self::STATUS_ARCHIVE
        ]);
        $record->save();

        $record = new Activity([
            'type' => self::TYPE_PUBLISH,
            'subtype' => self::SUBTYPE_CURATION,
            'workflow' => [
                'classification_date' => '2021-12-02T07:02:34-05:00',
                'publish_date' => '2021-09-06T07:02:34-05:00',
                'original_publis_date' => '2019-11-19T12:34:02-05:00'
            ],
            'source' => 'GENEGRAPH',
            'activity' => self::ACTIVITY_VALIDITY,
            'activity_string' => 'GENE_DISEASE_VALIDITY',
            'source_uuid' => 'CGGV:assertion_375ffa04-a8d9-427e-8948-f913bc8ff68d-2022-05-24T214158',
            'alternate_uuid' => '6e14e6fb-aef7-4c97-9e06-a60e6ffcf64b',
            'references' => [
                'source_uuid' => 'CGGV:assertion_375ffa04-a8d9-427e-8948-f913bc8ff68d-2022-05-24T214158',
                'dx_location' => 'gene-validity-raw',
                'alternate_uuid' => '6e14e6fb-aef7-4c97-9e06-a60e6ffcf64b',
                'additional_properties' =>  []
            ],
            'affiliation' => [
                'affiliate_id' => '40072',
                'affiliate_name' => 'Retinal Gene Curation Expert Panel'
            ],
            'version' => [
                'display' => '1.0',
                'internal' =>  '1.0.0',
                'reasons' => ['NEW_CURATION'],
                'description' => '',
                'additional_properties' => []
            ],
            'changes' => [
            ],
            'notes' => [
                'public' => '',
                'private' => ''
            ],
            'urls' => [
                'source' =>  ''
            ],
            'status' => self::STATUS_ARCHIVE
        ]);
        $record->save();
        
    }
}
