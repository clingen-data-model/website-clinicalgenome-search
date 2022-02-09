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
class ActionabilityAssertion extends Model
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
        "docid" => 'strung',
        "iri" => 'strung',
        "latest_search_date" => 'strung',
        "last_updated" => 'strung',
        "last_author" => 'strung',
        "context" => 'strung',
        "contextiri" => 'strung',
        "release" => 'strung',
        "gene" => 'strung',
        "gene_omim" => 'strung',
        "disease" => 'strung',
        "omim" => 'strung',
        "mondo" => 'strung',
        "consensus_assertion" => 'strung',
        "status_assertion" => 'strung',
        "status_overall" => 'strung',
        "status_stg1" => 'strung',
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
	protected $fillable = [
        'ident', 'type', "docid", "iri", "latest_search_date", "last_updated", "last_author", "context", "contextiri",
        "release", "gene", "gene_omim", "disease", "omim", "mondo", "consensus_assertion", "status_assertion",
        "status_overall", "status_stg1", 'status'
     ];

	/**
     * Non-persistent storage model attributes.
     *
     * @var array
     */
     protected $appends = [];

     public const TYPE_NONE = 0;
     public const TYPE_ADULT = 1;
     public const TYPE_PEDIATRIC = 2;

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
     * Query scope by type
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeType($query, $type)
    {
       return $query->where('type', $type);
    }


    /**
     * Query scope by context
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeContext($query, $context)
    {
       return $query->where('context', $context);
    }


    /**
     * Query scope by classification
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeClassification($query, $class)
    {
       return $query->where('consensus_assertion', $class);
    }
}
