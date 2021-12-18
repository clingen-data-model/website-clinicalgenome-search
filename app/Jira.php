<?php

namespace App;

use Jenssegers\Model\Model;

use JiraRestApi\Issue\IssueService;
use JiraRestApi\Issue\IssueField;
use JiraRestApi\User\UserService;
use JiraRestApi\JiraException;

use Carbon\Carbon;

use App\Gene;
use App\Iscamap;
use App\Minute;
use App\Omim;

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
class Jira extends Model
{
	/**
     * This class is designed to be used statically.  It is a non-persistant model
     * with no corresponding table in the database.
     */

     /**
     * The attributes that should be validity checked.
     *
     * @var array
     */
    public static $rules = [];

    /**
     * The attributes that are mass assignable.  Remember to fill it
     * in when all the attributes are known.
     *
     * @var array
     */
     protected $fillable = ['summary', 'issuetype', 'grch37', 'grch38',
                              'triplo_score', 'haplo_score', 'cytoband' ];

	/**
     * Non-persistent storage model attributes.
     *
     * @var array
     */
    protected $appends = [];

    private const groupname = 'DCI';

    public const FIELD_SUMMARY = 'summary';
    public const FIELD_ISSUETYPE = 'issuetype';
    public const FIELD_PMID1 = 'customfield_10189';
    public const FIELD_PMID1_DESCRIPTION = 'customfield_10190';
    public const FIELD_PMID2 = 'customfield_10191';
    public const FIELD_PMID2_DESCRIPTION = 'customfield_10192';
    public const FIELD_PMID3 = 'customfield_10193';
    public const FIELD_PMID3_DESCRIPTION = 'customfield_10194';
    public const FIELD_GRCH37_GENOME_POSITION = 'customfield_10160';
    public const FIELD_GRCH38_GENOME_POSITION = 'customfield_10532';
    public const FIELD_ISCA_TRIPLO_SCORE = 'customfield_10166';
    public const FIELD_ISCA_HAPLO_SCORE = 'customfield_10165';
    public const FIELD_CYTOBAND = 'customfield_10145';

    /* new PMID fields on production side
          HGNC ID:  customfield_12230
          Loss PMID 4:  customfield_12231
          Loss PMID 4 Description:  customfield_12237
          Loss PMID 5:  customfield_12232
          Loss PMID 5 Description:  customfield_12238
          Loss PMID 6:  customfield_12233
          Loss PMID 6 Description:  customfield_12239
          Gain PMID 4:  customfield_12234
          Gain PMID 4 Description:  customfield_12240
          Gain PMID 5:  customfield_12235
          Gain PMID 5 Description:  customfield_12241
          Gain PMID 6:  customfield_12236
          Gain PMID 6 Description:  customfield_12242
     */

