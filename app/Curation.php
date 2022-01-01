<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Display;

use Uuid;
use Carbon\Carbon;

use App\Jirafield;

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
class Curation extends Model
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
            'gene_id' => 'integer',
            'type' => 'integer',
            'curation_type' => 'string',
            'original_gene_symbol' => 'string',
            'description' => 'text',
            'on_180_chip' => 'integer',
            'reduced_penetrance' => 'integer',
            'reduced_penetrance_comment' => 'text',
            'loss_phenotype_id' => 'string',
            'loss_phenotype_specificity' => 'string',
            'loss_phenotype_name' => 'string',
            'loss_phenotype_comment' => 'text',
            'haploinsufficiency_score' => 'string',
            'triplosensitivity_score' => 'string',
            'targeting_decision' => 'string',
            'targeting_basis' => 'string',
            'targeting_comment' => 'text',
            'cgd_condition' => 'string',
            'cgd_inheritance' => 'string',
            'cgd_references' => 'text',
            'curation_status' => 'string',
            'resolution' => 'string',
            'curator' => 'string',
            'comment' => 'text',
            'created_date' => 'date',
            'update_date' => 'date',
            'resolved_date' => 'date',
            'reopened_date' => 'date',
            'version' => 'integer',
            'type' => 'integer',
            'status' => 'integer'
	];

	/**
     * Map the json attributes to associative arrays.
     *
     * @var array
     */
	protected $casts = [
	];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = ['ident', 'gene_id', 'type', 'curation_type', 'original_gene_symbol',
                            'description', 'on_180_chip', 'reduced_penetrance', 'reduced_penetrance_comment',
                            'loss_phenotype_id', 'loss_phenotype_specificity', 'loss_phenotype_name', 'loss_phenotype_comment',
                            'gain_phenotype_id', 'gain_phenotype_specificity', 'gain_phenotype_name', 'gain_phenotype_comment',
                            'haploinsufficiency_score', 'triplosensitivity_score', 'targeting_decision',  'targeting_basis',
                            'targeting_comment', 'cgd_condition', 'cgd_inheritance', 'cgd_references',  'curation_status',
                            'resolution', 'curator', 'comment', 'created_date', 'update_date', 'resolved_date',
                            'reopened_date', 'version',
                            'type', 'status'
                         ];

	/**
     * Non-persistent storage model attributes.
     *
     * @var array
     */
    protected $appends = ['display_date', 'list_date', 'display_status'];

    public const TYPE_NONE = 0;
    public const TYPE_DOSAGE_SENSITIVITY = 1;

    /*
     * Type strings for display methods
     *
     * */
    protected $type_strings = [
	 		0 => 'Unknown',
	 		1 => 'Dosage Sensitivity'
        ];

    public const STATUS_INITIALIZED = 0;
    public const STATUS_ACTIVE = 1;

    /*
     * Status strings for display methods
     *
     * */
    protected $status_strings = [
	 		0 => 'Initialized',
            1=> 'Active',
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
     * Query scope by pmid
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopePmid($query, $pmid)
    {
		return $query->where('pmid', $pmid);
    }


    /**
     * Query scope by type
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeType($query, $type)
    {
		return $query->where('type', $type);
    }


    /**
     * Query scope by subtype
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeSub($query, $sub)
    {
		return $query->where('subtype', $sub);
    }


    /**
     * Map a Jira issue to a curation record format
     *
     */
    public static function map($issue)
    {
        // $key = $issue->key;

        $fields = (array) $issue->fields;

        $keys = array_keys($fields);

        $mappings = Jirafield::select(['field', 'column_name'])->whereIn('field', $keys)->get();

        $new = [];

        foreach($mappings as $mapping)
            if ($mapping->column_name !== null)
                $new[$mapping->column_name] = $fields[$mapping->field];


        dd($mappings);
    }
}
