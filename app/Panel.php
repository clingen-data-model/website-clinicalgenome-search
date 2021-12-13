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
          'affiliate_id' => 'string',
          'title' => 'string',
          'title_short' => 'string',
          'title_abbreviated' => 'string',
          'affiliate_id' => 'string',
          'affiliate_type' => 'string',
          'affiliate_status' => 'json',
          'cdwg_parent_name' => 'string',
          'contacts' => 'json|nullable',
          'member' => 'json|nullable',
          'summary' => 'string|nullable',
          'type' => 'integer',
          'status' => 'integer'
	];

	/**
     * Map the json attributes to associative arrays.
     *
     * @var array
     */
	protected $casts = [
            'contacts' => 'array',
            'affiliate_status' => 'array',
            'member' => 'array',
            'contact' => 'array'
		];

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = ['ident', 'name', 'affiliate_id', 'alternate_id', 'title', 'title_short',
                            'title_abbreviated', 'affiliate_type', 'affiliate_status',
                            'cdwg_parent_name', 'member', 'contacts',
                           'summary', 'type', 'status'];

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
            1 => 'GCEP',
            2 => 'VCEP',
            3 => 'WG',
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


    /*
     * The diseases associated with this group
     */
    public function diseases()
    {
       return $this->belongsToMany('App\Disease');
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
	public function scopeAffiliate($query, $id)
    {
        return $query->where('affiliate_id', $id);
    }


    /**
     * Query scope by wither group id or alternate
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeAllids($query, $id)
    {
        return $query->where('affiliate_id', $id)->orWhere('alternate_id', $id);
    }


    /**
     * Query scope by gcep type
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeGcep($query)
    {
        return $query->where('type', self::TYPE_GCEP);
    }


    /**
     * Query scope by vcep type
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeVcep($query)
    {
        return $query->where('type', self::TYPE_VCEP);
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
                //$t = substr($this->name, 3);
                //$k = strpos($t, ' ');
                //return substr($t, 0, $k);
                return $this->affiliate_id;
            default:
                //return $this->name;
                return $this->affiliate_id;
        }

        return '';
    }


    /**
     * Return an best choice of titles
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getSmartTitleAttribute()
    {
        if (!empty($this->title_short))
            return $this->title_short . ' ' . $this->type_string;

        if (!empty($this->title_abbreviated))
            return $this->title_abbreviated;

        return $this->title;
    }


    /**
     * Return an href suitable identifier
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function getTypeStringAttribute()
    {
        switch ($this->affiliate_type)
        {
            case 'gcep':
            case 'vcep':
                return strtoupper($this->affiliate_type);
            case 'working group':
                return 'Working Group';
            default:
                return '';
        }

        return '';
    }


    /**
     * Map genegraph curie to affiliate ID
     */
    public static function gg_map_to_panel($curie, $adjust = false)
    {
        $curie = (strpos($curie, 'CGAGENT:') === 0 ? substr($curie, 8) : $curie);

        if (!$adjust)
            return $curie;

        if ($curie < 20000)
            $curie += 30000;

        return $curie;


    }


    /**
     * Map genegraph curie to affiliate ID
     */
    public static function erepo_map_to_panel($curie)
    {
        //CG-PCER-AGENT:CG_50015_EP.1551905782.01949",
        if (strpos($curie, 'CG-PCER-AGENT:CG_') === 0)
        {
            $k = substr($curie, 17);
            $curie = substr($k, 0, strpos($k, '_'));
        }

        return $curie;
    }
}
