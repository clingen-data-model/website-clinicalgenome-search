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
          'values' => 'json',
          'type' => 'integer',
          'status' => 'integer'
    ];

	/**
     * Map the json attributes to associative arrays.
     *
     * @var array
     */
	protected $casts = [
                'values' => 'json'
		];

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = ['owner', 'values', 'type', 'status' ];

	/**
     * Non-persistent storage model attributes.
     *
     * @var array
     */
    protected $appends = ['display_date', 'list_date', 'display_status'];

    public const TYPE_NONE = 0;
    public const TYPE_SYSTEM = 1;


    public const STATUS_INITIALIZED = 0;

    /*
    * Standard Metric Keys
    */
    public const KEY_TOTAL_CURATED_GENES = "total_curated_genes";
    public const KEY_TOTAL_VALIDITY_GENES = "total_validity_genes";
    public const KEY_TOTAL_ACTIONABILITY_GENES = "total_actionability";
    public const KEY_TOTAL_DOSAGE_GENES = "total_dosage_genes";

    public const KEY_TOTAL_GENE_LEVEL_CURATIONS = "total_gene_level_curations";
    
    public const KEY_TOTAL_VALIDITY_CURATIONS = "total_validity_curations";
    public const KEY_TOTAL_VALIDITY_DEFINITIVE = "total_validity_definitive";
    public const KEY_TOTAL_VALIDITY_STRONG = "total_validity_strong";
    public const KEY_TOTAL_VALIDITY_MODERATE = "total_validity_moderate";
    public const KEY_TOTAL_VALIDITY_LIMITED = "total_validity_limited";
    public const KEY_TOTAL_VALIDITY_DISPUTED = "total_validity_disputed";
    public const KEY_TOTAL_VALIDITY_REFUTED = "total_validity_refuted";
    public const KEY_TOTAL_VALIDITY_NONE = "total_validity_none";

    public const KEY_TOTAL_DOSAGE_CURATIONS = "total_dosage_curations";
    public const KEY_TOTAL_DOSAGE_HAP_NONE = "total_dosage_hap_none";
    public const KEY_TOTAL_DOSAGE_HAP_LITTLE = "total_dosage_hap_little";
    public const KEY_TOTAL_DOSAGE_HAP_EMERGING = "total_dosage_hap_emerging";
    public const KEY_TOTAL_DOSAGE_HAP_SUFFICIENT = "total_dosage_hap_sufficient";
    public const KEY_TOTAL_DOSAGE_HAP_AR = "total_dosage_hap_ar";
    public const KEY_TOTAL_DOSAGE_HAP_UNLIKELY = "total_dosage_hap_unlikely";

    public const KEY_TOTAL_DOSAGE_TRIP_NONE = "total_dosage_trip_none";
    public const KEY_TOTAL_DOSAGE_TRIP_LITTLE = "total_dosage_trip_little";
    public const KEY_TOTAL_DOSAGE_TRIP_EMERGING = "total_dosage_trip_emerging";
    public const KEY_TOTAL_DOSAGE_TRIP_SUFFICIENT = "total_dosage_trip_sufficient";
    public const KEY_TOTAL_DOSAGE_TRIP_AR = "total_dosage_trip_ar";
    public const KEY_TOTAL_DOSAGE_TRIP_UNLIKELY = "total_dosage_trip_unlikely";

    public const KEY_TOTAL_ACTIONABILITY_CURATIONS = "total_actionability_curations";


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
   * Get the definitive percentage of total validity curations
   *
   * @@param
   * @return
   */
  public function getValidityPercentDefinitiveAttribute()
  {
    if (!(isset($this->values[self::KEY_TOTAL_VALIDITY_CURATIONS]) &&
          isset($this->values[self::KEY_TOTAL_VALIDITY_DEFINITIVE])))
            return 0;

    return (int) ($this->values[self::KEY_TOTAL_VALIDITY_DEFINITIVE] /
                $this->values[self::KEY_TOTAL_VALIDITY_CURATIONS] * 100);
  }


  /**
   * Get the strong percentage of total validity curations
   *
   * @@param
   * @return
   */
  public function getValidityPercentStrongAttribute()
  {
    if (!(isset($this->values[self::KEY_TOTAL_VALIDITY_CURATIONS]) &&
          isset($this->values[self::KEY_TOTAL_VALIDITY_STRONG])))
            return 0;

    return (int) ($this->values[self::KEY_TOTAL_VALIDITY_STRONG] /
                $this->values[self::KEY_TOTAL_VALIDITY_CURATIONS] * 100);
  }


  /**
   * Get the moderate percentage of total validity curations
   *
   * @@param
   * @return
   */
  public function getValidityPercentModerateAttribute()
  {
    if (!(isset($this->values[self::KEY_TOTAL_VALIDITY_CURATIONS]) &&
          isset($this->values[self::KEY_TOTAL_VALIDITY_MODERATE])))
            return 0;

    return (int) ($this->values[self::KEY_TOTAL_VALIDITY_MODERATE] /
                $this->values[self::KEY_TOTAL_VALIDITY_CURATIONS] * 100);
  }

  /**
   * Get the limited percentage of total validity curations
   *
   * @@param
   * @return
   */
  public function getValidityPercentLimitedAttribute()
  {
    if (!(isset($this->values[self::KEY_TOTAL_VALIDITY_CURATIONS]) &&
          isset($this->values[self::KEY_TOTAL_VALIDITY_LIMITED])))
            return 0;

    return (int) ($this->values[self::KEY_TOTAL_VALIDITY_LIMITED] /
                $this->values[self::KEY_TOTAL_VALIDITY_CURATIONS] * 100);
  }

  /**
   * Get the disputed percentage of total validity curations
   *
   * @@param
   * @return
   */
  public function getValidityPercentDisputedAttribute()
  {
    if (!(isset($this->values[self::KEY_TOTAL_VALIDITY_CURATIONS]) &&
          isset($this->values[self::KEY_TOTAL_VALIDITY_DISPUTED])))
            return 0;

    return (int) ($this->values[self::KEY_TOTAL_VALIDITY_DISPUTED] /
                $this->values[self::KEY_TOTAL_VALIDITY_CURATIONS] * 100);
  }


  /**
   * Get the disputed percentage of total validity curations
   *
   * @@param
   * @return
   */
  public function getValidityPercentRefutedAttribute()
  {
    if (!(isset($this->values[self::KEY_TOTAL_VALIDITY_CURATIONS]) &&
          isset($this->values[self::KEY_TOTAL_VALIDITY_REFUTED])))
            return 0;

    return (int) ($this->values[self::KEY_TOTAL_VALIDITY_REFUTED] /
                $this->values[self::KEY_TOTAL_VALIDITY_CURATIONS] * 100);
  }


  /**
   * Get the no evidence percentage of total validity curations
   *
   * @@param
   * @return
   */
  public function getValidityPercentNoneAttribute()
  {
    if (!(isset($this->values[self::KEY_TOTAL_VALIDITY_CURATIONS]) &&
          isset($this->values[self::KEY_TOTAL_VALIDITY_NONE])))
            return 0;

    return (int) ($this->values[self::KEY_TOTAL_VALIDITY_NONE] /
                $this->values[self::KEY_TOTAL_VALIDITY_CURATIONS] * 100);
  }


  /**
   * Get the percentage of total dosage genes
   *
   * @@param
   * @return
   */
  public function graphDosagePercentage($a = null)
  {
    if ($a == null)
      return 0;
      
    if (!(isset($this->values[self::KEY_TOTAL_DOSAGE_GENES]) &&
          isset($this->values[$a])))
            return 0;

    return (int) ($this->values[$a] /
                $this->values[self::KEY_TOTAL_DOSAGE_GENES] * 100);
  }

}
