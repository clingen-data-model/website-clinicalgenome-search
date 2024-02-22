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
 * @copyright  2024 Geisinger
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/PackageName
 * @see        NetOther, Net_Sample::Net_Sample()
 * @since      Class available since Release 1.0.0
 *
 * */
class Packet extends Model
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
        'topic' => 'string',
        'timestamp' => 'integer',
        'uuid' => 'string',
        'payload' => 'jsonb',
        'offset' => 'integer',
		'type' => 'integer',
		'status' => 'integer'
	];

	/**
     * Map the json attributes to associative arrays.
     *
     * @var array
     */
	protected $casts = [
	];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = ['ident', 'topic', 'timestamp', 'uuid',
                            'offset', 'type', 'status', 'payload',
                         ];

	/**
     * Non-persistent storage model attributes.
     *
     * @var array
     */
    protected $appends = ['display_date', 'list_date', 'display_status'];

    public const TYPE_NONE = 0;
    public const TYPE_KAFKA = 1;

    /*
     * Type strings for display methods
     *
     * */
    protected $type_strings = [
	 		0 => 'Unknown',
	 		9 => 'Kafka'
	];

    public const STATUS_INITIALIZED = 0;
    public const STATUS_ACTIVE = 1;

    /*
     * Status strings for display methods
     *
     * */
    protected $status_strings = [
	 		0 => 'Initialized',
            1=> 'Active',
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


    /*
     * The curations associated with this message packet
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
     * Query scope by type
     *
     * @@param	string	$type
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeType($query, $type)
    {
		return $query->where('type', $type);
    }


    /**
     * Query scope by uuid
     *
     * @@param	string	$uuid
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeUuid($query, $uuid)
    {
		return $query->where('uuid', $uuid);
    }


    /**
     * Query scope by offset
     *
     * @@param	string	$topic
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeTopic($query, $topic)
    {
		return $query->where('topic', $topic);
    }


    /**
     * Query scope by offset
     *
     * @@param	string	$offset
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeOffset($query, $offset)
    {
		return $query->where('offset', $offset);
    }


    /**
     * Query scope by status
     *
     * @@param	string	$status
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeStatus($query, $status)
    {
		return $query->where('status', $status);
    }


}
