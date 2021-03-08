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
class Sensitivity extends Model
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
        'gene_label' => 'string',
        'gene_hgnc_id' => 'string',
        'haplo_disease_label' => 'string',
        'haplo_disease_mondo' => 'string',
        'haplo_classification' => 'string',
        'haplo_other' => 'json',
        'triplo_disease_label' => 'string',
        'triplo_disease_mondo' => 'string',
        'triplo_classification' => 'string',
        'triplo_other' => 'json',
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
	protected $fillable = ['ident', 'curie', 'report_date', 'haplo_disease_label',
                            'haplo_disease_mondo', 'haplo_classification', 'haplo_other',
                            'triplo_disease_label',
                            'triplo_disease_mondo', 'triplo_classification', 'triplo_other',
                            'gene_label', 'gene_hgnc_id',
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
     * Retrieve, compare, and load a fresh dataset
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function assertions()
    {
        $assertions = GeneLib::dosageList([
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
        foreach ($assertions->collection as $assertion)
        {
            //dd($assertion);
            $current = Sensitivity::curie($assertion->dosage_curation->curie)->orderBy('version', 'desc')->first();

            if ($current === null)          // new assertion
            {
                $new = new Sensitivity([
                                    'curie' => $assertion->dosage_curation->curie,
                                    'report_date' => Carbon::parse($assertion->dosage_curation->report_date)->format('Y-m-d H:i:s.0000'),
                                    'haplo_disease_label' => $assertion->dosage_curation->haploinsufficiency_assertion->disease->label ?? null,
                                    'haplo_disease_mondo' => $assertion->dosage_curation->haploinsufficiency_assertion->disease->curie ?? null,
                                    'haplo_classification' => $assertion->dosage_curation->haploinsufficiency_assertion->dosage_classification->ordinal ?? null,
                                    'haplo_other' => null,
                                    'triplo_disease_label' => $assertion->dosage_curation->triplosensitivity_assertion->disease->label ?? null,
                                    'triplo_disease_mondo' => $assertion->dosage_curation->triplosensitivity_assertion->disease->curie ?? null,
                                    'triplo_classification' => $assertion->dosage_curation->triplosensitivity_assertion->dosage_classification->ordinal ?? null,
                                    'triplo_other' => null,
                                    'gene_label' => $assertion->label,
                                    'gene_hgnc_id' => $assertion->hgnc_id,
                                    'specified_by' => $assertion->specified_by->label ?? null,
                                    'attributed_to' => $assertion->attributed_to->label ?? null,
                                    'version' => 1,
                                    'type' => 1,
                                    'status' => 1
                                ]);

                // for now we need to mess around with the conditions
                foreach ($assertion->genetic_conditions as $condition)
                {
                    foreach ($condition->gene_dosage_assertions as $dosage)
                    {
                        if ($dosage->assertion_type == "HAPLOINSUFFICIENCY_ASSERTION")
                        {
                            $new->haplo_disease_label = $condition->disease->label ?? null;
                            $new->haplo_disease_mondo = $condition->disease->curie ?? null;
                        }
                        if ($dosage->assertion_type == "TRIPLOSENSITIVITY_ASSERTION")
                        {
                            $new->triplo_disease_label = $condition->disease->label ?? null;
                            $new->triplo_disease_mondo = $condition->disease->curie ?? null;
                        }
                    }
                }

                $new->save();

                $gene = Gene::hgnc($new->gene_hgnc_id)->first();

                Change::create([
                                'type' => Change::TYPE_DOSAGE,
                                'category' => Change::CATEGORY_NONE,
                                'element_id' => $gene->id,
                                'element_type' => 'App\Gene',
                                'old_id' =>null,
                                'old_type' => null,
                                'new_id' => $new->id,
                                'new_type' => 'App\Sensitivity',
                                'change_date' => $current->report_date,
                                'status' => 1
                    ]);

                continue;
            }

            $new = new Sensitivity([
                                    'curie' => $assertion->dosage_curation->curie,
                                    'report_date' => Carbon::parse($assertion->dosage_curation->report_date)->format('Y-m-d H:i:s.0000'),
                                    'haplo_disease_label' => $assertion->dosage_curation->haploinsufficiency_assertion->disease->label ?? null,
                                    'haplo_disease_mondo' => $assertion->dosage_curation->haploinsufficiency_assertion->disease->curie ?? null,
                                    'haplo_classification' => $assertion->dosage_curation->haploinsufficiency_assertion->dosage_classification->ordinal ?? null,
                                    'haplo_other' => null,
                                    'triplo_disease_label' => $assertion->dosage_curation->triplosensitivity_assertion->disease->label ?? null,
                                    'triplo_disease_mondo' => $assertion->dosage_curation->triplosensitivity_assertion->disease->curie ?? null,
                                    'triplo_classification' => $assertion->dosage_curation->triplosensitivity_assertion->dosage_classification->ordinal ?? null,
                                    'triplo_other' => null,
                                    'gene_label' => $assertion->label,
                                    'gene_hgnc_id' => $assertion->hgnc_id,
                                    'specified_by' => $assertion->specified_by->label ?? null,
                                    'attributed_to' => $assertion->attributed_to->label ?? null,
                                    'version' => $current->version + 1,
                                    'type' => 1,
                                    'status' => 1
                                ]);

            // for now we need to mess around with the conditions
            foreach ($assertion->genetic_conditions as $condition)
            {
                foreach ($condition->gene_dosage_assertions as $dosage)
                {
                    if ($dosage->assertion_type == "HAPLOINSUFFICIENCY_ASSERTION")
                    {
                        $new->haplo_disease_label = $condition->disease->label ?? null;
                        $new->haplo_disease_mondo = $condition->disease->curie ?? null;
                    }
                    if ($dosage->assertion_type == "TRIPLOSENSITIVITY_ASSERTION")
                    {
                        $new->triplo_disease_label = $condition->disease->label ?? null;
                        $new->triplo_disease_mondo = $condition->disease->curie ?? null;
                    }
                }
            }

            if (!$this->compare($current, $new))      // update
            {
                //dd($new);
                $new->save();

                $gene = Gene::hgnc($new->gene_hgnc_id)->first();

                Change::create([
                                'type' => Change::TYPE_DOSAGE,
                                'category' => Change::CATEGORY_NONE,
                                'element_id' => $gene->id,
                                'element_type' => 'App\Gene',
                                'old_id' => $current->id,
                                'old_type' => 'App\Sensitivity',
                                'new_id' => $new->id,
                                'new_type' => 'App\Sensitivity',
                                'change_date' => $current->report_date,
                                'status' => 1
                    ]);
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

        return empty($diff);
    }
}
