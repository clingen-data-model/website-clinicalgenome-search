<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Display;

use Uuid;
use Carbon\Carbon;

use App\GeneLib;

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
class Change extends Model
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
        'category' => 'integer',
        'new' => '',
        'old' => '',
        'change_date' => 'timestamp',
		'status' => 'integer'
	];

	/**
     * Map the json attributes to associative arrays.
     *
     * @var array
     */
	protected $casts = [
			'haplo_other' => 'array',
            'triplo_other' => 'array',
            'description' => 'array'
		];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = ['ident', 'element_id', 'element_type', 'old_id', 'old_type', 'new_id', 'new_type',
                            'type', 'category', 'change_date', 'status', 'description',
                         ];

	/**
     * Non-persistent storage model attributes.
     *
     * @var array
     */
    protected $appends = ['display_date', 'list_date', 'display_status'];

    public const TYPE_NONE = 0;
    public const TYPE_ACTIONABILITY = 1;
    public const TYPE_VALIDITY = 2;
    public const TYPE_DOSAGE = 3;
    public const TYPE_VARIANT = 4;
    public const TYPE_PHARMA = 5;

    public const CATEGORY_NONE = 0;

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
     * Get the parent old activity entry
     */
    public function old()
    {
        return $this->morphTo();
    }


    /**
     * Get the parent new model activity entry
     */
    public function new()
    {
        return $this->morphTo();
    }


    /**
     * Get the parent new model activity entry
     */
    public function element()
    {
        return $this->morphTo();
    }


    public function getActivityAttribute()
    {
        switch ($this->new_type)
        {
            case 'App\Actionability':
                return 'Clinical Actionability';
            case 'App\Validity':
                return 'Gene-Disease Validity';
            case 'App\Sensitivity':
                return 'Dosage Sensitivity';
            case 'App\Variantpath':
                return 'Variant Pathogenicity';
        }

        return 'Unknown';

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
     * Query scope by change date
     *
     * @@param	string	$hgnc
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeChange($query, $date)
    {
		return $query->where('change_date', $date);
    }


    /**
     * Query scope by start date
     *
     * @@param	Carbon	$date
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeStart($query, $date)
    {
        // reset any time components to midnight
        $date->hour(0)->minute(0)->second(0);

		return $query->where('change_date', '>=', $date);
    }


    /**
     * Query scope by stop date
     *
     * @@param	Carbon	$date
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeStop($query, $date)
    {
        // reset any time components to just before midnight
        $date->hour(23)->minute(59)->second(59);

		return $query->where('change_date', '<=', $date);
    }


    /**
     * Query scope by filters
     *
     * @@param	json	$filters
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeFilters($query, $filters)
    {
        if (isset($filters['first']) && $filters['first'] == "on")
        {

            $query = $query->whereHas('new', function($subquery) use($genes){
                return $subquery->whereIn('gene_label', $genes);
            })->groupBy('element_id');

            return $query;
        }


        if (isset($filters['gene_label']))
        {
            $genes = $filters['gene_label'];

            // wildcard all genes shortcut
            if (in_array('*', $genes))
                return $query;

            $query = $query->whereHas('new', function($subquery) use($genes){
                return $subquery->whereIn('gene_label', $genes);
            });

            // internal groups
            if (in_array('@AllValidity', $genes))
                $query = $query->orWhere('new_type', 'App\Validity');

            if (in_array('@AllDosage', $genes))
                $query = $query->orWhere('new_type', 'App\Sensitivity');

            if (in_array('@AllActionability', $genes))
                $query = $query->orWhere('new_type', 'App\Actionability');

            if (in_array('@AllVariant', $genes))
                $query = $query->orWhere('new_type', 'App\Variantpath');

            if (in_array('@ACMG59', $genes))
            {
                $query = $query->orWhereHas('element', function ($query){
                    $query->where('acmg59', 1);
                });
            }

            // regions
            $groups = preg_grep('/^\%.*/', $genes);
            foreach ($groups as $group)
            {
                // get list genes
                $region = Group::where('search_name', $group)->first();

                // if this is a non-recurrent report, build a psuedo region
                if ($region === null)
                {
                    $split = explode('||', substr($group,1));
                    if ($split === false)
                        continue;

                    if (Region::checkRegion($split[0]) == false)
                        continue;

                    $type = $split[1] ?? Group::TYPE_REGION_37;

                    $region = new Group(['type' => $type,
                                         'description' => $split[0],
                                        'option' => 1 ]);
                }

                $type = ($region->type == Group::TYPE_REGION_38 ? 'GRCh38' : 'GRCh37');

                $temp = Gene::searchList(['type' => $type,
                        "region" => $region->description,
                        'option' => 1 ]);

                $items = $temp->collection->pluck('name');

                $query = $query->orWhereHas('element', function ($query) use ($items) {
                            $query->whereIn('name', $items);
                        });
            }

            // panels
            $groups = preg_grep('/^\!.*/', $genes);
            foreach ($groups as $group)
            {
                // get list genes
                $panel = Panel::ident(substr($group, 1))->first();

                // if this is a non-recurrent report, build a psuedo region
                if ($panel === null)
                {
                    continue;
                }


                $temp = $panel->genes;

                $items = $temp->pluck('name');

                $query = $query->orWhereHas('element', function ($query) use ($items) {
                            $query->whereIn('name', $items);
                        });
            }

        }

        /*$a = vsprintf(str_replace('?', '%s', $query->toSql()), collect($query->getBindings())->map(function ($binding) {
            return is_numeric($binding) ? $binding : "'{$binding}'";
        })->toArray());
        dd($a);*/

		return $query;
    }


    /**
     * Query scope by filters
     *
     * @@param	json	$filters
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeDiseaseFilters($query, $filters)
    {
        if (isset($filters['first']) && $filters['first'] == "on")
        {

            if (isset($filters['disease_label']))
            {
                $diseases = $filters['disease_label'];

                $query = $query->whereHas('new', function($subquery) use($diseases){
                    return $subquery->whereIn('gene_label', $diseases);
                })->groupBy('element_id');

                return $query;
            }
        }


        if (isset($filters['disease_label']))
        {
            $diseases = $filters['disease_label'];

            $query = $query->whereHas('new', function($subquery) use($diseases){
                return $subquery->whereIn('disease_label', $diseases);
            });

        }

		return $query;
    }



    /**
     * Retrieve, compare, and load a fresh dataset
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function assertions()
    {

    }
}
