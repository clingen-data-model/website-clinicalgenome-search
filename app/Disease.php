<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
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
class Disease extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Display;

    /**
     * The attributes that should be validity checked.
     *
     * @var array
     */
    public static $rules = [
		'ident' => 'alpha_dash|max:80|required',
		'curie' => 'name|max:80|required',
		'label' => 'string|nullable',
		'synonyms' => 'json|nullable',
        'curation_activities' => 'json|nullable',
        'last_curated_date' => 'string|nullable',
		'type' => 'integer',
		'status' => 'integer'
	];

	/**
     * Map the json attributes to associative arrays.
     *
     * @var array
     */
	protected $casts = [
			'synonyms' => 'array',
            'curation_activities' => 'array',
		];

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = ['curie', 'label', 'synonyms', 'curation_activities', 'last_curated_date',
					        'omim', 'description', 'type', 'status',
                         ];

	/**
     * Non-persistent storage model attributes.
     *
     * @var array
     */
     protected $appends = ['display_date', 'list_date', 'display_status',
                            'first_synonym'];

     public const TYPE_NONE = 0;
     public const TYPE_MONDO = 1;
     public const TYPE_OMIM = 2;
     public const TYPE_ORPHANET = 3;
     public const TYPE_MEDGEN = 4;
     public const TYPE_DOID = 5;

     /*
     * Type strings for display methods
     *
     * */
     protected $type_strings = [
	 		0 => 'Unknown',
	 		9 => 'Deleted'
	];

     public const STATUS_INITIALIZED = 0;
     public const STATUS_ACTIVE = 1;
     public const STATUS_GG_DEPRECATED = 9;
     public const STATUS_DEPRECATED = 10;

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
	public function scopeCurie($query, $curie)
    {
		return $query->where('curie', $curie);
    }


    /**
     * Query scope by symbol name
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeDeprecated($query)
    {
		return $query->where('status', 9);
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

		return $query->where('omim', $value);
    }


    /**
     * Query scope by symbol name
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeFilter($query)
    {
		return $query->whereIn('status', [1, 9]);
    }


	/**
     * Get a display formatted form of aliases
     *
     * @@param
     * @return
     */
     public function getFirstSynonymAttribute()
     {
		if (empty($this->synonyms))
			return '';

		return $this->syonyms[0] ?? '';
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
     * Query title for mondo id
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
    public static function titles($id)
    {
      $record = self::curie($id)->first();

      if ($record === null)
        return '';

      return $record->label;
    }

    /**
     * Determine ontology typle by parsing the id
     *
     * @@param  string  $id
     * @return  array
     */
    public static function parseIdentifier($id = null)
    {
        if (empty($id))
            return ['type' => self::TYPE_NONE, 'adjusted' => $id ];

        $k = strpos($id, ':');

        if ($k === false)
            if (is_numeric($id))
                return ['type' => self::TYPE_OMIM, 'adjusted' => $id];         //default
            else
                return ['type' => self::TYPE_NONE, 'adjusted' => $id ];

        switch (strtoupper(substr($id, 0, $k)))
        {
            case 'MONDO':
                return ['type' => self::TYPE_MONDO, 'adjusted' => substr($id, $k + 1)];
            case 'OMIM':
                return ['type' => self::TYPE_OMIM, 'adjusted' => substr($id, $k + 1)];
            case 'ORPHANET':
                return ['type' => self::TYPE_ORPHANET, 'adjusted' => substr($id, $k + 1)];
            case 'MEDGEN':
                return ['type' => self::TYPE_MEDGEN, 'adjusted' => substr($id, $k + 1)];
            case 'DOID':
                return ['type' => self::TYPE_DOID, 'adjusted' => substr($id, $k + 1)];
            default:
                return ['type' => self::TYPE_NONE, 'adjusted' => $id ];

        }

        return ['type' => self::TYPE_NONE, 'adjusted' => $id ];

    }
}
