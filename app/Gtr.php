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
class Gtr extends Model
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
          'type' => 'integer',
          'status' => 'integer'
	];

	/**
     * Map the json attributes to associative arrays.
     *
     * @var array
     */
	protected $casts = [
            'state_licenses' => 'array',
            'state_license_numbers' => 'array',
            'condition_identifiers' => 'array',
            'indication_types' => 'array',
            'inheritances' => 'array',
            'method_categories' => 'array',
            'methods' => 'array',
            'platforms' => 'array',
            'genes' => 'array',
            'drug_responses' => 'array'
		];

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = [ 'ident', 'type', 'status',
                            'test_accession_ver', 'name_of_laboratory', 'name_of_institution', 'facility_state',
                            'facility_postcode', 'facility_country', 'CLIA_number', 'state_licenses', 'state_license_numbers',
                            'lab_test_id', 'last_touch_date', 'lab_test_name', 'manufacturer_test_name', 'test_development',
                            'lab_unique_code', 'condition_identifiers', 'indication_types', 'inheritances', 'method_categories',
                            'methods', 'platforms', 'genes', 'drug_responses', 'now_current', 'test_currStat',
                            'test_pubStat', 'lab_currStat', 'lab_pubStat', 'test_create_date', 'test_deletion_data',
                            'version',
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
}
