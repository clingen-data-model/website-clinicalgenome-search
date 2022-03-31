<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
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
class Dosage extends Model
{
    use HasFactory;
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
              'history' => 'array',
              'gain_pheno_omim' => 'array',
              'loss_pheno_omim' => 'array',
    ];

    /**
     * The attributes that are mass assignable.  Remember to fill it
     * in when all the attributes are known.
     *
     * @var array
     */
     protected $fillable = ['label', 'issue', 'curation', 'description', 'cytoband',
                            'chr', 'start', 'stop', 'start38', 'stop38', 'grch37',
                            'grch38', 'pli', 'omiim', 'haplo', 'triplo', 'history',
                            'haplo_history', 'triplo_history',
                            'gain_pheno_omim', 'gain_pheno_ontology', 'gain_pheno_ontology_id',
                            'gain_pheno_name', 'gain_comments',
                            'loss_pheno_omim', 'loss_pheno_ontology', 'loss_pheno_ontology_id',
                            'loss_pheno_name', 'loss_comments',
                            'workflow', 'resolved', 'notes', 'type', 'status'
                            ];

	  /**
     * Non-persistent storage model attributes.
     *
     * @var array
     */
    protected $appends = [];

    protected static $field_map = [
        'description' => 'description',
        'ISCA Region Name' => false,
        'Gene Symbol' => false,
        'HGNC ID' => false,
        'CytoBand' => false,
        'Genome Position' => false,
        'Genome SeqID' => false,
        'labels' => false,
        'Attachment' => false,
        'Targeting decision comment' => false,
        'Phenotype comment' => false,
        'gnomAD Allele Frequency' => false,
        'Loss phenotype comments' => 'loss_comments',
        'Triplosensitive phenotype comments' => 'gain_comments',
        'Breakpoint Type' => false,
        'Do Population Variants Overlap This Region?' => false,
        'Triplosensitive phenotype name' => 'gain_pheno_name',
        'Reduced Penetrance Comment' => 'reduced_penetrance_comment',
        'Epic Link' => false,
        'Associated with Reduced Penetrance' => 'reduced_penetrance',
        'Loss phenotype name' => 'loss_pheno_name',
        'Loss Phenotype OMIM ID Specificity' => false,
        'Loss Specificity' => false,

        'Link' => ['key' => 'attributes', 'value' => 'linked_issues'],

        'on 180K Chip' => false,
        'Contains Known HI/TS Region?' => false,
        'Loss phenotype OMIM ID' => ['key' => 'loss_pheno_omim', 'value' => 'id', 'type' => Disease::TYPE_OMIM],

        'Haploinsufficiency Disease ID' => ['key' => 'loss_pheno_omim', 'value' => 'id', 'type' => 0],

        'Number of probands with a loss' => false,
        'Triplosensitive phenotype OMIM ID' => ['key' => 'gain_pheno_omim', 'value' => 'id', 'type' => Disease::TYPE_OMIM],

        'Triplosensitive Disease ID' => ['key' => 'attributes', 'value' => 'gain_pheno_omim'],

        'Number of probands with a gain' => false,
        'Targeting decision based on' => false,
        'Inheritance Pattern' => false,
        'Should be targeted?' => false,
        'Triplosensitive phenotype ontology' => ['key' => '', 'value' => 'gain_pheno_ontology'],
        'Triplosensitive phenotype ontology identifier' => ['key' => '', 'value' => 'gain_pheno_ontology_id'],
        'Loss phenotype ontology' => ['key' => '', 'value' => 'loss_pheno_ontology'],
        'Loss phenotype ontology identifier' => ['key' => '', 'value' => 'loss_pheno_ontology_id'],
        'CGD Inheritance' => false,
        'CGD Condition' => false,
        'CGD References' => false,
        'Population Variants Description' => false,
        'Population Variants Frequency' => false,
        'Population Variants Data Source' => false,
        'ISCA Haploinsufficiency score' => ['key' => '', 'value' => 'haplo score'],
        'ISCA Loss of function score' => ['key' => '', 'value' => 'haplo_score'],
        'ISCA Triplosensitivity score' => ['key' => '', 'value' => 'triplo score'],
        'Summary' => "summary",
        'assignee' => false,
        'Reporter' => false,
        'Creator' => false,
        'DDG2P Status' => false,
        'DDG2P Inheritance' => false,
        'DDG2P Details' => false,
        'DDG2P Consequences' => false,
        'DDG2P Gene' => false,
        'GeneReviews Link' => ['key' => '', 'value' => 'genereviews'],
        'dbVar ID' => false,
        'Link to Gene' => false,
        'OMIM Link' => false,
        'GRCh37 strand' => false,
        'GRCh38 strand' => false,
        'GRCh38 Minimum position' => false,
        'GRCh38 annotation run' => false,
        'GRCh38 SeqID' => false,
        'GRCh38 Genome Position' => false,
        'ExAC pLI score' => false,
        'gnomAD pLI score' => false,
        'Previous Gene Symbol' => false,
        'Original Loss Phenotype ID' => false,
        'Original Loss phenotype name' => false,
        'Minimum Genome Position' => false,
        'Comment' => "comment",
        'resolution' => ['key' => '', 'value' => 'resolution'],
        'status' => ['key' => '', 'value' => 'jira_status'],
        'Component/s' => false,
        'Component' => false,
        'GRCh37 Genome Position' => false,
        'WatcherField' => false,
        'Workflow' => false,
        'Loss PMID 1' => ['key' => 'loss_pmids', 'value' => 'pmid', 'sid' => 1],
        'Loss PMID 1 Description' => ['key' => 'loss_pmids', 'value' => 'desc', 'sid' => 1],
        'Type of Evidence Loss PMID 1' => false,
        'Loss PMID 2' => ['key' => 'loss_pmids', 'value' => 'pmid', 'sid' => 2],
        'Loss PMID 2 Description' => ['key' => 'loss_pmids', 'value' => 'desc', 'sid' => 2],
        'Type of Evidence Loss PMID 2' => false,
        'Loss PMID 3' => ['key' => 'loss_pmids', 'value' => 'pmid', 'sid' => 3],
        'Loss PMID 3 Description' => ['key' => 'loss_pmids', 'value' => 'desc', 'sid' => 3],
        'Type of Evidence Loss PMID 3' => false,
        'Loss PMID 4' => ['key' => 'loss_pmids', 'value' => 'pmid', 'sid' => 4],
        'Loss PMID 4 Description' => ['key' => 'loss_pmids', 'value' => 'desc', 'sid' => 4],
        'Type of Evidence Loss PMID 4' => false,
        'Loss PMID 5' => ['key' => 'loss_pmids', 'value' => 'pmid', 'sid' => 5],
        'Loss PMID 5 Description' => ['key' => 'loss_pmids', 'value' => 'desc', 'sid' => 5],
        'Type of Evidence Loss PMID 5' => false,
        'Loss PMID 6' => ['key' => 'loss_pmids', 'value' => 'pmid', 'sid' => 6],
        'Loss PMID 6 Description' => ['key' => 'loss_pmids', 'value' => 'desc', 'sid' => 6],
        'Type of Evidence Loss PMID 6' => false,
        'Gain PMID 1' => ['key' => 'gain_pmids', 'value' => 'pmid', 'sid' => 1],
        'Gain PMID 1 Description' => ['key' => 'gain_pmids', 'value' => 'desc', 'sid' => 1],
        'Type of Evidence Gain PMID 1' => false,
        'Gain PMID 1 Desc' => ['key' => 'gain_pmids', 'value' => 'desc', 'sid' => 1],
        'Gain PMID 2' => ['key' => 'gain_pmids', 'value' => 'pmid', 'sid' => 2],
        'Gain PMID 2 Description' => ['key' => 'gain_pmids', 'value' => 'desc', 'sid' => 2],
        'Type of Evidence Gain PMID 2' => false,
        'Gain PMID 2 Desc' => ['key' => 'gain_pmids', 'value' => 'desc', 'sid' => 2],
        'Gain PMID 3' => ['key' => 'gain_pmids', 'value' => 'pmid', 'sid' => 3],
        'Gain PMID 3 Description' => ['key' => 'gain_pmids', 'value' => 'desc', 'sid' => 3],
        'Type of Evidence Gain PMID 3' => false,
        'Gain PMID 3 Desc' => ['key' => 'gain_pmids', 'value' => 'desc', 'sid' => 3],
        'Gain PMID 4' => ['key' => 'gain_pmids', 'value' => 'pmid', 'sid' => 4],
        'Gain PMID 4 Description' => ['key' => 'gain_pmids', 'value' => 'desc', 'sid' => 4],
        'Type of Evidence Gain PMID 4' => false,
        'Gain PMID 4 Desc' => ['key' => 'gain_pmids', 'value' => 'desc', 'sid' => 4],
        'Gain PMID 5' => ['key' => 'gain_pmids', 'value' => 'pmid', 'sid' => 5],
        'Gain PMID 5 Description' => ['key' => 'gain_pmids', 'value' => 'desc', 'sid' => 5],
        'Type of Evidence Gain PMID 5' => false,
        'Gain PMID 5 Desc' => ['key' => 'gain_pmids', 'value' => 'desc', 'sid' => 5],
        'Gain PMID 6' => ['key' => 'gain_pmids', 'value' => 'pmid', 'sid' => 6],
        'Gain PMID 6 Description' => ['key' => 'gain_pmids', 'value' => 'desc', 'sid' => 6],
        'Type of Evidence Gain PMID 6' => false,
        'Gain PMID 6 Desc' => ['key' => 'gain_pmids', 'value' => 'desc', 'sid' => 6],
        'GRCh37 Minimum Genome Position' => false,
        'Gene Type' => false,
        'Loss phenotype ontology' => false,
        'Loss phenotype ontology ' => false,
        'Loss phenotype ontology name'  => false,
        'Original Gain phenotype Name' => false,
        'PMID' => false,
        'Original Gain Phenotype ID' => false,
        'Locus Specific DB link' => false
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
     * Return full name of gene
     *
     * @@param
     * @return
     */
    public function getHgncIdAttribute()
    {
		  return $this->issue ?? null;
    }


    /**
     * Return full name of gene
     *
     * @@param
     * @return
     */
    public function getSymbolAttribute()
    {
		  return $this->label ?? null;
    }


    /**
     * Return full name of gene
     *
     * @@param
     * @return
     */
    public function getChromosomeBandAttribute()
    {
		  return $this->cytoband ?? null;
    }


    /**
     * Return full name of gene
     *
     * @@param
     * @return
     */
    public function getOmimlinkAttribute()
    {
		  return $this->omim ?? null;
    }


    /**
     * Return full name of gene
     *
     * @@param
     * @return
     */
    public function getTriploAssertionAttribute()
    {
		  return $this->triplo ?? null;
    }


    /**
     * Return full name of gene
     *
     * @@param
     * @return
     */
    public function getHaploAssertionAttribute()
    {
		  return $this->haplo ?? null;
    }


    /**
     * Return full name of gene
     *
     * @@param
     * @return
     */
    public function getResolvedDateAttribute()
    {
		  return $this->resolved ?? null;
	}


    /**
     * Map a dosage history attribute to a curation field.
     *
     */
    public static function mapHistory($attribute)
    {

        if (isset(self::$field_map[$attribute]))
            return self::$field_map[$attribute];

        return null;
    }
}
