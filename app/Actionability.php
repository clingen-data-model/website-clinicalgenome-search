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
use App\Disease;
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
                                            'forcegg' => true,
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
    public static function parser($message, $packet = null)
    {

        $bad_mondos = [ 'MONDO:015515' => 'MONDO:0015515',
                       'MONDO: 0016473' => 'MONDO:0016473',
                       'MONDO:000456' => 'MONDO:0000456',
                       'MONDO:00017279' => 'MONDO:0017279',
                       'MONDO:10743' => 'MONDO:0010743'
                     ];

        $record = json_decode($message->payload);

        // if this is a new variant-condition record, call the variant parser
        if (isset($record->curationType) && $record->curationType == 'Variant-Condition')
            return self::variant_parser($message, $packet);

        // there are no incremental updates in actionability, so
        // every document received can archive all previous ones
        $old_curations = Curation::actionability()->where('alternate_uuid', $record->iri)
                                                  ->where('status', '!=', Curation::STATUS_ARCHIVE)
                                                  ->get();

        // what other are there?
        switch ($record->statusFlag)
        {
            case 'Released - Under Revision':
                $status = Curation::STATUS_ACTIVE_REVIEW;
                break;
            case 'Retracted':
                $status = Curation::STATUS_RETRACTED;
                break;
            case 'In Preparation':
                $status = Curation::STATUS_PRELIMINARY;
                break;
            case 'Released':
                $status = Curation::STATUS_ACTIVE;
                break;
            default:
                dd($record);
        }
        
        // because each message can have multiple curations, we need to break them up.
        foreach($record->genes as $gene)
        {
            // lookup local gene
            $mygene = Gene::hgnc($gene->curie)->first();

            if ($mygene === null)
                dd($gene);

            // create a list of all preferred conditions associated with this gene
            $preferred = [];

            foreach($record->preferred_conditions as $condition)
                if ($gene->curie == $condition->gene)
                    $preferred[] = $condition->curie;

            $preferred = array_unique($preferred);
            $preferred_done = [];

            // find all the conditions associated with this gene
            foreach($record->conditions as $condition)
            {
                // extract everything specific to gene and conditions
                if ($gene->curie == $condition->gene)
                {
                    // there are some malformed disease ids.  Repair them
                    if (isset($bad_mondos[$condition->curie]))
                    {
                        echo "Changed malformed $condition->curie to ";
                        $condition->curie = $bad_mondos[$condition->curie];
                        echo "$condition->curie \n";
                    }

                    // the preferred condition will frequently be repeated.  Only do it once.
                    if (in_array($condition->curie, $preferred_done))
                        continue;

                    // lookup local disease.  The curie may be MONDO or OMIM
                    $disease = Disease::rosetta($condition->curie);

                    if ($disease === null)
                    {
                        // Some older records will have a malformed mondo id.  They'll eventually get 
                        // replaced, but becuase we depend on a disease structure later on, lets just
                        // create an empty one.
                        echo "Bad disease $condition->curie \n";
                        $disease = new Disease();
                    }

                    // older records may not have assertions, so fake it.
                    if (!isset($record->assertions))
                    {

                        $record->assertions = json_decode(json_encode([
                                [
                                    "iri" => "http://purl.obolibrary.org/obo/" . $condition->curie,
                                    "uri" => str_replace(':', '', $condition->curie),
                                    "gene" => $gene->curie,
                                    "curie" => $condition->curie,
                                    "ontology" => "",
                                    "assertion" => "Assertion Pending"
                                ]
                            ]). FALSE);
                    }

                    foreach($record->assertions as $assertion)
                    {
                        if ($gene->curie == $assertion->gene && $condition->curie == $assertion->curie)
                        {
                            // Find the exact old curation to maintain our own internal version sequencing
                            if ($disease->id !== null)
                            {
                                $curation = Curation::type(Curation::TYPE_ACTIONABILITY)
                                                    ->source('actionability')
                                                    ->aid($record->iri)
                                                    ->where('gene_id', $mygene->id)
                                                    ->where('disease_id', $disease->id)
                                                    ->orderBy('id', 'desc')->first();
                            }
                            else
                            {
                                $curation = Curation::type(Curation::TYPE_ACTIONABILITY)
                                                    ->source('actionability')
                                                    ->aid($record->iri)
                                                    ->where('gene_id', $mygene->id)
                                                    ->whereJsonContains('conditions', $condition->curie)
                                                    ->orderBy('id', 'desc')->first();
                            }

                            // parse into standard structure
                            $data = [
                                'type' => Curation::TYPE_ACTIONABILITY,
                                'type_string' => 'Actionability',
                                'subtype' => Curation::SUBTYPE_ACTIONABILITY,
                                'subtype_string' => $record->curationType ?? null,
                                'group_id' => 0,
                                'sop_version' => null,
                                'curation_version' => $record->curationVersion,
                                'source' => 'actionability',
                                'source_uuid' => $message->key,
                                'source_timestamp' => $message->timestamp,
                                'source_offset' => $message->offset,
                                'packet_id' => $packet->id ?? null,
                                'message_version' =>  $record->jsonMessageVersion ?? null,
                                'assertion_uuid' => $record->uuid ?? null,
                                'alternate_uuid' => $record->iri ?? null,
                                'panel_id' => Panel::title($record->affiliations[0]->name)->first()->id ?? 0,
                                'affiliate_id' => $record->affiliations[0]->id ?? null,
                                'affiliate_details' => $record->affiliations[0],
                                'gene_id' => $mygene->id,
                                'gene_hgnc_id' => $gene->curie ?? null,
                                'gene_details' => $gene,
                                'variant_iri' => null,
                                'variant_details' => $record->variants ?? null,
                                'document' => basename($record->iri),
                                'context' => (strpos($record->scoreDetails, 'Adult') > 0 ? 'Adult' : 'Pediatric'),
                                'title' => $record->title,
                                'summary' => null,
                                'description' => null,
                                'comments' => $record->releaseNotes,
                                'disease_id' => $disease->id,
                                'conditions' => [($condition->curie ?? null)],
                                'condition_details' => $condition,
                                'evidence' => null,
                                'evidence_details' => $record->preferred_conditions,
                                'assertions' => $assertion ?? null,
                                'scores' => ['earlyRuleOutStatus' => $record->earlyRuleOutStatus
                                            ],
                                'score_details' => $record->scores,
                                'curators' => $record->curators ?? ($record->contributors ?? null),
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
                                'version' => 1,
                                'status' => $status
                            ];

                            $new = new Curation($data);
                            
                            // retire the old curation and adjust the version on the new
                            if ($curation !== null)
                            {
                                $new->version = $curation->version + 1;
                            }

                            $new->save();

                        }
                    }

                    // if this was a preferred condition, mark it as done
                    if (in_array($condition->curie, $preferred))
                        $preferred_done[] = $condition->curie;
                }
            }
        }

        $old_curations->each(function ($item) {
            $item->update(['status' => Curation::STATUS_ARCHIVE]);
        });

    }


    /**
     * Map a kafka Variant-Condition actionability record to a curation
     *
     */
    public static function variant_parser($message, $packet = null)
    {

        $record = json_decode($message->payload);

        // there are no incremental updates in actionability, so
        // every document received can archive all previous ones
        $old_curations = Curation::actionability()->where('alternate_uuid', $record->iri)
                                                  ->where('status', '!=', Curation::STATUS_ARCHIVE)
                                                  ->get();

        // what other are there?
        switch ($record->statusFlag)
        {
            case 'Released - Under Revision':
                $status = Curation::STATUS_ACTIVE_REVIEW;
                break;
            case 'Retracted':
                $status = Curation::STATUS_RETRACTED;
                break;
            case 'In Preparation':
                $status = Curation::STATUS_PRELIMINARY;
                break;
            case 'Released':
                $status = Curation::STATUS_ACTIVE;
                break;
            default:
                dd($record);
        }
        
        // because each message can have multiple curations, we need to break them up.
        foreach($record->variants as $variant)
        {
            // create a list of all preferred conditions associated with this gene
            $preferred = [];

            foreach($record->preferred_conditions as $condition)
                if ($variant->id == $condition->id)
                    $preferred[] = $condition->curie;

            $preferred = array_unique($preferred);
            $preferred_done = [];

            // find all the conditions associated with this gene
            foreach($record->conditions as $condition)
            {
                // extract everything specific to gene and conditions
                if ($variant->id == $condition->id)
                {
                    // the preferred condition will frequently be repeated.  Only do it once.
                    if (in_array($condition->curie, $preferred_done))
                        continue;

                    // lookup local disease.  The curie may be MONDO or OMIM
                    $disease = Disease::rosetta($condition->curie);

                    if ($disease === null)
                    {
                        // Some older records will have a malformed mondo id.  They'll eventually get 
                        // replaced, but becuase we depend on a disease structure later on, lets just
                        // create an empty one.
                        echo "Bad disease $condition->curie \n";
                        $disease = new Disease();
                    }

                    // older records may not have assertions, so fake it.
                    /*if (!isset($record->assertions))
                    {

                        $record->assertions = json_decode(json_encode([
                                [
                                    "iri" => "http://purl.obolibrary.org/obo/" . $condition->curie,
                                    "uri" => str_replace(':', '', $condition->curie),
                                    "gene" => $gene->curie,
                                    "curie" => $condition->curie,
                                    "ontology" => "",
                                    "assertion" => "Assertion Pending"
                                ]
                            ]). FALSE);
                    }*/

                    foreach($record->assertions as $assertion)
                    {
                        if ($variant->id == $assertion->id && $condition->curie == $assertion->curie)
                        {
                            // Find the exact old curation to maintain our own internal version sequencing
                            if ($disease->id !== null)
                            {
                                $curation = Curation::type(Curation::TYPE_ACTIONABILITY)
                                                    ->source('actionability')
                                                    ->aid($record->iri)
                                                    ->where('variant_iri', $variant->id)
                                                    ->where('disease_id', $disease->id)
                                                    ->orderBy('id', 'desc')->first();
                            }
                            else
                            {
                                $curation = Curation::type(Curation::TYPE_ACTIONABILITY)
                                                    ->source('actionability')
                                                    ->aid($record->iri)
                                                    ->where('variant_iri', $variant->id)
                                                    ->whereJsonContains('conditions', $condition->curie)
                                                    ->orderBy('id', 'desc')->first();
                            }

                            // parse into standard structure
                            $data = [
                                'type' => Curation::TYPE_ACTIONABILITY,
                                'type_string' => 'Actionability',
                                'subtype' => Curation::SUBTYPE_ACTIONABILITY,
                                'subtype_string' => $record->curationType ?? null,
                                'group_id' => 0,
                                'sop_version' => null,
                                'curation_version' => $record->curationVersion,
                                'source' => 'actionability',
                                'source_uuid' => $message->key,
                                'source_timestamp' => $message->timestamp,
                                'source_offset' => $message->offset,
                                'packet_id' => $packet->id ?? null,
                                'message_version' =>  $record->jsonMessageVersion ?? null,
                                'assertion_uuid' => $record->uuid ?? null,
                                'alternate_uuid' => $record->iri ?? null,
                                'panel_id' => Panel::title($record->affiliations[0]->name)->first()->id ?? 0,
                                'affiliate_id' => $record->affiliations[0]->id ?? null,
                                'affiliate_details' => $record->affiliations[0],
                                'gene_id' => null,
                                'gene_hgnc_id' => null,
                                'gene_details' => null,
                                'variant_iri' => $variant->id,
                                'variant_details' => $record->variants ?? null,
                                'document' => basename($record->iri),
                                'context' => (strpos($record->scoreDetails, 'Adult') > 0 ? 'Adult' : 'Pediatric'),
                                'title' => $record->title,
                                'summary' => null,
                                'description' => null,
                                'comments' => $record->releaseNotes,
                                'disease_id' => $disease->id,
                                'conditions' => [($condition->curie ?? null)],
                                'condition_details' => $condition,
                                'evidence' => null,
                                'evidence_details' => $record->preferred_conditions,
                                'assertions' => $assertion ?? null,
                                'scores' => ['earlyRuleOutStatus' => $record->earlyRuleOutStatus
                                            ],
                                'score_details' => $record->scores,
                                'curators' => $record->curators ?? ($record->contributors ?? null),
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
                                'version' => 1,
                                'status' => $status
                            ];

                            $new = new Curation($data);
                            
                            // retire the old curation and adjust the version on the new
                            if ($curation !== null)
                            {
                                $new->version = $curation->version + 1;
                            }

                            $new->save();

                        }
                    }

                    // if this was a preferred condition, mark it as done
                    if (in_array($condition->curie, $preferred))
                        $preferred_done[] = $condition->curie;
                }
            }
        }

        $old_curations->each(function ($item) {
            $item->update(['status' => Curation::STATUS_ARCHIVE]);
        });

    }

}
