<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

use Uuid;
use Str;

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
class Slug extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that should be validity checked.
     *
     * @var array
     */
    public static $rules = [
		'ident' => 'alpha_dash|max:80|required',
        'type' => 'integer',
        'subtype' => 'integer',
        'alias' => 'string',
        'target' => 'string',
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
	protected $fillable = ['ident', 'type', 'subtype', 'alias', 'target', 'status'
                         ];

	/**
     * Non-persistent storage model attributes.
     *
     * @var array
     */
    protected $appends = ['display_date', 'list_date', 'display_status'];

    public const TYPE_NONE = 0;
    public const TYPE_CURATION = 1;

    public const SUBTYPE_VALIDITY = 1;

    /*
     * Type strings for display methods
     *
     * */
    protected $type_strings = [
	 		0 => 'Unknown',
            1 => 'Curation',
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


    public static function boot()
    {
      parent::boot();

      /**
       * Create a numeric slug based on the primary key value
       */
      self::created(function($model){

        // reserve the first 4000 entries for possible backfilling
        $model->update(['alias' => 'CCID:' . str_pad($model-> id + 4000, 6, "0", STR_PAD_LEFT)]);

      });
    }

    /**
     * Automatically assign an ident on instantiation
     *
     * @param	array	$attributes
     * @return 	void
     */
    public function __construct(array $attributes = array())
    {
        $this->attributes['ident'] = (string) Uuid::generate(4);
        $this->attributes['alias'] = strtoupper('TEMP:' . Str::random(8));
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
   * Query scope by alias
   *
   * @@param	string	$ident
   * @return Illuminate\Database\Eloquent\Collection
   */
	public function scopeAlias($query, $ident)
  {
		return $query->where('alias', $ident);
  }


  /**
   * Query scope by target
   *
   * @@param	string	$ident
   * @return Illuminate\Database\Eloquent\Collection
   */
	public function scopeTarget($query, $ident)
  {
		return $query->where('target', $ident);
  }
}
