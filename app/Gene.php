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
class Gene extends Model
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
		'name' => 'name|max:80|required',
		'hgnc_id' => 'string|nullable',
          'description' => 'string|nullable',
          'location' => 'string|nullable',
		'alias_symbol' => 'json|nullable',
		'prev_symbol' => 'json|nullable',
		'date_symbol_changed' => 'string|nullable',
		'hi' => 'string|nullable',
		'plof' => 'string|nullable',
		'pli' => 'string|nullable',
		'haplo' => 'string|nullable',
		'triplo' => 'string|nullable',
		'notes' => 'string|nullable',
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
			'prev_symbol' => 'array'
		];

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = ['name', 'hgnc_id', 'description', 'location', 'alias_symbol',
					   'prev_symbol', 'date_symbol_changed', 'hi', 'plof', 'pli',
					   'haplo', 'triplo', 'type', 'notes', 'status' ];

	/**
     * Non-persistent storage model attributes.
     *
     * @var array
     */
     protected $appends = ['display_date', 'list_date', 'display_status',
							'display_aliases', 'display_previous'];

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
     * Set all names to uppercase
     *
     * @param
     * @return string
     */
    //public function setNameAttribute($value)
	//{
	//	$this->attributes['name'] = strtoupper($value);
	//}


	/**
     * Get a display formatted form of aliases
     *
     * @@param
     * @return
     */
     public function getDisplayAliasesAttribute()
     {
		if (empty($this->alias_symbol))
			return 'No aliases found';

		return implode(', ', $this->alias_symbol);
	}


	/**
     * Get a display formatted form of previous names
     *
     * @@param
     * @return
     */
     public function getDisplayPreviousAttribute()
     {
		if (empty($this->prev_symbol))
			return 'No previous names found';

		return implode(', ', $this->prev_symbol);
	}

}
