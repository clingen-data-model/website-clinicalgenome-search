<?php

namespace App;

use Jenssegers\Model\Model;

use JiraRestApi\Issue\IssueService;
use JiraRestApi\User\UserService;
use JiraRestApi\JiraException;

use App\Gene;
use App\Iscamap;

/**
 *
 * @category   Library
 * @package    Search
 * @author     P. Weller <pweller1@geisinger.edu>
 * @copyright  2019 ClinGen
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
     * This class is designed to be used statically.
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
                    
               $issue = $issueService->get($issue, $queryParam);
               
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
