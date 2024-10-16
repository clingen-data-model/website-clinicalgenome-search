<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
class Reportable extends Model
{
    use HasFactory;
    use SoftDeletes;

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
        'ident', 'type', 'gene_symbol', 'gene_hgnc_id', 'disease_name', 'disease_mondo_id',
        'moi', 'reportable', 'comment', 'status'
    ];

    /**
     * Non-persistent storage model attributes.
     *
     * @var array
     */
    protected $appends = [];

    public const TYPE_NONE = 0;
    public const TYPE_ACTIVE = 1;

    /*
    * Type strings for display methods
    *
    * */
    protected $type_strings = [
        0 => 'Unknown',
        1 => "Active",
    ];

    public const STATUS_INITIALIZED = 0;
    public const STATUS_ACTIVE = 1;
    public const STATUS_DELETED = 2;

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
    * Query scope by symbol name
    *
    * @@param	string	$ident
    * @return Illuminate\Database\Eloquent\Collection
    */
    public function scopeSymbol($query, $name)
    {
       return $query->where('gene_symbol', $name);
    }


    /**
    * Query scope by hgncid name
    *
    * @@param	string	$ident
    * @return Illuminate\Database\Eloquent\Collection
    */
    public function scopeHgnc($query, $id)
    {
       return $query->where('gene_hgnc_id', $id);
    }


    /**
    * Query scope by disease mondo id
    *
    * @@param	string	$ident
    * @return Illuminate\Database\Eloquent\Collection
    */
    public function scopeMondo($query, $id)
    {
       return $query->where('disease_mondo_id', $id);
    }

}
