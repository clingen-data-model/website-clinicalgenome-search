<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Display;

use Uuid;

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
class Location extends Model
{
    use SoftDeletes;
    use Display;

    /**
     * The attributes that should be validity checked.
     *
     * @var array
     */
    public static $rules = [
		'ident' => 'alpha_dash|max:80|required',
        'gene_id' => 'integer|nullable',
        'cytoband' => 'string|nullable',
        'chromosome' => 'integer|nullable',
        'start' => 'integer|nullable',
		'stop' => 'integer|nullable',
		'stain' => 'integer|nullable',
		'type' => 'integer',
		'status' => 'integer'
	];

	/**
     * Map the json attributes to associative arrays.
     *
     * @var array
     */
	protected $casts = [
			'alias_symbol' => 'array',
               'prev_symbol' => 'array',
               'omim_id' => 'array',
               'history' => 'array'
		];

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = ['name', 'gene_id', 'cytoband', 'chromosome', 'start',
					        'stop', 'stain', 'type', 'status' ];

	/**
     * Non-persistent storage model attributes.
     *
     * @var array
     */
     protected $appends = ['display_date', 'list_date', 'display_status' ];

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
     * Access the gene associated with this device
     * 
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function gene()
    {
		return $this->belongsTo('App\Gene');
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
     * Query scope by symbol name
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeCytoband($query, $name)
    {
		return $query->where('cytoband', $name);
    }

}