     /**
     * Get details of a specific gene
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
     static function dosageDetail($args, $expanded = false, $page = 0, $pagesize = 20)
     {
		// break out the args
		foreach ($args as $key => $value)
               $$key = $value;

          // get the issue number
          $symbol = Gene::where('hgnc_id', $gene)->first();

          if ($symbol === null && !$expanded)
               return null;

        $response = null;

        if (!$expanded)
        {
            $issue = Iscamap::symbol($symbol->name)->first();

            if ($issue === null)
            {
                $results = self::getIssues('project = ISCA AND issuetype = "ISCA Gene Curation" AND "HGNC ID" ~ "' . $symbol->hgnc_id . '"');

                if (!isset($results->issues[0]))
                    return null;

                $response = $results->issues[0];
                $issue = $response->key;
                $response = $response->fields;
                //dd($response);
            }
            else
                $issue = $issue->issue;
        }
        else
            $issue = $gene;

        if ($response === null)
            $response = self::getIssue($issue);
//dd($response);
          // map the jira response into a somewhat sane structure
		$node = new Nodal([
               'label' => $response->customfield_10030 ?? 'unknown',
               'summary' => $response->summary,
               'key' => $issue,
               'links' => $response->issuelinks ?? null,
               'genesymbol' => $response->customfield_10030,
               'genetype' => $response->customfield_10156->value ?? 'unknown',
               'grch37' => $response->customfield_10160 ?? null,
               'grch38' => $response->customfield_10532 ?? null,
               'GRCh37_seqid' => $response->customfield_10158 ?? null,
               'GRCh38_seqid' => $response->customfield_10537 ?? null,
               'triplo_score' => $response->customfield_10166->value ?? 'unknown',
               'haplo_score' => $response->customfield_10165->value ?? 'unknown',
               'cytoband' => $response->customfield_10145 ?? null,
               'loss_comments' => $response->customfield_10198 ?? null,
               'loss_pheno_omim' => $response->customfield_10200 ?? null,
               'loss_pheno_name' => $response->customfield_11830 ?? null,
               'loss_pheno_ontology' => $response->customfield_11630->value ?? null,
               'loss_pheno_ontology_id' => $response->customfield_11631 ?? null,
               'gain_comments' => $response->customfield_10199 ?? null,
               'gain_pheno_omim' => $response->customfield_10201 ?? null,
               'gain_pheno_name' => $response->customfield_11831 ?? null,
               'gain_pheno_ontology' => $response->customfield_11632->value ?? null,
               'gain_pheno_ontology_id' => $response->customfield_11633 ?? null,
               'resolution' => $response->resolution->name ?? 'In Review',
               'issue_type' => $response->issuetype->name,
               'jira_status' => $response->status->name,
               'genereviews' => $response->customfield_10150 ?? null,
               'locusdb' => $response->customfield_10161 ?? null,
               'reduced_penetrance' => $response->customfield_12245 ?? null,
               'reduced_penetrance_comment' => $response->customfield_12246 ?? null
          ]);

          // create a custom status string based on legacy comparisons
          if ($node->jira_status == "Open")
          {
               $node->issue_status = "Awaiting Review";
          }
          else if ($node->jira_status == "Closed")
          {
               switch ($node->resolution)
               {
                    case "Won't Fix":
                         $node->issue_status = "Won't Fix";
                         break;
                    case 'Complete':
                         $node->issue_status = "Complete";
                         break;
                    default:
                         $node->issue_status = "Awaiting Review";
                         break;
               }
          }
          else
          {
               $node->issue_status = $node->jira_status;
          }

          // Hide some Not Yet evaluated items
          if (isset($node->reduced_penetrance->value) && $node->reduced_penetrance->value == "Not yet evaluated")
                $node->reduced_penetrance = null;

          // create the structures for pmid.  Jira will not send the fields if empty
          $pmids = [];
          if (isset($response->customfield_10183))
               $pmids[] = ['pmid' => $response->customfield_10183, 'desc' => $response->customfield_10184 ?? null];
          if (isset($response->customfield_10185))
               $pmids[] = ['pmid' => $response->customfield_10185, 'desc' => $response->customfield_10186  ?? null];
          if (isset($response->customfield_10187))
               $pmids[] = ['pmid' => $response->customfield_10187, 'desc' => $response->customfield_10188 ?? null];
          if (isset($response->customfield_12231))
               $pmids[] = ['pmid' => $response->customfield_12231, 'desc' => $response->customfield_12237 ?? null];
          if (isset($response->customfield_12232))
               $pmids[] = ['pmid' => $response->customfield_12232, 'desc' => $response->customfield_12238 ?? null];
          if (isset($response->customfield_12233))
               $pmids[] = ['pmid' => $response->customfield_12233, 'desc' => $response->customfield_12239 ?? null];
          $node->loss_pmids = $pmids;
          $pmids = [];
          if (isset($response->customfield_10189))
               $pmids[] = ['pmid' => $response->customfield_10189, 'desc' => $response->customfield_10190 ?? null];
          if (isset($response->customfield_10191))
               $pmids[] = ['pmid' => $response->customfield_10191, 'desc' => $response->customfield_10192 ?? null];
          if (isset($response->customfield_10193))
               $pmids[] = ['pmid' => $response->customfield_10193, 'desc' => $response->customfield_10194 ?? null];
          if (isset($response->customfield_12234))
               $pmids[] = ['pmid' => $response->customfield_12234, 'desc' => $response->customfield_12240 ?? null];
          if (isset($response->customfield_12235))
               $pmids[] = ['pmid' => $response->customfield_12235, 'desc' => $response->customfield_12241 ?? null];
          if (isset($response->customfield_12236))
               $pmids[] = ['pmid' => $response->customfield_12236, 'desc' => $response->customfield_12242 ?? null];
          $node->gain_pmids = $pmids;

          // for the omim fields, transform into structure and add title
          $omims = [];
          if (!empty($node->loss_pheno_omim))
          {
               foreach (explode(',', $node->loss_pheno_omim) as $item)
               {
                $cat = Disease::parseIdentifier($item);
                switch ($cat['type'])
                {
                    case Disease::TYPE_MONDO:
                        $omims[] = ['id' => $item, 'type' => $cat['type'], 'no_prefix' => $cat['adjusted'], 'titles' => Disease::titles($item)];
                        break;
                    case Disease::TYPE_OMIM:
                        $omims[] = ['id' => $item, 'type' => $cat['type'], 'no_prefix' => $cat['adjusted'], 'titles' => Omim::titles($item)];
                        break;
                    case Disease::TYPE_ORPHANET:
                        $omims[] = ['id' => $item, 'type' => $cat['type'], 'no_prefix' => $cat['adjusted'], 'titles' => ''];
                        break;
                    case Disease::TYPE_MEDGEN:
                        $omims[] = ['id' => $item, 'type' => $cat['type'], 'no_prefix' => $cat['adjusted'], 'titles' => ''];
                        break;
                    case Disease::TYPE_DOID:
                        $omims[] = ['id' => $item, 'type' => $cat['type'], 'no_prefix' => $cat['adjusted'], 'titles' => ''];
                        break;
                    default:
                        $omims[] = ['id' => $item, 'type' => $cat['type'], 'no_prefix' => $cat['adjusted'], 'titles' => ''];
                        break;
                }
               }
          }
          $node->loss_pheno_omim = $omims;

          $omims = [];
          if (!empty($node->gain_pheno_omim))
          {
               foreach (explode(',', $node->gain_pheno_omim) as $item)
               {
                    $cat = Disease::parseIdentifier($item);
                    switch ($cat['type'])
                    {
                        case Disease::TYPE_MONDO:
                            $omims[] = ['id' => $item, 'type' => $cat['type'], 'no_prefix' => $cat['adjusted'], 'titles' => Disease::titles($item)];
                            break;
                        case Disease::TYPE_OMIM:
                            $omims[] = ['id' => $item, 'type' => $cat['type'], 'no_prefix' => $cat['adjusted'], 'titles' => Omim::titles($item)];
                            break;
                        case Disease::TYPE_ORPHANET:
                            $omims[] = ['id' => $item, 'type' => $cat['type'], 'no_prefix' => $cat['adjusted'], 'titles' => ''];
                            break;
                        case Disease::TYPE_MEDGEN:
                            $omims[] = ['id' => $item, 'type' => $cat['type'], 'no_prefix' => $cat['adjusted'], 'titles' => ''];
                            break;
                        case Disease::TYPE_DOID:
                            $omims[] = ['id' => $item, 'type' => $cat['type'], 'no_prefix' => $cat['adjusted'], 'titles' => ''];
                            break;
                        default:
                            $omims[] = ['id' => $item, 'type' => $cat['type'], 'no_prefix' => $cat['adjusted'], 'titles' => ''];
                            break;
                    }
               }
          }
          $node->gain_pheno_omim = $omims;

          //dd($node);

          // for 30 and 40, Jira also sends text
          if ($node->triplo_score == "30: Gene associated with autosomal recessive phenotype")
               $node->triplo_score = 30;
          else if ($node->triplo_score == "40: Dosage sensitivity unlikely")
               $node->triplo_score = 40;
            else if ($node->triplo_score == "Not yet evaluated")
               $node->triplo_score = -5;

          if ($node->haplo_score == "30: Gene associated with autosomal recessive phenotype")
               $node->haplo_score = 30;
          else if ($node->haplo_score == "40: Dosage sensitivity unlikely")
               $node->haplo_score = 40;
          else if ($node->haplo_score == "Not yet evaluated")
               $node->haplo_score = -5;

          // condense the links to only inward issues
          if ($node->links !== null)
         {
             $t = [];

             foreach ($node->links as $link)
             {
                 if (isset($link->inwardIssue))
                 {
                   //$t[] = $link;
                   $a = self::getIssue($link->inwardIssue->key);
                   $t[] = (object) ['key' => $link->inwardIssue->key, 'label' => $a->customfield_10202 ?? $a->summary];
                 }
             }

             if (empty($t))
               $node->links = null;
             else
               $node->links = $t;

         }

	//dd($node);

		return $node;
     }


     /**
     * Get details of a specific gene
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function dosageRegionDetail($args, $page = 0, $pagesize = 20)
    {
         // break out the args
         foreach ($args as $key => $value)
              $$key = $value;

         $response = self::getIssue($gene);
         //dd($response);
         // map the jira response into a somewhat sane structure
         $node = new Nodal([
              'summary' => $response->summary,
              'key' => $gene,
              'links' => $response->issuelinks ?? null,
              'genetype' => $response->customfield_10156->value ?? 'unknown',
              'grch37' => $response->customfield_10160 ?? null,
              'grch38' => $response->customfield_10532 ?? null,
              'GRCh37_seqid' => $response->customfield_10158 ?? null,
              'GRCh38_seqid' => $response->customfield_10537 ?? null,
              'triplo_score' => $response->customfield_10166->value ?? 'unknown',
              'haplo_score' => $response->customfield_10165->value ?? 'unknown',
              'cytoband' => $response->customfield_10145 ?? null,
              'chromosome_band' => $response->customfield_10145 ?? null,
              'loss_comments' => $response->customfield_10198 ?? null,
              'loss_pheno_omim' => $response->customfield_10200 ?? null,
              'loss_pheno_name' => $response->customfield_11830 ?? null,
              'loss_pheno_ontology' => $response->customfield_11630->value ?? null,
              'loss_pheno_ontology_id' => $response->customfield_11631 ?? null,
              'gain_comments' => $response->customfield_10199 ?? null,
              'gain_pheno_omim' => $response->customfield_10201 ?? null,
              'gain_pheno_name' => $response->customfield_11831 ?? null,
              'gain_pheno_ontology' => $response->customfield_11632->value ?? null,
              'gain_pheno_ontology_id' => $response->customfield_11633 ?? null,
              'breakpoint' => $response->customfield_12531->value ?? null,
              'label' => $response->customfield_10202 ?? null,
              'allele' => $response->customfield_12530 ?? null,
              'knownhits' => $response->customfield_12343->value ?? null,
              'reduced_penetrance' => $response->customfield_12245 ?? null,
               'reduced_penetrance_comment' => $response->customfield_12246 ?? null,
              //'description' => $response->customfield_12030 ?? '',
              //'description' => str_replace(["\r\n", "\r", "\n"], "<br/>",
               //       $response->description ?? ''),
               'description' => $response->description ?? '',
              'resolution' => $response->resolution->name ?? 'In Review',
              'issue_type' => $response->issuetype->name,
              'issue_type' => $response->issuetype->name,
               'jira_status' => $response->status->name
         ]);
//dd($node);

          //  Hide ot yet evaluated from these items.
          if ($node->knownhits == "Not yet evaluated")
                $node->knownhits = null;

          if (isset($node->reduced_penetrance->value) && $node->reduced_penetrance->value == "Not yet evaluated")
                $node->reduced_penetrance = null;

          // create a custom status string based on legacy comparisons
          if ($node->jira_status == "Open")
          {
               $node->issue_status = "Awaiting Review";
          }
          else if ($node->jira_status == "Closed")
          {
               switch ($node->resolution)
               {
                    case "Won't Fix":
                         $node->issue_status = "Won't Fix";
                         break;
                    case 'Complete':
                         $node->issue_status = "Complete";
                         break;
                    default:
                         $node->issue_status = "Awaiting Review";
                         break;
               }
          }
          else
          {
               $node->issue_status = $node->jira_status;
          }

         $node->date = $node->displayDate($response->resolutiondate ?? '');

         // some of the region fields for G37 have commas in them, remove them
         $node->grch37 = str_replace(',', '', $node->grch37);

         // create the structures for pmid.  Jira will not send the fields if empty
         $pmids = [];
         if (isset($response->customfield_10183))
              $pmids[] = ['pmid' => $response->customfield_10183, 'desc' => $response->customfield_10184 ?? null];
         if (isset($response->customfield_10185))
              $pmids[] = ['pmid' => $response->customfield_10185, 'desc' => $response->customfield_10186 ?? null];
         if (isset($response->customfield_10187))
              $pmids[] = ['pmid' => $response->customfield_10187, 'desc' => $response->customfield_10188 ?? null];
          if (isset($response->customfield_12231))
              $pmids[] = ['pmid' => $response->customfield_12231, 'desc' => $response->customfield_12237 ?? null];
         if (isset($response->customfield_12232))
              $pmids[] = ['pmid' => $response->customfield_12232, 'desc' => $response->customfield_12238 ?? null];
         if (isset($response->customfield_12233))
              $pmids[] = ['pmid' => $response->customfield_12233, 'desc' => $response->customfield_12239 ?? null];
         $node->loss_pmids = $pmids;
         $pmids = [];
         if (isset($response->customfield_10189))
              $pmids[] = ['pmid' => $response->customfield_10189, 'desc' => $response->customfield_10190 ?? null];
         if (isset($response->customfield_10191))
              $pmids[] = ['pmid' => $response->customfield_10191, 'desc' => $response->customfield_10192 ?? null];
         if (isset($response->customfield_10193))
              $pmids[] = ['pmid' => $response->customfield_10193, 'desc' => $response->customfield_10194 ?? null];
          if (isset($response->customfield_12234))
              $pmids[] = ['pmid' => $response->customfield_12234, 'desc' => $response->customfield_12240 ?? null];
         if (isset($response->customfield_12235))
              $pmids[] = ['pmid' => $response->customfield_12235, 'desc' => $response->customfield_12241 ?? null];
         if (isset($response->customfield_12236))
              $pmids[] = ['pmid' => $response->customfield_12236, 'desc' => $response->customfield_12242 ?? null];
         $node->gain_pmids = $pmids;
//dd($node);
         // for the omim fields, transform into structure and add title
         $omims = [];
         if (!empty($node->loss_pheno_omim))
         {
              foreach (explode(',', $node->loss_pheno_omim) as $item)
              {
                $cat = Disease::parseIdentifier($item);
                switch ($cat['type'])
                {
                    case Disease::TYPE_MONDO:
                        $omims[] = ['id' => $item, 'type' => $cat['type'], 'no_prefix' => $cat['adjusted'], 'titles' => Disease::titles($item)];
                        break;
                    case Disease::TYPE_OMIM:
                        $omims[] = ['id' => $item, 'type' => $cat['type'], 'no_prefix' => $cat['adjusted'], 'titles' => Omim::titles($item)];
                        break;
                    case Disease::TYPE_ORPHANET:
                        $omims[] = ['id' => $item, 'type' => $cat['type'], 'no_prefix' => $cat['adjusted'], 'titles' => ''];
                        break;
                    case Disease::TYPE_MEDGEN:
                        $omims[] = ['id' => $item, 'type' => $cat['type'], 'no_prefix' => $cat['adjusted'], 'titles' => ''];
                        break;
                    case Disease::TYPE_DOID:
                        $omims[] = ['id' => $item, 'type' => $cat['type'], 'no_prefix' => $cat['adjusted'], 'titles' => ''];
                        break;
                    default:
                        $omims[] = ['id' => $item, 'type' => $cat['type'], 'no_prefix' => $cat['adjusted'], 'titles' => ''];
                        break;
                }
              }
         }
         $node->loss_pheno_omim = $omims;

         $omims = [];
         if (!empty($node->gain_pheno_omim))
         {
              foreach (explode(',', $node->gain_pheno_omim) as $item)
              {
                   $cat = Disease::parseIdentifier($item);
                    switch ($cat['type'])
                    {
                        case Disease::TYPE_MONDO:
                            $omims[] = ['id' => $item, 'type' => $cat['type'], 'no_prefix' => $cat['adjusted'], 'titles' => Disease::titles($item)];
                            break;
                        case Disease::TYPE_OMIM:
                            $omims[] = ['id' => $item, 'type' => $cat['type'], 'no_prefix' => $cat['adjusted'], 'titles' => Omim::titles($item)];
                            break;
                        case Disease::TYPE_ORPHANET:
                            $omims[] = ['id' => $item, 'type' => $cat['type'], 'no_prefix' => $cat['adjusted'], 'titles' => ''];
                            break;
                        case Disease::TYPE_MEDGEN:
                            $omims[] = ['id' => $item, 'type' => $cat['type'], 'no_prefix' => $cat['adjusted'], 'titles' => ''];
                            break;
                        case Disease::TYPE_DOID:
                            $omims[] = ['id' => $item, 'type' => $cat['type'], 'no_prefix' => $cat['adjusted'], 'titles' => ''];
                            break;
                        default:
                            $omims[] = ['id' => $item, 'type' => $cat['type'], 'no_prefix' => $cat['adjusted'], 'titles' => ''];
                            break;
                    }
              }
         }
         $node->gain_pheno_omim = $omims;

         // for 30 and 40, Jira also sends text
         if ($node->triplo_score == "30: Gene associated with autosomal recessive phenotype")
               $node->triplo_score = 30;
          else if ($node->triplo_score == "40: Dosage sensitivity unlikely")
               $node->triplo_score = 40;
            else if ($node->triplo_score == "Not yet evaluated")
               $node->triplo_score = -5;

          if ($node->haplo_score == "30: Gene associated with autosomal recessive phenotype")
               $node->haplo_score = 30;
          else if ($node->haplo_score == "40: Dosage sensitivity unlikely")
               $node->haplo_score = 40;
          else if ($node->haplo_score == "Not yet evaluated")
               $node->haplo_score = -5;

         // condense the links to only inward issues
         if ($node->links !== null)
         {
             $t = [];

             foreach ($node->links as $link)
             {
                 if (isset($link->inwardIssue))
                   $t[] = $link;
             }

             if (empty($t))
               $node->links = null;
             else
               $node->links = $t;

         }

    //dd($node);

         return $node;
    }


    /**
     * Format the description field
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function formatDescription($description)
    {
        $lines = [];

        $desc = explode("\n", $description);

        foreach ($desc as $line)
        {
            if (strpos($line, '( INTERNAL CLINGEN  REFERENCE=') === 0)
                continue;

            $stat = preg_match("@(?i)\b((?:https?://|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:'\".,<>?«»\“\”‘’]))@", $line, $matches);

            if ($stat !== 0)
                $line = str_replace($matches[0], '<a href="' . $matches[0] . '">' . $matches[0] . '</a>',$line);

            $lines[] = $line;
        }

        return implode("\n", $lines);

    }


     /**
     * Get a list of recurrent CNVs
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function cnvList($args, $page = 0, $pagesize = 20)
    {
         // break out the args
         foreach ($args as $key => $value)
              $$key = $value;

          $collection = collect();

          // filter 14035 is erica's recurrent cnv search string
          $response = self::getIssues('filter=14035');

          if (empty($response))
               return $collection;
//dd($response->issues);
          foreach ($response->issues as $issue)
          {
               // map the jira response into a somewhat sane structure
               $node = new Nodal([
                    'key' => $issue->key,
                    'summary' => $issue->fields->summary,
                    'grch37' => trim($issue->fields->customfield_10160),
                    'triplo_score' => $issue->fields->customfield_10166->value ?? 'unknown',
                    'haplo_score' => $issue->fields->customfield_10165->value ?? 'unknown',
                    'jira_report_date' => $issue->fields->resolutiondate ?? ''
               ]);

               // for 30 and 40, Jira also sends text
               if ($node->triplo_score == "30: Gene associated with autosomal recessive phenotype")
               $node->triplo_score = 30;
          else if ($node->triplo_score == "40: Dosage sensitivity unlikely")
               $node->triplo_score = 40;
            else if ($node->triplo_score == "Not yet evaluated")
               $node->triplo_score = -5;

          if ($node->haplo_score == "30: Gene associated with autosomal recessive phenotype")
               $node->haplo_score = 30;
          else if ($node->haplo_score == "40: Dosage sensitivity unlikely")
               $node->haplo_score = 40;
          else if ($node->haplo_score == "Not yet evaluated")
               $node->haplo_score = -5;

               $collection->push($node);
          }

          $nhaplo = $collection->where('haplo_score', '>', 0)->count();
          $ntriplo = $collection->where('triplo_score', '>', 0)->count();

          return (object) ['count' => $response->total, 'collection' => $collection,
               'nhaplo' => $nhaplo, 'ntriplo' => $ntriplo];
    }


    /**
     * Get a list of all recent ratings changes
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function ratingsList($args, $page = 0, $pagesize = 20)
    {
        // break out the args
        foreach ($args as $key => $value)
             $$key = $value;

         $collection = collect();

         $response = self::getIssues('project = ISCA AND issuetype in ("ISCA Gene Curation", "ISCA Region Curation") AND labels in ("RatingChange")');

         if (empty($response))
              return $collection;

          foreach ($response->getIssues() as $issue)
          {
               $changelog = self::getIssue($issue->key, 'changelog');

               foreach ($changelog->histories as $history)
               {
                    foreach ($history->items as $item)
                    {
                         if ($item->field == 'ISCA Triplosensitivity score')
                         {
                              if ($item->fromString !== null
                                   && trim($item->fromString) != 'Not yet evaluated'
                                   && $item->toString !== null)
                              {
                                   $created = new Carbon($history->created);
                                   if (Carbon::now()->diffInWeeks($created) <= 52)
                                   {
                                        if ($issue->fields->issuetype->name == "ISCA Gene Curation")
                                             $title = $issue->fields->customfield_10030;
                                        else if ($issue->fields->issuetype->name == "ISCA Region Curation")
                                             $title = $issue->fields->customfield_10202;
                                        else
                                             $title = 'Unknown Issue Type';

                                        $node = new Nodal([
                                             'key' => $issue->key,
                                             'title' => $title,
                                             'type' => $issue->fields->issuetype->name,
                                             'what' => 'Triplosensitivity Score',
                                             'when' => $created->format('m/d/Y'),
                                             'from' => $item->fromString,
                                             'to' => $item->toString,
                                             'age' => Carbon::now()->diffInWeeks($created)
                                        ]);

                                        // for 30 and 40, Jira also sends text
                                        if ($node->from == "30: Gene associated with autosomal recessive phenotype")
                                             $node->from = 30;
                                         else if ($node->from == "40: Dosage sensitivity unlikely")
                                             $node->from = 40;
                                        else if ($node->from == "Not yet evaluated")
                                             $node->from = -5;

                                         if ($node->to == "30: Gene associated with autosomal recessive phenotype")
                                             $node->to = 30;
                                        else if ($node->to == "40: Dosage sensitivity unlikely")
                                             $node->to = 40;
                                        else if ($node->to == "Not yet evaluated")
                                            $node->to = -5;

                                        $collection->push($node);
                                   }
                              }
                         }
                         else if ($item->field == 'ISCA Haploinsufficiency score')
                         {
                              if ($item->fromString !== null
                                   && trim($item->fromString) != 'Not yet evaluated'
                                   && $item->toString !== null)
                              {
                                   $created = new Carbon($history->created);
                                   if (Carbon::now()->diffInWeeks($created) <= 52)
                                   {
                                        if ($issue->fields->issuetype->name == "ISCA Gene Curation")
                                             $title = $issue->fields->customfield_10030;
                                        else if ($issue->fields->issuetype->name == "ISCA Region Curation")
                                             $title = $issue->fields->customfield_10202;
                                        else
                                             $title = 'Unknown Issue Type';

                                        $node = new Nodal([
                                             'key' => $issue->key,
                                             'title' => $title,
                                             'type' => $issue->fields->issuetype->name,
                                             'what' => 'Haploinsufficiency Score',
                                             'when' => $created->format('m/d/Y'),
                                             'from' => $item->fromString,
                                             'to' => $item->toString,
                                             'age' => Carbon::now()->diffInWeeks($created)
                                        ]);
                                        // for 30 and 40, Jira also sends text
               if ($node->from == "30: Gene associated with autosomal recessive phenotype")
                    $node->from = 30;
                else if ($node->from == "40: Dosage sensitivity unlikely")
                    $node->from = 40;
               else if ($node->from == "Not yet evaluated")
                                        $node->from = -5;

          if ($node->to == "30: Gene associated with autosomal recessive phenotype")
               $node->to = 30;
          else if ($node->to == "40: Dosage sensitivity unlikely")
               $node->to = 40;
        else if ($node->to == "Not yet evaluated")
            $node->to = -5;
                                        $collection->push($node);
                                   }
                              }
                         }
                    }
               }
          }

         return $collection;
     }


     /**
     * Get a list of ACMG 59 genes
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
     static function acmg59List($args, $page = 0, $pagesize = 20)
     {
         // break out the args
         foreach ($args as $key => $value)
              $$key = $value;

          $collection = collect();

          $response = self::getIssues('project = ISCA AND issuetype in ("ISCA Gene Curation") AND labels in ("ACMGSFv2.0") ');

          if (empty($response))
               return $collection;

          foreach ($response->issues as $issue)
          {
               //dd($issue);
               // map the jira response into a somewhat sane structure
               $node = new Nodal([
                    'key' => $issue->key,
                    'label' => $issue->fields->customfield_10030 ?? null,
                    'omim' => $issue->fields->customfield_10147 ?? null,
                    'triplo_score' => $issue->fields->customfield_10166->value ?? 'unknown',
                    'haplo_score' => $issue->fields->customfield_10165->value ?? 'unknown'
               ]);

               // for 30 and 40, Jira also sends text
               if ($node->triplo_score == "30: Gene associated with autosomal recessive phenotype")
               $node->triplo_score = 30;
          else if ($node->triplo_score == "40: Dosage sensitivity unlikely")
               $node->triplo_score = 40;
            else if ($node->triplo_score == "Not yet evaluated")
               $node->triplo_score = -5;

          if ($node->haplo_score == "30: Gene associated with autosomal recessive phenotype")
               $node->haplo_score = 30;
          else if ($node->haplo_score == "40: Dosage sensitivity unlikely")
               $node->haplo_score = 40;
          else if ($node->haplo_score == "Not yet evaluated")
               $node->haplo_score = -5;

               $collection->push($node);
          }

          $nhaplo = $collection->where('haplo_score', '>', 0)->count();
          $ntriplo = $collection->where('triplo_score', '>', 0)->count();

          return (object) ['count' => $response->total, 'collection' => $collection,
               'nhaplo' => $nhaplo, 'ntriplo' => $ntriplo];
    }


    /**
     * Get a list of regions
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function regionList($args, $page = 0, $pagesize = 20)
    {
         // break out the args
         foreach ($args as $key => $value)
              $$key = $value;

          $collection = Dosage::type(1)->get();

          /** the old direct method.  Want to save this for later
          // filter 10632 is the region selection filter
          //$response = self::getIssues('filter=10632');
          $response = self::getIssues('project = ISCA AND issuetype in ("ISCA Region Curation") AND Resolution = Complete');

          if (empty($response))
               return $collection;
;
          foreach ($response->issues as $issue)
          {
               // map the jira response into a somewhat sane structure
               $node = new Nodal([
                    'hgnc_id' => $issue->key,
                    'type' => 1,
                    'label' => $issue->fields->customfield_10202,
                    'cytoband' => $issue->fields->customfield_10145 ?? null,
                    'omimlink' => $issue->fields->customfield_10147 ?? null,
                    'GRCh37_position' => $issue->fields->customfield_10160 ?? null,
                    'GRCh38_position' => $issue->fields->customfield_10532 ?? null,
                    'pli' => $issue->fields->customfield_11635 ?? null,
                    'hi' => null,
                    'triplo_assertion' => $issue->fields->customfield_10166->value ?? 'unknown',
                    'haplo_assertion' => $issue->fields->customfield_10165->value ?? 'unknown',
                    'resolved_date' => $issue->fields->resolutiondate ?? ''
               ]);

               // some of the region fields for G37 have commas in them, remove them
               $node->GRCh37_position = str_replace(',', '', $node->GRCh37_position);

               // for 30 and 40, Jira also sends text
               if ($node->triplo_assertion == "30: Gene associated with autosomal recessive phenotype")
                    $node->triplo_assertion = 30;
               else if ($node->triplo_assertion == "40: Dosage sensitivity unlikely")
                    $node->triplo_assertion = 40;

               if ($node->haplo_assertion == "30: Gene associated with autosomal recessive phenotype")
                    $node->haplo_assertion = 30;
               else if ($node->haplo_assertion == "40: Dosage sensitivity unlikely")
                    $node->haplo_assertion = 40;

               $check = Region::issue($issue->key)->first();
               if ($check !== null && $check->history !== null)
               {
                    //dd($gene->history);
                    foreach ($check->history as $item)
                    {
                         //dd($item["what"]);
                         if ($item['what'] == 'Triplosensitivity Score')
                              $node->triplo_history = $item['what'] . ' changed from ' . $item['from']
                                                            . ' to ' . $item['to'] . ' on ' . $item['when'];
                         else if ($item['what'] == 'Haploinsufficiency Score')
                              $node->haplo_history = $item['what'] . ' changed from ' . $item['from']
                                                            . ' to ' . $item['to'] . ' on ' . $item['when'];
                    }
               }

               $collection->push($node);
          }

          $nhaplo = $collection->where('haplo_assertion', '>', 0)->count();
          $ntriplo = $collection->where('triplo_assertion', '>', 0)->count();
          */

