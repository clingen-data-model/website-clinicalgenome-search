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
class Acmg extends Model
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
		'type' => 'integer',
        'ident' => 'alpha_dash|max:80|required',
        'gene_id' => 'integer',
        'gene_symbol' => 'string',
        'gene_mim' => 'string',
        'disease_id' => 'integer',
        'disease_symbol' => 'string',
        'disease_mims' => 'json',
        'documents' => 'json',
        'demographics' => 'json',
        'scores' => 'json',
        'clinvar_link' => 'string',
        'is_curated' => 'integer',
		'status' => 'integer'
	];

	/**
     * Map the json attributes to associative arrays.
     *
     * @var array
     */
	protected $casts = [
			'disease_mims' => 'array',
            'documents' => 'array',
            'demographics' => 'array',
            'scores' => 'array'
     ];

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = [ 'type',  'ident', 'gene_id', 'gene_symbol', 'gene_mim', 'disease_id',
                            'disease_symbol', 'disease_mims', 'documents',  'demographics',
                            'scores', 'clinvar_link', 'is_curated', 'status'];

	/**
     * Non-persistent storage model attributes.
     *
     * @var array
     */
     protected $appends = ['display_date', 'list_date', 'display_status',
                           ];

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
     public function location()
     {
		return $this->hasOne('App\Location');
     }


    /**
     * The gene associated with this ACMF finding
     */
    public function gene()
    {
       return $this->belongsTo('App\Gene');
    }


    /**
     * The disease associated with this ACMF finding
     */
    public function disease()
    {
       return $this->belongsTo('App\Disease');
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
