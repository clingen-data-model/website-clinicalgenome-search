<?php

namespace App;

use App\Concerns\HttpClient;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Display;

use Uuid;

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
class Member extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Display;
    use HttpClient;

    const LEADER = 'chair';
    const PAST_MEMBER = 'past_member';
    const MEMBER = 'member';
    const CURATOR = 'biocurator';
    const COMMITTEE = 'committee';
    const COORDINATOR = 'coordinator';

    /**
     * The attributes that should be validity checked.
     *
     * @var array
     */
    public static $rules = [
          'ident' => 'alpha_dash|max:80|required',
          'gpm_id' => 'alpha_dash|required',
          'first_name' => 'string|required',
          'last_name' => 'string|required',
          'email' => 'string|required',
          'phone' => 'string',
          'institution' => 'json',
          'credentials' => 'string',
          'biography' => 'string',
          'profile_photo' => 'string',
          'orchid_id' => 'string',
          'hypothesis_id' => 'string',
          'address' => 'json',
          'timezone' => 'string',
          'type' => 'integer',
          'status' => 'integer'
	];

	/**
     * Map the json attributes to associative arrays.
     *
     * @var array
     */
	protected $casts = [
            'institution' => 'array',
            'address' => 'array'
		];

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = ['ident', 'type',' gpm_id', 'first_name', 'last_name',
                            'email', 'phone', 'institution', 'credentials', 'biography',
                            'profile_photo', 'orchid_id', 'hypothesis_id', 'address',
                            'timezone', 'status', 'display_name', 'processwire_id', 'gpm_id'];

	/**
     * Non-persistent storage model attributes.
     *
     * @var array
     */
     protected $appends = ['display_date', 'list_date', 'display_status'];

     public const TYPE_NONE = 0;
     public const TYPE_GPM_MEMBER = 1;

     /*
     * Type strings for display methods
     *
     * */
     protected $type_strings = [
	 		0 => 'Unknown',
            1 => 'GPM Member'
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

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function processwireData()
    {
        $inst = $this->institution ? json_decode($this->institution, true) : [];
        return [
            'user_name_full' => $this->display_name,
            'user_name_first' => $this->first_name,
            'user_name_last' => $this->last_name,
            'user_title' => '',
            'user_url' => '',
            'relate_institutions' => is_array($inst) && isset($inst['name']) ? $inst['name'] : '',
            'email' => $this->email,
            'user_photo' => $this->profile_photo,
            'user_bio' => $this->biography,
            'user_professional_attributes' => $this->credentials,
            'gpm_id' => $this->gpm_id,
            'prev_email' => $this->prev_email
            //''
        ];
    }

    public function getDisplayNameAttribute($value) {
        if ($value) return $value;
        return sprintf('%s %s %s', $this->first_name, $this->last_name, $this->credentials);
    }

    public function parser($data, $timestamp = null)
    {
        if ($eventType = data_get($data, 'event_type')) {
            if ($eventType === 'deleted') {
                if ($gpm_id = data_get($data,'data.person.id')) {
                    $member = self::where('gpm_id', $gpm_id)->first();
                    $member->removeFromProcessWire();
                    $member->delete();
                    return true;
                }

            } else {
                // it's either created or updated
                $credentialsArray = [];
                self::createFromGpm($data);
                return true;
            }
        }
    }


    public static function createFromGpm($data)
    {
        if ($person = data_get($data, 'data.person')) {
            $member = self::firstOrNew([
                'gpm_id' => $person['id']
            ]);

            $credentialsArray = [];

            if ($credentialsData = data_get($person, 'credentials')) {
                $credentialsArray = array_map(function ($cred) {
                    return $cred['name'];
                }, $credentialsData);
            }

            $email = data_get($person, 'email');

            if ($member->email && ($member->email !== $email)) {
                $member->prev_email = $member->email;
            }

            $credentials = implode(' ', $credentialsArray);

            $member->first_name = data_get($person, 'first_name');
            $member->last_name = data_get($person, 'last_name');
            $member->institution = json_encode(data_get($person, 'institution', []));
            $member->credentials = $credentials;
            $member->profile_photo = data_get($person, 'profile_photo');
            $member->address = json_encode(data_get($person, 'address'));
            $member->biography = data_get($person, 'biography', ' ');
            $member->email = $email;
            $member->phone = data_get($person, 'phone');

            $member->timezone = data_get($person, 'timezone');

            $member->save();


            $member->createProcessWireUser();

            return $member;

        }

        throw new \Exception('You need to provide a person');
    }

    private function processWireUrl()
    {
        return sprintf('%s/api/users/', config('processwire.url'));
    }

    public function removeFromProcessWire()
    {
        $response =  $this->pushToProcessWire('delete');
        return $response->successful();
    }

    public function createProcessWireUser()
    {
        $response = $this->pushToProcessWire();
        if ($response->successful()) {
            if ($userData = json_decode($response->body(), true)) {
                $this->processwire_id = $userData['id'];
                $this->save();
                return $this;
            }
        }

        return false;

    }

    public function pushToProcessWire($action = null)
    {
        $data = $this->processwireData();
        $data['action'] = $action;
        return $this->HttpRequest()->post($this->processWireUrl(), $data);
    }

    public function panelPosition( $memberPositions = [])
    {
        $hierarchy = [
            'chair',
            'coordinator',
            'grant liaison',
            'expert',
            'biocurator',
            'member',
            'past member'
        ];

        $memberPositions = array_map('strtolower', $memberPositions);

        foreach( $hierarchy as $position) {
            if (in_array($position, $memberPositions)) {
                return $position;
            }
        }

        return 'member';
    }

}
