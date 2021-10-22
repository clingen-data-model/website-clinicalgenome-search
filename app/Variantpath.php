<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Display;

use Uuid;
use Carbon\Carbon;

use App\GeneLib;
use App\Change;
use App\Gene;

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
class Variantpath extends Model
{
    use HasFactory;

    use SoftDeletes;
    use Display;

    /**
     * The attributes that should be Variant Pathogenicity checked.
     *
     * @var array
     */
    public static $rules = [
		'ident' => 'alpha_dash|max:80|required',
        'curie' => 'string',
        'vid' => 'string',
        'report_date' => 'timestamp',
        'disease_label' => 'string',
        'disease_curie' => 'string',
        'gene_label' => 'string',
        'gene_hgnc_id' => 'string',
        'classification' => 'string',
        'specified_by' => 'string',
        'attributed_to' => 'string',
		'type' => 'integer',
		'status' => 'integer'
	];

	/**
     * Map the json attributes to associative arrays.
     *
     * @var array
     */
	protected $casts = [
			'validity' => 'array',
            'actionability' => 'array',
            'dosage' => 'array',
            'pharma' => 'array',
            'variant' => 'array'
		];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = ['ident', 'curie', 'vid', 'report_date', 'disease_label',
                            'disease_mondo', 'gene_label', 'gene_hgnc_id',
                            'classification',
                            'specified_by', 'attributed_to', 'version', 'type', 'status',
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
     * Get the change
     */
    public function oldchange()
    {
        return $this->morphOne(Change::class, 'old');
    }


    /**
     * Get the change
     */
    public function newchange()
    {
        return $this->morphOne(Change::class, 'new');
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
     * Query scope by curie (assertion id)
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeCurie($query, $ident)
    {
		return $query->where('curie', $ident);
    }


    /**
     * Retrieve, compare, and load a fresh dataset
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function assertions()
    {
        // get new variant pathogenicity data
        try {

            $results = file_get_contents("http://erepo.genome.network/evrepo/api/interpretations?matchLogic=and&matchMode=keyword&matchLimit=all");

        } catch (\Exception $e) {

            die ("Failure to retrieve new data");
            exit;
        }

        $data = json_decode($results);
        // clear out the status field

        // compare and update
        foreach ($data->variantInterpretations as $assertion)
        {
            $current = Variantpath::curie($assertion->{'@id'})->orderBy('version', 'desc')->first();

            if ($current === null)          // new assertion
            {
                $current = Variantpath::create([
                                    'curie' => $assertion->{'@id'},
                                    'vid' => $assertion->variationId,
                                    'report_date' => Carbon::parse($assertion->publishedDate)->format('Y-m-d H:i:s.0000'),
                                    'disease_label' => $assertion->condition->label,
                                    'disease_mondo' => $assertion->condition->{'@id'},
                                    'gene_label' => $assertion->gene->label,
                                    'classification' => null,
                                    'specified_by' => null,
                                    'attributed_to' => null,
                                    'version' => 1,
                                    'type' => 1,
                                    'status' => 1
                                ]);

                $gene = Gene::name($current->gene_label)->first();

                if ($gene !== null)
                {
                    $current->update(['gene_hgnc_id' => $gene->hgnc_id]);


                    $a = Change::create([
                                    'type' => Change::TYPE_VARIANT,
                                    'category' => Change::CATEGORY_NONE,
                                    'element_id' => $gene->id,
                                    'element_type' => 'App\Gene',
                                    'old_id' =>null,
                                    'old_type' => null,
                                    'new_id' => $current->id,
                                    'new_type' => 'App\Variantpath',
                                    'change_date' => $current->report_date,
                                    'description' => ['New curation activity'],
                                    'status' => 1
                        ]);
                    }

                continue;
            }

            $new = new Variantpath([
                                    'curie' => $assertion->{'@id'},
                                    'vid' => $assertion->variationId,
                                    'report_date' => Carbon::parse($assertion->publishedDate)->format('Y-m-d H:i:s.0000'),
                                    'disease_label' => $assertion->condition->label,
                                    'disease_mondo' => $assertion->condition->{'@id'},
                                    'gene_label' => $assertion->gene->label,
                                    'classification' => null,
                                    'specified_by' => null,
                                    'attributed_to' => null,
                                    'version' => $current->version + 1,
                                    'type' => 1,
                                    'status' => 1
                                ]);

            $gene = Gene::name($new->gene_label)->first();

            if ($gene !== null)
                    $new->gene_hgnc_id = $gene->hgnc_id;

            $differences = $this->compare($current, $new);

            if (!empty($differences))      // update
            {
                //dd($new);
                $new->save();

                $gene = Gene::hgnc($new->gene_hgnc_id)->first();

                // we'll use the current date for search, since there is no concept of a reissue date in GCI
                Change::create([
                                'type' => Change::TYPE_VARIANT,
                                'category' => Change::CATEGORY_NONE,
                                'element_id' => $gene->id,
                                'element_type' => 'App\Gene',
                                'old_id' =>$current->id,
                                'old_type' => 'App\Variantpath',
                                'new_id' => $new->id,
                                'new_type' => 'App\Variantpath',
                                'change_date' => Carbon::yesterday(),   // $new->report_date,
                                'status' => 1,
                                'description' => $this->scribe($differences)
                    ]);
            }
        }

        return $data;
    }


    /**
     * Retrieve, compare, and load a fresh dataset
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function compare($old, $new)
    {
        $old_array = $old->toArray();
        $new_array = $new->toArray();

        // unset a few fields we don't care about
        unset($old_array['id'], $old_array['ident'], $old_array['version'], $old_array['type'], $old_array['status'],
              $old_array['created_at'], $old_array['updated_at'], $old_array['deleted_at'], $old_array['display_date'],
              $old_array['list_date'], $old_array['display_status']);
        unset($new_array['id'], $new_array['ident'], $new_array['version'], $new_array['type'], $new_array['status'],
              $new_array['created_at'], $new_array['updated_at'], $new_array['deleted_at'], $new_array['display_date'],
              $new_array['list_date'], $new_array['display_status']);

        $diff = array_diff_assoc($new_array, $old_array);

        return $diff;
    }


    /**
     * Parse the content array and return a scribe version for reports
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scribe($content)
    {
        if (empty($content))
            return null;

        $annot = [];

        foreach ($content as $key => $value)
        {
            switch ($key)
            {
                case 'curie':
                    $annot[] = 'New assertion';
                    break;
                case 'report_date':
                    $annot[] = 'Report date has changed';
                    break;
                case 'gene_label':
                    $annot[] = 'Gene symbol has changed';
                    break;
                case 'gene_hgnc_id':
                    $annot[] = 'Gene HGNC ID has changed';
                    break;
                case 'disease_label':
                    $annot[] = 'Disease label has changed';
                    break;
                case 'disease_mondo':
                    $annot[] = 'Disease MONDO ID has changed';
                    break;
                /*case 'classification':
                    $annot[] = 'Validity classification has changed';
                    break;
                case 'specified_by':
                    $annot[] = 'Evaluation SOP has changed';
                    break;
                case 'attributed_to':
                    $annot[] = 'CGEP/WG attribution has changed';
                    break;*/
            }
        }

        return $annot;
    }
}
