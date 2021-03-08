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
	protected $fillable = ['ident', 'curie', 'report_date', 'disease_label',
                            'disease_mondo', 'gene_label', 'gene_hgnc_id',
                            'mode_of_inheritance', 'classification',
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
        $assertions = GeneLib::validityList([
                                            'page' => 0,
                                            'pagesize' => "null",
                                            'sort' => 'GENE_LABEL',
                                            'search' => null,
                                            'direction' => 'ASC',
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
                                    'version' => $current->version + 1,
                                    'type' => 1,
                                    'status' => 1
                                ]);

            if (!$this->compare($current, $new))      // update
            {
                //dd($new);
                $new->save();

                $gene = Gene::hgnc($new->gene_hgnc_id)->first();

                Change::create([
                                'type' => Change::TYPE_VALIDITY,
                                'category' => Change::CATEGORY_NONE,
                                'element_id' => $gene->id,
                                'element_type' => 'App\Gene',
                                'old_id' =>$current->id,
                                'old_type' => 'App\Validity',
                                'new_id' => $new->id,
                                'new_type' => 'App\Validity',
                                'change_date' => $new->report_date,
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
