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
class Nodal extends Model
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
     * The attributes that are mass assignable.
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
    //protected $appends = ['display_location', 'display_date',
	//					  'list_date', 'display_status'];


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


    /*
     * Find all validity record in the interps matching the mondo id
     *
     * @param	string	$mondo
     * @return 	array
     */
    public function findValidity($mondo)
    {
		$records = [];

		$mondo = str_replace(':', '_', $mondo);

		// scan through gene validity interps items
		if (!empty($this->gene_validity_interps))
			foreach ($this->gene_validity_interps as $item)
				if (basename($item['condition']['iri']) == $mondo)
				{
					foreach($this->validity_report as $vnode)
					{
						foreach($vnode['idc'] as $key => $idc)
						{
							if (basename($idc[0]->value('iri')) == $mondo)
							{
								$permid = $vnode['node']->value('perm_id');
							}
						}
					}

					$records[] = ['date' => $item['date'],
								  'classification' => $item['significance']['label'],
								  'report' => '/validity/' . $permid ?? 0];
				}

		//dd($records);

		return $records;
	}


	/*
     * Find all actionability records in the actionability report matching the mondo id
     *
     * @param	string	$mondo
     * @return 	array
     */
    public function findActionability($mondo)
    {
		$records = [];
		$prefix = "http://purl.obolibrary.org/obo/";

		$mondo = str_replace(':', '_', $mondo);

		// scan through gene actionability interps items
		if (!empty($this->actionability_report))
			foreach ($this->actionability_report as $item)
				if (in_array($prefix . $mondo, $item['disease']))
				{
					if (strpos($item['report'], 'ac-adult') !== false)
						$type = 'ADULT';
					else if (strpos($item['report'], 'ac-pediatric') !== false)
						$type = 'PEDIATRIC';
					else
						$type = 'UNCLASSIFIED';

					$records[] = ['date' => $item['date'],
								  'report' => $item['report'],
								  'type' => $type];
				}

		//dd($records);

		return $records;
	}


	/*
     * Find all dosage record in the interps matching the mondo id
     *
     * @param	string	$mondo
     * @return 	array
     */
    public function findDosage($mondo)
    {
		$records = [];

		$mondo = str_replace(':', '_', $mondo);

		// scan through gene dosage interps items
		if (!empty($this->gene_dosage_interps))
			if (basename($this->gene_dosage_interps['condition']['iri']) == $mondo)
				$records[] = ['date' => $this->gene_dosage_interps['date'],
							  'classification' => $this->gene_dosage_interps['significance'][0]['label'],
							  'report' => 'https://www.ncbi.nlm.nih.gov/projects/dbvar/clingen/clingen_gene.cgi?sym=' . $this->symbol . '&subject'];

		//dd($records);

		return $records;
	}
}
