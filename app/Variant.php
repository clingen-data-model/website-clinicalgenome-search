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
class Variant extends Model
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
		'iri' => 'striing|max:80|required',
		'variant_id' => 'string|nullable',
		'caid' => 'string|nullable',
        'condition' => 'json|nullable',
        'evidence_links' => 'json|nullable',
        'gene' => 'json|nullable',
        'guidelines' => 'json|nullable',
        'hgvs' => 'json|nullable',
		'type' => 'integer',
		'status' => 'integer'
	];

	/**
     * Map the json attributes to associative arrays.
     *
     * @var array
     */
	protected $casts = [
			'caid' => 'array',
            'condition' => 'array',
            'evidence_links' => 'array',
            'gene' => 'array',
            'guidelines' => 'array',
            'hgvs' => 'array',
		];

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = ['iri', 'variant_id', 'caid', 'condition', 'evidence_links',
					        'published_date', 'gene', 'guidelines', 'hgvs', 'type', 'status'
                         ];

	/**
     * Non-persistent storage model attributes.
     *
     * @var array
     */
     protected $appends = ['display_date', 'list_date', 'display_status'];

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
	public function scopeIri($query, $curie)
    {
		return $query->where('iri', $curie);
    }


     /**
     * Query scope by symbol or condition name
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public static function sortByClassifications($symbol, $disease = false)
    {
        $classifications = [
            'Pathogenic' => 0,
            'Likely Pathogenic' => 0,
            'Uncertain Significance' => 0,
            'Likely Benign' => 0,
            'Benign' => 0
        ];

        if (!$disease)
        {
            $records = self::where('gene->label', $symbol)->get();
        }
        else
        {
            $records = self::where('condition->@id', $symbol)->get();
        }

        if (empty($records))
            return [$symbol => ['classifications' => $classifications,
                                'panels' => []]];

        $genelist = [];

       // dd($records[0]->guidelines);

        foreach ($records as $record)
        {
            $tag = ($disease ? $record->gene['label'] : $record->condition['label']);

            if (!isset($genelist[$tag]))
            {
                 // deal with some bad records coming from the erepo that contain no gene data
                 if ($disease && !isset($record->gene["NCBI_id"]))
                    continue;

                $genelist[$tag] = [ 'id' => ($disease ? $record->gene['NCBI_id'] : $record->condition['@id']),
                                        'classifications' => $classifications,
                                        'panels' => []];
            }

            $a =& $genelist[$tag]['classifications'];
            $b =& $genelist[$tag]['panels'];

            foreach ($record->guidelines as $guideline)
            {
                if (isset($a[$guideline["outcome"]["label"]]))
                    $a[$guideline["outcome"]["label"]]++;

                foreach($guideline['agents'] as $agent)
                {
                    if (!in_array($agent["affiliation"], array_column($b, 'affiliation')))
                        $b[] = ['affiliation' => $agent["affiliation"], 'id' => $agent['@id']] ;
                }
            }
            //$genelist[$record->gene['label']] = $a;
        }
        return $genelist;
    }

}
