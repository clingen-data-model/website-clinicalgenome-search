<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Display;

use Uuid;
use Str;

use Carbon\Carbon;

use App\Region;
use App\Curation;

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
          'disease' => 'json|nullable',
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
               'lsdb' => 'array',
               'history' => 'array',
               'activity' => 'array',
               'curation_activities' => 'array',
               'mane_select' => 'array',
               'mane_plus' => 'array',
               'genegraph' => 'array',
               'disease' => 'array',
               'curation_status' => 'array',
               'par_coordinates' => 'array'
     ];

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = ['name', 'hgnc_id', 'description', 'location', 'alias_symbol',
					   'prev_symbol', 'date_symbol_changed', 'hi', 'plof', 'pli', 'lsdb', 'vcep',
                            'haplo', 'triplo', 'omim_id', 'morbid', 'locus_group', 'locus_type',
                            'ensembl_gene_id', 'entrez_id', 'ucsc_id', 'uniprot_id', 'function',
                            'chr', 'start37', 'stop37', 'stop38', 'start38', 'history', 'type',
                            'notes', 'activity', 'curation_status', 'date_last_curated', 'status', 'disease',
                            'seqid37', 'seqid38', 'mane_select', 'mane_plus', 'genegraph', 'acmg59',
                            'activity->varpath', 'is_par', 'par_coordinates',
                            'par_coordinates->grch37->x', 'par_coordinates->grch37->y',
                            'par_coordinates->grch38->x', 'par_coordinates->grch38->y'
                          ];

	/**
     * Non-persistent storage model attributes.
     *
     * @var array
     */
     protected $appends = ['display_date', 'list_date', 'display_status',
                           'display_aliases', 'display_previous',
                           'display_omim', 'grch37', 'grch38'];

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


     /**
     * Access the genomeconnect entry associated with this gene
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function genomeconnect()
    {
         return $this->hasOne('App\Genomeconnect');
    }


     /*
     * The panels associated with this gene
     */
    public function panels()
    {
       return $this->belongsToMany('App\Panel');
    }


    /*
     * The MIMs associated with this gene
     */
    public function mims()
    {
       return $this->hasMany('App\Mim');
    }


     /*
     * The roles that belong to this user
     */
     public function users()
     {
        return $this->belongsToMany('App\User');
     }


     /*
     * The ACMG SFs associated with this gene
     */
    public function acmgs()
    {
       return $this->hasMany('App\Acmg');
    }


    /*
     * The curations associated with this gene
     */
    public function curations()
    {
       return $this->hasMany('App\Curation');
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
     * Query scope by ensemble id
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeEnsembl($query, $id)
    {
       return $query->where('ensembl_gene_id', $id);
    }


    /**
     * Query scope by omim value
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeOmim($query, $value)
    {
        // strip out the prefix if present
        if (strpos($value, 'OMIM:') === 0)
            $value = substr($value, 5);

        // should be left with just a numeric string
        if (!is_numeric($value))
            return $query;

		return $query->whereJsonContains('omim_id', $value);
    }


    /**
     * Query scope by uniprot id
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeUniprot($query, $id)
    {
       return $query->where('uniprot_id', $id);
    }


    /**
     * Query scope by entrez id
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeEntrez($query, $id)
    {
       return $query->where('entrez_id', $id);
    }


    /**
     * Query scope by ucsc id
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeUcsc($query, $id)
    {
       return $query->where('ucsc_id', $id);
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
     * Query scope by gene previous symbol
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopePrevious($query, $symbol)
    {
        return $query->whereJsonContains('prev_symbol', $symbol);
    }


    /**
     * Query scope by gene alias symbol
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeAlias($query, $symbol)
    {
        return $query->whereJsonContains('alias_symbol', $symbol);
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
		return (isset($this->curation_activities['dosage']) ?
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
		return (isset($this->curation_activities['actionability']) ?
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
		return (isset($this->curation_activities['validity']) ?
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
              $this->curation_activities['pharma'] ?? false : false);
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
              $this->curation_activities['varpath'] ?? false : false);
    }


    /**
     * Get a psuedo genegraph representation of the local activity field
     */
    public function getCurationAttribute()
     {
          $activities = [];

          if (!isset($this->activity))
               return $activities;

          if ($this->activity["dosage"])
               $activities[] = "GENE_DOSAGE";
          if ($this->activity["pharma"])
               $activities[] = "GENE_PHARMA";
          if ($this->activity["varpath"])
               $activities[] = "VAR_PATH";
          if ($this->activity["validity"])
               $activities[] = "GENE_VALIDITY";
          if ($this->activity["actionability"])
               $activities[] = "ACTIONABILITY";

          return $activities;
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
     * Get the Loss disease name
     *
     * @@param
     * @return
     */
     public function getLossDiseaseAttribute()
     {
          if ($this->disease === null)
               return '';

          return $this->disease['loss_disease'] ?? '';
     }


     /**
     * Get the Loss disease mondo id
     *
     * @@param
     * @return
     */
     public function getLossMondoAttribute()
     {
         if ($this->disease === null)
              return '';

         return $this->disease['loss_mondo'] ?? '';
     }


     /**
     * Get the Gain disease name
     *
     * @@param
     * @return
     */
    public function getGainDiseaseAttribute()
    {
         if ($this->disease === null)
              return '';

         return $this->disease['gain_disease'] ?? '';
    }


    /**
     * Get the Gain disease mondo id
     *
     * @@param
     * @return
     */
    public function getGainMondoAttribute()
    {
        if ($this->disease === null)
             return '';

        return $this->disease['gain_mondo'] ?? '';
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
     * General location formatter
     *
     * @@param
     * @return
     */
     public function format_location($coord, $attr = false)
     {
          if (empty($coord))
               return null;

          if ($attr)
               return $coord[$attr] ?? null;
               
          switch ($coord['chr'])
          {
               case '23':
                    $chr = 'X';
                    break;
               case '24':
                    $chr = 'Y';
                    break;
               default:
                    $chr = $coord['chr'];
          }

          return 'chr' . $chr . ':' . $coord['start'] . '-' . $coord['stop'];
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
          $curated_gene_count = 0;
          $curated_region_count = 0;

          // check the required input
          if (!isset($type) || !isset($region))
               return (object) ['count' => $collection->count(), 'collection' => $collection,
                         'gene_count' => $gene_count, 'region_count' => $region_count,
                         'curated_gene_count' => $curated_gene_count, 'curated_region_count' => $curated_region_count];

          // only recognize 37 and 38 at this time
          if ($type != 'GRCh37' && $type != 'GRCh38')
               return (object) ['count' => $collection->count(), 'collection' => $collection,
                         'gene_count' => $gene_count, 'region_count' => $region_count,
                         'curated_gene_count' => $curated_gene_count, 'curated_region_count' => $curated_region_count];

          // break out the location and clean it up
          $location = preg_split('/[:-]/', trim($region), 3);

          $chr = strtoupper($location[0]);

          if (strpos($chr, 'CHR') === 0 )   // strip out the chr
               $chr = substr($chr, 3);



          //vet the search terms
          $start = str_replace(',', '', empty($location[1]) ? '0' : $location[1]);  // strip out commas
          $stop = str_replace(',', '', empty($location[2]) ? '9999999999' : $location[2]);

          // change x and y to numerics
          if ($chr == 'X')
               $chr = 23;

          if ($chr == 'Y')
               $chr = 24;

          // make sure the start and stop make sense
          if ($start == '' || $stop == '')
               return (object) ['count' => $collection->count(), 'collection' => $collection,
                         'gene_count' => $gene_count, 'region_count' => $region_count,
                         'curated_gene_count' => $curated_gene_count, 'curated_region_count' => $curated_region_count];

          if (!is_numeric($start) || !is_numeric($stop))
               return (object) ['count' => $collection->count(), 'collection' => $collection,
                         'gene_count' => $gene_count, 'region_count' => $region_count,
                         'curated_gene_count' => $curated_gene_count, 'curated_region_count' => $curated_region_count];

          if ((int) $start >= (int) $stop)
               return (object) ['count' => $collection->count(), 'collection' => $collection,
                         'gene_count' => $gene_count, 'region_count' => $region_count,
                         'curated_gene_count' => $curated_gene_count, 'curated_region_count' => $curated_region_count
                    
                    ];

          // 
          if (isset($option) && $option == 1)  // only return contained
          {
               if ($type == 'GRCh37')
               {
                    $genes = self::where(function ($query) use ($chr, $start, $stop){
                                        $query->where('is_par', 0)
                                             ->where('chr', (int) $chr)
                                             ->where('start37', '>=', (int) $start)
                                             ->where('stop37', '<=', (int) $stop);
                                   })->orWhere(function ($query) use ($chr, $start, $stop){
                                        $query->where('is_par', 1);
                                        if ((int)$chr == 23) 
                                        {
                                             $query->where('par_coordinates->grch37->x->chr', (int) $chr)
                                                  ->where('par_coordinates->grch37->x->start', '>=', (int) $start)
                                                  ->where('par_coordinates->grch37->x->stop', '<=', (int) $stop);
                                        }
                                        else
                                        {
                                             $query->where('par_coordinates->grch37->y->chr', (int) $chr)
                                                  ->where('par_coordinates->grch37->y->start', '>=', (int) $start)
                                                  ->where('par_coordinates->grch37->y->stop', '<=', (int) $stop);
                                        }
                                   })->with(['curations' => function ($query) {
                                        $query->where('type', Curation::TYPE_DOSAGE_SENSITIVITY)
                                             ->whereIn('status', [Curation::STATUS_ACTIVE, Curation::STATUS_GROUP_REVIEW,
                                                       Curation::STATUS_PRIMARY_REVIEW, Curation::STATUS_SECONDARY_REVIEW]); 
                                   }])->get(); 
               }                
               else      // GRCh38
               {
                    $genes = self::where(function ($query) use ($chr, $start, $stop){
                                        $query->where('is_par', 0)
                                             ->where('chr', (int) $chr)
                                             ->where('start38', '>=', (int) $start)
                                             ->where('stop38', '<=', (int) $stop);
                                   })->orWhere(function ($query) use ($chr, $start, $stop){
                                        $query->where('is_par', 1);
                                        if ((int)$chr == 23) 
                                        {
                                             $query->where('par_coordinates->grch38->x->chr', (int) $chr)
                                                  ->where('par_coordinates->grch38->x->start', '>=', (int) $start)
                                                   ->where('par_coordinates->grch38->x->stop', '<=', (int) $stop);
                                        }
                                        else
                                        {
                                             $query->where('par_coordinates->grch38->y->chr', (int) $chr)
                                                  ->where('par_coordinates->grch38->y->start', '>=', (int) $start)
                                                   ->where('par_coordinates->grch38->y->stop', '<=', (int) $stop);
                                        }
                                   })->with(['curations' => function ($query) {
                                        $query->where('type', Curation::TYPE_DOSAGE_SENSITIVITY)
                                             ->whereIn('status', [Curation::STATUS_ACTIVE, Curation::STATUS_GROUP_REVIEW,
                                                       Curation::STATUS_PRIMARY_REVIEW, Curation::STATUS_SECONDARY_REVIEW]); 
                                   }])->get();
               }
          }
          else
          {
               if ($type == 'GRCh37')
               {
                    $genes = self::where(function ($query) use ($chr, $start, $stop){
                                        $query->where('is_par', 0)
                                             ->where('chr', (int) $chr)
                                             ->where('start37', '<=', (int) $stop)
                                             ->where('stop37', '>=', (int) $start);
                                   })->orWhere(function ($query) use ($chr, $start, $stop){
                                        $query->where('is_par', 1);
                                        if ((int) $chr == 23) 
                                        {
                                             $query->where('par_coordinates->grch37->x->chr', (int) $chr)
                                                  ->where('par_coordinates->grch37->x->start', '<=', (int) $stop)
                                                  ->where('par_coordinates->grch37->x->stop', '>=', (int) $start);
                                        }
                                        else
                                        {
                                             $query->where('par_coordinates->grch37->y->chr', (int) $chr)
                                                  ->where('par_coordinates->grch37->y->start', '<=', (int) $stop)
                                                  ->where('par_coordinates->grch37->y->stop', '>=', (int) $start);
                                        }
                                   })->with(['curations' => function ($query) {
                                             $query->where('type', Curation::TYPE_DOSAGE_SENSITIVITY)
                                                  ->whereIn('status', [Curation::STATUS_ACTIVE, Curation::STATUS_GROUP_REVIEW,
                                                            Curation::STATUS_PRIMARY_REVIEW, Curation::STATUS_SECONDARY_REVIEW]); 
                                   }])->get();
               }                
               else      // GRCh38
               {
                    $genes = self::where(function ($query) use ($chr, $start, $stop){
                                        $query->where('is_par', 0)
                                             ->where('chr', (int) $chr)
                                             ->where('start38', '<=', (int) $stop)
                                             ->where('stop38', '>=', (int) $start);
                                   })->orWhere(function ($query) use ($chr, $start, $stop){
                                        $query->where('is_par', 1);
                                        if ((int)$chr == 23) 
                                        {
                                             $query->where('par_coordinates->grch38->x->chr', (int) $chr)
                                                  ->where('par_coordinates->grch38->x->start', '<=', (int) $stop)
                                                   ->where('par_coordinates->grch38->x->stop', '>=', (int) $start);
                                        }
                                        else
                                        {
                                             $query->where('par_coordinates->grch38->y->chr', (int) $chr)
                                                  ->where('par_coordinates->grch38->y->start', '<=', (int) $stop)
                                                   ->where('par_coordinates->grch38->y->stop', '>=', (int) $start);
                                        }
                                   })->with(['curations' => function ($query) {
                                        $query->where('type', Curation::TYPE_DOSAGE_SENSITIVITY)
                                             ->whereIn('status', [Curation::STATUS_ACTIVE, Curation::STATUS_GROUP_REVIEW,
                                                       Curation::STATUS_PRIMARY_REVIEW, Curation::STATUS_SECONDARY_REVIEW]); 
                                   }])->get();
               }
          }

          //  also search the regions
          if (isset($option) && $option == 1)  // only return contained
          {
               if ($type == 'GRCh37')
               {
                    $regions = Region::where('coordinates->grch37->chr', (int) $chr)
                                                                 ->where('coordinates->grch37->start', '>=', (int) $start)
                                                                 ->where('coordinates->grch37->stop', '<=', (int) $stop)
                                                                 ->with(['curations' => function ($query) {
                                                                      $query->where('type', Curation::TYPE_DOSAGE_SENSITIVITY_REGION)
                                                                           ->whereIn('status', [Curation::STATUS_ACTIVE, Curation::STATUS_GROUP_REVIEW,
                                                                                Curation::STATUS_PRIMARY_REVIEW, Curation::STATUS_SECONDARY_REVIEW]); 
                                                                 }])->get();
               }
               else
               {
                    $regions = Region::where('coordinates->grch38->chr', (int) $chr)
                                                                 ->where('coordinates->grch38->start', '>=', (int) $start)
                                                                 ->where('coordinates->grch38->stop', '<=', (int) $stop)
                                                                 ->with(['curations' => function ($query) {
                                                                      $query->where('type', Curation::TYPE_DOSAGE_SENSITIVITY_REGION)
                                                                      ->whereIn('status', [Curation::STATUS_ACTIVE, Curation::STATUS_GROUP_REVIEW,
                                                                           Curation::STATUS_PRIMARY_REVIEW, Curation::STATUS_SECONDARY_REVIEW]); 
                                                                 }])->get();
               }
          }
          else
          {
               if ($type == 'GRCh37')
               {
                    $regions = Region::where('coordinates->grch37->chr', (int) $chr)
                                                                 ->where('coordinates->grch37->start', '<=', (int) $stop)
                                                                 ->where('coordinates->grch37->stop', '>=', (int) $start)
                                                                 ->with(['curations' => function ($query) {
                                                                      $query->where('type', Curation::TYPE_DOSAGE_SENSITIVITY_REGION)
                                                                      ->whereIn('status', [Curation::STATUS_ACTIVE, Curation::STATUS_GROUP_REVIEW,
                                                                           Curation::STATUS_PRIMARY_REVIEW, Curation::STATUS_SECONDARY_REVIEW]); 
                                                                 }])->get();
               }
               else
               {
                    $regions = Region::where('coordinates->grch38->chr', (int) $chr)
                                                                 ->where('coordinates->grch38->start', '<=', (int) $stop)
                                                                 ->where('coordinates->grch38->stop', '>=', (int) $start)
                                                                 ->with(['curations' => function ($query) {
                                                                      $query->where('type', Curation::TYPE_DOSAGE_SENSITIVITY_REGION)
                                                                      ->whereIn('status', [Curation::STATUS_ACTIVE, Curation::STATUS_GROUP_REVIEW,
                                                                           Curation::STATUS_PRIMARY_REVIEW, Curation::STATUS_SECONDARY_REVIEW]); 
                                                                 }])->get();
               }
          }

          foreach ($genes as $gene)
          {
               $haplo = $gene->curations->where('context', 'haploinsufficiency_assertion')->first();
               if ($haplo === null)
                    $haplo_score = null;
               else if ($haplo->subtype == Curation::SUBTYPE_DOSAGE_GGP)
                    $haplo_score = $haplo->assertions['haploinsufficiency_assertion']['dosage_classification']['ordinal'] ?? null;
               else
                    $haplo_score = $haplo->assertions['value'] ?? null;

               $triplo = $gene->curations->where('context', 'triplosensitivity_assertion')->first();
               if ($triplo === null)
                    $triplo_score = null;
               else if ($triplo->subtype == Curation::SUBTYPE_DOSAGE_GGP)
                    $triplo_score = $triplo->assertions['triplosensitivity_assertion']['dosage_classification']['ordinal'] ?? null;
               else
                    $triplo_score = $triplo->assertions['value'] ?? null;

               $node = new Nodal(['chr' => $chr]);

               $node->build = $type;
               $node->label = $gene->name;
               $node->type = 0;
               $node->locus_type = $gene->locus_type;
               $node->locus = $gene->locus_group;
               $node->symbol_id = $gene->hgnc_id;
               $node->location = $gene->location;
               $node->is_par = $gene->is_par;
               $node->hi = $gene->hi;
               $node->plof = $gene->plof;
               $node->pli = $gene->pli;
               $node->haplo = $haplo_score;
               $node->triplo = $triplo_score;
               $node->is_par = $gene->is_par;
               $node->omim = $gene->display_omim;
               $node->morbid = $gene->morbid;
               $node->curation_status = $gene->curation_status;
               $node->activity = $gene->activity;
               $node->nicedate = $gene->displayDate($gene->date_last_curated);
               $node->date_last_curated = $gene->date_last_curated;
               $node->status = (empty($gene->date_last_curated) ? 0 : 1);
               $node->precuration = $gene->curations->where('status', '!=', Curation::STATUS_ACTIVE)->pluck('status')->first();

               $gene_count++;

               if ($gene->history !== null)
               {
                    //dd($gene->history);
                    foreach ($gene->history as $item)
                    {
                         //dd($item["what"]);
                         if ($item['what'] == 'Triplosensitivity Score')
                         $node->triplo_history = $item['what'] . ' changed from ' . $item['from']
                                                  . ' to ' . $item['to'] . ' on ' . $item['when'];
                         else if ($item['what'] == 'Haploinsufficiency Score')
                         $node->haplo_history = $item['what'] . ' changed from ' . $item['from']
                                                  . ' to ' . $item['to'] . ' on ' . $item['when'];
                    }
               }

               if ($gene->date_last_curated !== null)
                    $curated_gene_count++;

               if ($type == 'GRCh37')
               {
                    if ($gene->is_par)
                    {
                         $c = ($chr == 23 ? 'x' : 'y');
                         $node->start = $gene->par_coordinates['grch37'][$c]['start'];
                         $node->stop = $gene->par_coordinates['grch37'][$c]['stop'];
                         $node->seqid = $gene->par_coordinates['grch37'][$c]['seqid'];
                    }
                    else
                    {
                         $node->start = $gene->start37;
                         $node->stop = $gene->stop37;
                         $node->seqid = $gene->seqid37;
                    }
               }
               else if ($type == 'GRCh38')
               {
                    if ($gene->is_par)
                    {
                         $c = ($chr == 23 ? 'x' : 'y');
                         $node->start = $gene->par_coordinates['grch38'][$c]['start'];
                         $node->stop = $gene->par_coordinates['grch38'][$c]['stop'];
                         $node->seqid = $gene->par_coordinates['grch38'][$c]['seqid'];
                    }
                    else
                    {
                         $node->start = $gene->start38;
                         $node->stop = $gene->stop38;
                         $node->seqid = $gene->seqid38;
                    }
               }

               $node->relationship = ($node->start >= (int) $start && $node->stop <= (int) $stop ? 'Contained' : 'Overlap');

               // temp fix for the NYE issue
               if ($node->haplo === null)
                    $node->haplo = -5;
               if ($node->triplo === null)
                    $node->triplo = -5;

               $collection->push($node);
          }

          // temp definitions until new props are added
          $activity = [
               'dosage' => true,
               'validity' => false,
               'pharma' => false,
               'actionability' => false,
               'varpath' => false
             ];
       
             $noactivity = [
               'dosage' => false,
               'validity' => false,
               'pharma' => false,
               'actionability' => false,
               'varpath' => false
             ];

          foreach ($regions as $region)
          {
               if ($region->status == Region::STATUS_CLOSED && $region->events['resolution'] == "Complete")
               {
                    $c = Carbon::parse($region->events['resolved']);
                    $displaydate = $c->format("m/d/Y");
               }
               else
                    $displaydate = null;

               $node = new Nodal(['chr' => $chr]);
               $node->build = $type;
               $node->label = $region->name;
               $node->type = 1;
               $node->symbol_id = $region->iri;
               $node->location = $region->cytoband;
               $node->locus_type = "region";
               $node->locus = "region";
               $node->is_par = false;
               $node->hi = null;
               $node->plof = null;
               $node->pli = $region->metadata['pli'];
               $node->haplo = ($region->status == Region::STATUS_CLOSED ? $region->scores['haploinsufficiency'] ?? null : null);
               $node->triplo = ($region->status == Region::STATUS_CLOSED ? $region->scores['triplosensitivity'] ?? null : null);
               $node->haplo_history = null;
               $node->triplo_history = null;
               $node->is_par = false;
               $node->omim = $region->metadata['omim'];
               $node->morbid = null;
               $node->curation_status = $region->curation_status;
               //$node->activity = $region->activity;
               $node->activity = ($region->status == Region::STATUS_CLOSED && $region->events['resolution'] == "Complete" ? $activity : $noactivity );
               $node->date_last_curated = ($region->status == Region::STATUS_CLOSED && $region->events['resolution'] == "Complete" ?
                                                  $region->events['resolved'] : null);
               $node->precuration = $region->curations->where('status', '!=', Curation::STATUS_ACTIVE)->pluck('status')->first();
               //$node->date_last_curated = $region->date_last_curated;
               $node->nicedate = $displaydate;
               $node->status = (empty($region->date_last_curated) ? 0 : 1);

               $region_count++;

               if ($region->date_last_curated !== null)
                    $curated_region_count++;

               if ($type == 'GRCh37')
               {
                    $node->start = $region->coordinates['grch37']['start'];
                    $node->stop = $region->coordinates['grch37']['stop'];
                    $node->seqid = $region->coordinates['grch37']['seqid'];
               }
               else if ($type == 'GRCh38')
               {
                    
                    $node->start = $region->coordinates['grch38']['start'];
                    $node->stop = $region->coordinates['grch38']['stop'];
                    $node->seqid = $region->coordinates['grch38']['seqid'];
               }

               $node->relationship = ($node->start >= (int) $start && $node->stop <= (int) $stop ? 'Contained' : 'Overlap');
//if($region->iri == 'ISCA-46744')
 //    dd($region);
               // for 30 and 40, Jira also sends text
               if ($node->triplo == "30: Gene associated with autosomal recessive phenotype")
                    $node->triplo = 30;
               else if ($node->triplo == "40: Dosage sensitivity unlikely")
                    $node->triplo_score = 40;
               else if ($node->triplo == "Not yet evaluated")
                    $node->triplo_score = -5;

               if ($node->haplo == "30: Gene associated with autosomal recessive phenotype")
                    $node->haplo = 30;
               else if ($node->haplo == "40: Dosage sensitivity unlikely")
                    $node->haplo = 40;
               else if ($node->haplo == "Not yet evaluated")
                    $node->haplo= -5;


               $collection->push($node);
          }

          return (object) ['count' => $collection->count(), 'collection' => $collection,
                      'gene_count' => $gene_count, 'region_count' => $region_count,
                      'curated_gene_count' => $curated_gene_count, 'curated_region_count' => $curated_region_count];
    }


    /**
     * Update local table from genegraph
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
    public static function gg2local($node)
    {

    }


    /**
     * Map various gene references gene record
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
    public static function rosetta($id)
    {
        if (empty($id))
            return null;

        // do some cleanup
        $id = basename(trim($id));

        $parts = explode(':', $id);

        if (!isset($parts[1]))
        {
            if (is_numeric($id))
                $check = Gene::omim($id)->first();
            else
                $check = Gene::name($id)->first();
        }
        else
        {
            $id = $parts[1];

            switch (strtoupper($parts[0]))
            {
                case 'MIM':
                case 'OMIM':
                    $check = Gene::omim($id)->first();
                    break;
                case 'ENSEMBL':
                case 'NCBI':
                    $check = Gene::ensembl($id)->first();
                    break;
                case 'ENTREZ':
                    $check = Gene::entrez($id)->first();
                    break;
                case 'HGNC':
                    $check = Gene::hgnc('HGNC:' . $id)->first();
                    break;
                case 'UCSC':
                    $check = Gene::ucsc($id)->first();
                    break;
                case 'UNIPROT':
                    $check = Gene::uniprot($id)->first();
                    break;
                default:
                    $check = null;

            }

        }

        return $check;
    }
}
