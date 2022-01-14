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
 * @package    DBDGenes
 * @author     P. Weller <pweller1@geisinger.edu>
 * @copyright  2019 Geisinger
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/PackageName
 * @see        NetOther, Net_Sample::Net_Sample()
 * @since      Class available since Release 1.0.0
 *
 * */
class Gdmmap extends Model
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
		'gdm_uuid' => 'string',
        'gg_uuid' => 'string',
		'type' => 'integer',
		'status' => 'integer'
	];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = [ 'gdm_uuid', 'gg_uuid', 'type', 'status' ];

	/**
     * Non-persistent storage model attributes.
     *
     * @var array
     */
    protected $appends = ['display_date', 'list_date', 'display_status'];


    //public const TYPE_MISSENSE = 1;

    /*
     * Status strings for display methods
     *
     * */
     protected $status_strings = [
	 		0 => 'Initialized',
	 		1 => 'Active',
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
     * Query scope by GDM uuid
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeGdm($query, $ident)
    {
		return $query->where('gdm_uuid', $ident);
    }


    /**
     * Query scope by Genegraph uuid
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeGg($query, $ident)
    {
		return $query->where('gg_uuid', $ident);
    }



}
