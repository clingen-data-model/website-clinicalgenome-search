<?php

namespace App;

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
class Gene extends Model
{
    use SoftDeletes;
    use Display;

    /**
     * The attributes that should be validity checked.
     *
     * @var array
     */
    public static $rules = [
		'ident' => 'alpha_dash|max:80|required',
		'name' => 'name|max:80|required',
		'hgnc_id' => 'string|nullable',
          'description' => 'string|nullable',
          'location' => 'string|nullable',
		'alias_symbol' => 'json|nullable',
		'prev_symbol' => 'json|nullable',
          'date_symbol_changed' => 'string|nullable',
          'locus_type' => 'string|nullable',
          'locus_group' => 'string|nullable',
		'hi' => 'string|nullable',
		'plof' => 'string|nullable',
		'pli' => 'string|nullable',
		'haplo' => 'string|nullable',
          'triplo' => 'string|nullable',
          'ensemble_gene_id' => 'string|nullable',
          'entrez_id' => 'string|nullable',
          'ucsc_id' => 'string|nullable',
          'notes' => 'string|nullable',
          'history' => 'json|nullable',
		'type' => 'integer',
		'status' => 'integer'
	];

	/**
     * Map the json attributes to associative arrays.
     *
     * @var array
     */
	protected $casts = [
			'alias_symbol' => 'array',
               'prev_symbol' => 'array',
               'omim_id' => 'array',
               'history' => 'array',
               'activity' => 'array',
               'curation_activities' => 'array',
               'mane_select' => 'array',
               'mane_plus' => 'array',
               'genegraph' => 'array'
		];

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = ['name', 'hgnc_id', 'description', 'location', 'alias_symbol',
					   'prev_symbol', 'date_symbol_changed', 'hi', 'plof', 'pli',
                            'haplo', 'triplo', 'omim_id', 'morbid', 'locus_group', 'locus_type',
                            'ensembl_gene_id', 'entrez_id', 'ucsc_id', 'uniprot_id', 'function',
                            'chr', 'start37', 'stop37', 'stop38', 'start38', 'history', 'type',
                            'notes', 'activity', 'date_last_curated', 'status',
                            'seqid37', 'seqid38', 'mane_select', 'mane_plus', 'genegraph', 'acmg59' ];

	/**
     * Non-persistent storage model attributes.
     *
     * @var array
     */
     protected $appends = ['display_date', 'list_date', 'display_status',
                           'display_aliases', 'display_previous',
                           'display_omim', 'grch37', 'grch38', 'mane'];

     public const TYPE_NONE = 0;

     /*
     * Type strings for display methods
     *
     * */
     protected $type_strings = [
	 		0 => 'Unknown',
	 		9 => 'Deleted'
	];

     public const STATUS_INITIALIZED = 0;

     /*
     * Status strings for display methods
     *
     * */
     protected $status_strings = [
	 		0 => 'Initialized',
	 		9 => 'Deleted'
	];


     /*
     From genegraph:
     {"immunoglobulin gene" "http://purl.obolibrary.org/obo/SO_0002122"
                  "T cell receptor gene" "http://purl.obolibrary.org/obo/SO_0002099"
                  "RNA, micro" "http://purl.obolibrary.org/obo/SO_0000276"
                  "gene with protein product" "http://purl.obolibrary.org/obo/SO_0001217"
                  "RNA, transfer" "http://purl.obolibrary.org/obo/SO_0000253"
                  "pseudogene" "http://purl.obolibrary.org/obo/SO_0000336"
                  "RNA, long non-coding" "http://purl.obolibrary.org/obo/SO_0001877"
                  "virus integration site" "http://purl.obolibrary.org/obo/SO_0000946?"
                  "RNA, vault" "http://purl.obolibrary.org/obo/SO_0000404"
                  "endogenous retrovirus" "http://purl.obolibrary.org/obo/SO_0000100"
                  "RNA, small nucleolar" "http://purl.obolibrary.org/obo/SO_0000275"
                  "T cell receptor pseudogene" "http://purl.obolibrary.org/obo/SO_0002099"
                  "immunoglobulin pseudogene" "http://purl.obolibrary.org/obo/SO_0002098"
                  "RNA, small nuclear" "http://purl.obolibrary.org/obo/SO_0000274"
                  "readthrough" "http://purl.obolibrary.org/obo/SO_0000883"
                  "RNA, ribosomal" "http://purl.obolibrary.org/obo/SO_0000252"
                  "RNA, misc" "http://purl.obolibrary.org/obo/SO_0000356"})
     From Jira: 
               miscRNA, mcRNA, other, protein-coding, psuedo, rRNA, scRNA,
               snoRNA, snRNA, tRNA, unknown, withdrawn
     */

     
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
     * Access the devices associated with this clinic
     * 
     * @return Illuminate\Database\Eloquent\Collection
     */
     public function location()
     {
		return $this->hasOne('App\Location');
     }
     

