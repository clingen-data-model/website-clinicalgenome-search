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
            'triplo_other' => 'array'
		];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = ['ident', 'element_id', 'element_type', 'old_id', 'old_type', 'new_id', 'new_type',
                            'type', 'category', 'change_date', 'status',
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
        // the only filter we recognize right now is the gene label
        if (isset($filters['gene_label']))
        {
            $genes = $filters['gene_label'];

            // wildcard all genes shortcut
            if (in_array('*', $genes))
                return $query;

            return $query->whereHas('new', function($subquery) use($genes){
                return $subquery->whereIn('gene_label', $genes);
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