          $nhaplo = $collection->where('haplo', '!=', 'unknown')->count();
          $ntriplo = $collection->where('triplo', '!=', 'unknown')->count();

          return (object) ['count' => $collection->count(), 'collection' => $collection,
               'nhaplo' => $nhaplo, 'ntriplo' => $ntriplo, 'ncurations' => $nhaplo + $ntriplo];
    }


    /**
     * Get a list of regions
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function regionLoad($args, $page = 0, $pagesize = 20)
    {
         // break out the args
         foreach ($args as $key => $value)
              $$key = $value;

          $collection = collect();

          // filter 10632 is the region selection filter
          //$response = self::getIssues('filter=10632');
          $response = self::getIssues('project = ISCA AND issuetype in ("ISCA Region Curation") AND Resolution = Complete');

          if (empty($response))
               return $collection;
;
          foreach ($response->issues as $issue)
          {
               //dd($issue);
               // map the jira response into a somewhat sane structure
               $node = new Nodal([
                    'issue' => $issue->key,
                    'type' => 1,
                    'label' => $issue->fields->customfield_10202 ?? '',
                    'curation' => $issue->fields->issuetype->name ?? '',
                    'description' => $issue->fields->description ?? null,
                    'cytoband' => $issue->fields->customfield_10145 ?? null,
                    'omim' => $issue->fields->customfield_10147 ?? null,
                    'grch37' => $issue->fields->customfield_10160 ?? null,
                    'grch38' => $issue->fields->customfield_10532 ?? null,
                    'pli' => $issue->fields->customfield_11635 ?? null,
                    'hi' => null,
                    'triplo' => $issue->fields->customfield_10166->value ?? 'unknown',
                    'haplo' => $issue->fields->customfield_10165->value ?? 'unknown',
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
                    'workflow' => $issue->fields->resolution->name ?? '',
                    'resolved' => $issue->fields->resolutiondate ?? ''
               ]);

               // some of the region fields for G37 have commas in them, remove them
               $node->grch37 = str_replace(',', '', $node->grch37);
               $node->grch38 = str_replace(',', '', $node->grch38);

               if(empty($node->pli))
                    $node->pli = null;

               //break out the location to distinct parts
               list($node->chr, $node->start, $node->stop) = self::regionMap($node->grch37);
               list($temp, $node->start38, $node->stop38) = self::regionMap($node->grch38);

               // for the omim fields, transform into structure and add title
               $omims = [];
               if (!empty($node->loss_pheno_omim))
               {
                    foreach (explode(',', $node->loss_pheno_omim) as $item)
                    {
                        $cat = Disease::parseIdentifier($item);
                        switch ($cat['type'])
                        {
                            case Disease::TYPE_MONDO:
                                $omims[] = ['id' => $item, 'type' => $cat['type'], 'no_prefix' => $cat['adjusted'], 'titles' => Disease::titles($item)];
                                break;
                            case Disease::TYPE_OMIM:
                                $omims[] = ['id' => $item, 'type' => $cat['type'], 'no_prefix' => $cat['adjusted'], 'titles' => Omim::titles($item)];
                                break;
                            case Disease::TYPE_ORPHANET:
                                $omims[] = ['id' => $item, 'type' => $cat['type'], 'no_prefix' => $cat['adjusted'], 'titles' => ''];
                                break;
                            case Disease::TYPE_MEDGEN:
                                $omims[] = ['id' => $item, 'type' => $cat['type'], 'no_prefix' => $cat['adjusted'], 'titles' => ''];
                                break;
                            case Disease::TYPE_DOID:
                                $omims[] = ['id' => $item, 'type' => $cat['type'], 'no_prefix' => $cat['adjusted'], 'titles' => ''];
                                break;
                            default:
                                $omims[] = ['id' => $item, 'type' => $cat['type'], 'no_prefix' => $cat['adjusted'], 'titles' => ''];
                                break;
                        }
                    }
               }
               $node->loss_pheno_omim = $omims;

               $omims = [];
               if (!empty($node->gain_pheno_omim))
               {
                    foreach (explode(',', $node->gain_pheno_omim) as $item)
                    {
                        $cat = Disease::parseIdentifier($item);
                        switch ($cat['type'])
                        {
                            case Disease::TYPE_MONDO:
                                $omims[] = ['id' => $item, 'type' => $cat['type'], 'no_prefix' => $cat['adjusted'], 'titles' => Disease::titles($item)];
                                break;
                            case Disease::TYPE_OMIM:
                                $omims[] = ['id' => $item, 'type' => $cat['type'], 'no_prefix' => $cat['adjusted'], 'titles' => Omim::titles($item)];
                                break;
                            case Disease::TYPE_ORPHANET:
                                $omims[] = ['id' => $item, 'type' => $cat['type'], 'no_prefix' => $cat['adjusted'], 'titles' => ''];
                                break;
                            case Disease::TYPE_MEDGEN:
                                $omims[] = ['id' => $item, 'type' => $cat['type'], 'no_prefix' => $cat['adjusted'], 'titles' => ''];
                                break;
                            case Disease::TYPE_DOID:
                                $omims[] = ['id' => $item, 'type' => $cat['type'], 'no_prefix' => $cat['adjusted'], 'titles' => ''];
                                break;
                            default:
                                $omims[] = ['id' => $item, 'type' => $cat['type'], 'no_prefix' => $cat['adjusted'], 'titles' => ''];
                                break;
                        }
                    }
               }
               $node->gain_pheno_omim = $omims;

               // for 30 and 40, Jira also sends text
                if ($node->triplo == "30: Gene associated with autosomal recessive phenotype")
                    $node->triplo = 30;
               else if ($node->triplo == "40: Dosage sensitivity unlikely")
                    $node->triplo = 40;
                 else if ($node->triplo == "Not yet evaluated")
                    $node->triplo = -5;

               if ($node->haplo == "30: Gene associated with autosomal recessive phenotype")
                    $node->haplo = 30;
               else if ($node->haplo == "40: Dosage sensitivity unlikely")
                    $node->haplo = 40;
               else if ($node->haplo == "Not yet evaluated")
                    $node->haplo = -5;

               $node->haplo_history = null;
               $node->triplo_history = null;

               $check = self::getHistory($issue);

               if ($check->isNotEmpty())
               {
                    foreach ($check as $item)
                    {
                         if ($item->what == 'Triplosensitivity Score')
                              $node->triplo_history = $item->what . ' changed from ' . $item->from
                                                            . ' to ' . $item->to . ' on ' . $item->when;
                         else if ($item->what == 'Haploinsufficiency Score')
                              $node->haplo_history = $item->what . ' changed from ' . $item->from
                                                            . ' to ' . $item->to . ' on ' . $item->when;
                    }
               }

               $collection->push($node);
          }

          $nhaplo = $collection->where('haplo_assertion', '>', 0)->count();
          $ntriplo = $collection->where('triplo_assertion', '>', 0)->count();

          return (object) ['count' => $response->total, 'collection' => $collection,
               'nhaplo' => $nhaplo, 'ntriplo' => $ntriplo];
    }


    /**
     * Get and format the history of an issue
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function getHistory($issue)
    {
         $collection = collect();

          $changelog = self::getIssue($issue->key, 'changelog');

          $first = true;

          foreach ($changelog->histories as $history)
          {
               foreach ($history->items as $item)
               {
                    if ($item->field == 'resolution' && $item->from === null && $item->toString == "Complete")
                         $first = false;

                    if ($first)
                         continue;

                    if ($item->field == 'ISCA Triplosensitivity score')
                    {
                         if ($item->fromString !== null
                              && trim($item->fromString) != 'Not yet evaluated'
                              && $item->toString !== null)
                         {
                              $created = new Carbon($history->created);
                              if (Carbon::now()->diffInWeeks($created) <= 52)
                              {
                                   //dd($item);
                                   if ($issue->fields->issuetype->name == "ISCA Gene Curation")
                                        $title = $issue->fields->customfield_10030;
                                   else if ($issue->fields->issuetype->name == "ISCA Region Curation")
                                        $title = $issue->fields->customfield_10202;
                                   else
                                        $title = 'Unknown Issue Type';

                                   $node = new Nodal([
                                        'key' => $issue->key,
                                        'title' => $title,
                                        'type' => $issue->fields->issuetype->name,
                                        'what' => 'Triplosensitivity Score',
                                        'when' => $created->format('m/d/Y'),
                                        'from' => $item->fromString,
                                        'to' => $item->toString,
                                        'age' => Carbon::now()->diffInWeeks($created)
                                   ]);

                                   // for 30 and 40, Jira also sends text
                                   if ($node->from == "30: Gene associated with autosomal recessive phenotype")
                                        $node->from = 30;
                                    else if ($node->from == "40: Dosage sensitivity unlikely")
                                        $node->from = 40;
                                    else if ($node->from == "Not yet evaluated")
                                        $node->from = -5;

                                    if ($node->to == "30: Gene associated with autosomal recessive phenotype")
                                        $node->to = 30;
                                    else if ($node->to == "40: Dosage sensitivity unlikely")
                                        $node->to = 40;
                                    else if ($node->to == "Not yet evaluated")
                                        $node->to = -5;

                                   $collection->push($node);

                              }
                         }
                    }
                    else if ($item->field == 'ISCA Haploinsufficiency score')
                    {
                         if ($item->fromString !== null
                              && trim($item->fromString) != 'Not yet evaluated'
                              && $item->toString !== null)
                         {
                              $created = new Carbon($history->created);
                              if (Carbon::now()->diffInWeeks($created) <= 52)
                              {
                                   //dd($issue);
                                   if ($issue->fields->issuetype->name == "ISCA Gene Curation")
                                        $title = $issue->fields->customfield_10030;
                                   else if ($issue->fields->issuetype->name == "ISCA Region Curation")
                                        $title = $issue->fields->customfield_10202;
                                   else
                                        $title = 'Unknown Issue Type';

                                   $node = new Nodal([
                                        'key' => $issue->key,
                                        'title' => $title,
                                        'type' => $issue->fields->issuetype->name,
                                        'what' => 'Haploinsufficiency Score',
                                        'when' => $created->format('m/d/Y'),
                                        'from' => $item->fromString,
                                        'to' => $item->toString,
                                        'age' => Carbon::now()->diffInWeeks($created)
                                   ]);

                                   // for 30 and 40, Jira also sends text
                                   if ($node->from == "30: Gene associated with autosomal recessive phenotype")
                                        $node->from = 30;
                                   else if ($node->from == "40: Dosage sensitivity unlikely")
                                        $node->from = 40;
                                    else if ($node->from == "Not yet evaluated")
                                        $node->from = -5;

                                   if ($node->to == "30: Gene associated with autosomal recessive phenotype")
                                        $node->to = 30;
                                   else if ($node->to == "40: Dosage sensitivity unlikely")
                                        $node->to = 40;
                                    else if ($node->to == "Not yet evaluated")
                                        $node->to = -5;

                                   $collection->push($node);
                              }
                         }
                    }
               }
          }

          return $collection;
     }


     /**
     * Split the location into an array of three parts
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function regionMap($region)
    {
         if ($region === null)
          return [null, null, null];

          // break out the location and clean it up
          $temp = preg_split('/[:-]/', trim($region), 3);

          $chr = strtoupper($temp[0]);

          if (strpos($chr, 'CHR') == 0)   // strip out the chr
               $chr = substr($chr, 3);

          $start = (isset($temp[1]) ? str_replace(',', '', $temp[1]) : null);
          $stop = (isset($temp[2]) ? str_replace(',', '', $temp[2]) : null);

          return [$chr, $start, $stop];
    }



     /*-------------------------library methods---------------------------*/

     /**
     * Create the internal DCI group and store accounts
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
     static function createGroup()
     {
          // check if parent exists, if not create.

     }


     /**
     * Get all issue
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function getIssues($query, $start = 0)
    {
         try {
              $issueService = new IssueService();

              $begin = Carbon::now();
              $issues = $issueService->search($query, $start, 250000);
              $end = Carbon::now();
               $record = new Minute([
				'system' => 'Search',
				'subsystem' => __METHOD__,
				'method' => 'query',
				'start' => $begin,
				'finish' => $end,
				'status' => 1

               ]);
               $record->save();

              //var_dump($issue->fields);
         } catch (JiraRestApi\JiraException $e) {
              print("Error Occured! " . $e->getMessage());
         }

         return $issues ?? null;
    }


    /**
     * Get an issue
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
     static function getIssue($issue, $field = 'fields')
     {
          try {
               $issueService = new IssueService();

               $queryParam = [
                    'expand' => [
                         'renderedFields',
                         'names',
                         'schema',
                         'transitions',
                         'operations',
                         'editmeta',
                         'changelog',
                    ]
               ];

               $begin = Carbon::now();
               $issue = $issueService->get($issue, $queryParam);
               $end = Carbon::now();
               $record = new Minute([
				'system' => 'Search',
				'subsystem' => __METHOD__,
				'method' => 'query',
				'start' => $begin,
				'finish' => $end,
				'status' => 1

               ]);
               $record->save();

               //var_dump($issue->fields);
          } catch (JiraRestApi\JiraException $e) {
               print("Error Occured! " . $e->getMessage());
          }

          if ($field == null)
               return $issue;

          return $issue->$field ?? null;
     }


     /**
     * Get an issue
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function updateIssue($issue, $field, $value)
    {
         try {
              $issueService = new IssueService();

              $issueField = new IssueField(true);

               $issueField->addCustomField($field, $value);

                // optionally set some query params
                $editParams = [
                    'notifyUsers' => false,
                ];

                // You can set the $paramArray param to disable notifications in example

               $issue = $issueService->update($issue, $issueField, $editParams);

         } catch (JiraRestApi\JiraException $e) {
              print("Error Occured! " . $e->getMessage());
         }
//dd($issue);
         return $issue->$field ?? null;
    }


     /**
     * Get all users
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
     static function getUsers()
     {
          try {
               $us = new UserService();

               $paramArray = [
               'username' => '.', // get all users.
               'startAt' => 0,
               'maxResults' => 1000,
               'includeInactive' => true,
               //'property' => '*',
               ];

               // get the user info.
               $users = $us->findUsers($paramArray);
          } catch (JiraRestApi\JiraException $e) {
               print("Error Occured! " . $e->getMessage());
          }

          return $users ?? null;
     }


     /**
     * Get a user
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
     static function getUser()
     {
          try {
               $us = new UserService();

               $paramArray = [
                   //'username' => null,
                   'project' => 'TEST',
                   //'issueKey' => 'TEST-1',
                   'startAt' => 0,
                   'maxResults' => 50, //max 1000
                   //'actionDescriptorId' => 1,
               ];

               $users = $us->findAssignableUsers($paramArray);
          } catch (JiraRestApi\JiraException $e) {
               print("Error Occured! " . $e->getMessage());
          }
     }


     /**
     * Create a new user
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
     static function createUser()
     {
          try {
               $us = new UserService();

               // create new user
               $user = $us->create([
                    'name'=>'charlie',
                    'password' => 'abracadabra',
                    'emailAddress' => 'charlie@atlassian.com',
                    'displayName' => 'Charlie of Atlassian',
               ]);

               var_dump($user);
          } catch (JiraRestApi\JiraException $e) {
               print("Error Occured! " . $e->getMessage());
          }
     }


     /**
     * Delete a user
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    static function deleteUser()
    {
          try {
               $us = new UserService();

               $paramArray = ['username' => 'user@example.com'];

               $users = $us->deleteUser($paramArray);
          } catch (JiraRestApi\JiraException $e) {
               print("Error Occured! " . $e->getMessage());
          }
    }
}
