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
use App\Slug;
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
class Validity extends Model
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
        'curie' => 'string',
        'report_date' => 'timestamp',
        'disease_label' => 'string',
        'disease_curie' => 'string',
        'gene_label' => 'string',
        'gene_hgnc_id' => 'string',
        'mode_of_inheritance' => 'string',
        'classification' => 'string',
        'specified_by' => 'string',
        'attributed_to' => 'string',
        'properties' => 'text',
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
    protected $fillable = [
        'ident',
        'curie',
        'report_date',
        'report_id',
        'disease_label',
        'disease_mondo',
        'gene_label',
        'gene_hgnc_id',
        'animal_model_only',
        'mode_of_inheritance',
        'classification',
        'properties',
        'specified_by',
        'attributed_to',
        'version',
        'type',
        'status',
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

    protected static $evidence_type_strings = [
        'null variant evidence line' => "Predicted or proven null variant type",
        'SEPIO:0000247' => "Candidate gene sequencing",
        'SEPIO:0004017' => "Predicted or proven null variant type",
        'SEPIO:0004018' => "Two variants in trans and at least one de novo or a predicted/proven null variant",
        'SEPIO:0004019' => "Two variants (not predicted/proven null) with some evidence of gene impact in trans",
        'SEPIO:0004020' => "Single variant analysis",
        'SEPIO:0004021' => "Aggregate variant analysis",
        'SEPIO:0004022' => "Bochemical Function",
        'SEPIO:0004023' => "Protein Interaction",
        'SEPIO:0004024' => "Expression",
        'SEPIO:0004025' => ["Functional Alteration", "Patient cells"],
        'SEPIO:0004026' => ["Functional Alteration", "Non-patient cells"],
        'SEPIO:0004027' => ["Model Systems", "Non-human model organism"],
        'SEPIO:0004028' => ["Model Systems", "Cell culture model"],
        'SEPIO:0004029' => ["Rescue", "Human"],
        'SEPIO:0004030' => ["Rescue", "Non-human model organism"],
        'SEPIO:0004031' => ["Rescue", "Cell culture model"],
        'SEPIO:0004032' => ["Rescue", " Patient cells"],
        'SEPIO:0004078' => "Variant is de novo", // changed 8/3 per Terry
        'SEPIO:0004079' => "Proband with predicted or proven null variant",
        'SEPIO:0004080' => "Proband with other variant type with some evidence of gene impact",
        'SEPIO:0004081' => "Variant is de novo",
        //  'variant functional impact evidence item' => "No translation",
        'SEPIO:0004119' => "Other variant type",
        //  'null variant evidence item' => "No translation",
        'SEPIO:0004118' => "Other Variant Type",
        'SEPIO:0004117' => "Predicted or proven null",
        //  'non-null variant evidence line' => "Other variant type",
        'SEPIO:0004119' => "Other variant type",
        'SEPIO:0004120' => "Predicted or proven null",
        'SEPIO:0004121' => "Other variant type",
        "SEPIO:0004180" => ["Biochemical Function", "A"],
        "SEPIO:0004181" => ["Biochemical Function", "B"],
        "SEPIO:0004182" => ["Protein interactions",  "genetic interaction (MI:0208)"],
        "SEPIO:0004183" => ["Protein interactions", "negative genetic interaction (MI:0933)"],
        "SEPIO:0004184" => ["Protein interactions", "physical association (MI:0915)"],
        "SEPIO:0004185" => ["Protein interactions", "positive genetic interaction (MI:0935)"],
        "SEPIO:0004188" => ["Expression", "A"],
        "SEPIO:0004189" => ["Expression", "B"],
        //'SEPIO:0004042' => "Other variant type",
    ];

    protected static $evidence_type_popup_strings = [
        'null variant evidence line' => "Predicted or proven null variant type",
        'SEPIO:0000247' => "",
        'SEPIO:0004017' => "",
        'SEPIO:0004018' => "",
        'SEPIO:0004019' => "",
        'SEPIO:0004020' => "",
        'SEPIO:0004021' => "",
        'SEPIO:0004022' => "The gene product performs a biochemical function shared with other known genes in the disease of interest (A), OR the gene product is consistent with the observed phenotype(s) (B)",
        'SEPIO:0004023' => "The gene product interacts with proteins previously implicated (genetically or biochemically) in the disease of interest",
        'SEPIO:0004024' => "The gene is expressed in tissues relevant to the disease of interest (A), OR the gene is altered in expression in patients who have the disease (B)",
        'SEPIO:0004025' => "The gene and/or gene product function is demonstrably altered in cultured patient or non-patient cells carrying candidate variant(s)",
        'SEPIO:0004026' => "The gene and/or gene product function is demonstrably altered in cultured patient or non-patient cells carrying candidate variant(s)",
        'SEPIO:0004027' => "Non-human model organism OR cell culture model with a similarly disrupted copy of the affected gene shows a phenotype consistent with human disease state",
        'SEPIO:0004028' => "Non-human model organism OR cell culture model with a similarly disrupted copy of the affected gene shows a phenotype consistent with human disease state",
        'SEPIO:0004029' => "The phenotype in humans, non-human model organisms, cell culture models, or patient cells can be rescued by exogenous wild-type gene or gene product",
        'SEPIO:0004030' => "The phenotype in humans, non-human model organisms, cell culture models, or patient cells can be rescued by exogenous wild-type gene or gene product",
        'SEPIO:0004031' => "The phenotype in humans, non-human model organisms, cell culture models, or patient cells can be rescued by exogenous wild-type gene or gene product",
        'SEPIO:0004032' => "The phenotype in humans, non-human model organisms, cell culture models, or patient cells can be rescued by exogenous wild-type gene or gene product",
        'SEPIO:0004078' => "",
        'SEPIO:0004079' => "",
        'SEPIO:0004080' => "",
        'SEPIO:0004119' => "",
        'SEPIO:0004118' => "",
        'SEPIO:0004117' => "",
        'SEPIO:0004119' => "",
        'SEPIO:0004120' => "",
        'SEPIO:0004121' => "",
        "SEPIO:0004180" => "The gene product performs a biochemical function shared with other known genes in the disease of interest (A), OR the gene product is consistent with the observed phenotype(s) (B)",
        "SEPIO:0004181" => "The gene product performs a biochemical function shared with other known genes in the disease of interest (A), OR the gene product is consistent with the observed phenotype(s) (B)",
        "SEPIO:0004182" => "The gene product interacts with proteins previously implicated (genetically or biochemically) in the disease of interest",
        "SEPIO:0004183" => "The gene product interacts with proteins previously implicated (genetically or biochemically) in the disease of interest",
        "SEPIO:0004184" => "The gene product interacts with proteins previously implicated (genetically or biochemically) in the disease of interest",
        "SEPIO:0004185" => "The gene product interacts with proteins previously implicated (genetically or biochemically) in the disease of interest",
        "SEPIO:0004188" => "The gene is expressed in tissues relevant to the disease of interest (A), OR the gene is altered in expression in patients who have the disease (B)",
        "SEPIO:0004189" => "The gene is expressed in tissues relevant to the disease of interest (A), OR the gene is altered in expression in patients who have the disease (B)",
    ];

    protected static $location_sop = [
        'ClinGen Gene Validity Evaluation Criteria SOP11' => 'https://clinicalgenome.org/docs/gene-disease-validity-standard-operating-procedures-version-11/',
        'ClinGen Gene Validity Evaluation Criteria SOP10' => 'https://clinicalgenome.org/docs/gene-disease-validity-standard-operating-procedure-version-10',
        'ClinGen Gene Validity Evaluation Criteria SOP9' => 'https://clinicalgenome.org/docs/gene-disease-validity-standard-operating-procedure-version-9',
        'ClinGen Gene Validity Evaluation Criteria SOP8' => 'https://www.clinicalgenome.org/docs/summary-of-updates-to-the-clingen-gene-clinical-validity-curation-sop-version-8',
        'ClinGen Gene Validity Evaluation Criteria SOP7' => 'https://clinicalgenome.org/docs/summary-of-updates-to-the-clingen-gene-clinical-validity-curation-sop-version-7',
        'ClinGen Gene Validity Evaluation Criteria SOP6' => 'https://clinicalgenome.org/docs/gene-disease-validity-standard-operating-procedures-version-6',
        'ClinGen Gene Validity Evaluation Criteria SOP5' => 'https://clinicalgenome.org/docs/gene-disease-validity-sop-version-5',
        'ClinGen Gene Validity Evaluation Criteria SOP4' => 'https://clinicalgenome.org/docs/gene-disease-validity-sop-version-4'
    ];

    protected static $zygosity_type_strings = [
        'GENO:0000402' => 'Biallelic compound heterozygous',
        'GENO:0000136' => 'Biallelic homozygous',
        'GENO:0000135' => 'Monoallelic heterozygous'
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
     * Query scope by hgnc id
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function scopeHgnc($query, $id)
    {
        return $query->where('gene_hgnc_id', $id);
    }


    public static function secondaryContributor($assertion)
    {
        if (empty($assertion->contributions))
            return '';

        $strings = [];

        foreach ($assertion->contributions as $contributor)
            if ($contributor->realizes->curie == "SEPIO:0004099")
                $strings[] = $contributor->agent->label;

        return empty($strings) ? 'NONE' : implode(', ', $strings);
    }


    /**
     * Query scope by animal model only
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function scopeAnimal($query)
    {
        return $query->where('animal_model_only', true);
    }


    /**
     * Retrieve, compare, and load a fresh dataset
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function assertions()
    {
        $assertions = GeneLib::validityList([
            'page' => 0,
            'pagesize' => "null",
            'sort' => 'GENE_LABEL',
            'search' => null,
            'direction' => 'ASC',
            'forcegg' => true,
            'properties' => true,
            'curated' => false
        ]);

        if (empty($assertions))
            die("Failure to retrieve new data");

        // clear out the status field

        // compare and update
        foreach ($assertions->collection as $assertion) {
            // check or update Slug table
            /* $s = Slug::firstOrCreate(['target' => $assertion->curie],
                                    [ 'type' => Slug::TYPE_CURATION,
                                      'subtype' => Slug::SUBTYPE_VALIDITY,
                                      'status' => Slug::STATUS_INITIALIZED
                                    ]); */

            //dd($assertion->disease->curie);
            $current = Validity::curie($assertion->curie)->orderBy('version', 'desc')->first();

            if ($current === null)          // new assertion
            {
                if (!isset($assertion->disease->label))
                    dd($assertion);
                $current = Validity::create([
                    'curie' => $assertion->curie,
                    'report_date' => Carbon::parse($assertion->report_date)->format('Y-m-d H:i:s.0000'),
                    'disease_label' => $assertion->disease->label,
                    'disease_mondo' => $assertion->disease->curie,
                    'gene_label' => $assertion->gene->label,
                    'gene_hgnc_id' => $assertion->gene->hgnc_id,
                    'mode_of_inheritance' => $assertion->mode_of_inheritance->label,
                    'classification' => $assertion->classification->label,
                    'specified_by' => $assertion->specified_by->label,
                    'attributed_to' => $assertion->attributed_to->label,
                    'properties' => $assertion->legacy_json,
                    'report_id' => $assertion->report_id,
                    'animal_model_only' => $assertion->animal_model_only,
                    'version' => 1,
                    'type' => 1,
                    'status' => 1
                ]);

                $gene = Gene::hgnc($current->gene_hgnc_id)->first();

                Change::create([
                    'type' => Change::TYPE_VALIDITY,
                    'category' => Change::CATEGORY_NONE,
                    'element_id' => $gene->id,
                    'element_type' => 'App\Gene',
                    'old_id' => null,
                    'old_type' => null,
                    'new_id' => $current->id,
                    'new_type' => 'App\Validity',
                    'change_date' => $current->report_date,
                    'description' => ['New curation activity'],
                    'status' => 1
                ]);

                continue;
            }

            $new = new Validity([
                'curie' => $assertion->curie,
                'report_date' => Carbon::parse($assertion->report_date)->format('Y-m-d H:i:s.0000'),
                'disease_label' => $assertion->disease->label,
                'disease_mondo' => $assertion->disease->curie,
                'gene_label' => $assertion->gene->label,
                'gene_hgnc_id' => $assertion->gene->hgnc_id,
                'mode_of_inheritance' => $assertion->mode_of_inheritance->label,
                'classification' => $assertion->classification->label,
                'specified_by' => $assertion->specified_by->label,
                'attributed_to' => $assertion->attributed_to->label,
                'properties' => $assertion->legacy_json,
                'report_id' => $assertion->report_id,
                'animal_model_only' => $assertion->animal_model_only,
                'version' => $current->version + 1,
                'type' => 1,
                'status' => 1
            ]);

            $differences = $this->compare($current, $new);


            if (!empty($differences))      // update
            {
                //dd($new);
                $new->save();

                $gene = Gene::hgnc($new->gene_hgnc_id)->first();

                // we'll use the current date for search, since there is no concept of a reissue date in GCI
                Change::create([
                    'type' => Change::TYPE_VALIDITY,
                    'category' => Change::CATEGORY_NONE,
                    'element_id' => $gene->id,
                    'element_type' => 'App\Gene',
                    'old_id' => $current->id,
                    'old_type' => 'App\Validity',
                    'new_id' => $new->id,
                    'new_type' => 'App\Validity',
                    'change_date' => Carbon::yesterday(),   // $new->report_date,
                    'status' => 1,
                    'description' => $this->scribe($differences)
                ]);
            } else {
                // even if they match, keep the properties updates
                $current->update(['properties' => $new->properties, 'animal_model_only' => $new->animal_model_only]);
            }
        }

        return $assertions;
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
        unset(
            $old_array['id'],
            $old_array['ident'],
            $old_array['version'],
            $old_array['type'],
            $old_array['status'],
            $old_array['created_at'],
            $old_array['updated_at'],
            $old_array['deleted_at'],
            $old_array['display_date'],
            $old_array['list_date'],
            $old_array['display_status'],
            $old_array['properties'],
            $old_array['report_id'],
            $old_array['animal_model_only']
        );
        unset(
            $new_array['id'],
            $new_array['ident'],
            $new_array['version'],
            $new_array['type'],
            $new_array['status'],
            $new_array['created_at'],
            $new_array['updated_at'],
            $new_array['deleted_at'],
            $new_array['display_date'],
            $new_array['report_id'],
            $new_array['animal_model_only'],
            $new_array['list_date'],
            $new_array['display_status'],
            $new_array['properties']
        );

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

        foreach ($content as $key => $value) {
            switch ($key) {
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
                case 'mode_of_inheritance':
                    $annot[] = 'MOI classification has changed';
                    break;
                case 'classification':
                    $annot[] = 'Validity classification has changed';
                    break;
                case 'specified_by':
                    $annot[] = 'Evaluation SOP has changed';
                    break;
                case 'attributed_to':
                    $annot[] = 'CGEP/WG attribution has changed';
                    break;
            }
        }

        return $annot;
    }


    /**
     * Determine if the passed validity assertion is Animal Model Only
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
    public static function isAnimalModelOnly($assertion)
    {
        $json = json_decode($assertion->legacy_json, false);

        $score_data = $json->scoreJson ?? $json;

        return (
            ($score_data->summary->FinalClassification == "No Known Disease Relationship") &&
            (isset($score_data->ExperimentalEvidence->Models->NonHumanModelOrganism->TotalPoints)) &&
            ($score_data->ExperimentalEvidence->Models->NonHumanModelOrganism->TotalPoints > 0) &&
            ($score_data->ValidContradictoryEvidence->Value == "NO")
        );
    }


    /**
     * Determine if the passed validity assertion has lumping and splitting content
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
    public static function hasLumpingContent($assertion)
    {
        if (
            empty($assertion->las_included) && empty($assertion->las_excluded)
            && empty($assertion->las_rationale['rationales'])
            && empty($assertion->las_rationale['pmids'])
            && empty($assertion->las_rationale['notes'])
            && empty($assertion->las_curation)
        )
            return false;

        return true;
    }


    /**
     * The validity topic queue is missing records, so preload from
     * genegraph as of offset 2225
     *
     */
    public static function preload()
    {
        $records = GeneLib::validityList([
            'page' => 0,
            'pagesize' => "null",
            'sort' => 'GENE_LABEL',
            'search' => null,
            'direction' => 'ASC',
            'properties' => true,
            'forcegg' => true,
            'curated' => false
        ]);

        // flag all the current curations in case any have been removed
        Curation::validity()->active()->update(['group_id' => 1]);

        foreach ($records->collection as $record) {
            $publish_date = null;

            // find published date
            foreach ($record->contributions as $contribution)
                if ($contribution->realizes->curie == "CG:PublisherRole" )
                    $publish_date = $contribution->date;

            $curation = Curation::validity()->active()->where('source_uuid', $record->curie)->first();
        
            //skip over duplicates
            /*if (isset($curation->events['publish_date']))
                $check = Curation::validity()->active()->where('source_uuid', $record->curie)->whereJsonContains('events->publish_date', $publish_date)->exists();
            else
                $check = Curation::validity()->active()->where('source_uuid', $record->curie)->exists();*/

            if ($curation !== null && isset($curation->events['publish_date']) && $curation->events['publish_date'] == $publish_date) 
            {
                // unset the remove flag
                Curation::validity()->active()->where('source_uuid', $record->curie)->update(['group_id' => 0]);

                continue;
            }

            echo "Updating " . $record->curie .  "\n";

            $gene = Gene::hgnc($record->gene->hgnc_id)->first();
            $disease = Disease::curie($record->disease->curie)->first();

            // strip off the genegraph prefix and look up the EP
            $pid = str_replace("CGAGENT:", '', $record->attributed_to->curie);
            $panel = Panel::allids($pid)->first();

            //now we strip the timestamp off the curie and check if its a GCEX load
            if (strpos($record->curie, 'CGGCIEX') === 0) {
                $curie_root = substr($record->curie, 8);
                $subtype = Curation::SUBTYPE_VALIDITY_GCE;
            } else {
                $curie_root = substr($record->curie, 15, 36);
                $subtype = Curation::SUBTYPE_VALIDITY_GGP;
            }

            // 'report_date' => Carbon::parse($assertion->report_date)->format('Y-m-d H:i:s.0000'),

            // tecnically, genegraph should not send us any older records, but it doesn't hurt to make sure
            $old_curations = Curation::validity()->where('document', $curie_root)
                ->where('status', '!=', Curation::STATUS_ARCHIVE)
                ->get();

            // gg's animal_model is only accurate on newer curations.  They still need to be calcurated for older ones
            if (!isset($record->animal_model) || $record->animal_model === null) {
                // we can find what we need from the legacy_json pack
                if (!empty($record->legacy_json)) {
                    $score = json_decode($record->legacy_json);
                    $animal_model_only = $score->scoreJson->summary->AnimalModelOnly ?? false;

                    if ($animal_model_only === false && isset($score->scoreJson))
                        $animal_model_only = (
                            ($score->scoreJson->summary->FinalClassification == "No Known Disease Relationship") &&
                            (isset($score->scoreJson->ExperimentalEvidence->Models->NonHumanModelOrganism->TotalPoints)) &&
                            ($score->scoreJson->ExperimentalEvidence->Models->NonHumanModelOrganism->TotalPoints > 0) &&
                            ($score->scoreJson->ValidContradictoryEvidence->Value == "NO")
                        );
                    else
                        $animal_model_only = ($animal_model_only == "YES");
                } else {
                    // check legacy list of animal mode only assertions
                    $amo = self::animal()->get(['curie']);

                    $animal_model_only = $amo->contains('curie', $record->curie);
                }
            } else {
                $animal_model_only = $record->animal_model;
            }

            // finally we can build the new curation
            $data = [
                'type' => Curation::TYPE_GENE_VALIDITY,
                'type_string' => 'Gene-Disease Validity',
                'subtype' => $subtype,
                'subtype_string' => 'Genegraph preload',
                'group_id' => 0,
                'sop_version' => $record->specified_by->label ?? null,
                'curation_version' => null,
                'source' => 'genegraph',
                'source_uuid' => $record->curie,
                'source_timestamp' => 0,
                'source_offset' => 0,
                'packet_id' => null,
                'message_version' =>  $record->legacy->jsonMessageVersion ?? null,
                'assertion_uuid' => $record->iri,
                'alternate_uuid' => $record->report_id ?? null,
                'panel_id' => $panel->id,
                'affiliate_id' => $panel->affiliate_id,
                'affiliate_details' => $record->attributed_to,
                'gene_id' => $gene->id,
                'gene_hgnc_id' => $record->gene->hgnc_id,
                'gene_details' => $record->gene,
                'variant_iri' => null,
                'variant_details' => null,
                'document' => $curie_root,
                'context' => null,
                'title' => null,
                'summary' => $record->description,
                'description' => null,
                'comments' => null,
                'disease_id' => $disease->id,
                'conditions' => [$record->disease->curie],
                'condition_details' => $record->disease,
                'evidence' => null,
                'evidence_details' => $record->legacy_json,
                'assertions' => $record->classification->label,
                'scores' => [
                    'classification' => $record->classification->label,
                    'moi' => $record->mode_of_inheritance->label,
                    'moi_hp' => $record->mode_of_inheritance->curie,
                ],
                'score_details' => $record->classification,
                'curators' => $record->contributions ?? [],
                'published' => true,
                'animal_model_only' => $animal_model_only,
                'events' => ['report_date' => $record->report_date, 'publish_date' => $publish_date],
                'url' => [],
                'version' => 1,
                'status' => Curation::STATUS_ACTIVE
            ];

            $curation = new Curation($data);

            $curation->save();

            // strip off the timestamp
            $curie = (strpos($record->curie, 'CGGV:assertion_') === 0 ? substr($record->curie, 0, 51)
                : $record->curie);

            // create or update the CCID
            $s = Slug::firstOrCreate(
                ['target' => $curie],
                [
                    'type' => Slug::TYPE_CURATION,
                    'subtype' => Slug::SUBTYPE_VALIDITY,
                    'status' => Slug::STATUS_INITIALIZED
                ]
            );

            // update the panel pivot table
            foreach ($data['curators'] as $contribution) {
                if ($contribution->realizes->label == 'approver role')                      // primary
                {
                    $pid = $panel->id;
                    $level = 1;
                } else if ($contribution->realizes->label == 'secondary contributor role')    // secondary
                {
                    // strip off the genegraph prefix and look up the EP
                    $pid = str_replace("CGAGENT:", '', $contribution->agent->curie);
                    $secondary_panel = Panel::allids($pid)->first();
                    if ($secondary_panel === null) {
                        echo "EP $pid not found \n";
                        continue;
                    }
                    $pid = $secondary_panel->id;
                    $level = 2;
                } else {
                    continue;
                }

                $curation->panels()->attach($pid, ['level' => $level]);
            }

            // archive aany old versions
            $old_curations->each(function ($item) {
                $item->panels()->detach();
                $item->update(['status' => Curation::STATUS_ARCHIVE]);
            });
        }

        // archive any unpublished items
        Curation::validity()->where('group_id', 1)->each(function ($item) {
            $item->panels()->detach();
            $item->update(['status' => Curation::STATUS_UNPUBLISH]);
        });
    }


    /**
     * Parse a ,essaage from the gene_disease_validity topic stream
     *
     */
    public static function parser($message, $packet = null)
    {

        $record = json_decode($message->payload);

        /* if ($record->sopVersion != "8" && $record->sopVersion != "6" && $record->sopVersion != "7")
            dd($record);*/

        // process unpublish requests
        if ($record->statusPublishFlag == "Unpublish") {
            if (!isset($record->iri))
                die("Cannot unplublish iri"); //echo "Unpublish request with no iri \n";
            else {
                $old_curations = Curation::validity()->where('document', $record->iri)
                    ->where('status', '!=', Curation::STATUS_ARCHIVE)
                    ->get();

                $old_curations->each(function ($item) {
                    $item->update(['status' => Curation::STATUS_ARCHIVE]);
                });
            }

            return;
        }

        if ($record->statusPublishFlag != "Publish")
            dd($record);

        // save old ones to later archive
        $old_curations = Curation::validity()->where('document', $record->iri)
            ->where('status', '!=', Curation::STATUS_ARCHIVE)
            ->get();

        $gene = Gene::hgnc($record->genes[0]->curie)->first();
        $disease = Disease::curie($record->conditions[0]->curie)->first();
        $panel = Panel::allids($record->affiliation->gcep_id)->first();

        // if no animal flag, make the calculation
        // finally we can build the new curation
        $data = [
            'type' => Curation::TYPE_GENE_VALIDITY,
            'type_string' => 'Gene-Disease Validity',
            'subtype' => Curation::SUBTYPE_VALIDITY_GCI,
            'subtype_string' => 'Gene-Disease Validity',
            'group_id' => 0,
            'sop_version' => $record->sopVersion,
            'curation_version' => $record->curationVersion,
            'source' => 'gene-validity',
            'source_uuid' => $message->key,
            'source_timestamp' => $message->timestamp,
            'source_offset' => $message->offset,
            'packet_id' => $packet->id ?? null,
            'message_version' =>  $record->jsonMessageVersion ?? null,
            'assertion_uuid' => $record->iri,
            'alternate_uuid' => $record->report_id ?? null,
            'panel_id' => $panel->id,
            'affiliate_id' => $panel->affiliate_id,
            'affiliate_details' => $record->affiliation,
            'gene_id' => $gene->id,
            'gene_hgnc_id' => $record->genes[0]->curie,
            'gene_details' => $record->genes,
            'variant_iri' => null,
            'variant_details' => null,
            'document' => $record->iri,
            'context' => $record->selectedSOPVersion ?? null,
            'title' => $record->title,
            'summary' => $record->scoreJson->summary->FinalClassificationNotes,
            'description' => null,
            'comments' => null,
            'disease_id' => $disease->id,
            'conditions' => [$record->conditions[0]->curie],
            'condition_details' => $record->conditions,
            'evidence' => $record->scoreJson->EarliestArticles ?? null,
            'evidence_details' => [
                'genetic_evidence' => $record->scoreJson->GeneticEvidence,
                'ExperimentalEvidence' => $record->scoreJson->ExperimentalEvidence,
                'valid_contradictory_evidence' => $record->scoreJson->ValidContradictoryEvidence
            ],
            'assertions' => null,
            'scores' => [
                'classification' => $record->scoreJson->summary,
                'moi' => $record->scoreJson->ModeOfInheritance,
                'moi_hp' => $record->scoreJson->ModeOfInheritance,
                'replication_over_time' => $record->scoreJson->ReplicationOverTime
            ],
            'score_details' => $record->scoreJson->summary,
            'curators' => $record->scoreJson->summary->contributors ?? null,
            'published' => ($record->statusPublishFlag == "Publish"),
            'animal_model_only' => (isset($record->scoreJson->summary->AnimalModelOnly) ? $record->scoreJson->summary->AnimalModelOnly == "YES" : false),
            'events' => [
                'report_date' => null,
                'statusFlag' => $record->statusFlag,
                'statusPublishFlag' => $record->statusPublishFlag
            ],
            'url' => [],
            'version' => 1,
            'status' => Curation::STATUS_ACTIVE
        ];

        $curation = new Curation($data);
        //dd($curation);
        // adjust the version number

        $curation->save();

        $old_curations->each(function ($item) {
            $item->update(['status' => Curation::STATUS_ARCHIVE]);
        });
    }


    /**
     * Map a gdv record to a model
     *
     */
    public static function parser2($data)
    {
        return;

        $record = $data->data;

        $current = self::gtid($record->id)->first();

        if ($current === null) {
            // the minimal required are id, uuid, gene, and group
            $current = new self([
                'type' => self::TYPE_GENE_TRACKER,
                'gtid' => $record->id,
                'gt_uuid' => $record->uuid,
                'hgnc_id' => $record->gene->hgnc_id,
                'group_id' => $record->group->affiliation_id ?? "",
                'group_detail' => (array) $record->group
            ]);
        }

        // update with optional fields
        $current->fill([
            'gdm_uuid' => $record->gdm_uuid ?? null,
            'mondo_id' => $record->disease_entity->mondo_id ?? null,
            'hp_id' => $record->mode_of_inheritance->hp_id ?? null,
            'group_id' => $record->group->affiliation_id ?? "",
            'group_detail' => (array) $record->group,
            'curator_detail' => (array) ($record->curator ?? null),
            'rationale' => (array) ($record->rationales ?? null),
            'curation_type' => (array) ($record->curation_type ?? null),
            'omim_phenotypes' => (array) ($record->omim_phenotypes ?? null),
            'notes' => $record->notes ?? null
        ]);

        switch ($data->event_type) {
            case 'created':
                $current->status = self::STATUS_CREATED;
                break;
            case 'updated':
                $current->status = self::STATUS_UPDATED;
                break;
            case 'deleted':
                $current->status = self::STATUS_DELETED;
                break;
        }

        // if event type is deleted, then no extra status detail will be present
        if ($current->status !== self::STATUS_DELETED) {
            switch ($record->status->name) {
                case 'Uploaded':
                    $current->date_uploaded = $record->status->effective_date;
                    break;
                case "Precuration":
                    $current->date_precuration = $record->status->effective_date;
                    break;
                case "Disease Entity Assigned":
                case "Disease entity assigned":
                    $current->date_disease_assigned = $record->status->effective_date;
                    break;
                case "Precuration Complete":
                    $current->date_precuration_complete = $record->status->effective_date;
                    break;
                case "Curation Provisional":
                    $current->date_curation_provisional = $record->status->effective_date;
                    break;
                case "Curation Approved":
                    $current->date_curation_approved = $record->status->effective_date;
                    break;
                case "Retired Assignment":
                    $current->date_retired = $record->status->effective_date;
                    break;
                case "Published":
                    $current->date_published = $record->status->effective_date;
                    break;
            }
        }

        $current->save();

        if ($current->status !== self::STATUS_DELETED) {

            // we want to copy the latest status into a gene column, but we can't garuntee order
            $gene = Gene::hgnc('HGNC:' . $current->hgnc_id)->first();

            if ($gene !== null) {
                $a = $gene->curation_status;
                if ($a === null) {
                    $gene->curation_status = [$record->id => [
                        'group' => $record->group->name,
                        'group_type' => $record->group->type->name ?? null,
                        'group_id' => $record->group->affiliation_id,
                        'status' => $record->status->name,
                        'status_date' => $record->status->effective_date
                    ]];

                    //dd($gene->curation_status);

                    $gene->save();
                } else {
                    if (!isset($a[$record->id]) || ((self::$curation_priority[$record->status->name] ?? 0) >= (self::$curation_priority[$a[$record->id]['status']] ?? 0))) {
                        $a[$record->id] = [
                            'group' => $record->group->name,
                            'group_type' => $record->group->type->name ?? null,
                            'group_id' => $record->group->affiliation_id,
                            'status' => $record->status->name,
                            'status_date' => $record->status->effective_date
                        ];

                        $gene->curation_status = $a;

                        //dd($gene->curation_status);

                        $gene->save();
                    }
                }
            }


            // we also want to keep the disease status updated
            if ($current->mondo_id !== null) {
                $disease = Disease::curie($current->mondo_id)->first();

                if ($disease !== null) {
                    $a = $disease->curation_status;
                    if ($a === null) {
                        $disease->curation_status = [$record->id => [
                            'group' => $record->group->name,
                            'group_type' => $record->group->type->name ?? null,
                            'group_id' => $record->group->affiliation_id,
                            'status' => $record->status->name,
                            'status_date' => $record->status->effective_date
                        ]];

                        //dd($gene->curation_status);

                        $disease->save();
                    } else {
                        if (!isset($a[$record->id]) || ((self::$curation_priority[$record->status->name] ?? 0) >= (self::$curation_priority[$a[$record->id]['status']] ?? 0))) {
                            $a[$record->id] = [
                                'group' => $record->group->name,
                                'group_type' => $record->group->type->name ?? null,
                                'group_id' => $record->group->affiliation_id,
                                'status' => $record->status->name,
                                'status_date' => $record->status->effective_date
                            ];

                            $disease->curation_status = $a;

                            //dd($gene->curation_status);

                            $disease->save();
                        }
                    }
                }
            }
        }

        // TODO:  resync the gene_panel table
        /*$precurations = self::all();

        foreach ($preccurations as $precuration)
        {
            // if published and not deleted and not retired...
        }*/

        // Check if the group exists, if not, add
        if (!empty($record->group->affiliation_id)) {
            $id = ($record->group->affiliation_id < 20000 ? $record->group->affiliation_id + 30000 :
                $record->group->affiliation_id);

            $panel = Panel::affiliate($id)->first();

            if ($panel === null) {
                $panel = new Panel([
                    'affiliate_id' => $id,
                    'alternate_id' => $record->group->affiliation_id,
                    'name' => $record->group->name,
                    'title' => $record->group->name,
                    'title_abbreviated' => $record->group->name,
                    'title_short' => $record->group->name,
                    'summary' => '',
                    'affiliate_type' => '',
                    'type' => Panel::TYPE_WG,
                    'status' => 1
                ]);
                $panel->save();
            }
        }
    }

    /**
     * Displayable evidence type string
     *
     */
    public static function evidenceTypeString($x)
    {
        //dd($x);
        $t = self::$evidence_type_strings[$x] ?? '';

        if (is_array($t))
            return "<strong>" . $t[0] . "</strong> " . $t[1];

        return $t;
    }


    /**
     * Displayable evidence type definition for popup
     *
     */
    public static function evidenceTypePopupString($x)
    {

        //return $x;

        return self::$evidence_type_popup_strings[$x] ?? '';
    }


    /**
     * Displayable evidence type definition for popup
     *
     */
    public static function zygosityTypeString($x)
    {

        //return $x;
        return self::$zygosity_type_strings[$x] ?? '';
    }


    /**
     * Displayable location url for the sop
     *
     */
    public static function locationSOP($x)
    {

        //return $x;

        return self::$location_sop[$x] ?? '';
    }


    /**
     * Displayable Allele Registy Link
     */
    public static function alleleUrlString($x)
    {
        if (strpos($x, "CLINVAR:variation/") === 0) {
            //return "https://reg.clinicalgenome.org/redmine/projects/registry/genboree_registry/alleles?ClinVar.variationId=" . basename($x);

            return "https://www.ncbi.nlm.nih.gov/clinvar/variation/" . basename($x);
        } else {
            return "https://reg.clinicalgenome.org/redmine/projects/registry/genboree_registry/by_canonicalid?canonicalid=" . basename($x);
        }
    }

    /**
     * Genegraph changes the timestamp portion of an assertion id on 1/14/2023, which has broken
     * anyone who saved links to a gene page.  Reported by Gloria 2/17/2023.  Since both versions
     * are now in the wild, reformat the bad one prior to ghe graphql call.
     */
    public static function fixid($id)
    {
        if (strpos($id, ':', 5) !== false) {
            // if there are two dashes separating out the time stamp, replace with one
            $id = str_replace('--', '-', $id);

            // now fix the timestamp
            $ts = substr($id, 51);
            $ts = str_replace(':', '', $ts);
            $id = substr($id, 0, 51);

            $id .= $ts . '.000Z';
        }

        return $id;
    }


    public static function hpsort($array)
    {
        uasort(
            $array,
            function ($a, $b) {
                return strnatcmp($a->label, $b->label);
            }
        );

        return $array;
    }
}
