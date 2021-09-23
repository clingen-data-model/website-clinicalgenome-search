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
class Group extends Model
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
          'display_name' => 'string|nullable',
          'search_name' => 'string|nullable',
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
		];

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = ['ident', 'name', 'display_name', 'search_name',
                            'user_id', 'description', 'type', 'status'];

	/**
     * Non-persistent storage model attributes.
     *
     * @var array
     */
     protected $appends = ['display_date', 'list_date', 'display_status'];

     public const TYPE_INTERNAL = 0;
     public const TYPE_REGION_37 = 1;
     public const TYPE_REGION_38 = 2;

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
    public function user()
    {
       return $this->belongsTo('App\User');
    }


    /*
     * The users following this group
     */
    public function users()
    {
       return $this->belongsToMany('App\User');
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
	public function scopeName($query, $name)
    {
        return $query->where('name', $name);
    }


    /**
     * Query scope by search name
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeSearch($query, $name)
    {
        return $query->where('search_name', $name);
    }


    /**
     * Initialize with known groups
     */
    public static function intializeGroups()
    {
        $t = new Group(['name' => '@AllGenes', 'display_name' => 'All Genes', 'search_name' => '*',
                        'description' => 'Follow All Genes']);
        $t->save();

        $t = new Group(['name' => '@AllDosage', 'display_name' => 'All Dosage', 'search_name' => '@AllDosage',
                        'description' => 'Follow All Dosage Sensitivity Activity']);
        $t->save();

        $t = new Group(['name' => '@AllValidity', 'display_name' => 'All Validity', 'search_name' => '@AllValidity',
                        'description' => 'Follow All Gene-Disease Validity Activity']);
        $t->save();

        $t = new Group(['name' => '@AllActionability', 'display_name' => 'All Actionability', 'search_name' => '@AllActionability',
                        'description' => 'Follow All Clinical Actionability Activity']);
        $t->save();

        $t = new Group(['name' => '@ACMG59', 'display_name' => 'ACMG 59 Genes', 'search_name' => '@ACMG59',
                        'description' => 'Follow All ACMG 59 Genes']);
        $t->save();

        $t = new Group(['name' => '@AllVariant', 'display_name' => 'All Variant', 'search_name' => '@AllVariant',
                        'description' => 'Follow All Variant Pathogenicity Activity']);
        $t->save();
    }


    public static function parse_group($group)
    {
        // only works for region right now
        if (strpos($group, '%') === 0)
            $group = substr($group, 1);

        $parts = explode('||', $group);

        return $parts[0];
    }

}
