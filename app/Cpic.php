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
class Cpic extends Model
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
		'gene' => 'name|max:80|required',
		'drug' => 'string|nullable',
        'guideline' => 'string|nullable',
        'cpic_level' => 'string|nullable',
		'cpic_level_status' => 'json|nullable',
		'pharmgkb_level_of_evidence' => 'json|nullable',
        'pgx_on_fda_label' => 'string|nullable',
        'cpic_publications_pmid' => 'string|nullable',
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
		];

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = ['gene', 'drug', 'guideline', 'cpic_level', 'cpic_level_status',
                            'pharmgkb_level_of_evidence', 'pgx_on_fda_label', 'cpic_publications_pmid',
                            'hgnc_id', 'pa_id', 'is_vip', 'has_va', 'had_cpic_guideline',
                            'notes', 'type', 'status' ];

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
     * Access the devices associated with this clinic
     * 
     * @return Illuminate\Database\Eloquent\Collection
     */
     /*public function location()
     {
		return $this->hasOne('App\Location');
     }*/
     

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
	public function scopeGene($query, $gene)
    {
    return $query->where('gene', $gene)->orderBy('guideline')->orderBy('drug');
    }

}
