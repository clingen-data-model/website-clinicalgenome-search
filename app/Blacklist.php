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
class Blacklist extends Model
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
	protected $fillable = ['gci_id', 'gg_id', 'gene_symbol', 'disease', 'moi',
					   'gcep', 'grant', 'coordinator', 'notes', 'type', 'status' ];

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

    public const STATUS_ACTIVE = 1;
    public const STATUS_IGNORE = 0;

     /*
     * Status strings for display methods
     *
     * */
     protected $status_strings = [
	 		0 => 'Ignore',
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
     * Query scope by genegraph id
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopegg($query, $id)
    {
       return $query->where('gg_id', $id);
    }

}
