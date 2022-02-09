<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Display;

use Uuid;

/**
 *
 * @category   Library
 * @package    Search
 * @author     P. Weller <pweller1@geisinger.edu>
 * @copyright  2021 ClinGen
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/PackageName
 * @see        NetOther, Net_Sample::Net_Sample()
 * @since      Class available since Release 1.0.0
 *
 * */
class Mim extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Display;

     /**
     * The attributes that should be validity checked.
     *
     * @var array
     */
    public static $rules = [];

    /**
     * Map the json attributes to associative arrays.
     *
     * @var array
     */
	protected $casts = [
    ];

    /**
     * The attributes that are mass assignable.  Remember to fill it
     * in when all the attributes are known.
     *
     * @var array
     */
     protected $fillable = ['type', 'mim', 'gene_name', 'gene_id', 'title',
                        'moi', 'map_key', 'status' ];

	  /**
     * Non-persistent storage model attributes.
     *
     * @var array
     */
    protected $appends = [];

    public const TYPE_NONE = 0;
    public const TYPE_PHENO = 1;
    public const TYPE_GENE = 2;


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
     * The gene associated with this MIM
     */
    public function gene()
    {
       return $this->belongsTo('App\Gene');
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
     * Query scope by MIM id
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function scopeMim($query, $mim)
    {
      return $query->where('mim', $mim);
    }


    /**
     * Query scope type is pheno
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function scopePheno($query)
    {
      return $query->where('type', self::TYPE_PHENO);
    }


    /**
     * Query scope by type is gene
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function scopeGene2($query)
    {
      return $query->where('type', self::TYPE_GENE);
    }
}
