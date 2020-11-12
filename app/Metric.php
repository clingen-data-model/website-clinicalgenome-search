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
class Metric extends Model
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
		'key' => 'string',
		'value' => 'string',
        'type' => 'integer',
		'status' => 'integer'
	];

	/**
     * Map the json attributes to associative arrays.
     *
     * @var array
     */
	protected $casts = [
		];

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = ['owner', 'key', 'value', 'type', 'status' ];

	/**
     * Non-persistent storage model attributes.
     *
     * @var array
     */
    protected $appends = ['display_date', 'list_date', 'display_status'];

    public const TYPE_NONE = 0;


    public const STATUS_INITIALIZED = 0;

    /*
    * Standard Metric Keys
    */
    public const KEY_TOTAL_CURATED_GENES = "Total Curated Genes";


    /*
    * Status strings for display methods
    *
    * */
    protected $status_strings = [
        0 => 'Initialized',
        1 => 'Successful',
        2 => 'Failed',
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
     * Query scope by key
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeKey($query, $key)
    {
		return $query->where('key', $key);
    }

  /**
   * Add or update a metric
   * 
   */
  public static function store($key, $value)
  {
    self::updateOrCreate(['key' => $key], ['value' => $value]);
  }


  /**
   * Add or update a metric
   * 
   */
  public static function show($key, $default = null)
  {
    $metric = self::key($key)->first();

    return ($metric === null ? $default : $metric->value);
  }
}
