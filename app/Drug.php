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
class Drug extends Model
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
		'curie' => 'name|max:80|required',
		'label' => 'string|nullable',
          'curation_activities' => 'json|nullable',
          'last_curated_date' => 'string|nullable',
		'type' => 'integer',
		'status' => 'integer'
	];

	/**
     * Map the json attributes to associative arrays.
     *
     * @var array
     */
	protected $casts = [
			'synonyms' => 'array',
               'curation_activities' => 'array',
		];

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = ['iri', 'curie', 'label', 'curation_activities', 'last_curated_date',
					     'type', 'status',
                         ];

	/**
     * Non-persistent storage model attributes.
     *
     * @var array
     */
     protected $appends = ['display_date', 'list_date', 'display_status',
                            'first_synonym'];

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
     * Query scope by symbol name
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeCurie($query, $curie)
    {
		return $query->where('curie', $curie);
    }


    /**
     * Flag indicating if gene has any dosage curations 
     * 
     * @@param	
     * @return 
     */
    public function getHasDosageAttribute()
    {
		return (isset($this->curation_activities) ? 
			$this->curation_activities['dosage'] : false); 
     }
     

     /**
     * Flag indicating if gene has any actionability curations 
     * 
     * @@param	
     * @return 
     */
    public function getHasActionabilityAttribute()
    {
		return (isset($this->curation_activities) ? 
			$this->curation_activities['actionability'] : false); 
     }
     

     /**
     * Flag indicating if gene has any validity curations 
     * 
     * @@param	
     * @return 
     */
    public function getHasValidityAttribute()
    {
		return (isset($this->curation_activities) ? 
			$this->curation_activities['validity'] : false); 
     }
     

     /**
     * Flag indicating if gene has any pharma activity 
     * 
     * @@param	
     * @return 
     */
     public function getHasPharmaAttribute()
     {
		return (isset($this->curation_activities) ? 
			$this->curation_activities['pharma'] : false); 
	}
}
