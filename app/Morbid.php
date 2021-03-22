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
 * @copyright  2020 ClinGen
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/PackageName
 * @see        NetOther, Net_Sample::Net_Sample()
 * @since      Class available since Release 1.0.0
 *
 * */
class Morbid extends Model
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
        'genes' => 'array'
    ];

    /**
     * The attributes that are mass assignable.  Remember to fill it
     * in when all the attributes are known.
     *
     * @var array
     */
    protected $fillable = ['ident', 'phenotype', 'secondary', 'pheno_omim', 'mim', 'mapkey',
                            'nondisease', 'mutations',
                            'disputing', 'genes', 'cyto', 'status', 'type' ];

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
     * Parse the phenotype string to separate out titles, omim, and map key
     */
    public static function parsePhenotype($string, $secondary = true)
    {
        // we need to parse in reverse because some of the delimiters are used in text
        // probably cleaner to do this in preg_match, but for now...

        $struct = [ 'primary' => '',
                    'secondary' => null,
                    'omim' => '',
                    'map' => ''
                ];

        // primary [; secondary], omim (map)
        if (($k = strrpos($string, ')')) === 0)
            return $struct;

        $j = strrpos($string, '(');
        $struct['map'] = substr($string, $j + 1, 1);

        $string = substr($string, 0, $j - 1);
        $string = trim($string);

        // primary [; secondary], omim
        $j = strrpos($string, ' ');
        $temp = substr($string, $j + 1);
        if (is_numeric($temp))
        {
            $struct['omim'] = $temp;
            $string = substr($string, 0, $j - 1);
            $string = trim($string);
        }

        // primary [; secondary],
        if ($secondary)
        {
            $j = strrpos($string, ';');
            if ($j !== false)
            {
                $struct['secondary'] = substr($string, $j);

                $string = substr($string, 0, $j - 1);
                $string = trim($string);
            }
        }

        $struct['primary'] = $string;

        return $struct;
    }
}
