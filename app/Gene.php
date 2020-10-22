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
               'history' => 'array'
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
                            'chr', 'start37', 'stop37', 'stop38', 'start38', 'history', 'type', 'notes', 'status' ];

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
     * Set all names to uppercase
     *
     * @param
     * @return string
     */
    //public function setNameAttribute($value)
	//{
	//	$this->attributes['name'] = strtoupper($value);
	//}


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
              return 'No ids found';

         return implode(', ', $this->omim_id);
    }


    /**
     * Get a display formatted form of aliases
     *
     * @@param
     * @return
     */
    public function getGrch37Attribute()
    {
         if ($this->chr === null || $this->start37 === null || $this->stop37 === null)
              return null;

         return 'chr' . $this->chr . ':' . $this->start37 . '-' . $this->stop37;
    }


    /**
     * Get a display formatted form of aliases
     *
     * @@param
     * @return
     */
    public function getGrch38Attribute()
    {
         if ($this->chr == null || $this->start38 == null || $this->stop38 == null)
              return null;

         return 'chr' . $this->chr . ':' . $this->start38 . '-' . $this->stop38;
    }
}
