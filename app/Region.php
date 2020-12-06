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
class Region extends Model
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
     * Map the json attributes to associative arrays.
     *
     * @var array
     */
	  protected $casts = [
              'history' => 'array'
    ];

    /**
     * The attributes that are mass assignable.  Remember to fill it
     * in when all the attributes are known.
     *
     * @var array
     */
     protected $fillable = ['location', 'chr', 'start', 'stop', 'issue', 'curation',
                            'workflow', 'history',
                            'name', 'gain', 'loss', 'pli', 'status', 'omim', 'type' ];

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
     * Query scope by iddur
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function scopeIssue($query, $issue)
    {
      return $query->where('issue', $issue);
    }


    /**
     * Query scope by location
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function scopeLocation($query, $location)
    {
      return $query->where('location', $location);
    }


    /**
     * Check for proper region formatting.  Accepted formats are:
     *
     *    chr#:#-#
     *    #:#-#
     *    #p#[.#]-#p#[.#]
     * 
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
    public static function checkRegion($region)
    {
      if (empty($region))
        return false;

      if (strtoupper(substr($region, 0, 3)) == 'CHR')     // get rid of the useless chr
        $region = substr($region, 3);

      if (preg_match('/^([0-9xX]{1,2}):([0-9,]+)-([0-9,]+)$/', $region, $matches))
      {

        if (count($matches) != 4)
          return false;

        // clean up the values
        $chr = strtoupper($matches[1]);
        $start = str_replace(',', '', $matches[2]);
        $stop = str_replace(',', '', $matches[3]);

        if ($start == '' || $stop == '')
          return false;

        if ((int) $start >= (int) $stop)
          return false;

        return true;
      }
      else if (preg_match('/^([0-9X]{1,2})([pPqQ])([0-9]+)([\.[0-9]+])?-([0-9X]{1,2})([pPqQ])([0-9]+)([\.[0-9]+])?$/', $region, $matches))
      {

        /*dd($matches);
        $chr = \mb_strtoupper($matches[1]);

        $arm = strtolower($matches[2]);

        $band = $matches[3];

        $subband = $matches[4];

        $chr = \mb_strtoupper($matches[1]);

        $arm = strtolower($matches[2]);

        $band = $matches[3];

        $subband = $matches[4];*/
        return false;
      }
      else
        return false;
    }


    /**
     * Search for all contained or overlapped genes and regions
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
    public static function searchList($args, $page = 0, $pagesize = 20)
    {
      // break out the args
      foreach ($args as $key => $value)
        $$key = $value;
      
      // initialize the collection
      $collection = collect();
      $gene_count = 0;
      $region_count = 0;

      // map string type to type flag
      if ($type == 'GRCh37')
        $type = 1;
      else if ($type == 'GRCh38')
        $type = 2;
      else
        return (object) ['count' => $collection->count(), 'collection' => $collection,
                        'gene_count' => $gene_count, 'region_count' => $region_count];

      // break out the location and clean it up
      $location = preg_split('/[:-]/', trim($region), 3);

      $chr = strtoupper($location[0]);
      
      if (strpos($chr, 'CHR') == 0)   // strip out the chr
          $chr = substr($chr, 3);

      //vet the search terms
      $start = str_replace(',', '', $location[1] ?? '');  // strip out commas
      $stop = str_replace(',', '', $location[2] ?? '');

      if ($start == '' || $stop == '')
        return (object) ['count' => $collection->count(), 'collection' => $collection,
                        'gene_count' => $gene_count, 'region_count' => $region_count];

      if (!is_numeric($start) || !is_numeric($stop))
        return (object) ['count' => $collection->count(), 'collection' => $collection,
                        'gene_count' => $gene_count, 'region_count' => $region_count];

      if ((int) $start >= (int) $stop)
        return (object) ['count' => $collection->count(), 'collection' => $collection,
                        'gene_count' => $gene_count, 'region_count' => $region_count];

      $regions = self::where('type', $type)
                        ->where('chr', $chr)
                        ->where('start', '<=', (int) $stop)
                        ->where('stop', '>=', (int) $start)->get();

      foreach ($regions as $region)
      {
        $region->relationship = ($region->start >= (int) $start && $region->stop <= (int) $stop ? 'Contained' : 'Overlap');
        if ($region->curation == 'ISCA Gene Curation')
        {
          $map = Iscamap::issue($region->issue)->first();
          if ($map !== null)
            $region->symbol = $map->symbol;
          $region->type = 0;  //gene

          // rats, we need the hgnc_id until jira supports it directly
          $g = Gene::name($region->symbol)->first();
          if ($g !== null)
          {
            $region->hgnc_id = $g->hgnc_id; 
            $region->plof = $g->plof;
            $region->hi = $g->hi;
            $region->pli = $g->pli;
            $region->morbid = $g->morbid;
          }
          $gene_count++;
        }
        else
        {
          $region->symbol = $region->name;
          $region->type = 1;    //region
          $region_count++;
          $region->plof = null;
            $region->hi = null;
            $region->pli = null;
        }
  
        // for 30 and 40, Jira also sends text
        if ($region->loss == 'N/A')
            $region->loss = "Not Yet Evaluated";
        if ($region->gain == 'N/A')
            $region->gain = "Not Yet Evaluated";
        if ($region->loss == 'Not yet evaluated')
            $region->loss = "Not Yet Evaluated";
        if ($region->gain == 'Not yet evaluated')
            $region->gain = "Not Yet Evaluated";
        if ($region->loss == "30: Gene associated with autosomal recessive phenotype")
              $region->loss = 30;
        else if ($region->loss == "40: Dosage sensitivity unlikely")
              $region->loss = 40;

        if ($region->gain == "30: Gene associated with autosomal recessive phenotype")
              $region->gain = 30;
        else if ($region->gain == "40: Dosage sensitivity unlikely")
              $region->gain = 40;

        $collection->push($region);

      }
      
      return (object) ['count' => $collection->count(), 'collection' => $collection,
                      'gene_count' => $gene_count, 'region_count' => $region_count];
    }
}
