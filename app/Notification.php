<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Collection;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Display;

use Auth;
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

class Notification extends Model
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
		'primary' => 'json|required',
		'secondary' => 'json|nullable',
          'frequency' => 'json|nullable',
		'type' => 'integer',
		'status' => 'integer'
	];

	/**
     * Map the json attributes to associative arrays.
     *
     * @var array
     */
	protected $casts = [
			'primary' => 'array',
               'secondary' => 'array',
               'frequency' => 'array'
		];

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = ['ident', 'user_id', 'primary', 'secondary',
					        'frequency', 'type', 'status',
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
     
     public const FREQUENCY_NONE = 0;
     public const FREQUENCY_DEFAULT = 0;
     public const FREQUENCY_DAILY = 1;
     public const FREQUENCY_WEEKLY = 2;
     public const FREQUENCY_SEMI_MONTHLY = 3;
     public const FREQUENCY_MONTHLY = 4;
     public const FREQUENCY_EVERY2MONTHS = 5;
     public const FREQUENCY_QUARTERLY = 6;
     public const FREQUENCY_SEMI_ANNUAL = 7;
     public const FREQUENCY_ANNUAL = 8;


     /*
     * Frequency strings for display methods
     *
     * */
     protected $frequency_strings = [
          self::FREQUENCY_NONE => 'None',
          self::FREQUENCY_DAILY => 'Daily',
          self::FREQUENCY_WEEKLY => 'Weekly',
          self::FREQUENCY_SEMI_MONTHLY => 'Semimonthly',
          self::FREQUENCY_MONTHLY => 'Monthly',
          self::FREQUENCY_EVERY2MONTHS => 'Every 2 Months',
          self::FREQUENCY_QUARTERLY => 'Quarterly',
          self::FREQUENCY_SEMI_ANNUAL => 'Semiannual',
          self::FREQUENCY_ANNUAL => 'annual'
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

        // initialize the structure
        $this->primary = ['email' => (Auth::guard('api')->check() ? Auth::guard('api')->user()->email : '')];
        $this->secondary = ['email' => ''];
        $this->frequency = [  'Daily' => [],
                              'Weekly' => [],
                              'Monthly' => [],
                              'Default' => [],
                              'Groups' => [],
                              'Pause' => [],
                              'global' => "on",
                              'first' => self::FREQUENCY_NONE,
                              'frequency' => self::FREQUENCY_DAILY,
                              'summary' => self::FREQUENCY_MONTHLY
                           ];
     }
     

     /*
     * The owner of this notification
     */
    public function user()
    {
       return $this->belongsTo('App\User');
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
     * Assert if the value is selected or not
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function checked($attribute, $value)
     {
          if (!isset($this->frequency[$attribute]))
               return '';

          return ($this->frequency[$attribute] == $value ? 'checked' : '');
     }



     /**
     * Add one of more genes to the default list
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
     public function addDefault($genes)
     {
          if ($genes instanceof Collection)
          {
               foreach ($genes as $gene)
                    $this->addDefault($gene);
          }
          else if ($genes instanceof \App\Gene)          // just one
          {
               $freq = $this->frequency;
               array_push($freq['Default'], $genes->name);
               $this->frequency = $freq;
          }
          else           //string
          {
               $freq = $this->frequency;
               array_push($freq['Default'], $genes);
               $this->frequency = $freq;    
          }
     }


     /**
     * remove an item from the default list
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function removeDefault($item)
     {
         if ($this->frequency === null)
             return true;
 
         if (!isset($this->frequency['Default']))
             return true;
     
         if (!in_array($item, $this->frequency['Default']))
             return true;
         
         $frequency = $this->frequency;
         if (($key = array_search($item, $frequency['Default'])) !== false)
              unset($frequency['Default'][$key]);
         $frequency['Default'] = array_values($frequency['Default']);
         $this->frequency = $frequency;
 
         return true;
     }

     /**
     * Convert the stored constant to hours
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function toHours($value)
     {
          switch ($value)
          {
               case self::FREQUENCY_NONE:
                    return -1;
               case self::FREQUENCY_DAILY:
                    return 24;
               case self::FREQUENCY_WEEKLY:
                    return 168;
               case self::FREQUENCY_SEMI_MONTHLY:
                    return 336;
               case self::FREQUENCY_MONTHLY:
                    return 720;
               case self::FREQUENCY_EVERY2MONTHS:
                    return 1440;
               case self::FREQUENCY_QUARTERLY:
                    return 2160;
               case self::FREQUENCY_SEMI_ANNUAL:
                    return 4320;
               case self::FREQUENCY_ANNUAL:
                    return 8790;
               default: 
                    return -1;
          }
     }

    
     /**
     * Transform the stored frequency strucure
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function toReport()
     {
          $reports = [];

          $frequency = $this->frequency;

          // Daily report or First Curation
          if ($frequency['first'] || !empty($frequency['Daily']) || ($frequency['frequency'] == self::FREQUENCY_DAILY && !empty($frequency['Default'])))
          {
               $genes = [];

               if (isset($frequency['Daily']))
                    $genes = array_merge($genes, $frequency['Daily']);
               
               if ($frequency['frequency'] == self::FREQUENCY_DAILY && isset($frequency['Default']))
                    $genes = array_merge($genes, $frequency['Default']);

               array_walk($genes, array($this, 'walk'));

               $reports[] = ['start_date' => Carbon::yesterday(), 'stop_date' => Carbon::yesterday()->setTime(23, 59, 59),
                            'filters' => json_decode('{"gene_label":[' . implode(', ', $genes)  . ']}')];
          }

          // Weekly Report
          if (Carbon::now()->isDayOfWeek(Carbon::SUNDAY) && (!empty($frequency['Weekly']) || ($frequency['frequency'] == self::FREQUENCY_WEEKLY && !empty($frequency['Default']))))
          {
               $genes = [];

               if (isset($frequency['Weekly']))
                    $genes = array_merge($genes, $frequency['Weekly']);
               
               if ($frequency['frequency'] == self::FREQUENCY_WEEKLY && isset($frequency['Default']))
                    $genes = array_merge($genes, $frequency['Default']);

               array_walk($genes, array($this, 'walk'));


               $reports[] = ['start_date' => Carbon::subWeek(), 'stop_date' => Carbon::yesterday()->setTime(23, 59, 59),
                            'filters' => json_decode('{"gene_label":[' . implode(', ', $genes)  . ']}')];
          }

          // Monthly Report
          if (Carbon::now()->format('d') == '01' && (!empty($frequency['Monthly']) || ($frequency['frequency'] == self::FREQUENCY_MONTHLY && !empty($frequency['Default']))))
          {
               $genes = [];

               if (isset($frequency['Monthly']))
                    $genes = array_merge($genes, $frequency['Monthly']);
               
               if ($frequency['frequency'] == self::FREQUENCY_MONTHLY && isset($frequency['Default']))
                    $genes = array_merge($genes, $frequency['Default']);

               array_walk($genes, array($this, 'walk'));


               $reports[] = ['start_date' => Carbon::subMonth()->setTime(0, 0, 0), 'stop_date' => Carbon::yesterday()->setTime(23, 59, 59),
                            'filters' => json_decode('{"gene_label":[' . implode(', ', $genes)  . ']}')];
          }

          return $reports;
     }


     /**
     * Transform the stored frequency strucure
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function toSummaryReport()
     {
          $reports = [];

          $frequency = $this->frequency;

          // Annual Summary Report
          if (Carbon::now()->format('d') == '01' && Carbon::now()->format('m') == '01' && (($frequency['summary'] == self::FREQUENCY_ANNUAL)))
          {

               $reports[] = ['start_date' => Carbon::now()->subYear(), 'stop_date' => Carbon::yesterday()->setTime(23, 59, 59),
                            'filters' => json_decode('{"gene_label":["*"]}')];
               
               return $reports;
          }


          // Quartery Summary Report
          if (Carbon::now()->format('d') == '01' && (Carbon::now()->format('m') == '01' ||
          Carbon::now()->format('m') == '04' || Carbon::now()->format('m') == '07' ||
          Carbon::now()->format('m') == '10') && (($frequency['summary'] == self::FREQUENCY_QUARTERLY)))
          {
     
               $reports[] = ['start_date' => Carbon::now()->subQuarter(), 'stop_date' => Carbon::yesterday()->setTime(23, 59, 59),
                            'filters' => json_decode('{"gene_label":["*"]}')];

               return $reports;
          }


          // Monthly Summary Report
          if (Carbon::now()->format('d') == '01' && (($frequency['summary'] == self::FREQUENCY_MONTHLY)))
          {

               $reports[] = ['start_date' => Carbon::now()->subMonth(), 'stop_date' => Carbon::yesterday()->setTime(23, 59, 59),
                            'filters' => json_decode('{"gene_label":["*"]}')];

               return $reports;
          }

          // Weekly Summary Report
          if (Carbon::now()->isDayOfWeek(Carbon::SUNDAY) && (($frequency['summary'] == self::FREQUENCY_WEEKLY)))
          {

               $reports[] = ['start_date' => Carbon::now()->subWeek(), 'stop_date' => Carbon::yesterday()->setTime(23, 59, 59),
                            'filters' => json_decode('{"gene_label":["*"]}')];
               
               return $reports;
          }

          return $reports;
     }


     /**
     * Current notification setting for the gene
     *
     * @@param	string	$gene
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function setting($gene)
     {
          $freq = $this->frequency;

          if (isset($freq['Default']) && in_array($gene, $freq['Default']))
               return 'Default';         //'$this->frequency_strings[$freq['frequency']];'
          
          if (isset($freq['Daily']) && in_array($gene, $freq['Daily']))
               return 'Daily';

          if (isset($freq['Weekly']) && in_array($gene, $freq['Weekly']))
               return 'Weekly';
          
          if (isset($freq['Monthly']) && in_array($gene, $freq['Monthly']))
               return 'Monthly';

          if (isset($freq['Pause']) && in_array($gene, $freq['Pause']))
               return 'Pause';

          return 'Default'; 
     }


     /**
     * Add to the group
     *
     * @@param	string	$gene
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function addGroup($group)
     {
          if ($this->frequency === null)
          {
               $this->frequency = ['Groups' => [$group]];
               return true;
          }

          if (!isset($this->frequency['Groups']))
          {  
               $this->frequency['Groups'] = [$group];
               return true;
          }
    
          if (!in_array($group, $this->frequency['Groups']))
          {
               $frequency = $this->frequency;
               array_push($frequency['Groups'], $group);
               $this->frequency = $frequency;
          }
     }


     /**
     * remove an interest item from the profile
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function removeGroup($group)
     {
         if ($this->frequency === null)
             return true;
 
         if (!isset($this->frequency['Groups']))
             return true;
     
         if (!in_array($group, $this->frequency['Groups']))
             return true;
         
         $frequency = $this->frequency;
         if (($key = array_search($group, $frequency['Groups'])) !== false)
              unset($frequency['Groups'][$key]);
         $frequency['Groups'] = array_values($frequency['Groups']);
         $this->frequency = $frequency;
 
         return true;
     }


     public function walk(&$item, $key)
     {
          $item = '"' . $item . '"';
     }

}
