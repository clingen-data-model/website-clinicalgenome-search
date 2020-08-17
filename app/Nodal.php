<?php

namespace App;

use Jenssegers\Model\Model;

use App\Traits\Display;
use App\Traits\Scores;


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
 *
 * */
class Nodal extends Model
{
	use Display;
	use Scores;

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
			//dd($mondo);
		// scan through gene validity interps items
		if (!empty($this->gene_validity_interps))
			foreach ($this->gene_validity_interps as $item)
			//dd($item);
				if (basename($item['condition']['iri']) == $mondo)
				{
					foreach($this->validity_report as $vnode)
					{
						//dd($vnode['node']->moi);
						foreach($vnode['idc'] as $key => $idc)
						{
							if (basename($idc[0]->value('iri')) == $mondo)
							{
								$permid = $vnode['node']->perm_id;

								// Dropped in the permid as the key so a if looped again it wouldn't add another record
								$records[$permid] = [
									'date' => $item['date'],
									'moi' => $vnode['node']->moi,
									'classification' => $item['significance']['label'],
									'report' => '/validity/' . $permid ?? 0
								];
							}
						}
					}


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

		//dd($this);
		// null is sent through at the end of the gene show and disease show page to make sure dosages without diseases are displayed.
		if($mondo != null) {
		// scan through gene dosage interps items
			if (!empty($this->gene_dosage_interps))
				if (basename($this->gene_dosage_interps['condition']['iri']) == $mondo)
					$records[] = ['date' => $this->gene_dosage_interps['date'],
									'classification' => $this->gene_dosage_interps['significance'][0]['label'],
									'report' => env('CG_URL_CURATIONS_DOSAGE') . $this->symbol . '&subject'];
		} else {
			// check to see if this has a condition
			if (!$this->gene_dosage_interps['condition']['iri']) {
				$records[] = [
					'date' => $this->gene_dosage_interps['date'],
					'classification' => $this->gene_dosage_interps['significance'][0]['label'],
					'report' => env('CG_URL_CURATIONS_DOSAGE') . $this->symbol . '&subject'
				];
			} else {
				// if no contition send back nothing to the view if doesn't render
				return;
			}
		}

		//dd($records);

		return $records;
	}
	
	
	/**
     * Flag indicating if gene has any dosage curations 
     * 
     * @@param	
     * @return 
     */
    public function setCurationFlagAttribute($value)
    {
		if (!isset($this->curation_activities))
			$this->curation_activities = [$value];
		else
			$this->curation_activities = array_merge($this->curation_activities,
													[ $value ]);
	}
	
	/**
     * Flag indicating if gene has any dosage curations 
     * 
     * @@param	
     * @return 
     */
    public function getHasDosageAttribute()
    {
		return (isset($this->curation_activities) ? 
					in_array('GENE_DOSAGE', $this->curation_activities) :
					false); 
	}
	
	
	/**
     * Flag indicating if gene has any dosage triplo scores 
     * 
     * @@param	
     * @return 
     */
    public function getHasDosageTriploAttribute()
    {
		if (empty($this->dosage_curation))
			return false;

		return $this->dosage_curation->triplosensitivity_assertion->score ?? false;
	
	}
	
	
	/**
     * Flag indicating if gene has any dosage haplo scores 
     * 
     * @@param	
     * @return 
     */
    public function getHasDosageHaploAttribute()
    {
		if (empty($this->dosage_curation))
			return false;
		
		return $this->dosage_curation->haploinsufficiency_assertion->score ?? false;
		
	}
	
	
	/**
     * Get the latest date
     * 
     * @@param	
     * @return 
     */
    public function getDosageReportDateAttribute()
    {
		return $this->dosage_curation->report_date ?? '';	
	}
	
	
	/**
     * Flag indicating if gene has any validity curations 
     * 
     * @@param	
     * @return 
     */
    public function getHasValidityAttribute()
    {
		return (isset($this->curation_activities) ? 
					in_array('GENE_VALIDITY', $this->curation_activities) :
					false); 
	}
	
	
	/**
     * Flag indicating if gene has any actionability curations 
     * 
     * @@param	
     * @return 
     */
    public function getHasActionabilityAttribute()
    {
		return (isset($this->curation_activities) ? 
					in_array('ACTIONABILITY', $this->curation_activities) :
					false); 
	}
	
	
	/**
     * Return full name of gene 
     * 
     * @@param	
     * @return 
     */
    public function getNameAttribute()
    {
		return $this->alternative_label ?? '';
	}
	
	
	/**
     * Return symbol of gene 
     * 
     * @@param	
     * @return 
     */
    public function getSymbolAttribute()
    {
		return $this->label ?? '';
	}


	/**
     * Return symbol of gene 
     * 
     * @@param	
     * @return 
     */
    public function getMondoString($original, $colonflag = false)
    {

		$prefix = "http://purl.obolibrary.org/obo/";
		$prelen = strlen($prefix);

		$mondo = substr($original, $prelen);

		return ($colonflag ? str_replace(':', '_', $mondo) : $mondo);
	}


	/**
     * Return whether Actionability report is an adult or a pediatric 
     * 
     * @@param	
     * @return 
     */
    public function displayActionType($record)
    {
		
		if (strpos($record, '/Adult/'))
			return 'Adult - ';
		else if (strpos($record, '/Pediatric/'))
			return 'Pediatric - ';
		else
			return '';

	}
}
