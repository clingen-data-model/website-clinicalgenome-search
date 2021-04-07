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
class Title extends Model
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
        'type' => 'integer',
        'title' => 'string',
        'description' => 'string',
        'abstract' => 'text',
        'last_run_date' => 'timestamp',
        'notes' => 'text',
		'status' => 'integer'
	];
    
	/**
     * Map the json attributes to associative arrays.
     *
     * @var array
     */
	protected $casts = [
		];

    protected $dates = [ 'last_run_date' ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = ['ident', 'user_id', 'title', 'type', 'description', 'abstract',
                            'last_run_date', 'notes', 'status'
                         ];

	/**
     * Non-persistent storage model attributes.
     *
     * @var array
     */
    protected $appends = ['display_date', 'list_date', 'display_status', 'display_created_date'];

    public const TYPE_NONE = 0;
    public const TYPE_SYSTEM_NOTIFICATIONS = 1;
    public const TYPE_USER = 10;
    public const TYPE_SHARED = 20;

    /*
     * Type strings for display methods
     *
     * */
    protected $type_strings = [
             0 => 'Unknown',
             1 => 'System Notifications',
             10 => "User Created",
             20 => "Shared",
	 		99 => 'Deleted'
	];

    public const STATUS_INITIALIZED = 0;
    public const STATUS_ACTIVE = 1;
    public const STATUS_LOCKED = 2;


    /*
     * Status strings for display methods
     *
     * */
    protected $status_strings = [
	 		0 => 'Initialized',
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
     * Get user of this report
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }


    /**
     * Get all reports associated with this title
     */
    public function reports()
    {
        return $this->hasMany('App\Report');
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
     * Query scope by ident
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeSystem($query)
    {
		return $query->where('type', self::TYPE_SYSTEM_NOTIFICATIONS);
    }


    /**
     * Query scope by ident
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeExpire($query, $days)
    {
		return $query->where('created_at', '<', Carbon::now()->subDays($days)->toDateTimeString());
    }


    /**
     * Query scope by ident
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeUnlocked($query)
    {
		return $query->where('status', self::STATUS_ACTIVE);
    }


    /**
     * Return a displayable string of last run date
     *
     * @param
     * @return string
     */
	public function getDisplayLastDateAttribute()
	{
		if (empty($this->last_run_date))
			return '';

		return $this->last_run_date->timezone('America/New_York')
					->format("M j, Y");
    }
    

    /**
     * Return a displayable string of created
     *
     * @param
     * @return string
     */
	public function getDisplayCreatedDateAttribute()
	{
		if (empty($this->created_at))
			return '';

		return $this->created_at->timezone('America/New_York')
					->format("M j, Y");
	}

    /**
     * Run a change report
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function mrun($params)
    {
        if (!is_array($params))
            return [];

        foreach ($params as $param)
        {
            //dd($param);
            $changes = Change::start($param['start'])->stop($param['stop'])->filters($param['filters'])->get();
        }

        return $changes;
    }


    /**
     * Run a change report
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function run()
    {
        //$changes = Change::start($this->start_date)->stop($this->stop_date)->filters($this->filters)->get();

        $changes = collect();

        foreach ($this->reports as $report)
        {
            $newchanges = $report->run();
            $changes = $changes->concat($newchanges);
        }

        return $changes;
    }

}
