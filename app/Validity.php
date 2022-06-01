<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Display;

use Uuid;
use Carbon\Carbon;

use App\GeneLib;
use App\Change;
use App\Gene;

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
class Validity extends Model
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
        'curie' => 'string',
        'report_date' => 'timestamp',
        'disease_label' => 'string',
        'disease_curie' => 'string',
        'gene_label' => 'string',
        'gene_hgnc_id' => 'string',
        'mode_of_inheritance' => 'string',
        'classification' => 'string',
        'specified_by' => 'string',
        'attributed_to' => 'string',
        'properties' => 'text',
		'type' => 'integer',
		'status' => 'integer'
	];

	/**
     * Map the json attributes to associative arrays.
     *
     * @var array
     */
	protected $casts = [
			'validity' => 'array',
            'actionability' => 'array',
            'dosage' => 'array',
            'pharma' => 'array',
            'variant' => 'array'
		];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = ['ident', 'curie', 'report_date', 'report_id', 'disease_label',
                            'disease_mondo', 'gene_label', 'gene_hgnc_id', 'animal_model_only',
                            'mode_of_inheritance', 'classification', 'properties',
                            'specified_by', 'attributed_to', 'version', 'type', 'status',
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


    protected static $evidence_type_strings = [
            'null variant evidence line' => "Predicted or proven null variant type",
            'SEPIO:0000247' => "Candidate gene sequencing",
            'SEPIO:0004017' => "Predicted or proven null variant type",
            'SEPIO:0004018' => "Predicted or proven null variant type",
            'SEPIO:0004019' => "Other variant type",
            'SEPIO:0004020' => "Single variant analysis",
            'SEPIO:0004021' => "Aggregate variant analysis",
            'SEPIO:0004022' => "Bochemical Function",
            'SEPIO:0004023' => "Protein Interaction",
            'SEPIO:0004024' => "Expression",
            'SEPIO:0004025' => "Patient cells",
            'SEPIO:0004026' => "Non-patient cells",
            'SEPIO:0004027' => "Non-human model organism",
            'SEPIO:0004028' => "Cell culture model",
            'SEPIO:0004029' => "Rescue in human",
            'SEPIO:0004030' => "Rescue in non-human model organism",
            'SEPIO:0004031' => "Rescue in cell culture model",
            'SEPIO:0004032' => "Rescue in patient cells",
            'SEPIO:0004029' => "",
            'SEPIO:0004078' => "Predicted or proven null variant type",
            'SEPIO:0004079' => "Predicted or proven null variant type",
            'SEPIO:0004080' => "Other variant type",
          //  'variant functional impact evidence item' => "No translation",
            'SEPIO:0004119' => "Other variant type",
          //  'null variant evidence item' => "No translation",
            'SEPIO:0004118' => "Other Variant Type",
            'SEPIO:0004117' => "Predicted or proven null",
          //  'non-null variant evidence line' => "Other variant type",
            'SEPIO:0004119' => "Other variant type",
            'SEPIO:0004120' => "Predicted or proven null",
            'SEPIO:0004121' => "Other variant type"
            //'SEPIO:0004042' => "Other variant type",
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
     * Query scope by curie (assertion id)
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeCurie($query, $ident)
    {
		return $query->where('curie', $ident);
    }


    /**
     * Query scope by hgnc id
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeHgnc($query, $id)
    {
		return $query->where('gene_hgnc_id', $id);
    }


    public static function secondaryContributor($assertion)
    {
        if (empty($assertion->contributions))
            return '';

        $strings = [];

        foreach($assertion->contributions as $contributor)
            if ($contributor->realizes->curie == "SEPIO:0004099")
                $strings[] = $contributor->agent->label;

        return empty($strings) ? 'NONE' : implode(', ', $strings);
    }


    /**
     * Query scope by animal model only
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeAnimal($query)
    {
		return $query->where('animal_model_only', true);
    }


    /**
     * Retrieve, compare, and load a fresh dataset
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function assertions()
    {
        $assertions = GeneLib::validityList([
                                            'page' => 0,
                                            'pagesize' => "null",
                                            'sort' => 'GENE_LABEL',
                                            'search' => null,
                                            'direction' => 'ASC',
                                            'properties' => true,
                                            'curated' => false
                                        ]);

        if (empty($assertions))
            die ("Failure to retrieve new data");

        // clear out the status field

        // compare and update
        foreach ($assertions->collection as $assertion)
        {
            //dd($assertion->disease->curie);
            $current = Validity::curie($assertion->curie)->orderBy('version', 'desc')->first();

            if ($current === null)          // new assertion
            {
                $current = Validity::create([
                                    'curie' => $assertion->curie,
                                    'report_date' => Carbon::parse($assertion->report_date)->format('Y-m-d H:i:s.0000'),
                                    'disease_label' => $assertion->disease->label,
                                    'disease_mondo' => $assertion->disease->curie,
                                    'gene_label' => $assertion->gene->label,
                                    'gene_hgnc_id' => $assertion->gene->hgnc_id,
                                    'mode_of_inheritance' => $assertion->mode_of_inheritance->label,
                                    'classification' => $assertion->classification->label,
                                    'specified_by' => $assertion->specified_by->label,
                                    'attributed_to' => $assertion->attributed_to->label,
                                    'properties' => $assertion->legacy_json,
                                    'report_id' => $assertion->report_id,
                                    'animal_model_only' => $assertion->animal_model_only,
                                    'version' => 1,
                                    'type' => 1,
                                    'status' => 1
                                ]);

                $gene = Gene::hgnc($current->gene_hgnc_id)->first();

                Change::create([
                                'type' => Change::TYPE_VALIDITY,
                                'category' => Change::CATEGORY_NONE,
                                'element_id' => $gene->id,
                                'element_type' => 'App\Gene',
                                'old_id' =>null,
                                'old_type' => null,
                                'new_id' => $current->id,
                                'new_type' => 'App\Validity',
                                'change_date' => $current->report_date,
                                'description' => ['New curation activity'],
                                'status' => 1
                    ]);

                continue;
            }

            $new = new Validity([
                                    'curie' => $assertion->curie,
                                    'report_date' => Carbon::parse($assertion->report_date)->format('Y-m-d H:i:s.0000'),
                                    'disease_label' => $assertion->disease->label,
                                    'disease_mondo' => $assertion->disease->curie,
                                    'gene_label' => $assertion->gene->label,
                                    'gene_hgnc_id' => $assertion->gene->hgnc_id,
                                    'mode_of_inheritance' => $assertion->mode_of_inheritance->label,
                                    'classification' => $assertion->classification->label,
                                    'specified_by' => $assertion->specified_by->label,
                                    'attributed_to' => $assertion->attributed_to->label,
                                    'properties' => $assertion->legacy_json,
                                    'report_id' => $assertion->report_id,
                                    'animal_model_only' => $assertion->animal_model_only,
                                    'version' => $current->version + 1,
                                    'type' => 1,
                                    'status' => 1
                                ]);

            $differences = $this->compare($current, $new);

            if (!empty($differences))      // update
            {
                //dd($new);
                $new->save();

                $gene = Gene::hgnc($new->gene_hgnc_id)->first();

                // we'll use the current date for search, since there is no concept of a reissue date in GCI
                Change::create([
                                'type' => Change::TYPE_VALIDITY,
                                'category' => Change::CATEGORY_NONE,
                                'element_id' => $gene->id,
                                'element_type' => 'App\Gene',
                                'old_id' =>$current->id,
                                'old_type' => 'App\Validity',
                                'new_id' => $new->id,
                                'new_type' => 'App\Validity',
                                'change_date' => Carbon::yesterday(),   // $new->report_date,
                                'status' => 1,
                                'description' => $this->scribe($differences)
                    ]);
            }
            else
            {
                // even if they match, keep the properties updates
                $current->update(['properties' => $new->properties, 'animal_model_only' => $new->animal_model_only]);
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
              $old_array['list_date'], $old_array['display_status'], $old_array['properties'],
              $old_array['report_id'], $old_array['animal_model_only']);
        unset($new_array['id'], $new_array['ident'], $new_array['version'], $new_array['type'], $new_array['status'],
              $new_array['created_at'], $new_array['updated_at'], $new_array['deleted_at'], $new_array['display_date'],
              $new_array['report_id'], $new_array['animal_model_only'],
              $new_array['list_date'], $new_array['display_status'], $new_array['properties']);

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
                case 'curie':
                    $annot[] = 'New assertion';
                    break;
                case 'report_date':
                    $annot[] = 'Report date has changed';
                    break;
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
                case 'mode_of_inheritance':
                    $annot[] = 'MOI classification has changed';
                    break;
                case 'classification':
                    $annot[] = 'Validity classification has changed';
                    break;
                case 'specified_by':
                    $annot[] = 'Evaluation SOP has changed';
                    break;
                case 'attributed_to':
                    $annot[] = 'CGEP/WG attribution has changed';
                    break;
            }
        }

        return $annot;
    }


    /**
     * Determine if the passed validity assertion is Animal Model Only
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public static function isAnimalModelOnly($assertion)
    {
        $json = json_decode($assertion->legacy_json, false);

		$score_data = $json->scoreJson ?? $json;

        return (
            ($score_data->summary->FinalClassification == "No Known Disease Relationship") &&
            (isset($score_data->ExperimentalEvidence->Models->NonHumanModelOrganism->TotalPoints)) &&
            ($score_data->ExperimentalEvidence->Models->NonHumanModelOrganism->TotalPoints > 0) &&
            ($score_data->ValidContradictoryEvidence->Value == "NO")
        );
    }


    /**
     * Determine if the passed validity assertion has lumping and splitting content
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public static function hasLumpingContent($assertion)
    {
        if (empty($assertion->las_included) && empty($assertion->las_excluded)
            && empty($assertion->las_rationale['rationales'])
            && empty($assertion->las_rationale['pmids'])
            && empty($assertion->las_rationale['notes'])
            && empty($assertion->las_curation)
            )
               return false;

        return true;
    }


    /**
     * Map a gdv record to a model
     *
     */
    public static function parser($data)
    {
        dd($data);

        $record = $data->data;

        $current = self::gtid($record->id)->first();

        if ($current === null)
        {
            // the minimal required are id, uuid, gene, and group
            $current = new self([
                'type' => self::TYPE_GENE_TRACKER,
                'gtid' => $record->id,
                'gt_uuid' => $record->uuid,
                'hgnc_id' => $record->gene->hgnc_id,
                'group_id' => $record->group->affiliation_id ?? "",
                'group_detail' => (array) $record->group
            ]);
        }

        // update with optional fields
        $current->fill([
                        'gdm_uuid' => $record->gdm_uuid ?? null,
                        'mondo_id' => $record->disease_entity->mondo_id ?? null,
                        'hp_id' => $record->mode_of_inheritance->hp_id ?? null,
                        'group_id' => $record->group->affiliation_id ?? "",
                        'group_detail' => (array) $record->group,
                        'curator_detail' => (array) ($record->curator ?? null),
                        'rationale' => (array) ($record->rationales ?? null),
                        'curation_type' => (array) ($record->curation_type ?? null),
                        'omim_phenotypes' => (array) ($record->omim_phenotypes ?? null),
                        'notes' => $record->notes ?? null
                    ]);

        switch ($data->event_type)
        {
            case 'created':
                $current->status = self::STATUS_CREATED;
                break;
            case 'updated':
                $current->status = self::STATUS_UPDATED;
                break;
            case 'deleted':
                $current->status = self::STATUS_DELETED;
                break;
        }

        // if event type is deleted, then no extra status detail will be present
        if ($current->status !== self::STATUS_DELETED)
        {
            switch ($record->status->name)
            {
                case 'Uploaded':
                    $current->date_uploaded = $record->status->effective_date;
                    break;
                case "Precuration":
                    $current->date_precuration = $record->status->effective_date;
                    break;
                case "Disease Entity Assigned":
                case "Disease entity assigned":
                    $current->date_disease_assigned = $record->status->effective_date;
                    break;
                case "Precuration Complete":
                    $current->date_precuration_complete = $record->status->effective_date;
                    break;
                case "Curation Provisional":
                    $current->date_curation_provisional = $record->status->effective_date;
                    break;
                case "Curation Approved":
                    $current->date_curation_approved = $record->status->effective_date;
                    break;
                case "Retired Assignment":
                    $current->date_retired = $record->status->effective_date;
                    break;
                case "Published":
                    $current->date_published = $record->status->effective_date;
                    break;
            }
        }

        $current->save();

        if ($current->status !== self::STATUS_DELETED)
        {

            // we want to copy the latest status into a gene column, but we can't garuntee order
            $gene = Gene::hgnc('HGNC:' . $current->hgnc_id)->first();

            if ($gene !== null)
            {
                $a = $gene->curation_status;
                if ($a === null)
                {
                    $gene->curation_status = [ $record->id => [
                                        'group' => $record->group->name,
                                        'group_type' => $record->group->type->name ?? null,
                                        'group_id' => $record->group->affiliation_id,
                                        'status' => $record->status->name,
                                        'status_date' => $record->status->effective_date
                                    ]];

                    //dd($gene->curation_status);

                    $gene->save();
                }
                else
                {
                    if (!isset($a[$record->id]) || ((self::$curation_priority[$record->status->name] ?? 0) >= (self::$curation_priority[$a[$record->id]['status']] ?? 0)))
                    {
                        $a[$record->id] = [ 'group' => $record->group->name,
                                            'group_type' => $record->group->type->name ?? null,
                                            'group_id' => $record->group->affiliation_id,
                                            'status' => $record->status->name,
                                            'status_date' => $record->status->effective_date
                                        ];

                        $gene->curation_status = $a;

                        //dd($gene->curation_status);

                        $gene->save();
                    }
                }
            }


            // we also want to keep the disease status updated
            if ($current->mondo_id !== null)
            {
                $disease = Disease::curie($current->mondo_id)->first();

                if ($disease !== null)
                {
                    $a = $disease->curation_status;
                    if ($a === null)
                    {
                        $disease->curation_status = [ $record->id => [
                                            'group' => $record->group->name,
                                            'group_type' => $record->group->type->name ?? null,
                                            'group_id' => $record->group->affiliation_id,
                                            'status' => $record->status->name,
                                            'status_date' => $record->status->effective_date
                                        ]];

                        //dd($gene->curation_status);

                        $disease->save();
                    }
                    else
                    {
                        if (!isset($a[$record->id]) || ((self::$curation_priority[$record->status->name] ?? 0) >= (self::$curation_priority[$a[$record->id]['status']] ?? 0)))
                        {
                            $a[$record->id] = [ 'group' => $record->group->name,
                                                'group_type' => $record->group->type->name ?? null,
                                                'group_id' => $record->group->affiliation_id,
                                                'status' => $record->status->name,
                                                'status_date' => $record->status->effective_date
                                            ];

                            $disease->curation_status = $a;

                            //dd($gene->curation_status);

                            $disease->save();
                        }
                    }
                }
            }

        }

        // TODO:  resync the gene_panel table
        /*$precurations = self::all();

        foreach ($preccurations as $precuration)
        {
            // if published and not deleted and not retired...
        }*/

        // Check if the group exists, if not, add
        if (!empty($record->group->affiliation_id))
        {
            $id = ($record->group->affiliation_id < 20000 ? $record->group->affiliation_id + 30000 :
                                                $record->group->affiliation_id);

            $panel = Panel::affiliate($id)->first();

            if ($panel === null)
            {
                $panel = new Panel(['affiliate_id' => $id,
                                    'alternate_id' => $record->group->affiliation_id,
                                    'name' => $record->group->name,
                                    'title' => $record->group->name,
                                    'title_abbreviated' => $record->group->name,
                                    'title_short' => $record->group->name,
                                    'summary' => '',
                                    'affiliate_type' => '',
                                    'type' => Panel::TYPE_WG,
                                    'status' => 1]);
                $panel->save();
            }
        }
    }

    /**
     * Displayable evidence type string
     *
     */
    public static function evidenceTypeString($x)
    {

       //return $x;

        return self::$evidence_type_strings[$x] ?? $x;
    }
}