     /*
     * The roles that belong to this user
     */
     public function users()
     {
        return $this->belongsToMany('App\User');
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
	public function scopeName($query, $name)
     {
		return $query->where('name', $name);
     }


     /**
     * Query scope by hgncid name
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeHgnc($query, $id)
     {
		return $query->where('hgnc_id', $id);
     }


     /**
     * Query scope by cytoband
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeCytoband($query, $name)
     {
		return $query->where('location', $name);
     }


     /**
     * Query scope by cytoband
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeAcmg59($query)
     {
		return $query->where('acmg59', 1);
     }


	/**
     * Get a display formatted form of aliases
     *
     * @@param
     * @return
     */
     public function getDisplayAliasesAttribute()
     {
		if (empty($this->alias_symbol))
			return 'No aliases found';

		return implode(', ', $this->alias_symbol);
	}


	/**
     * Get a display formatted form of previous names
     *
     * @@param
     * @return
     */
     public function getDisplayPreviousAttribute()
     {
		if (empty($this->prev_symbol))
			return 'No previous names found';

		return implode(', ', $this->prev_symbol);
     }
     

     /**
     * Get a display formatted form of omim ids
     *
     * @@param
     * @return
     */
     public function getDisplayOmimAttribute()
     {
         if (empty($this->omim_id))
              return null;

         return implode(', ', $this->omim_id);
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
			$this->curation_activities['dosage'] : false); 
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
			$this->curation_activities['actionability'] : false); 
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
			$this->curation_activities['validity'] : false); 
     }


     /**
     * Flag indicating if gene has any pharma curations 
     * 
     * @@param	
     * @return 
     */
    public function getHasPharmaAttribute()
    {
         return (isset($this->curation_activities) ? 
              $this->curation_activities['pharma'] : false); 
    }


    /**
     * Flag indicating if gene has any actionability curations 
     * 
     * @@param	
     * @return 
     */
    public function getHasVariantAttribute()
    {
         return (isset($this->curation_activities) ? 
              $this->curation_activities['variant'] : false); 
    }
     

    /**
     * Get a display formatted form of grch37
     *
     * @@param
     * @return
     */
     public function getGrch37Attribute()
     {
          if ($this->chr === null || $this->start37 === null || $this->stop37 === null)
               return null;

          switch ($this->chr)
          {
               case '23':
                    $chr = 'X';
                    break;
               case '24':
                    $chr = 'Y';
                    break;
               default: 
                    $chr = $this->chr;
          }
          
          return 'chr' . $chr . ':' . $this->start37 . '-' . $this->stop37;
     }


    /**
     * Get a display formatted form of grch38
     *
     * @@param
     * @return
     */
     public function getGrch38Attribute()
     {
          if ($this->chr == null || $this->start38 == null || $this->stop38 == null)
               return null;

          switch ($this->chr)
          {
               case '23':
                    $chr = 'X';
                    break;
               case '24':
                    $chr = 'Y';
                    break;
               default: 
                    $chr = $this->chr;
          }

          return 'chr' . $chr . ':' . $this->start38 . '-' . $this->stop38;
     }


     /**
     * Flag indicating if gene has any dosage curations 
     * 
     * @@param	
     * @return 
     */
    public function hasActivity($activity)
    {
         return (isset($this->activity[$activity]) ? 
              $this->activity[$activity] : false); 
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

          // check the required input
          if (!isset($type) || !isset($region))
               return (object) ['count' => $collection->count(), 'collection' => $collection,
                         'gene_count' => $gene_count, 'region_count' => $region_count];

          // only recognize 37 and 38 at this time
          if ($type != 'GRCh37' || $type != 'GRCh38')
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

          if ($chr == 'X')
               $chr = 23;

          if ($chr == 'Y')
               $chr = 24;

          if ($type == 'GRCh37')
               $regions = self::where('chr', (int) $chr)
                         ->where('start37', '<=', (int) $stop)
                         ->where('stop37', '>=', (int) $start)->get();
          else if ($type == 'GRCh38')
               $regions = self::where('chr', (int) $chr)
                         ->where('start38', '<=', (int) $stop)
                         ->where('stop38', '>=', (int) $start)->get();

          foreach ($regions as $region)
          {
               $region->type = $type;
               $gene_count++;

               if ($type == 'GRCh37')
               {
                    $region->start = $region->start37;
                    $region->stop = $region->stop37;
               }
               else if ($type == 'GRCh38')
               {
                    $region->start = $region->start38;
                    $region->stop = $region->stop38;
               }

               $region->relationship = ($region->start >= (int) $start && $region->stop <= (int) $stop ? 'Contained' : 'Overlap');

               $collection->push($region);
          }
      
          return (object) ['count' => $collection->count(), 'collection' => $collection,
                      'gene_count' => $gene_count, 'region_count' => $region_count];
    }
}
