<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Display;

use Uuid;
use Carbon\Carbon;

use App\Jira;
use App\Curation;
use App\Stream;

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
class Region extends Model
{
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
              'tags' => 'array',
              'events' => 'array',
              'metadata' => 'array',
              'coordinates' => 'array',
              'scores' => 'array',
              'activity' => 'array'
    ];

    /**
     * The attributes that are mass assignable.  Remember to fill it
     * in when all the attributes are known.
     *
     * @var array
     */
     protected $fillable = ['location', 'chr', 'start', 'stop', 'issue', 'curation', 'events',
                            'workflow', 'history', 'iri', 'coordinates', 'type_string', 'subtype',
                            'subtype_string', 'scores', 'cytoband', 'description', 'tags', 'metadata',
                            'date_last_curated', 'activity',
                            'name', 'gain', 'loss', 'pli', 'status', 'omim', 'type' ];

	  /**
     * Non-persistent storage model attributes.
     *
     * @var array
     */
    protected $appends = [];


    public const TYPE_REGION_GRCH37 = 1;
    public const TYPE_REGION_GRCH38 = 2;
    public const TYPE_REGION_DOSAGE = 3;


    public const SUBTYPE_REGION_DOSAGE_JIRA_PRELOAD = 1;

    public const STATUS_INITIALIZED = 0;
    public const STATUS_ACTIVE = 1;
    public const STATUS_OPEN = 2;
    public const STATUS_UNDER_PRIMARY_REVIEW = 3;
    public const STATUS_UNDER_SECONDARY_REVIEW = 4;
    public const STATUS_UNDER_GROUP_REVIEW = 5;
    public const STATUS_CLOSED = 6;
    public const STATUS_REOPENED = 7;
    public const STATUS_ARCHIVED = 8;
    public const STATUS_DELETED = 8;
    public const STATUS_UNPUBLISH = 8;


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


    /*
     * The curations associated with this region
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
     * Query scope by location
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function scopeLocation($query, $location)
    {
      return $query->where('location', $location);
    }


    /**
     * Query scope by iri
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function scopeCurie($query, $curie)
    {
      return $query->where('iri', $curie);
    }


    /**
     * Query scope by status of active
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function scopeActive($query)
    {
      return $query->where('status', self::STATUS_CLOSED);
    }


    /**
     * Get a display formatted form of grch37
     *
     * @@param
     * @return
     */
    public function getGrch37Attribute()
    {
      if (!isset($this->coordinates['grch37']))
        return null;

      $c = (object) $this->coordinates['grch37'];

      if ($c->chr === null || $c->start === null || $c->stop === null)
          return null;

      switch ($c->chr)
      {
          case '23':
                $chr = 'X';
                break;
          case '24':
                $chr = 'Y';
                break;
          default:
                $chr = $c->chr;
      }

      return 'chr' . $chr . ':' . $c->start . '-' . $c->stop;
    }


   /**
    * Get a display formatted form of grch38
    *
    * @@param
    * @return
    */
    public function getGrch38Attribute()
    {
      if (!isset($this->coordinates['grch38']))
        return null;

      $c = (object) $this->coordinates['grch38'];

      if ($c->chr === null || $c->start === null || $c->stop === null)
          return null;

      switch ($c->chr)
      {
          case '23':
                $chr = 'X';
                break;
          case '24':
                $chr = 'Y';
                break;
          default:
                $chr = $c->chr;
      }

      return 'chr' . $chr . ':' . $c->start . '-' . $c->stop;
    }


    /**
     * Check for proper region formatting.  Accepted formats are:
     *
     *    chr#:#-#
     *    #:#-#
     *    #p#[.#]-#p#[.#]
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
    public static function checkRegion($region)
    {
      if (empty($region))
        return false;

    // remove any whitespace
    $region = preg_replace('/\s/', '', $region);

      if (strtoupper(substr($region, 0, 3)) == 'CHR')     // get rid of the useless chr
        $region = substr($region, 3);

      if (preg_match('/^([0-9xXyY]{1,2}):([0-9,]+)-([0-9,]+)$/', $region, $matches))
      {

        if (count($matches) != 4)
          return false;

        // clean up the values
        $chr = strtoupper($matches[1]);
        $start = str_replace(',', '', $matches[2]);
        $stop = str_replace(',', '', $matches[3]);

        if ($start == '' || $stop == '')
          return false;

        if ((int) $start >= (int) $stop)
          return false;

        return true;
      }
      else if (preg_match('/^([0-9X]{1,2})([pPqQ])([0-9]+)([\.[0-9]+])?-([0-9X]{1,2})([pPqQ])([0-9]+)([\.[0-9]+])?$/', $region, $matches))
      {

        /*dd($matches);
        $chr = \mb_strtoupper($matches[1]);

        $arm = strtolower($matches[2]);

        $band = $matches[3];

        $subband = $matches[4];

        $chr = \mb_strtoupper($matches[1]);

        $arm = strtolower($matches[2]);

        $band = $matches[3];

        $subband = $matches[4];*/
        return false;
      }
      else
        return false;
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

      // map string type to type flag
      if ($type == 'GRCh37')
        $type = 1;
      else if ($type == 'GRCh38')
        $type = 2;
      else
        return (object) ['count' => $collection->count(), 'collection' => $collection,
                        'gene_count' => $gene_count, 'region_count' => $region_count];

    // remove any whitespace
    $region = preg_replace('/\s/', '', $region);

      // break out the location and clean it up
      $location = preg_split('/[:-]/', trim($region), 3);

      $chr = strtoupper($location[0]);

      if (strpos($chr, 'CHR') == 0)   // strip out the chr
          $chr = substr($chr, 3);

      //vet the search terms
      $start = str_replace(',', '', empty($location[1]) ? '0' : $location[1]);  // strip out commas
      $stop = str_replace(',', '', empty($location[2]) ? '9999999999' : $location[2]);

      if ($start == '' || $stop == '')
        return (object) ['count' => $collection->count(), 'collection' => $collection,
                        'gene_count' => $gene_count, 'region_count' => $region_count];

      if (!is_numeric($start) || !is_numeric($stop))
        return (object) ['count' => $collection->count(), 'collection' => $collection,
                        'gene_count' => $gene_count, 'region_count' => $region_count];

      if ((int) $start >= (int) $stop)
        return (object) ['count' => $collection->count(), 'collection' => $collection,
                        'gene_count' => $gene_count, 'region_count' => $region_count];

      $regions = self::where('type', $type)
                        ->where('chr', $chr)
                        ->where('start', '<=', (int) $stop)
                        ->where('stop', '>=', (int) $start)->get();

      foreach ($regions as $region)
      {
        $region->relationship = ($region->start >= (int) $start && $region->stop <= (int) $stop ? 'Contained' : 'Overlap');
        if ($region->curation == 'ISCA Gene Curation')
        {
          $map = Iscamap::issue($region->issue)->first();
          if ($map !== null)
            $region->symbol = $map->symbol;
            //dd($region);
          $region->type = 0;  //gene

          // rats, we need the hgnc_id until jira supports it directly
          $g = Gene::name($region->symbol)->first();
          if ($g === null)
          {
              $g = Gene::previous($region->symbol)->first();
          }
          if ($g !== null)
          {
            $region->hgnc_id = $g->hgnc_id;
            $region->plof = $g->plof;
            $region->hi = $g->hi;
            $region->pli = $g->pli;
            $region->morbid = $g->morbid;
            $region->locus = $g->locus_group;
            $region->omim = $g->display_omim; // override jira with genenames for consistency
          }
          if ($g === null || $g->locus_type == 'pseudogene')
            $region->type = 3;

          $gene_count++;
        }
        else
        {
          $region->symbol = $region->name;
          $region->type = 1;    //region
          $region_count++;
          $region->plof = null;
            $region->hi = null;
            $region->pli = null;
        }

        // for 30 and 40, Jira also sends text
        if ($region->loss == 'N/A')
            $region->loss = "Not Yet Evaluated";
        if ($region->gain == 'N/A')
            $region->gain = "Not Yet Evaluated";
        if ($region->loss == 'Not yet evaluated')
            $region->loss = "Not Yet Evaluated";
        if ($region->gain == 'Not yet evaluated')
            $region->gain = "Not Yet Evaluated";
        if ($region->loss == "30: Gene associated with autosomal recessive phenotype")
              $region->loss = 30;
        else if ($region->loss == "40: Dosage sensitivity unlikely")
              $region->loss = 40;
        else if ($region->loss == "Not yet evaluated")
              $region->loss = -5;

        if ($region->gain == "30: Gene associated with autosomal recessive phenotype")
              $region->gain = 30;
        else if ($region->gain == "40: Dosage sensitivity unlikely")
              $region->gain = 40;
        else if ($region->gain == "Not yet evaluated")
              $region->gain = -5;

        if ($region->type == 3)
        {
          $region->loss = -1;
          $region->gain = -1;
        }

        $collection->push($region);

      }

      return (object) ['count' => $collection->count(), 'collection' => $collection,
                      'gene_count' => $gene_count, 'region_count' => $region_count];
    }


    /**
     * Preload the curation table with region data from the DCI
     *
     */
    public static function preload()
    {
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

      do {

        // get all the regions from jira
        $records = Jira::getIssues('project = ISCA AND issuetype = "ISCA Region Curation"', $start);

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

          switch ($issue->fields->status->name)
          {
            case 'Open':
              $status =  self::STATUS_OPEN;
              $curation_status = Curation::STATUS_OPEN;
              break;
            case 'Under Primary Review':
              $status = self::STATUS_UNDER_PRIMARY_REVIEW;
              $curation_status = Curation::STATUS_PRIMARY_REVIEW;
              break;
            case 'Under Secondary Review':
              $status = self::STATUS_UNDER_SECONDARY_REVIEW;
              $curation_status = Curation::STATUS_SECONDARY_REVIEW;
              break;
            case 'Under Group Review':
              $status = self::STATUS_UNDER_GROUP_REVIEW;
              $curation_status = Curation::STATUS_GROUP_REVIEW;
              break;
            case 'Closed':
              $status = self::STATUS_CLOSED;
              $curation_status = ($issue->fields->resolution->name == "Complete" ? Curation::STATUS_ACTIVE : Curation::STATUS_CLOSED);
              break;
            case 'Reopened':
              $status = self::STATUS_REOPENED;
              $curation_status = Curation::STATUS_REOPENED;
              break;
            default:
              dd($issue->fields->status->name);
          }

          echo "Updating " . $issue->key .  "\n";

          // we groom the status a bit
          /*if ($issue->fields->status->name == "Closed" && $issue->fields->resolution->name == "Complete")
              $status = self::STATUS_CLOSED;      // 'Complete';
          else if ($issue->fields->status->name == "Open")
              $status =  self::STATUS_OPEN;       // 'Awaiting Review';
          else
              $status = $issue->fields->status->name;      */ 

          // break out coordinates
          $coordinates = ['grch37' => self::location_split($record->customfield_10160 ?? null, $record->customfield_10158 ?? ''),
                          'grch38' => self::location_split($record->customfield_10532 ?? null, $record->customfield_10157 ?? '')
                        ];

          // build the most recent score history for fast reference
          $haplo_history = null;
          $triplo_history = null;

          $check = Jira::getHistory($issue->key);

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

          $node = [
                    'iri' => $issue->key,
                    'issue' => $issue->key,
                    'type' => self::TYPE_REGION_DOSAGE,
                    'type_substring' => 'Dosage Sensitivity Region',
                    'subtype' => self::SUBTYPE_REGION_DOSAGE_JIRA_PRELOAD,
                    'subtype_string' => 'Dosage Sensitivity Jira Preoad',
                    'name' => $record->customfield_10202 ?? null,
                    'description' => $issue->fields->description,
                    'cytoband' => $record->customfield_10145 ?? null,
                    'coordinates' => $coordinates,
                    'tags' => $issue->fields->labels,
                    // customfield_10195 minimum genome position grch37
                    'scores' => ['haploinsufficiency' => $record->customfield_10165->value ?? null,
                                'triplosensitivity' => $record->customfield_10166->value ?? null],
                    'events' => ['created' => $issue->fields->created,
                                'updated' => $issue->fields->updated,
                                'resolved' => $issue->fields->resolutiondate,
                                'resolution' => $issue->fields->resolution->name ?? null,
                                'haplo_score_change' => $haplo_history,
                                'triplo_score_change' => $triplo_history
                                ],
                    'metadata' => ['pli' => $record->customfield_11635 ?? null,
                                  'omim' => (isset($record->customfield_10147) ? basename($record->customfield_10147) : null),
                                  'dbVar' => $record->customfield_10141 ?? null,
                                  'breakpoint_type' => $record->customfield_12531 ?? null,
                                  'population_overlap' => $record->customfield_12533 ?? null,
                                  'should_be_targeted' => $record->customfield_10152->value ?? null,
                                  'targeting_decision_based_on' => $record->customfield_10169->value ?? null,
                                  'targeting_decision_comments' => $record->customfield_10196 ?? null,
                                  'loss_comments' => $issue->fields->customfield_10198 ?? null,
                                  'loss_pheno_omim' => $issue->fields->customfield_10200 ?? null,
                                  'loss_pheno_name' => $issue->fields->customfield_11830 ?? null,
                                  'loss_pheno_ontology' => $issue->fields->customfield_11630->value ?? null,
                                  'loss_pheno_ontology_id' => $issue->fields->customfield_11631 ?? null,
                                  'gain_comments' => $issue->fields->customfield_10199 ?? null,
                                  'gain_pheno_omim' => $issue->fields->customfield_10201 ?? null,
                                  'gain_pheno_name' => $issue->fields->customfield_11831 ?? null,
                                  'gain_pheno_ontology' => $issue->fields->customfield_11632->value ?? null,
                                  'gain_pheno_ontology_id' => $issue->fields->customfield_11633 ?? null,
                                  ],
                    'notes' => ['summary' => $issue->fields->summary,
                                'comment' => $issue->fields->comment
                              ],
                    'activity' => ($status == self::STATUS_CLOSED && $issue->fields->resolution->name == "Complete" ? $activity : $noactivity ),
                    'date_last_curated' => ($status == self::STATUS_CLOSED && $issue->fields->resolution->name == "Complete" ?
                                              $issue->fields->resolutiondate : null),
                    'status' => $status,
                    // some legacy fields we'll carry over for now
                    'omim' => $issue->fields->customfield_10147 ?? null,
                    'pli' => $issue->fields->customfield_11635 ?? null
                ];

          // we maintain history on a curation, not a region definition.  So just update the regions table
          $region = self::updateOrCreate(['iri' => $issue->key], $node);

          // if status has changed to closed, publish a curation
          if ($status != self::STATUS_OPEN && $status != self::STATUS_REOPENED)
          {
            // are there existing curations?
            $old_curations = Curation::where('source_uuid', $region->iri)
                             ->where('type', Curation::TYPE_DOSAGE_SENSITIVITY_REGION)
                            ->where('status', '!=', Curation::STATUS_ARCHIVE)
                            ->get();

            $haplo_disease = (empty($record->customfield_10200) ? null :
                                    Disease::curie($record->customfield_10200)->first());
            $triplo_disease = (empty($record->customfield_10201) ? null :
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
                'type' => Curation::TYPE_DOSAGE_SENSITIVITY_REGION,
                'type_string' => 'Dosage Sensitivity',
                'subtype' => Curation::SUBTYPE_DOSAGE_REGION_DCI,
                'subtype_string' => 'DCI JIRA QUERY',
                'group_id' => 0,
                'sop_version' => "1.0",
                'curation_version' => null,
                'source' => 'dci',
                'source_uuid' => $region->iri,
                'source_timestamp' => 0,
                'source_offset' => 0,
                'packet_id' => null,
                'message_version' =>  null,
                'assertion_uuid' => $region->iri,
                'alternate_uuid' => $region->ident,
                'panel_id' => $panel->id,
                'affiliate_id' => $panel->affiliate_id,
                'affiliate_details' => ['name' => 'Dosage Sensitivity Curation WG'],
                'gene_id' => null,
                'gene_hgnc_id' => null,
                'gene_details' => null,
                'variant_iri' => null,
                'variant_details' => null,
                'region_id' => $region->id,
                'region_details' => null,
                'document' => null,
                'context' => $assertion,
                'title' => $region->name,
                'summary' => null,
                'description' => $region->description,
                'comments' => null,
                'disease_id' => ($assertion == 'haploinsufficiency_assertion' ?  ($haplo_disease->id ?? null) : ($triplo_disease->id ?? null)),
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
                                               'Loss2' => ['evidence_id' => $record->customfield_10185 ?? null, 'evidence_type' => null,
                                                           'description' => $record->customfield_10186 ?? null],
                                               'Loss3' => ['evidence_id' => $record->customfield_10187 ?? null, 'evidence_type' => $record->customfield_12333 ?? null,
                                                           'description' => $record->customfield_10188 ?? null],
                                               'Loss4' => ['evidence_id' => $record->customfield_12231 ?? null, 'evidence_type' => $record->customfield_12334 ?? null,
                                                           'description' => $record->customfield_12237 ?? null],
                                               'Loss5' => ['evidence_id' => $record->customfield_12232 ?? null, 'evidence_type' => $record->customfield_12335 ?? null,
                                                           'description' => $record->customfield_12238 ?? null],
                                               'Loss6' => ['evidence_id' => $record->customfield_12233 ?? null, 'evidence_type' => $record->customfield_12336 ?? null,
                                                           'description' => $record->customfield_12239 ?? null]] : 
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
                                                           'description' => $record->customfield_12242 ?? null]]),
                'assertions' => ($assertion == 'haploinsufficiency_assertion' ?  $record->customfield_10165 ?? null : $record->customfield_10166 ?? null),
                'scores' => ['haploinsufficiency' => $record->customfield_10165 ?? null, 'triplosensitivity' => $record->customfield_10166 ?? null],
                'score_details' => [$issue->fields->labels],
                'curators' => $record->contributors ?? null,
                'published' => ($curation_status == Curation::STATUS_ACTIVE),
                'animal_model_only' => false,
                'events' => ['created' => $issue->fields->created,
                              'updated' => $issue->fields->updated,
                              'resolved' => $issue->fields->resolutiondate,
                              'resolution' => $issue->fields->resolution->name ?? null
                ],
                'url' => ['website_display' => "http://search.clinicalgenome.org/kb/gene-dosage/region/" . $issue->key],
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
      $last = Stream::name('dosage-region-jira')->first();
      $now = Carbon::now()->timestamp;
      //$record = self::query()->orderBy('updated_at', 'desc')->first();

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
        $records = Jira::getIssues('project = ISCA AND issuetype = "ISCA Region Curation" AND updated > "' 
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


          switch ($issue->fields->status->name)
          {
            case 'Open':
              $status =  self::STATUS_OPEN;
              $curation_status = Curation::STATUS_OPEN;
              break;
            case 'Under Primary Review':
              $status = self::STATUS_UNDER_PRIMARY_REVIEW;
              $curation_status = Curation::STATUS_PRIMARY_REVIEW;
              break;
            case 'Under Secondary Review':
              $status = self::STATUS_UNDER_SECONDARY_REVIEW;
              $curation_status = Curation::STATUS_SECONDARY_REVIEW;
              break;
            case 'Under Group Review':
              $status = self::STATUS_UNDER_GROUP_REVIEW;
              $curation_status = Curation::STATUS_GROUP_REVIEW;
              break;
            case 'Closed':
              $status = self::STATUS_CLOSED;
              $curation_status = ($issue->fields->resolution->name == "Complete" ? Curation::STATUS_ACTIVE : Curation::STATUS_CLOSED);
              break;
            case 'Reopened':
              $status = self::STATUS_REOPENED;
              $curation_status = Curation::STATUS_REOPENED;
              break;
            default:
              dd($issue->fields->status->name);
          }

          echo "Updating " . $issue->key .  "\n";
          
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

          $node = [
                    'iri' => $issue->key,
                    'type' => self::TYPE_REGION_DOSAGE,
                    'type_substring' => 'Dosage Sensitivity Region',
                    'subtype' => self::SUBTYPE_REGION_DOSAGE_JIRA_PRELOAD,
                    'subtype_string' => 'Dosage Sensitivity Jira Preoad',
                    'name' => $record->customfield_10202 ?? null,
                    'description' => $issue->fields->description,
                    'cytoband' => $record->customfield_10145 ?? null,
                    'coordinates' => $coordinates,
                    'tags' => $issue->fields->labels,
                    // customfield_10195 minimum genome position grch37
                    'scores' => ['haploinsufficiency' => $record->customfield_10165->value ?? null,
                                'triplosensitivity' => $record->customfield_10166->value ?? null],
                    'events' => ['created' => $issue->fields->created,
                                'updated' => $issue->fields->updated,
                                'resolved' => $issue->fields->resolutiondate,
                                'resolution' => $issue->fields->resolution->name ?? null,
                                'haplo_score_change' => $haplo_history,
                                'triplo_score_change' => $triplo_history
                                ],
                    'metadata' => ['pli' => $record->customfield_11635 ?? null,
                                  'omim' => (isset($record->customfield_10147) ? basename($record->customfield_10147) : null),
                                  'dbVar' => $record->customfield_10141 ?? null,
                                  'breakpoint_type' => $record->customfield_12531 ?? null,
                                  'population_overlap' => $record->customfield_12533 ?? null,
                                  'should_be_targeted' => $record->customfield_10152->value ?? null,
                                  'targeting_decision_based_on' => $record->customfield_10169->value ?? null,
                                  'targeting_decision_comments' => $record->customfield_10196 ?? null,
                                  'loss_comments' => $issue->fields->customfield_10198 ?? null,
                                  'loss_pheno_omim' => $issue->fields->customfield_10200 ?? null,
                                  'loss_pheno_name' => $issue->fields->customfield_11830 ?? null,
                                  'loss_pheno_ontology' => $issue->fields->customfield_11630->value ?? null,
                                  'loss_pheno_ontology_id' => $issue->fields->customfield_11631 ?? null,
                                  'gain_comments' => $issue->fields->customfield_10199 ?? null,
                                  'gain_pheno_omim' => $issue->fields->customfield_10201 ?? null,
                                  'gain_pheno_name' => $issue->fields->customfield_11831 ?? null,
                                  'gain_pheno_ontology' => $issue->fields->customfield_11632->value ?? null,
                                  'gain_pheno_ontology_id' => $issue->fields->customfield_11633 ?? null,
                                  ],
                    'notes' => ['summary' => $issue->fields->summary,
                                'comment' => $issue->fields->comment
                              ],
                    'activity' => ($status == self::STATUS_CLOSED && $issue->fields->resolution->name == "Complete" ? $activity : $noactivity ),
                    'date_last_curated' => ($status == self::STATUS_CLOSED && $issue->fields->resolution->name == "Complete" ?
                                              $issue->fields->resolutiondate : null),
                    'status' => $status,
                    // some legacy fields we'll carry over for now
                    'omim' => $issue->fields->customfield_10147 ?? null,
                    'pli' => $issue->fields->customfield_11635 ?? null
                ];

          $region = self::updateOrCreate(['iri' => $issue->key], $node);

          // if status has changed to closed, publish a curation
          if ($status == self::STATUS_CLOSED)
          {
            // are there existing curations?
            $old_curations = Curation::where('document', $region->iri)
                             ->where('type', Curation::TYPE_DOSAGE_SENSITIVITY_REGION)
                            ->where('status', '!=', Curation::STATUS_ARCHIVE)
                            ->get();

            $haplo_disease = (empty($record->customfield_10200) ? null :
                                    Disease::curie($record->customfield_10200)->first());
            $triplo_disease = (empty($record->customfield_10201) ? null :
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
                'type' => Curation::TYPE_DOSAGE_SENSITIVITY_REGION,
                'type_string' => 'Dosage Sensitivity',
                'subtype' => Curation::SUBTYPE_DOSAGE_REGION_DCI,
                'subtype_string' => 'DCI JIRA QUERY',
                'group_id' => 0,
                'sop_version' => "1.0",
                'curation_version' => null,
                'source' => 'dci',
                'source_uuid' => $region->iri,
                'source_timestamp' => 0,
                'source_offset' => 0,
                'packet_id' => null,
                'message_version' =>  null,
                'assertion_uuid' => $region->iri,
                'alternate_uuid' => $region->ident,
                'panel_id' => $panel->id,
                'affiliate_id' => $panel->affiliate_id,
                'affiliate_details' => ['name' => 'Dosage Sensitivity Curation WG'],
                'gene_id' => null,
                'gene_hgnc_id' => null,
                'gene_details' => null,
                'variant_iri' => null,
                'variant_details' => null,
                'region_id' => $region->id,
                'region_details' => null,
                // NEED SUPPORT FOR REGIONS!
                'document' => null,
                'context' => $assertion,
                'title' => $region->name,
                'summary' => null,
                'description' => $region->description,
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
                                               'Loss2' => ['evidence_id' => $record->customfield_10185 ?? null, 'evidence_type' => null,
                                                           'description' => $record->customfield_10186 ?? null],
                                               'Loss3' => ['evidence_id' => $record->customfield_10187 ?? null, 'evidence_type' => $record->customfield_12333 ?? null,
                                                           'description' => $record->customfield_10188 ?? null],
                                               'Loss4' => ['evidence_id' => $record->customfield_12231 ?? null, 'evidence_type' => $record->customfield_12334 ?? null,
                                                           'description' => $record->customfield_12237 ?? null],
                                               'Loss5' => ['evidence_id' => $record->customfield_12232 ?? null, 'evidence_type' => $record->customfield_12335 ?? null,
                                                           'description' => $record->customfield_12238 ?? null],
                                               'Loss6' => ['evidence_id' => $record->customfield_12233 ?? null, 'evidence_type' => $record->customfield_12336 ?? null,
                                                           'description' => $record->customfield_12239 ?? null]] : 
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
                                                           'description' => $record->customfield_12242 ?? null]]),
                'assertions' => ($assertion == 'haploinsufficiency_assertion' ?  $record->customfield_10165 ?? null : $record->customfield_10166 ?? null),
                'scores' => ['haploinsufficiency' => $record->customfield_10165 ?? null, 'triplosensitivity' => $record->customfield_10166 ?? null],
                'score_details' => [$issue->fields->labels],
                'curators' => $record->contributors ?? null,
                'published' => true,
                'animal_model_only' => false,
                'events' => ['created' => $issue->fields->created,
                              'updated' => $issue->fields->updated,
                              'resolved' => $issue->fields->resolutiondate,
                              'resolution' => $issue->fields->resolution->name ?? null
                ],
                'url' => ['website_display' => "http://search.clinicalgenome.org/kb/gene-dosage/region/" . $issue->key],
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
