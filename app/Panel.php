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
class Panel extends Model
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
          'name' => 'string',
          'curie' => 'string',
          'contacts' => 'string|nullable',
          'description' => 'string|nullable',
          'type' => 'integer',
          'status' => 'integer'
	];

	/**
     * Map the json attributes to associative arrays.
     *
     * @var array
     */
	protected $casts = [
            'contacts' => 'array'
		];

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = ['ident', 'name', 'curie', 'contacts',
                           'description', 'type', 'status'];

	/**
     * Non-persistent storage model attributes.
     *
     * @var array
     */
     protected $appends = ['display_date', 'list_date', 'display_status'];

     public const TYPE_INTERNAL = 0;
     public const TYPE_GCEP = 1;
     public const TYPE_VCEP = 2;
     public const TYPE_WG = 3;

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


    /*
     * The users following this group
     */
    public function users()
    {
       return $this->belongsToMany('App\User');
    }


    /*
     * The genes associated with this group
     */
    public function genes()
    {
       return $this->belongsToMany('App\Gene');
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
     * Query scope by group name
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeName($query, $name)
    {
        return $query->where('name', $name);
    }


    /**
     * Query scope by group id
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeCurie($query, $curie)
    {
        return $query->where('curie', $curie);
    }


    /**
     * Return an href suitable identifier
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function getHrefAttribute()
    {
        switch ($this->type)
        {
            case self::TYPE_GCEP:
            case self::TYPE_VCEP:
                $t = substr($this->name, 3);
                $k = strpos($t, ' ');
                return substr($t, 0, $k);
            default:
                return $this->name;
        }
        return $query->where('curie', $curie);
    }
}
