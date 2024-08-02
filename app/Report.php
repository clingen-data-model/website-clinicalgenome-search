<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Display;

use Uuid;
use Carbon\Carbon;

use App\Change;
use App\Region;

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
class Report extends Model
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
        'start_date' => 'datetime',
        'stop_date' => 'datetime',
        'filters' => 'json',
        'notes' => 'string',
		'status' => 'integer'
	];

	/**
     * Map the json attributes to associative arrays.
     *
     * @var array
     */
	protected $casts = [
            'filters' => 'array',
            'start_date' => 'datetime',
            'stop_date' => 'datetime'
		];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = ['ident', 'user_id', 'title_id', 'type', 'start_date', 'stop_date',
                            'filters', 'notes', 'status'
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
     * Get user of this report
     */
    public function title()
    {
        return $this->belongsTo('App\Title');
    }


    /**
     * Return a displayable string of start_date
     *
     * @param
     * @return string
     */
	public function getDisplayStartDateAttribute()
	{
		if (empty($this->start_date))
			return '';

		return $this->start_date->timezone('America/New_York')
					->format("m/d/Y");
    }


    /**
     * Return a displayable string of stop date
     *
     * @param
     * @return string
     */
	public function getDisplayStopDateAttribute()
	{
		if (empty($this->stop_date))
			return '';

		return $this->stop_date->timezone('America/New_York')
					->format("m/d/Y");
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
    public function run($type = 'gene')
    {
        if ($type == 'gene')
            $changes = Change::start(Carbon::parse($this->start_date))
                                ->stop(Carbon::parse($this->stop_date))
                                ->filters($this->filters)->get();
        else if ($type == 'disease')
            $changes = Change::start(Carbon::parse($this->start_date))
                                ->stop(Carbon::parse($this->stop_date))
                                ->disease_filters($this->filters)->get();
        return $changes;
    }


    /**
     * Parse filter and return a structured list
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function parse_filter()
    {
        $resp = ['genes' => [], 'regex' => [], 'groups' => [], 'regions' => [], 'region_type' => 1];

        if ($this->filters['gene_label'] === null)
            return $resp;

        foreach ($this->filters['gene_label'] as $item)
        {
            switch (substr($item, 0, 1))
            {
                case '*':
                    $resp['regex'][] = $item;
                    break;
                case '%':
                    $split = explode('||', substr($item,1));
                    if ($split === false)
                        break;
                    if (isset($split[1]) && $split[1] == Region::TYPE_REGION_GRCH38)
                        $resp['region_type'] = Region::TYPE_REGION_GRCH38;
                    $resp['regions'][] = $split[0];
                    break;
                case '@':
                    $resp['groups'][] = $item;
                    break;
                default:
                    $resp['genes'][] = $item;
            }
        }

        return $resp;
    }
}
