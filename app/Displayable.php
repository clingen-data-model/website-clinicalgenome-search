<?php

namespace App;

use Jenssegers\Model\Model;

use App\Traits\Display;


/**
 *
 * @category   Library
 * @package    Search
 * @author     P. Weller <pweller1@geisinger.edu>
 * @author     S. Goehringer <scottg@creationproject.com>
 * @copyright  2019 ClinGen
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/PackageName
 * @see        NetOther, Net_Sample::Net_Sample()
 * @since      Class available since Release 1.2.0
 * @deprecated Class deprecated in Release 2.0.0
 *
 * */
class Displayable extends Model
{
	use Display;

    /**
     * The attributes that should be validity checked.
     *
     * @var array
     */
    public static $rules = [
	];

    /**
     * The attributes that are mass assignable.  Remember to fill this
     * in when all the attributes are known.
     *
     * @var array
     */
	//protected $fillable = ['name', 'address1', 'address2', 'city', 'state',
	//					   'zip', 'contact', 'phone', 'status' ];

	/**
     * Non-persistent storage model attributes.
     *
     * @var array
     */
    protected $appends = ['has_dosage', 'has_actionability', 'has_validity',
							'last_curated', 'description', 'symbol',
							'curation_flag', 'dosage_report_date'];


    public const STATUS_ACTIVE = 1;

    /*
     * Status strings for display methods
     *
     * */
     protected $status_strings = [
	 		0 => 'Initialized',
	 		1 => 'Active',
	 		2 => 'Discontinued',
	 		9 => 'Aborted'
	];


	/**
     * Automatically assign an ident on instantiation
     *
     * @param	array	$attributes
     * @return 	void
     */
    public function __construct(array $attributes = array())
    {
		parent::__construct($attributes);
    }


    
}
