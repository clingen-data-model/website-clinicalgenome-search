<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Display;

use Uuid;

/**
 *
 * @category   Library
 * @package    Search
 * @author     P. Weller <pweller1@geisinger.edu>
 * @copyright  2020 ClinGen
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/PackageName
 * @see        NetOther, Net_Sample::Net_Sample()
 * @since      Class available since Release 1.0.0
 *
 * */
class Omim extends Model
{
    use SoftDeletes;
    use Display;

     /**
     * The attributes that should be validity checked.
     *
     * @var array
     */
    public static $rules = [];

    /**
     * The attributes that are mass assignable.  Remember to fill it
     * in when all the attributes are known.
     *
     * @var array
     */
     protected $fillable = ['prefix', 'omimid', 'titles', 'alt_titles', 'hgnc_id',
                            'inc_titles', 'status', 'type' ];

	  /**
     * Non-persistent storage model attributes.
     *
     * @var array
     */
    protected $appends = [];


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
     * Query scope by ident
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function scopeOmimid($query, $ident)
    {
      return $query->where('omimid', $ident);
    }


    /**
     * Query title for omim id
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
    public static function titles($id)
    {
      $record = self::omimid($id)->first();

      if ($record === null)
        return '';

      return $record->titles;
    }
}
