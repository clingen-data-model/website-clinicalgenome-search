<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Display;

use Uuid;
use Carbon\Carbon;

use App\Curation;
use App\Slug;
use App\Jira;

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
        'Triplosensitive phenotype ontology' => ['key' => 'gain_pheno_ontology', 'value' => 'id'],
        'Triplosensitive phenotype ontology identifier' => ['key' => 'gain_pheno_ontology_id', 'value' => 'id'],
        'Loss phenotype ontology' => ['key' => 'loss_pheno_ontology', 'value' => 'id'],
        'Loss phenotype ontology identifier' => ['key' => 'loss_pheno_ontology_id', 'value' => 'id'],
        'CGD Inheritance' => false,
        'CGD Condition' => false,
        'CGD References' => false,
        'Population Variants Description' => false,
        'Population Variants Frequency' => false,
        'Population Variants Data Source' => false,
        'ISCA Haploinsufficiency score' => ['key' => 'haplo score', 'value' => 'id'],
        'ISCA Loss of function score' => ['key' => 'haplo score', 'value' => 'id'],
        'ISCA Triplosensitivity score' => ['key' => 'triplo score', 'value' => 'id'],
        'Summary' => "summary",
        'assignee' => false,
        'Reporter' => false,
        'Creator' => false,
        'DDG2P Status' => false,
        'DDG2P Inheritance' => false,
        'DDG2P Details' => false,
        'DDG2P Consequences' => false,
        'DDG2P Gene' => false,
        'GeneReviews Link' => ['key' => 'genereviews', 'value' => 'id'],
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
        'resolution' => 'resolution',
        'status' => 'jira_status',
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


    protected static $pmid_fields = [
        'Loss 1' => [
            'evidence_id' => 'customfield_10183',
            'evidence_type' => 'customfield_12331',
            'description' => 'customfield_10184',
        ],
        'Loss 2' => [
            'evidence_id' => 'customfield_10185',
            'evidence_type' => '',
            'description' => 'customfield_10186',
        ],
        'Loss 3' => [
            'evidence_id' => 'customfield_10187',
            'evidence_type' => 'customfield_12333',
            'description' => 'customfield_10188',
        ],
        'Loss 4' => [
            'evidence_id' => 'customfield_12231',
            'evidence_type' => 'customfield_12334',
            'description' => 'customfield_12237',
        ],
        'Loss 5' => [
            'evidence_id' => 'customfield_12232',
            'evidence_type' => 'customfield_12335',
            'description' => 'customfield_12238',
        ],
        'Loss 6' => [
            'evidence_id' => 'customfield_12233',
            'evidence_type' => 'customfield_12336',
            'description' => 'customfield_12239',
        ],
        'Gain 1' => [
            'evidence_id' => 'customfield_10189',
            'evidence_type' => 'customfield_12337',
            'description' => 'customfield_10190',
        ],
        'Gain 2' => [
            'evidence_id' => 'customfield_10191',
            'evidence_type' => 'customfield_12338',
            'description' => 'customfield_10192',
        ],
        'Gain 3' => [
            'evidence_id' => 'customfield_10193',
            'evidence_type' => 'customfield_12339',
            'description' => 'customfield_10194',
        ],
        'Gain 4' => [
            'evidence_id' => 'customfield_12234',
            'evidence_type' => 'customfield_12340',
            'description' => 'customfield_12240',
        ],
        'Gain 5' => [
            'evidence_id' => 'customfield_12235',
            'evidence_type' => 'customfield_12341',
            'description' => 'customfield_12241',
        ],
        'Gain 6' => [
            'evidence_id' => 'customfield_12236',
            'evidence_type' => 'customfield_12342',
            'description' => 'customfield_12242',
        ],
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


    /**
     * The dosage topic queue is missing records, so preload from
     * genegraph as of offset 
     *
     */
    public static function preload()
    {
        $records = GeneLib::dosageList([
                                            'page' => 0,
                                            'pagesize' => "null",
                                            'sort' => 'GENE_LABEL',
                                            'search' => null,
                                            'direction' => 'ASC',
                                            'properties' => true,
                                            'forcegg' => true,
                                            'report' => true,
                                            'curated' => false
                                        ]);

        // flag all the current curations in case any have been removed
        Curation::dosage()->active()->update(['group_id' => 1]);

        foreach ($records->collection as $record)
        {
            //skip over duplicates
            $check = Curation::dosage()->active()->where('source_uuid',$record->dosage_curation->curie)->exists();
            if ($check)
            {
                // unset the remove flag
                Curation::dosage()->active()->where('source_uuid',$record->dosage_curation->curie)->update(['group_id' => 0]);
                continue;
            }

            echo "Updating " . $record->dosage_curation->curie .  "\n";

            $gene = Gene::hgnc($record->hgnc_id)->first();

            $haplo_disease = (empty($record->dosage_curation->haploinsufficiency_assertion->disease->curie) ? null :
                                    Disease::curie($record->dosage_curation->haploinsufficiency_assertion->disease->curie)->first());
            $triplo_disease = (empty($record->dosage_curation->triplosensitivity_assertion->disease->curie) ? null :
                                    Disease::curie($record->dosage_curation->triplosensitivity_assertion->disease->curie)->first());

            // Currently, there is no dosage working group panel id defined
            $panel = Panel::title('Dosage Sensitivity Curation')->first();

            // we need to break out the ISCA number and the timestamp
            preg_match('/CGDOSAGE:(ISCA-[0-9]+)-([0-9\-T\:Z]+)/', $record->dosage_curation->curie, $matches);
            $isca = $matches[1];
            $timestamp = $matches[2];

            // tecnically, genegraph should not send us any older records, but it doesn't hurt to make sure
            $old_curations = Curation::dosage()->where('document', $isca)
                                        ->where('status', '!=', Curation::STATUS_ARCHIVE)
                                        ->get();

            // we add seperate haplo and triplo curation, because in the future they will be separate
            foreach(['triplosensitivity_assertion', 'haploinsufficiency_assertion'] as $assertion)
            {
                if ($record->dosage_curation->$assertion == null)
                    continue;
                
                $data = [
                    'type' => Curation::TYPE_DOSAGE_SENSITIVITY,
                    'type_string' => 'Dosage Sensitivity',
                    'subtype' => Curation::SUBTYPE_DOSAGE_GGP,
                    'subtype_string' => 'Genegraph preload',
                    'group_id' => 0,
                    'sop_version' => "1.0",
                    'curation_version' => null,
                    'source' => 'genegraph',
                    'source_uuid' => $record->dosage_curation->curie,
                    'source_timestamp' => $timestamp,
                    'source_offset' => 0,
                    'packet_id' => null,
                    'message_version' => null,
                    'assertion_uuid' => $record->dosage_curation->curie,
                    'alternate_uuid' => $isca,
                    'panel_id' => $panel->id ?? null,
                    'affiliate_id' => null,
                    'affiliate_details' => ['name' => 'Dosage Sensitivity Curation WG'],
                    'gene_id' => $gene->id,
                    'gene_hgnc_id' => $record->hgnc_id,
                    'gene_details' => ['label' => $record->label, 'hgnc_id' => $record->hgnc_id],
                    'variant_iri' => null,
                    'variant_details' => null,
                    'document' => $isca,
                    'context' => $assertion,
                    'title' => null,
                    'summary' => null,
                    'description' => null,
                    'comments' => null,
                    'disease_id' => ($assertion == 'haploinsufficiency_assertion' ?  $haplo_disease->id ?? null : $triplo_disease->id ?? null),
                    'conditions' => (isset($record->dosage_curation->$assertion->disease->curie) ? [$record->dosage_curation->$assertion->disease->curie] : []),
                    'condition_details' => [$assertion => $record->dosage_curation->$assertion->disease ?? null],
                    'evidence' => null,
                    'evidence_details' => null,
                    'assertions' => [$assertion => $record->dosage_curation->$assertion ?? null],
                    'scores' => ['classification' => $record->dosage_curation->$assertion->dosage_classification->ordinal ?? null],
                    'score_details' => [],
                    'curators' => null,
                    'published' => true,
                    'animal_model_only' => false,
                    'events' => ['report_date' => $record->dosage_curation->$assertion->report_date ?? null],
                    'url' => [],
                    'version' => 1,
                    'status' => Curation::STATUS_ACTIVE
                ];

                $curation = new Curation($data);
                
                //update version number

                $curation->save();

            }

            // create or update the CCID
            $s = Slug::firstOrCreate(['target' => $record->hgnc_id],
                                    [ 'type' => Slug::TYPE_CURATION,
                                      'subtype' => Slug::SUBTYPE_DOSAGE,
                                      'status' => Slug::STATUS_INITIALIZED
                                    ]);
            
            // archive any replace items
            $old_curations->each(function ($item) {
                $item->update(['status' => Curation::STATUS_ARCHIVE]);
            });

        }

        // archive any unpublished items
        Curation::dosage()->where('group_id', 1)->update(['status' => Curation::STATUS_UNPUBLISH]);
    }


    /**
     * The dosage topic queue is missing records, so preload from
     * genegraph as of offset 
     *
     */
    public static function precuration()
    {
    
        $start = 0;

        do {
  
            $records = Jira::getIssues('project = ISCA AND type = "ISCA Gene Curation" AND (status = "Under Group Review" OR status = "Under Primary Review" OR status = "Under Secondary Review")', $start);

            foreach ($records->issues as $issue)
            {

                 $record = (object) $issue->fields->customFields;

                 // map the status to a model value
                 switch ($issue->fields->status->name)
                 {
                   case 'Under Primary Review':
                     $status = Curation::STATUS_PRIMARY_REVIEW;
                     break;
                   case 'Under Secondary Review':
                     $status = Curation::STATUS_SECONDARY_REVIEW;
                     break;
                   case 'Under Group Review':
                     $status = Curation::STATUS_GROUP_REVIEW;
                     break;
                   default:
                     dd($issue->fields->status->name);
                 }

                 if (!isset($record->customfield_12230))
                    continue;

                // only store the minimal amount of precuration information
                $gene = Gene::hgnc($record->customfield_12230)->first();

                $panel = Panel::title('Dosage Sensitivity Curation')->first();


                // tecnically, genegraph should not send us any older records, but it doesn't hurt to make sure
                $old_curations = Curation::dosage()->where('document', $issue->key)
                                            ->whereNotIn('status', [Curation::STATUS_ACTIVE, Curation::STATUS_ARCHIVE])
                                            ->get();

                // we add seperate haplo and triplo curation, because in the future they will be separate
                foreach(['triplosensitivity_assertion', 'haploinsufficiency_assertion'] as $assertion)
                {
                    
                    $data = [
                        'type' => Curation::TYPE_DOSAGE_SENSITIVITY,
                        'type_string' => 'Dosage Sensitivity',
                        'subtype' => Curation::SUBTYPE_DOSAGE_GENE_PRECURATION,
                        'group_id' => 0,
                        'sop_version' => 1,
                        'source' => 'DCI JIRA',
                        'source_uuid' => $issue->key,
                        'alternate_uuid' => $issue->key,
                        'panel_id' => $panel->id,
                        'affiliate_id' => null,
                        'affiliate_details' => ["id" => "", "name" => "Dosage Sensitivity Curation"],
                        'gene_id' => $gene->id,
                        'gene_hgnc_id' => $record->customfield_12230,
                        'gene_details' => [],
                        'document' => $issue->key,
                        'context' => $assertion,
                        'title' => $issue->fields->issuetype->name,
                        'summary' => $issue->fields->summary,
                        'description' => null,
                        'curators' => null,
                        'published' => false,
                        'animal_model_only' => false,
                        'contributors' => [],
                        'events' => [
                            'created' => $issue->fields->created,
                            'updated' => $issue->fields->updated ?? null,
                            'resolved' => $issue->fields->resolutiondate ?? null
                        ],
                        'version' => 0,
                        'status' => $status
                    ];

                    $curation = new Curation($data);
                    $curation->save();
                }


                $old_curations->each(function ($item) {
                    $item->update(['status' => Curation::STATUS_ARCHIVE]);
                });
            }

            $start += $records->maxResults;

        } while ($start < $records->total);

    }


    /**
     * Parse the source for new or updated curations
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function parser()
    {
      // get the datetime of the last update
      $last = Stream::name('dosage-gene-jira')->first();
      $now = Carbon::now()->timestamp;

      $start = 0;

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

      echo "Checking for updates since " . Carbon::createFromTimestamp($last->offset)->setTimezone('America/New_York')->format('Y-m-d H:i') . " \n";

      do {

        // get all the regions from jira
        $records = Jira::getIssues('project = ISCA AND issuetype = "ISCA Gene Curation" AND updated > "' 
                            . Carbon::createFromTimestamp($last->offset)->setTimezone('America/New_York')->format('Y-m-d H:i') . '"', $start);

        foreach ($records->issues as $issue)
        {

          $record = (object) $issue->fields->customFields;

          // duplicate
          if ($issue->fields->status->name == "Closed" && $issue->fields->resolution->name == 'Duplicate')
            continue;

          // won't fix
          if ($issue->fields->status->name == "Closed" && $issue->fields->resolution->name == "Won't Fix")
            continue;

          // won't do
          if ($issue->fields->status->name == "Closed" && $issue->fields->resolution->name == "Won't Do")
            continue;

          // not a bug
          if ($issue->fields->status->name == "Closed" && $issue->fields->resolution->name == "Not a Bug")
            continue;


          echo "Updating " . $issue->key .  "\n";

          switch ($issue->fields->status->name)
          {
            case 'Open':
              $curation_status = Curation::STATUS_OPEN;
              break;
            case 'Under Primary Review':
              $curation_status = Curation::STATUS_PRIMARY_REVIEW;
              break;
            case 'Under Secondary Review':
              $curation_status = Curation::STATUS_SECONDARY_REVIEW;
              break;
            case 'Under Group Review':
              $curation_status = Curation::STATUS_GROUP_REVIEW;
              break;
            case 'Closed':
              $curation_status = ($issue->fields->resolution->name == "Complete" ? Curation::STATUS_ACTIVE : Curation::STATUS_CLOSED);
              break;
            case 'Reopened':
              $curation_status = Curation::STATUS_REOPENED;
              break;
            default:
              dd($issue->fields->status->name);
          }

  
          // break out coordinates
          $coordinates = ['grch37' => self::location_split($record->customfield_10160 ?? null, $record->customfield_10158 ?? ''),
                          'grch38' => self::location_split($record->customfield_10532 ?? null, $record->customfield_10157 ?? '')
                        ];

          // build the most recent score history for fast reference
          $haplo_history = null;
          $triplo_history = null;

          $check = Jira::getHistory($issue);

          if ($check->isNotEmpty())
          {
            foreach ($check as $item)
            {
              if ($item->what == 'Triplosensitivity Score')
                  $triplo_history = $item->what . ' changed from ' . $item->from
                                                . ' to ' . $item->to . ' on ' . $item->when;
              else if ($item->what == 'Haploinsufficiency Score')
                  $haplo_history = $item->what . ' changed from ' . $item->from
                                                . ' to ' . $item->to . ' on ' . $item->when;
            }
          }

          if (!isset($record->customfield_12230))
            continue;

          $gene = Gene::hgnc($record->customfield_12230)->first();

          if ($gene === null)
            die($record);

          // if status has changed to closed, publish a curation
          if ($curation_status == Curation::STATUS_ACTIVE)
          {
            // if there is a score change, update the gene record
           // if ($haplo_history !== null || $triplo_history !== null)
            //    $gene->update()

            // are there existing curations?
            $old_curations = Curation::where('document', $issue->key)
                             ->where('type', Curation::TYPE_DOSAGE_SENSITIVITY)
                            ->where('status', '!=', Curation::STATUS_ARCHIVE)
                            ->get();

            $haplo_disease = (empty($record->customfield_10200) ? null :
                                    Disease::curie($record->customfield_10200)->first());
            $triplo_disease = (empty($record->customfied_10201) ? null :
                                    Disease::curie($record->customfield_10201)->first());

            // look up the dosage panel
            $panel = Panel::title('Dosage Sensitivity Curation')->first();

            // we split the haplo and triplo curations out 
            foreach(['triplosensitivity_assertion', 'haploinsufficiency_assertion'] as $assertion)
            {
              // some of the classifications are strings, which we want to nomalize to its numeric only value
              $field = ($assertion == "haploinsufficiency_assertion" ? 'customfield_10165' : 'customfield_10166');
              $score = $record->$field->value ?? null;
              switch ($score)
              {
                case '40: Dosage sensitivity unlikely':
                  $score = 40;
                  break;
                case '30: Gene associated with autosomal recessive phenotype':
                  $score = 30;
                  break;
              }

              // finally we can build the new curation
              $data = [
                'type' => Curation::TYPE_DOSAGE_SENSITIVITY,
                'type_string' => 'Dosage Sensitivity',
                'subtype' => Curation::SUBTYPE_DOSAGE_DCI,
                'subtype_string' => 'DCI JIRA QUERY',
                'group_id' => 0,
                'sop_version' => "1.0",
                'curation_version' => null,
                'source' => 'dci',
                'source_uuid' => $issue->key,
                'source_timestamp' => 0,
                'source_offset' => 0,
                'packet_id' => null,
                'message_version' =>  null,
                'assertion_uuid' => $issue->key,
                'alternate_uuid' => $issue->key,
                'panel_id' => $panel->id,
                'affiliate_id' => $panel->affiliate_id,
                'affiliate_details' => ['name' => 'Dosage Sensitivity Curation WG'],
                'gene_id' => $gene->id,
                'gene_hgnc_id' => $record->customfield_12230,
                'gene_details' => ['symbol' => $record->customfield_10030, 'hgnc_id' => $record->customfield_12230],
                'variant_iri' => null,
                'variant_details' => null,
                'region_id' => null,
                'region_details' => null,
                'document' => $issue->key,
                'context' => $assertion,
                'title' =>  null,
                'summary' => $issue->fields->summary,
                'description' => $issue->fields->description,
                'comments' => null,
                'disease_id' => ($assertion == 'haploinsufficiency_assertion' ?  $haplo_disease->id ?? null : $triplo_disease->id ?? null),
                'conditions' => ($assertion == 'haploinsufficiency_assertion' ?  
                                              (isset($record->customfield_10200) ? [$record->customfield_10200] : []) :
                                              (isset($record->customfield_10201) ? [$record->customfield_10201] : [] )),
                'condition_details' => ($assertion == 'haploinsufficiency_assertion' ? 
                                              ['disease_id' => $record->customfield_10200 ?? null, 'disease_phenotype_name' => $record->customfield_11830 ?? null,
                                               'phenotype_comments' =>  $record->customfield_10199 ?? null] :
                                              ['disease_id' => $record->customfield_10201 ?? null, 'disease_phenotype_name' => $record->customfield_11831 ?? null,
                                               'phenotype_comments' =>  $record->customfield_10198 ?? null]),
                'evidence' => ($assertion == 'haploinsufficiency_assertion' ? 
                                              ['pmid1' => $record->customfield_10183 ?? null, 'pmid2' => $record->customfield_10185 ?? null, 'pmid3' => $record->customfield_10187 ?? null,
                                               'pmid4' => $record->customfield_12231 ?? null, 'pmid5' => $record->customfield_12232 ?? null, 'pmid6' => $record->customfield_12233 ?? null] :
                                              ['pmid1' => $record->customfield_10189 ?? null, 'pmid2' => $record->customfield_10191 ?? null, 'pmid3' => $record->customfield_10193 ?? null,
                                               'pmid4' => $record->customfield_12234 ?? null, 'pmid5' => $record->customfield_12235 ?? null, 'pmid6' => $record->customfield_12236 ?? null]),
                'evidence_details' => ($assertion == 'haploinsufficiency_assertion' ? 
                                              ['Loss1' => ['evidence_id' => $record->customfield_10183 ?? null, 'evidence_type' => $record->customfield_12331 ?? null,
                                                           'description' => $record->customfield_10184 ?? null],
                                               'Loss2' => ['evidence_id' => $record->customfield_10185 ?? null, 'evidence_type' => $record->customfield_12332 ?? null,
                                                           'description' => $record->customfield_10186 ?? null],
                                               'Loss3' => ['evidence_id' => $record->customfield_10187 ?? null, 'evidence_type' => $record->customfield_12333 ?? null,
                                                           'description' => $record->customfield_10188 ?? null],
                                               'Loss4' => ['evidence_id' => $record->customfield_12231 ?? null, 'evidence_type' => $record->customfield_12334 ?? null,
                                                           'description' => $record->customfield_12237 ?? null],
                                               'Loss5' => ['evidence_id' => $record->customfield_12232 ?? null, 'evidence_type' => $record->customfield_12335 ?? null,
                                                           'description' => $record->customfield_12238 ?? null],
                                               'Loss6' => ['evidence_id' => $record->customfield_12233 ?? null, 'evidence_type' => $record->customfield_12336 ?? null,
                                                           'description' => $record->customfield_12239 ?? null],
                                                'associated_w_reduced_penetrance' => $record->customfield_12245 ?? null,
                                                'reduced_penetrance_comment' => $record->customfield_12246 ?? null,
                                                'loss_specificity' => $record->customfield_12247 ?? null,
                                                'should_be_targeted' => $record->customfield_10152 ?? null,
                                                'targeting_decision_based_on' => $record->customfield_10169 ?? null,
                                                'targeting_decision_comment' => $record->customfield_10196 ?? null,
                                                "cgd_inheritance" => $record->customfield_11331 ?? null,
                                                "cgd_condition" => $record->customfield_11330 ?? null,
                                                "cgd_references" => $record->customfield_11332 ?? null
                                                           ] : 
                                              ['Gain1' => ['evidence_id' => $record->customfield_10189 ?? null, 'evidence_type' => $record->customfield_12337 ?? null,
                                                           'description' => $record->customfield_10190 ?? null],
                                               'Gain2' => ['evidence_id' => $record->customfield_10191 ?? null, 'evidence_type' => $record->customfield_12338 ?? null,
                                                           'description' => $record->customfield_10192 ?? null],
                                               'Gain3' => ['evidence_id' => $record->customfield_10193 ?? null, 'evidence_type' => $record->customfield_12339 ?? null,
                                                           'description' => $record->customfield_10194 ?? null],
                                               'Gain4' => ['evidence_id' => $record->customfield_12234 ?? null, 'evidence_type' => $record->customfield_12340 ?? null,
                                                           'description' => $record->customfield_12240 ?? null],
                                               'Gain5' => ['evidence_id' => $record->customfield_12235 ?? null, 'evidence_type' => $record->customfield_12341 ?? null,
                                                           'description' => $record->customfield_12241 ?? null],
                                               'Gain6' => ['evidence_id' => $record->customfield_12236 ?? null, 'evidence_type' => $record->customfield_12342 ?? null,
                                                           'description' => $record->customfield_12242 ?? null],
                                                'associated_w_reduced_penetrance' => $record->customfield_12245 ?? null,
                                                'reduced_penetrance_comment' => $record->customfield_12246 ?? null,
                                                'loss_specificity' => $record->customfield_12247 ?? null,
                                                'should_be_targeted' => $record->customfield_10152 ?? null,
                                                'targeting_decision_based_on' => $record->customfield_10169 ?? null,
                                                'targeting_decision_comment' => $record->customfield_10196 ?? null,
                                                "cgd_inheritance" => $record->customfield_11331 ?? null,
                                                "cgd_condition" => $record->customfield_11330 ?? null,
                                                "cgd_references" => $record->customfield_11332 ?? null
                                                        ]),
                'assertions' => ($assertion == 'haploinsufficiency_assertion' ?  $record->customfield_10165 ?? null : $record->customfield_10166 ?? null),
                //'scores' => ['haploinsufficiency' => $record->customfield_10165 ?? null, 'triplosensitivity' => $record->customfield_10166 ?? null],
                'scores' => [$assertion => $score],
                'score_details' => [$issue->fields->labels],
                'curators' => $record->contributors ?? null,
                'published' => true,
                'animal_model_only' => false,
                'events' => ['created' => $issue->fields->created,
                              'updated' => $issue->fields->updated,
                              'resolved' => $issue->fields->resolutiondate,
                              'report_date' => $issue->fields->resolutiondate,
                              'resolution' => $issue->fields->resolution->name ?? null,
                              'haplo_score_change' => $haplo_history,
                              'triplo_score_change' => $triplo_history
                ],
                'url' => ['website_display' => "http://search.clinicalgenome.org/kb/gene-dosage/" . $record->customfield_12230],
                'version' => 1,
                'status' => $curation_status
              ];

              $curation = new Curation($data);
              $curation->save();

            }

            // archive the old ones
            $old_curations->each(function ($item) {
                $item->update(['status' => Curation::STATUS_ARCHIVE]);
            });

          }

        }

        $start += $records->maxResults;

      } while ($start < $records->total);

    // update the last checked timestamp
    $last->update(['offset' => $now]);

    }


    /**
     * Map a dosage record to a curation
     *
     */
    public static function parser2($message)
    {
        $data = json_decode($message->payload);
dd($data);
        $fields = $data->fields;

        // is this a gene or region
        if ($fields->issuetype->name == 'ISCA Gene Curation')
        {
            echo "key=" . $data->key . ", gene=" . $fields->customfield_10030 . "\n";

            if ($message->offset == 15557 || $message->offset == 17941 || $message->offset == 18996 || $message->offset == 19411)
            {
                var_dump($message);
            }

            // older messages wont have an hgnc_id and may be a previous symbol
            if (isset($fields->customfield_12230))
                $record = Gene::hgnc($fields->customfield_12230)->first();
            else
            {
                $record = Gene::name($fields->customfield_10030)->first();

                if ($record === null)
                {
                    // check previous names
                    $record = Gene::previous($fields->customfield_10030)->first();

                    if ($record === null)
                    {
                        echo "Gene not found " . $fields->customfield_10030 . "\n";
                        return;
                    }

                }
            }

            return;

            $morph_type = 'App\Models\Gene';

            /**
             * Need to work in all the miscRNA and other special genes
             */

            if ($record === null)
            {
                echo "parser:  gene not found issue $data->key \n";
                return;
            }
        }
        else if ($fields->issuetype->name == 'ISCA Region Curation')
        {
            return;
            $record = Region::source($data->key)-> first();

            // create if non existant
            if ($record === null)
            {
                $record = new Region(['type' => Region::TYPE_DOSAGE, 'name' => $fields->customfield_10202,
                                    'source_id' => $data->key, 'location' => $fields->customfield_10145,
                                    'grch37' => explode_genomic_coordinates($fields->customfield_10160 ?? null, $fields->customfield_10533 ?? null),
                                    'grch38' => explode_genomic_coordinates($fields->customfield_10532 ?? null),
                                    'curation_status' => null,
                                    'curation_activity' => null,
                                    'date_last_curated' => null, 'status' => Region::STATUS_INITIALIZED

                                ]);
                $record->save();
            }

            $morph_type = 'App\Models\Region';


        }
        else
        {
            // unknown dosage curation record
            die("parsed:  unknown record");
        }

        // eventually we'll want to reload without deletes.
        //$curation = Curation::source($message->topic_name . ':' . $message->key . ':' . $message->timestamp . ':' .  $message->offset)->first();

        $curation = null;

        if ($curation === null)
        {
            // see if there are existing versions
            $old = Curation::source("gene_dosage_raw")->sid($message->key)->orderBy('version', 'desc')->first();

            // build up the new curation record
            $curation = new Curation([
                            'type' => Curation::TYPE_DOSAGE_SENSITIVITY,
                            'type_string' => 'Dosage Sensitivity',
                            'subtype' => $fields->project->id,
                            'subtype_string' => $fields->project->key,
                            'group_id' => 0,
                            'sop_version' => 1,
                            'source' => $message->topic_name,
                            'source_uuid' => $data->key,
                            'assertion_uuid' => $message->topic_name . ':' . $data->key . ':' . $message->timestamp . ':' .  $message->offset,
                            'alternate_uuid' => $message->timestamp,
                            'affiliate_id' => null,
                            'affiliate_details' => ["id" => "", "name" => "Dosage Sensitivity Curation"],
                            'gene_hgnc_id' => $record->hgnc_id,
                            'gene_details' => [],
                            'title' => $fields->issuetype->name,
                            'summary' => $fields->summary,
                            'description' => $fields->description,
                            'comments' => null,
                            'conditions' => null,
                            'condition_details' => null,
                            'evidence' => null,
                            'evidence_details' => [
                                'targeting_decision_comment' => $fields->customfield_10196 ?? null,
                                "phenotype_comment" =>  $fields->customfield_10197 ?? null,
                                "gnomad_allele_frequency" => $fields->customfield_12530 ?? null,
                                "loss_phenotype_comments" => $fields->customfield_10198 ?? null,
                                "triplosensitive_phenotype_comments" => $fields->customfield_10199,
                                "breakpoint_type" => $fields->customfield_12531 ?? null,
                                "do_population_variants_overlap_this_region" => $fields->customfield_12533 ?? null,
                                "triplosensitive_phenotype_name" => $fields->customfield_11831 ?? null,
                                "reduced_penetrance_comment" => $fields->customfield_12246 ?? null,
                                "epic_link" => $fields->customfield_11431 ?? null,
                                "associated_with_reduced_penetrance" => $fields->customfield_12245 ?? null,
                                "loss_phenotype_name" => $fields->customfield_11830 ?? null,
                                "loss_phenotype_omim_id_specificity" => $fields->customfield_12247 ?? null,
                                "linked_issues" => $fields->issuelinks ?? null,
                                "on_180k_chip" => $fields->customfield_10164 ?? null,
                                "contains_known_hits_region" => $fields->customfield_12343 ?? null,
                                "loss_phenotype_omim_id" => $fields->customfield_10200 ?? null,
                                "number_of_probands_with_a_loss" => $fields->customfield_10167 ?? null,
                                "triplosensitive_phenotype_omim_id" => $fields->customfield_10201 ?? null,
                                "number_of_probands_with_a_gain" => $fields->customfield_10168 ?? null,
                                "targeting_decision_based_on" => $fields->customfield_10169->value ?? null,
                                "inheritance_pattern" => $fields->customfield_12330 ?? null,
                                "should_be_targeted" => $fields->customfield_10152->value ?? null,
                                "triplosensitive_phenotype_ontology_identifier" => $fields->customfield_11633 ?? null,
                                "loss_phenotype_ontology " => $fields->customfield_11630 ?? null,
                                "triplosensitive_phenotype_ontology" => $fields->customfield_11632 ?? null,
                                "loss phenotype_ontology_identifier" => $fields->customfield_11631 ?? null,
                                "cgd_inheritance" => $fields->customfield_11331 ?? null,
                                "cgd_condition" => $fields->customfield_11330 ?? null,
                                "cgd_references" => $fields->customfield_11332 ?? null,
                                "population_variants_description" => $fields->customfield_12536 ?? null,
                                "population_variants_frequency" => $fields->customfield_12535 ?? null,
                                "population_variants_data_source" => $fields->customfield_12537 ?? null,
                                "labels" => $fields->labels,
                                "resolution" => $fields->resolution->name ?? "ERROR",
                                'loss1' => [
                                    'evidence_id' => $fields->customfield_10183 ?? null,
                                    'evidence_type' => $fields->customfield_12331 ?? null,
                                    'description' => $fields->customfield_10184 ?? null,
                                ],
                                'loss2' => [
                                    'evidence_id' => $fields->customfield_10185 ?? null,
                                    'evidence_type' => '',
                                    'description' => $fields->customfield_10186 ?? null,
                                ],
                                'loss3' => [
                                    'evidence_id' => $fields->customfield_10187 ?? null,
                                    'evidence_type' => $fields->customfield_12333 ?? null,
                                    'description' => $fields->customfield_10188 ?? null,
                                ],
                                'loss4' => [
                                    'evidence_id' => $fields->customfield_12231 ?? null,
                                    'evidence_type' => $fields->customfield_12334 ?? null,
                                    'description' => $fields->customfield_12237 ?? null,
                                ],
                                'loss5' => [
                                    'evidence_id' => $fields->customfield_12232 ?? null,
                                    'evidence_type' => $fields->customfield_12335 ?? null,
                                    'description' => $fields->customfield_12238 ?? null,
                                ],
                                'loss6' => [
                                    'evidence_id' => $fields->customfield_12233 ?? null,
                                    'evidence_type' => $fields->customfield_12336 ?? null,
                                    'description' => $fields->customfield_12239 ?? null,
                                ],
                                'gain1' => [
                                    'evidence_id' => $fields->customfield_10189 ?? null,
                                    'evidence_type' => $fields->customfield_12337 ?? null,
                                    'description' => $fields->customfield_10190 ?? null,
                                ],
                                'gain2' => [
                                    'evidence_id' => $fields->customfield_10191 ?? null,
                                    'evidence_type' => $fields->customfield_12338 ?? null,
                                    'description' => $fields->customfield_10192 ?? null,
                                ],
                                'gain3' => [
                                    'evidence_id' => $fields->customfield_10193 ?? null,
                                    'evidence_type' => $fields->customfield_12339 ?? null,
                                    'description' => $fields->customfield_10194 ?? null,
                                ],
                                'gain4' => [
                                    'evidence_id' => $fields->customfield_12234 ?? null,
                                    'evidence_type' => $fields->customfield_12340 ?? null,
                                    'description' => $fields->customfield_12240 ?? null,
                                ],
                                'gain5' => [
                                    'evidence_id' => $fields->customfield_12235 ?? null,
                                    'evidence_type' => $fields->customfield_12341 ?? null,
                                    'description' => $fields->customfield_12241 ?? null,
                                ],
                                'gain6' => [
                                    'evidence_id' => $fields->customfield_12236 ?? null,
                                    'evidence_type' => $fields->customfield_12342 ?? null,
                                    'description' => $fields->customfield_12242 ?? null,
                                ],
                            ],
                            'scores' => [
                                'Haploinsufficiency score' => $fields->customfield_10165->value ?? null,
                                'Triplosensitivity score' => $fields->customfield_10166->value ?? null
                            ],
                            'score_details' => null,
                            'curators' => [
                                'reporter' => [ 'name' => $fields->reporter->displayName, 'email' => $fields->reporter->emailAddress ],
                                'creator' => [ 'name' => $fields->creator->displayName , 'email' => $fields->creator->emailAddress ],
                                'assingee' => [ 'name' => $fields->assignee->displayName ?? null, 'email' => $fields->assignee->emailAddress ?? null ]
                            ],
                            'published' => !empty($fields->resolutiondate),
                            'animal_model_only' => false,
                            'contributors' => [],
                            'events' => [
                                'created' => $fields->created,
                                'updated' => $fields->updated ?? null,
                                'resolved' => $fields->resolutiondate ?? null
                            ],
                            'version' => ($old->version ?? 0) + 1,
                            'status' => Curation::map_activity_status($fields->status->name, Curation::TYPE_DOSAGE_SENSITIVITY)

                          //  'curation_class' => $fields->issuetype->name,
                          //  'curatable_type' => $morph_type,
                          //  'curatable_id' => $record->id,

                          //  'is_closed' => !empty($fields->resolutiondate),



                         //   'resolution' => $fields->resolution->name  ?? null,
                        ]);
//dd($curation);
            //$record->curations()->save($curation);
            $curation->save();

            // unpublish the old record if necessary
            if ($curation->published && ($old->published ?? false))
                $old->update(['published' => false]);

        }
//dd($curation);
        // Create or attach named labels
        /*foreach ($fields->labels as $label)
        {
            $tag = Tag::dosage()->label($label)->first();

            if ($tag === null)
            {
                $tag = new Tag(['type' => Tag::TYPE_DOSAGE, 'label' => $label, 'status' => Tag::STATUS_INITIALIZED]);

                $tag->save();
            }

            if ($curation->tags()->where('tags.id', $tag->id)->doesntExist())
                $curation->tags()->attach($tag->id);
        }*/

        // Create or attach evidence
        /*
        foreach(self::$pmid_fields as $key => $value)
        {
            if (isset($fields->{$value['evidence_id']}))
            {
                $pmid = $fields->{$value['evidence_id']};

                //$pmid = normalize_pmid();

                $subtype = (strpos($key, 'Loss') === false ? Evidence::SUBTYPE_GAIN :
                                                             Evidence::SUBTYPE_LOSS);

                if ($subtype == Evidence::SUBTYPE_LOSS)
                    $evidence = $curation->evidences()->eid($pmid)->loss()->first();
                else
                    $evidence = $curation->evidences()->eid($pmid)->gain()->first();

                if ($evidence === null)
                {
                    $evidence = new Evidence(['type' => Evidence::TYPE_DOSAGE,
                                            'subtype' => $subtype,
                                            'is_pmid' => true,
                                            'evidence_id' => $pmid,
                                            'evidence_type' => $fields->{$value['evidence_type']}->value ?? null,
                                            'description' => $fields->{$value['description']} ?? null,
                                            'status' => Evidence::STATUS_INITIALIZED
                    ]);

                    $curation->evidences()->save($evidence);
                }

            }

        */

        // update status
        /*
        if ($morph_type == 'App\Models\Gene' && $curation->is_published && $curation->resolution == 'Complete')
        {
            Gene::where('id', $record->id)->update(['curation_status' => ['dosage_sensitivity' => true], 'date_last_curated' => Carbon
            ::parse($fields->updated ?? null)]);
        }
        */

        //dd($curation);

    }


    /**
     * Split a jira coordinate field out to its individual components
     * 
     */
    protected static function location_split($location, $sequence = '')
    {

      if (empty($location))
        return ['chr' => '', 'start' => '', 'stop' => '', 'seqid' => $sequence];

      // break out the location and clean it up
      $location = preg_split('/[:-]/', trim($location), 3);

      $chr = strtoupper($location[0]);

      if (strpos($chr, 'CHR') === 0 )   // strip out the chr
        $chr = substr($chr, 3);

      //strip out the commas
      $start = str_replace(',', '', $location[1] ?? '');
      $stop = str_replace(',', '', $location[2] ?? '');

      // change x and y to numerics
      if ($chr == 'X')
          $chr = 23;

      if ($chr == 'Y')
          $chr = 24;

      return ['chr' => $chr, 'start' => $start, 'stop' => $stop, 'seqid' => $sequence];
    }
}
