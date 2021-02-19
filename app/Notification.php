<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
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

class Notification extends Model
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
		'primary' => 'json|required',
		'secondary' => 'json|nullable',
        'frequency' => 'json|nullable',
		'type' => 'integer',
		'status' => 'integer'
	];

	/**
     * Map the json attributes to associative arrays.
     *
     * @var array
     */
	protected $casts = [
			'primary' => 'array',
            'secondary' => 'array',
            'frequency' => 'array'
		];

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = ['ident', 'user_id', 'primary', 'secondary',
					        'frequency', 'type', 'status',
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
     
     public const FREQUENCY_NONE = 0;
     public const FREQUENCY_DAILY = 1;
     public const FREQUENCY_WEEKLY = 2;
     public const FREQUENCY_SEMI_MONTHLY = 3;
     public const FREQUENCY_MONTHLY = 4;
     public const FREQUENCY_EVERY2MONTHS = 5;
     public const FREQUENCY_QUARTERLY = 6;
     public const FREQUENCY_SEMI_ANNUAL = 7;
     public const FREQUENCY_ANNUAL = 8;


     /*
     * Frequency strings for display methods
     *
     * */
     protected $frequency_strings = [
          self::FREQUENCY_NONE => 'None',
          self::FREQUENCY_DAILY => 'Daily',
          self::FREQUENCY_WEEKLY => 'Weekly',
          self::FREQUENCY_SEMI_MONTHLY => 'Semimonthly',
          self::FREQUENCY_MONTHLY => 'Monthly',
          self::FREQUENCY_EVERY2MONTHS => 'Every 2 Months',
          self::FREQUENCY_QUARTERLY => 'Quarterly',
          self::FREQUENCY_SEMI_ANNUAL => 'Semiannual',
          self::FREQUENCY_ANNUAL => 'annual'
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
     

     /*
     * The owner of this notification
     */
    public function user()
    {
       return $this->belongsTo('App\User');
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
     * Assert if the value is selected or not
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function checked($attribute, $value)
     {
          if (!isset($this->frequency[$attribute]))
               return '';

          return ($this->frequency[$attribute] == $value ? 'checked' : '');
     }


     /**
     * Convert the stored constant to hours
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function chetoHourscked($value)
     {
          switch ($value)
          {
               case self::FREQUENCY_NONE:
                    return -1;
               case self::FREQUENCY_DAILY:
                    return 24;
               case self::FREQUENCY_WEEKLY:
                    return 168;
               case self::FREQUENCY_SEMI_MONTHLY:
                    return 336;
               case self::FREQUENCY_MONTHLY:
                    return 720;
               case self::FREQUENCY_EVERY2MONTHS:
                    return 1440;
               case self::FREQUENCY_QUARTERLY:
                    return 2160;
               case self::FREQUENCY_SEMI_ANNUAL:
                    return 4320;
               case self::FREQUENCY_ANNUAL:
                    return 8790;
               default: 
                    return -1;
          }
     }
}
