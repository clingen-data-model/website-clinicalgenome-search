<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Support\Facades\Log;

use App\Traits\Display;

use Uuid;
use Carbon\Carbon;

use App\GeneLib;
use App\Change;
use App\Gene;
use App\Curation;
use App\Panel;

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
class Actionability extends Model
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
        'report_date' => 'timestamp',
        'gene_label' => 'string',
        'gene_hgnc_id' => 'string',
        'disease_label' => 'string',
        'disease_mondo' => 'string',
        'report_date' => 'timestamp',
        'source' => 'string',
        'other' => 'json',
        'classification' => 'string',
        'specified_by' => 'string',
        'attributed_to' => 'string',
        'version' => 'integer',
		'type' => 'integer',
		'status' => 'integer'
	];

	/**
     * Map the json attributes to associative arrays.
     *
     * @var array
     */
	protected $casts = [
			'haplo_other' => 'array',
            'triplo_other' => 'array'
		];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = ['ident', 'disease_label', 'disease_mondo',
                            'adult_report_date', 'adult_classification', 'adult_attrobuted_to', 'adult_source',
                            'pediatric_report_date', 'pediatric_classification', 'pediatric_attrobuted_to', 'pediatric_source',
                            'gene_label', 'gene_hgnc_id', 'other',
                            'version', 'type', 'status',
                         ];

	/**
     * Non-persistent storage model attributes.
     *
     * @var array
     */
    protected $appends = ['display_date', 'list_date', 'display_status'];

    public const TYPE_NONE = 0;

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
     * Get the change
     */
    public function oldchange()
    {
        return $this->morphOne(Change::class, 'old');
    }


    /**
     * Get the change
     */
    public function newchange()
    {
        return $this->morphOne(Change::class, 'new');
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
     * Query scope by hgnc id
     *
     * @@param	string	$hgnc
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeHgnc($query, $hgnc)
    {
		return $query->where('gene_hgnc_id', $hgnc);
    }


    /**
     * Query scope by mondo
     *
     * @@param	string	$mondo
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeMondo($query, $mondo)
    {
		return $query->where('disease_mondo', $mondo);
    }


    /**
     * Retrieve, compare, and load a fresh dataset
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function assertions()
    {
        $assertions = GeneLib::actionabilityList([
                                            'page' => 0,
                                            'pagesize' => "null",
                                            'sort' => 'GENE_LABEL',
                                            'search' => null,
                                            'direction' => 'ASC',
                                            'report' => true,
                                            'curated' => false
                                        ]);

        if (empty($assertions))
            die ("Failure to retrieve new data");

        // clear out the status field

        // compare and update
        foreach ($assertions->collection as $gene)
        {
            foreach($gene->genetic_conditions as $condition)
            {
                $current = Actionability::hgnc($gene->hgnc_id)->mondo($condition->disease->curie)->orderBy('version', 'desc')->first();

                Log::info('Actionabilty.assess: ' . $gene->label);
                if ($current === null)          // new assertion
                {
                    $new = new Actionability([
                                        'gene_label' => $gene->label,
                                        'gene_hgnc_id' => $gene->hgnc_id,
                                        'disease_label' => $condition->disease->label ?? null,
                                        'disease_mondo' => $condition->disease->curie ?? null,
                                        'adult_report_date' => null,
                                        'adult_source' => null,
                                        'adult_attributed_to' => null,
                                        'adult_classification' => null,
                                        'pediatric_report_date' => null,
                                        'pediatric_source' => null,
                                        'pediatric_attributed_to' => null,
                                        'pediatric_classification' => null,
                                        'other' => null,
                                        'version' => 1,
                                        'type' => 1,
                                        'status' => 1
                                    ]);

                    // Map the proper adult and ped fields
                    foreach ($condition->actionability_assertions as $assertion)
                    {
                        if ($assertion->attributed_to->label == "Adult Actionability Working Group")
                        {
                            $new->adult_report_date = (empty($assertion->report_date) ? null : Carbon::parse($assertion->report_date)->format('Y-m-d H:i:s.0000'));
                            $new->adult_source = $assertion->source;
                            $new->adult_attributed_to = $assertion->attributed_to->label;
                            $new->adult_classification = $assertion->classification->label;

                        }
                        if ($assertion->attributed_to->label == "Pediatric Actionability Working Group")
                        {
                            $new->pediatric_report_date = (empty($assertion->report_date) ? null : Carbon::parse($assertion->report_date)->format('Y-m-d H:i:s.0000'));
                            $new->pediatric_source = $assertion->source;
                            $new->pediatric_attributed_to = $assertion->attributed_to->label;
                            $new->pediatric_classification = $assertion->classification->label;
                        }
                    }

                    $new->save();

                    $genename = Gene::hgnc($new->gene_hgnc_id)->first();

                    Change::create([
                                     'type' => Change::TYPE_ACTIONABILITY,
                                     'category' => Change::CATEGORY_NONE,
                                     'element_id' => $genename->id,
                                     'element_type' => 'App\Gene',
                                     'old_id' => null,
                                     'old_type' => null,
                                     'new_id' => $new->id,
                                     'new_type' => 'App\Actionability',
                                     'change_date' => $new->adult_report_date > $new->pediatric_report_date ? $new->adult_report_date : $new->pediatric_report_date,
                                     'description' => ['New curation activity'],
                                     'status' => 1,
                            ]);

                    continue;
                }

                $new = new Actionability([
                                            'gene_label' => $gene->label,
                                            'gene_hgnc_id' => $gene->hgnc_id,
                                            'disease_label' => $condition->disease->label ?? null,
                                            'disease_mondo' => $condition->disease->curie ?? null,
                                            'adult_report_date' => null,
                                            'adult_source' => null,
                                            'adult_attributed_to' => null,
                                            'adult_classification' => null,
                                            'pediatric_report_date' => null,
                                            'pediatric_source' => null,
                                            'pediatric_attributed_to' => null,
                                            'pediatric_classification' => null,
                                            'other' => null,
                                            'version' => $current->version + 1,
                                            'type' => 1,
                                            'status' => 1
                                        ]);

                // Map the proper adult and ped fields
                foreach ($condition->actionability_assertions as $assertion)
                {
                    // skip over genegraph bug until it is fixed
                    if ($assertion->attributed_to === null)
                    {
                        Log::info('Actionabilty.assess: Skipping null attributed genegraph bug - ' . $assertion->source);
                        continue;
                    }
                    if ($assertion->attributed_to->label == "Adult Actionability Working Group")
                    {
                        $new->adult_report_date = (empty($assertion->report_date) ? null : Carbon::parse($assertion->report_date)->format('Y-m-d H:i:s.0000'));
                        $new->adult_source = $assertion->source;
                        $new->adult_attributed_to = $assertion->attributed_to->label;
                        $new->adult_classification = $assertion->classification->label;

                    }
                    if ($assertion->attributed_to->label == "Pediatric Actionability Working Group")
                    {
                        $new->pediatric_report_date = (empty($assertion->report_date) ? null : Carbon::parse($assertion->report_date)->format('Y-m-d H:i:s.0000'));
                        $new->pediatric_source = $assertion->source;
                        $new->pediatric_attributed_to = $assertion->attributed_to->label;
                        $new->pediatric_classification = $assertion->classification->label;
                    }
                }

                $differences = $this->compare($current, $new);

                if (!empty($differences))      // update
                {
                    //dd($new);
                    $new->save();

                    $genename = Gene::hgnc($new->gene_hgnc_id)->first();

                    Change::create([
                                     'type' => Change::TYPE_ACTIONABILITY,
                                     'category' => Change::CATEGORY_NONE,
                                     'element_id' => $genename->id,
                                     'element_type' => 'App\Gene',
                                     'old_id' =>$current->id,
                                     'old_type' => 'App\Actionability',
                                     'new_id' => $new->id,
                                     'new_type' => 'App\Actionability',
                                     'change_date' => $new->adult_report_date > $new->pediatric_report_date ? $new->adult_report_date : $new->pediatric_report_date,
                                     'status' => 1,
                                     'description' => $this->scribe($differences)
                            ]);
                }
            }
        }

        return $assertions;
    }


    /**
     * Retrieve, compare, and load a fresh dataset
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function compare($old, $new)
    {
        $old_array = $old->toArray();
        $new_array = $new->toArray();

        // unset a few fields we don't care about
        unset($old_array['id'], $old_array['ident'], $old_array['version'], $old_array['type'], $old_array['status'],
              $old_array['created_at'], $old_array['updated_at'], $old_array['deleted_at'], $old_array['display_date'],
              $old_array['list_date'], $old_array['display_status']);
        unset($new_array['id'], $new_array['ident'], $new_array['version'], $new_array['type'], $new_array['status'],
              $new_array['created_at'], $new_array['updated_at'], $new_array['deleted_at'], $new_array['display_date'],
              $new_array['list_date'], $new_array['display_status']);

        $diff = array_diff_assoc($new_array, $old_array);

        return $diff;
    }


    /**
     * Parse the content array and return a scribe version for reports
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scribe($content)
    {
        if (empty($content))
            return null;

        $annot = [];

        foreach ($content as $key => $value)
        {
            switch ($key)
            {
                case 'gene_label':
                    $annot[] = 'Gene symbol has changed';
                    break;
                case 'gene_hgnc_id':
                    $annot[] = 'Gene HGNC ID has changed';
                    break;
                case 'disease_label':
                    $annot[] = 'Disease label has changed';
                    break;
                case 'disease_mondo':
                    $annot[] = 'Disease MONDO ID has changed';
                    break;
                case 'adult_report_date':
                    $annot[] = 'Adult report date has changed';
                    break;
                case 'adult_source':
                    $annot[] = 'Adult report has changed';
                    break;
                case 'adult_classification':
                    $annot[] = 'Adult classification has changed';
                    break;
                case 'adult_attributed_to':
                    $annot[] = 'Adult Working Group has changed';
                    break;
                case 'pediatric_report_date':
                    $annot[] = 'Pediatric  report date has changed';
                    break;
                case 'pediatric_source':
                    $annot[] = 'Pediatric report has changed';
                    break;
                case 'pediatric_classification':
                    $annot[] = 'Pediatric classification has changed';
                    break;
                case 'pediatric_attributed_to':
                    $annot[] = 'Pediatric Working Group has changed';
                    break;
            }
        }

        return $annot;
    }


    /**
     * Map a kafka actionability record to a curation
     *
     */
    public static function parser($message)
    {

        $record = json_decode($message->payload);

        // what other are there?
        switch ($record->statusFlag)
        {
            case 'Released - Under Revision':
            case 'Retracted':
            case 'In Preparation':
                return;
            case 'Released':
                break;
            default:
                dd($record);
        }
        
        // because each message can have multiple curations, we'll eventuall want to break them up.

        // parse into standard structure
        $data = [
                'type' => Curation::TYPE_ACTIONABILITY,
                'type_string' => 'Actionability',
                'subtype' => Curation::SUBTYPE_ACTIONABILITY,
                'subtype_string' => $record->curationType ?? null,
                'group_id' => 0,
                'sop_version' => basename($record->iri),
                'curation_version' => $record->curationVersion,
                'source' => 'actionability',
                'source_uuid' => $message->key,
                'source_timestamp' => $message->timestamp,
                'source_offset' => $message->offset,
                'message_version' =>  $record->jsonMessageVersion ?? null,
                'assertion_uuid' => $record->uuid ?? null,
                'alternate_uuid' => $record->iri ?? null,
                'panel_id' => Panel::title($record->affiliations[0]->name)->first()->id ?? 0,
                'affiliate_id' => $record->affiliations[0]->id ?? null,
                'affiliate_details' => $record->affiliations[0],
                'gene_hgnc_id' => $record->genes[0]->curie ?? null,
                'gene_details' => $record->genes,
                'title' => $record->title,
                'summary' => null,
                'description' => null,
                'comments' => $record->releaseNotes,
                'conditions' => $record->preferred_conditions[0]->curie ?? ($record->conditions[0]->curie ?? null),
                'condition_details' => $record->conditions,
                'evidence' => null,
                'evidence_details' => null,
                'assertions' => $record->assertions ?? null,
                'scores' => ['earlyRuleOutStatus' => $record->earlyRuleOutStatus
                            ],
                'score_details' => $record->scores,
                'curators' => $record->contributors ?? null,
                'published' => ($record->statusFlag == "Released"),
                'animal_model_only' => false,
                'events' => ['dateISO8601' => $record->dateISO8601,
                             'eventTime' => $record->eventTime ?? null,
                             'statusFlag' => $record->statusFlag,
                             'statusPublishFlag' => $record->statusPublishFlag,
                             'searchDates' => $record->searchDates],
                'url' => ['evidence' => $record->iri,
                          'scoreDetails' => $record->scoreDetails,
                          'surveyDetails' => $record->surveyDetails],
                'version' => $record->version ?? 0,
                'status' => Curation::STATUS_ACTIVE
            ];
        
        

        $curation = Curation::type(Curation::TYPE_ACTIONABILITY)->source('actionability')
                                    ->status(Curation::STATUS_ACTIVE)
                                    ->aid($record->iri ?? '**NO IRI**')->orderBy('id', 'desc')->first();

        if ($curation !== null)
            $curation->update(['status' => Curation::STATUS_ARCHIVE]);

        $curation = new Curation($data);
        $curation->save();

    }

}
