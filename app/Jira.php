<?php

namespace App;

use Jenssegers\Model\Model;

use JiraRestApi\Issue\IssueService;
use JiraRestApi\User\UserService;
use JiraRestApi\JiraException;

use Carbon\Carbon;

use App\Gene;
use App\Iscamap;
use App\Minute;

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
     protected $fillable = ['summary', 'issuetype', 'GRCh37_position', 'GRCh38_position',
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
     
     
     /**
     * Get details of a specific gene
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
     static function dosageDetail($args, $page = 0, $pagesize = 20)
     {
		// break out the args
		foreach ($args as $key => $value)
               $$key = $value;
               
          // get the issue number
          $symbol = Gene::where('hgnc_id', $gene)->first();

          if ($symbol === null)
               return null;

          $issue = Iscamap::symbol($symbol->name)->first();

          if ($issue === null)
               return null;

          $response = self::getIssue($issue->issue);

          // map the jira response into a somewhat sane structure
		$node = new Nodal([
               'summary' => $response->summary,
               'genetype' => $response->customfield_10156->value ?? 'unknown',
               'GRCh37_position' => $response->customfield_10160,
               'GRCh38_position' => $response->customfield_10532,
               'triplo_score' => $response->customfield_10166->value ?? 'unknown',
               'haplo_score' => $response->customfield_10165->value ?? 'unknown',
               'cytoband' => $response->customfield_10145
          ]);

	//dd($node);	

		return $node;	
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

          foreach ($response->issues as $issue)
          {
               // map the jira response into a somewhat sane structure
               $node = new Nodal([
                    'key' => $issue->key,
                    'summary' => $issue->fields->summary,
                    'GRCh37_position' => $issue->fields->customfield_10160,
                    'triplo_score' => $issue->fields->customfield_10166->value ?? 'unknown',
                    'haplo_score' => $issue->fields->customfield_10165->value ?? 'unknown'
               ]);
               
               // for 30 and 40, Jira also sends text
               if ($node->triplo_score == "30: Gene associated with autosomal recessive phenotype")
                    $node->triplo_score = 30;
               else if ($node->triplo_score == "40: Dosage sensitivity unlikely")
                    $node->triplo_score = 40;

               if ($node->haplo_score == "30: Gene associated with autosomal recessive phenotype")
                    $node->haplo_score = 30;
               else if ($node->haplo_score == "40: Dosage sensitivity unlikely")
                    $node->haplo_score = 40;

               $collection->push($node);
          }

          $nhaplo = $collection->where('haplo_score', '>', 0)->count();
          $ntriplo = $collection->where('triplo_score', '>', 0)->count();

          return (object) ['count' => $response->total, 'collection' => $collection,
               'nhaplo' => $nhaplo, 'ntriplo' => $ntriplo];
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
               // map the jira response into a somewhat sane structure
               $node = new Nodal([
                    'key' => $issue->key,
                    'label' => $issue->fields->customfield_10030,
                    'omim' => $issue->fields->customfield_10147,
                    'triplo_score' => $issue->fields->customfield_10166->value ?? 'unknown',
                    'haplo_score' => $issue->fields->customfield_10165->value ?? 'unknown'
               ]);
               
               // for 30 and 40, Jira also sends text
               if ($node->triplo_score == "30: Gene associated with autosomal recessive phenotype")
                    $node->triplo_score = 30;
               else if ($node->triplo_score == "40: Dosage sensitivity unlikely")
                    $node->triplo_score = 40;

               if ($node->haplo_score == "30: Gene associated with autosomal recessive phenotype")
                    $node->haplo_score = 30;
               else if ($node->haplo_score == "40: Dosage sensitivity unlikely")
                    $node->haplo_score = 40;

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
              
          $collection = collect();

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
                    'resolved_date' => $issue->fields->resolutiondate
               ]);

               // for 30 and 40, Jira also sends text
               if ($node->triplo_assertion == "30: Gene associated with autosomal recessive phenotype")
                    $node->triplo_assertion = 30;
               else if ($node->triplo_assertion == "40: Dosage sensitivity unlikely")
                    $node->triplo_assertion = 40;

               if ($node->haplo_assertion == "30: Gene associated with autosomal recessive phenotype")
                    $node->haplo_assertion = 30;
               else if ($node->haplo_assertion == "40: Dosage sensitivity unlikely")
                    $node->haplo_assertion = 40;

               $collection->push($node);
          }

          $nhaplo = $collection->where('haplo_assertion', '>', 0)->count();
          $ntriplo = $collection->where('triplo_assertion', '>', 0)->count();

          return (object) ['count' => $response->total, 'collection' => $collection,
               'nhaplo' => $nhaplo, 'ntriplo' => $ntriplo];
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
    static function getIssues($query)
    {
         try {
              $issueService = new IssueService();

              $begin = Carbon::now();
              $issues = $issueService->search($query, 0, 2500);
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
     static function getIssue($issue)
     {
          try {
               $issueService = new IssueService();
               
               $queryParam = [
               'fields' => [ 
               ],
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

          return $issue->fields ?? null;
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
