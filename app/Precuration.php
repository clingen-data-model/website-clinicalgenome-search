<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Display;

use Uuid;
use Carbon\Carbon;

use App\Gene;
use App\Term;
use App\Omim;
use App\Disease;

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
class Precuration extends Model
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
            'gtid' => 'integer',
            'gt_uuid' => 'string',
            'gdm_uuid' => 'string',
            'hgnc_id' => 'string',
            'mondo_id' => 'string',
            'hp_id' => 'string',
            'group_id' => 'string',
            'group_detail' => 'string',
            'curator_detail' => 'string',
            'date_uploaded' => 'string',
            'date_precuration' => 'string',
            'date_disease_assigned' => 'string',
            'date_precuration_complete' => 'string',
            'date_curation_provisional' => 'string',
            'date_curation_approved' => 'string',
            'date_retired' => 'string',
            'rationale' => 'string',
            'curation_type' => 'string',
            'omim_phenotypes' => 'string',
            'notes' => 'string',
            'status' => 'integer'
    ];

	/**
     * Map the json attributes to associative arrays.
     *
     * @var array
     */
	protected $casts = [
        'group_detail' => 'array',
        'curator_detail' => 'array',
        'rationale' => 'array',
        'curation_type' => 'array',
        'omim_phenotypes' => 'array'
	];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = ['ident', 'gtid', 'gt_uuid', 'gdm_uuid', 'hgnc_id', 'mondo_id', 'hp_id', 'group_id',
                            'group_detail', 'curator_detail', 'date_uploaded', 'date_precuration', 'date_disease_assigned',
                            'date_precuration_complete', 'date_curation_provisional', 'date_curation_approved', 'date_retired',
                            'rationale', 'curation_type', 'omim_phenotypes', 'notes',
                            'type', 'status'
                         ];

	/**
     * Non-persistent storage model attributes.
     *
     * @var array
     */
    protected $appends = ['display_date', 'list_date', 'display_status'];

    public const TYPE_NONE = 0;
    public const TYPE_DOSAGE_SENSITIVITY = 1;
    public const TYPE_GENE_TRACKER = 2;

    /*
     * Type strings for display methods
     *
     * */
    protected $type_strings = [
	 		0 => 'Unknown',
	 		1 => 'Dosage Sensitivity'
        ];

    public const STATUS_INITIALIZED = 0;
    public const STATUS_CREATED = 1;
    public const STATUS_UPDATED = 2;
    public const STATUS_DELETED = 9;

    /*
     * Status strings for display methods
     *
     * */
    protected $status_strings = [
	 		0 => 'Initialized',
            1 => 'Created',
            2 => 'Updated',
	 		9 => 'Deleted'
    ];


    /**
     * Order of importance for curation status
     */
    protected $curation_priority = [
        0 => 0,
        'Uploaded' => 10,
        "Precuration" => 15,
        "Disease Entity Assigned" => 20,
        "Disease entity assigned" => 20,
        "Precuration Complete" => 25,
        "Curation Provisional" => 30,
        "Curation Approved" => 35,
        "Retired Assignment" => 40,
        "Published" => 45
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
     * Query scope by hgnc_id
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeHgnc($query, $id)
    {
        if (strpos($id, 'HGNC:') === 0)
            $id = substr($id, 5);

		return $query->where('hgnc_id', $id);
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
     * Query scope by subtype
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeGtid($query, $id)
    {
		return $query->where('gtid', $id);
    }


    /**
     * Query scope by subtype
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeGdmid($query, $id)
    {
		return $query->where('gdm_uuid', $id);
    }

    /**
     * Get the closest date for disease evaluation
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getDiseaseDateAttribute()
    {
        //if ($this->date_disease_assigned !== null)
         //   return $this->date_disease_assigned;

        // deal with the situation where this date was not assigned but others are
        if ($this->date_precuration_complete !== null)
            return  $this->date_precuration_complete;
        else if ($this->date_curation_provisional !== null)
            return  $this->date_curation_provisional;
        else if ($this->date_curation_provisional !== null)
            return  $this->date_curation_provisional;
        else if ($this->date_published !== null)
            return  $this->date_published;
        else
            return null;

        return null;
    }


    /**
     * Map a gt precuration record to a model
     *
     */
    /**
     * Precurations that have lumped the given OMIM phenotype id into their
     * disease entity, i.e. the id appears in omim_phenotypes.included.
     *
     * Used by the condition page to surface the curation of the disease a
     * standalone phenotype was lumped into.  mim_number is stored as either a
     * JSON string or number depending on source, so match both.
     *
     * @param  string|int  $omim  a bare OMIM/MIM number
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function lumpedInto($omim)
    {
        $omim = trim((string) $omim);

        if ($omim === '' || !is_numeric($omim))
            return collect();

        return self::whereNotNull('mondo_id')
                    ->where(function ($query) use ($omim) {
                        $query->whereJsonContains('omim_phenotypes->included', $omim)
                              ->orWhereJsonContains('omim_phenotypes->included', (int) $omim);
                    })
                    ->get();
    }


    /**
     * Materialize this precuration's *included* OMIM phenotypes as searchable
     * synonyms of the disease they were lumped into.
     *
     * An included phenotype is part of this precuration's disease entity
     * (mondo_id), so searching that phenotype's name should return the lumped
     * disease — letting the user see the full complement of the disease, not
     * the phenotype's own standalone entry.  We therefore write each included
     * phenotype's OMIM title into terms with value = this precuration's
     * mondo_id, so the FULLTEXT-backed condition autocomplete resolves it to
     * the main disease.
     *
     * Idempotent: keyed on (name, value) via updateOrCreate; safe to re-run.
     *
     * @return int  number of synonym terms written
     */
    public function syncOmimPhenotypeTerms()
    {
        // without a lumped disease there's nothing for the synonym to point to
        if (empty($this->mondo_id))
            return 0;

        $included = $this->omim_phenotypes['included'] ?? [];

        if (empty($included))
            return 0;

        // canonical label of the lumped (main) disease, used as the alias
        $disease = Disease::where('curie', $this->mondo_id)->first();
        $label = $disease->label ?? null;

        $count = 0;

        foreach ($included as $mim)
        {
            $mim = trim((string) $mim);

            if ($mim === '')
                continue;

            // human-readable name of the included OMIM phenotype
            $omim = Omim::where('omimid', $mim)->first();

            if ($omim === null || empty($omim->titles))
                continue;

            // 14 = OMIM match (see Mysql::conditionLook2 type switch).
            // value is the lumped disease curie so a hit returns that disease.
            Term::updateOrCreate(
                ['name' => $omim->titles, 'value' => $this->mondo_id],
                ['type' => 14, 'alias' => $label, 'status' => 0]
            );

            $count++;
        }

        return $count;
    }


    public static function parser($data)
    {
        //dd($data);

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

        // keep included OMIM phenotype names searchable as condition synonyms
        if ($current->status !== self::STATUS_DELETED)
            $current->syncOmimPhenotypeTerms();

        // regardless of status, add to the pmids list
        if (isset($current->rationale['pmids']))
        {
            foreach($current->rationale['pmids'] as $pmid)
            {
                if (empty($pmid))
                    continue;

                $entry = Pmid::firstOrCreate(['pmid' => $pmid, 'uid' => $pmid],
                                    [ 'status' => 20]);
            }
        }

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
}
