<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Display;

use Uuid;
use Carbon\Carbon;

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
class Jirafield extends Model
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
        'field' => 'string',
        'label' => 'string',
        'column_name' => 'string',
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
	protected $fillable = ['ident', 'field', 'label', 'column_name',
                            'type', 'status'
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
	 		0 => 'Unknown'
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
     * Query scope by field label
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeLabel($query, $label)
    {
		return $query->where('label', $label);
    }


    /**
     * Query scope by field
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeField($query, $field)
    {
		return $query->where('field', $field);
    }


    /**
     * Initialize the mappings
     *
     */
    public static function init()
    {
        $maps = [
            'summary' => '?',
            'labels[]' => 'labels?',
            'components[]->name' => '?',
            'resolution->name' => '?',
            'issuetype->name' => 'curation_type',
            'original_gene_symbol' => 'string',
            'description' => 'description',
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
            'status->name' => 'curation_status',
            'resolution' => 'string',
            'curator' => 'string',
            'comment' => 'text',
            'created_date' => 'date',
            'updated->date' => 'update_date',
            'resolutiondate' => 'resolved_date',
            'reopened_date' => 'date',
            'version' => 'integer',
            'type' => 'integer',
            'status' => 'integer'
        ];

        $f = [
        "customfield_10190" => "Gain PMID 1 Desc",
        "customfield_10191" => "Gain PMID 2",
        "customfield_10192" => "Gain PMID 2 Desc",
        "customfield_10193" => "Gain PMID 3",
        "customfield_12130" => null,
        "customfield_10194" => "Gain PMID 3 Desc",
        //"customfield_10195" => "GRCh37 Minimum Genome Position",
        "customfield_10196" => "targeting_comment",
        "customfield_10197" => "Phenotype comment",
        "customfield_12530" => "gnomAD Allele Frequency",
        "customfield_10198" => "loss_phenotype_comment",
        "customfield_10199" => "gain_phenotype_comment",
        "resolution" => "resolution",
        "customfield_12531" => "Breakpoint Type",
        "customfield_12533" => "Do Population Variants Overlap This Region?",
        "customfield_11831" => "gain_phenotype_name",
        "customfield_12240" => "Gain PMID 4 Description",
        "customfield_10183" => "Loss PMID 1",
        "customfield_10184" => "Loss PMID 1 Description",
        "customfield_12242" => "Gain PMID 6 Description",
        "customfield_10185" => "Loss PMID 2",
        "customfield_12241" => "Gain PMID 5 Description",
        "customfield_10186" => "Loss PMID 2 Description",
        //"customfield_12244" => "LOEUF",
        "customfield_10187" => "Loss PMID 3",
        //"customfield_12243" => "%HI",
        "customfield_10188" => "Loss PMID 3 Description",
        "customfield_12246" => "reduced_penetrance_comment",
        "customfield_10189" => "Gain PMID 1",
        "customfield_12245" => "reduced_penetrance",
        "customfield_11830" => "loss_phenotype_name",
        "labels" => "Labels",
        "customfield_12247" => "loss_phenotype_specificity",
        "customfield_10335" => "DDG2P Gene",
        "customfield_12239" => "Loss PMID 6 Description",
        "customfield_12238" => "Loss PMID 5 Description",
        "issuelinks" => "Linked Issues",
        "status" => "Status",
        "components" => "Component/s",
        "customfield_12231" => "Loss PMID 4",
        "customfield_12230" => "HGNC ID",
        "customfield_12233" => "Loss PMID 6",
        "customfield_12232" => "Loss PMID 5",
        "customfield_10331" => "DDG2P Inheritance",
        "customfield_12235" => "Gain PMID 5",
        "customfield_10332" => "DDG2P Status",
        "customfield_12234" => "Gain PMID 4",
        "customfield_10333" => "DDG2P Consequences",
        "customfield_12237" => "Loss PMID 4 Description",
        "customfield_10334" => "DDG2P Details",
        "customfield_12236" => "Gain PMID 6",
        //"customfield_11930" => "Development",
        "customfield_10160" => "GRCh37 Genome Position",
        "customfield_10161" => "Locus Specific DB link",
        "customfield_12341" => "Type of Evidence Gain PMID 5",
        "customfield_12340" => "Type of Evidence Gain PMID 4",
        "customfield_10164" => "on_180_chip",
        "customfield_10165" => "ISCA Haploinsufficiency score",
        "customfield_12342" => "Type of Evidence Gain PMID 6",
        "customfield_10166" => "ISCA Triplosensitivity score",
        "customfield_10200" => "loss_phenotype_id",
        "customfield_10167" => "Number of probands with a loss",
        "customfield_10201" => "gain_phenotype_id",
        "customfield_10168" => "Number of probands with a gain",
        "customfield_10169" => "targeting_basis",
        "customfield_12338" => "Type of Evidence Gain PMID 2",
        "customfield_12337" => "Type of Evidence Gain PMID 1",
        "customfield_12339" => "Type of Evidence Gain PMID 3",
        "issuetype" => "Issue Type",
        "customfield_10150" => "GeneReviews Link",
        "customfield_10030" => "Gene Symbol",
        "customfield_10152" => "Should be targeted?",
        "customfield_12332" => "Type of Evidence Loss PMID 2",
        "customfield_12331" => "Type of Evidence Loss PMID 1",
        "customfield_12334" => "Type of Evidence Loss PMID 4",
        "customfield_10156" => "Gene Type",
        "customfield_12333" => "Type of Evidence Loss PMID 3",
        "customfield_10157" => "Link to Gene",
        "customfield_12336" => "Type of Evidence Loss PMID 6",
        "customfield_10158" => "GRCh37 SeqID",
        "customfield_12335" => "Type of Evidence Loss PMID 5",
        "customfield_10148" => "Clinical Interpretation",
        "customfield_10149" => "PMID",
        "customfield_11633" => "Triplosensitive phenotype ontology identifier",
        "customfield_11635" => "gnomAD pLI score",
        "resolutiondate" => "Resolved",
        "customfield_10141" => "dbVar ID",
        "customfield_10143" => "chr_inner_start",
        "customfield_10144" => "chr_inner_stop",
        "customfield_11630" => "Loss phenotype ontology ",
        "customfield_10145" => "CytoBand",
        "customfield_10146" => "Allele Type",
        "customfield_11632" => "Triplosensitive phenotype ontology",
        "customfield_10147" => "OMIM Link",
        "customfield_11631" => "Loss phenotype ontology identifier",
        "customfield_10533" => "GRCh37 strand",
        "customfield_10534" => "GRCh38 strand",
        "customfield_10535" => "GRCh38 Minimum position",
        "customfield_10536" => "GRCh38 annotation run",
        "customfield_10537" => "GRCh38 SeqID",
        "customfield_10538" => "Entrez Gene ID",
        "updated" => "Updated",
        "customfield_12030" => "External Sender Email Addresses",
        "description" => "Description",
        "customfield_12430" => "Previous Gene Symbol",
        "customfield_10531" => "GRCh37 annotation run",
        "customfield_10532" => "GRCh38 Genome Position",
        "customfield_10126" => "Send reminder on",
        "summary" => "Summary",
        "customfield_11331" => "CGD Inheritance",
        "customfield_11330" => "CGD Condition",
        "customfield_11332" => "CGD References",
        "customfield_11730" => "WatcherField",
        "customfield_12536" => "Population Variants Description",
        "customfield_12535" => "Population Variants Frequency",
        "customfield_12537" => "Population Variants Data Source",
        ];
    }
}
