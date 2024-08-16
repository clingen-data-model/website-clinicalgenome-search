<?php

namespace App;

use App\Concerns\HttpClient;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Display;

use Illuminate\Support\Facades\Http;
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
class Panel extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Display;
    use HttpClient;

    /**
     * The attributes that should be validity checked.
     *
     * @var array
     */
    public static $rules = [
          'ident' => 'alpha_dash|max:80|required',
          'name' => 'string',
          'affiliate_id' => 'string',
          'title' => 'string',
          'title_short' => 'string',
          'title_abbreviated' => 'string',
          'affiliate_id' => 'string',
          'affiliate_type' => 'string',
          'affiliate_status' => 'json',
          'cdwg_parent_name' => 'string',
          'contacts' => 'json|nullable',
          'member' => 'json|nullable',
          'summary' => 'string|nullable',
          'type' => 'integer',
          'status' => 'integer'
	];

	/**
     * Map the json attributes to associative arrays.
     *
     * @var array
     */
	protected $casts = [
            'contacts' => 'array',
            'affiliate_status' => 'array',
            'member' => 'array',
            'contact' => 'array'
		];

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = ['ident', 'name', 'affiliate_id', 'alternate_id', 'title', 'title_short',
                            'title_abbreviated', 'affiliate_type', 'affiliate_status',
                            'cdwg_parent_name', 'member', 'contacts',
                           'summary', 'type', 'status', 'wg_status', 'metadata_search_terms', 'is_active',
                           'group_clinvar_org_id', 'inactive_date', 'url_clinvar', 'url_cspec', 'url_curations',
                            'url_erepo', 'gpm_id'
                            ];

	/**
     * Non-persistent storage model attributes.
     *
     * @var array
     */
     protected $appends = ['display_date', 'list_date', 'display_status'];

     public const TYPE_INTERNAL = 0;
     public const TYPE_GCEP = 1;
     public const TYPE_VCEP = 2;
     public const TYPE_WG = 3;

     /*
     * Type strings for display methods
     *
     * */
     protected $type_strings = [
	 		0 => 'Unknown',
            1 => 'GCEP',
            2 => 'VCEP',
            3 => 'WG',
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


    /*
     * The users following this group
     */
    public function users()
    {
       return $this->belongsToMany('App\User');
    }


    /*
     * The genes associated with this group
     */
    public function genes()
    {
       return $this->belongsToMany('App\Gene');
    }


    /*
     * The diseases associated with this group
     */
    public function diseases()
    {
       return $this->belongsToMany('App\Disease');
    }

    /*
     * The activities associated with this panel
     */
    public function activities()
    {
        return $this->hasMany(PanelActivity::class);
    }


    /*
     * The curations associated with this group as a primary
     */
    public function primary_curations()
    {
       return $this->hasMany('App\Curation');
    }


    /*
     * The curations associated with this group
     */
    public function curations()
    {
       return $this->belongsToMany('App\Curation');
    }

    /*
     * The members associated with this panel
     */
    public function members()
    {
        return $this->belongsToMany(Member::class)->withPivot(['role']);
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
     * Query scope by group name
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeName($query, $name)
    {
        return $query->where('name', $name);
    }


    /**
     * Query scope by group id
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeAffiliate($query, $id)
    {
        return $query->where('affiliate_id', $id);
    }


    /**
     * Query scope by group title
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeTitle($query, $name)
    {
        return $query->where('title', $name);
    }


    /**
     * Query scope by wither group id or alternate
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeAllids($query, $id)
    {
        return $query->where('affiliate_id', $id)->orWhere('alternate_id', $id);
    }


    /**
     * Query scope by gcep type
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeGcep($query)
    {
        return $query->where('type', self::TYPE_GCEP);
    }


    /**
     * Query scope by vcep type
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeVcep($query)
    {
        return $query->where('type', self::TYPE_VCEP);
    }


    /**
     * Query scope by vcep type
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeblacklist($query, $list)
    {
        return $query->whereNotIn('affiliate_id', $list);
    }


    /**
     * Return an href suitable identifier
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function getHrefAttribute()
    {
        switch ($this->type)
        {
            case self::TYPE_GCEP:
            case self::TYPE_VCEP:
                //$t = substr($this->name, 3);
                //$k = strpos($t, ' ');
                //return substr($t, 0, $k);
                return $this->affiliate_id;
            default:
                //return $this->name;
                return $this->affiliate_id;
        }

        return '';
    }


    /**
     * Return an best choice of titles
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getSmartTitleAttribute()
    {
        if (!empty($this->title_short))
            return $this->title_short . ' ' . $this->type_string;

        if (!empty($this->title_abbreviated))
            return $this->title_abbreviated;

        return $this->title;
    }


    /**
     * Return an best choice of names
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getSmartNameAttribute()
    {
        if (!empty($this->name))
            return $this->name;

        if (!empty($this->title_short))
            return $this->title_short;

        if (!empty($this->title_abbreviated))
            return $this->title_abbreviated;

        $title = str_replace(' Expert Panel', '', $this->title);

        return  $title;
    }

    /**
     * Return an href suitable identifier
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function getTypeStringAttribute()
    {
        switch ($this->affiliate_type)
        {
            case 'gcep':
            case 'vcep':
                return strtoupper($this->affiliate_type);
            case 'working group':
                return 'Working Group';
            default:
                return '';
        }

        return '';
    }


    /**
     * Map genegraph curie to affiliate ID
     */
    public static function gg_map_to_panel($curie, $adjust = false)
    {
        $curie = (strpos($curie, 'CGAGENT:') === 0 ? substr($curie, 8) : $curie);

        if (!$adjust)
            return $curie;

        if ($curie < 20000)
            $curie += 30000;

        return $curie;


    }


    /**
     * Map genegraph curie to affiliate ID
     */
    public static function erepo_map_to_panel($curie)
    {
        //CG-PCER-AGENT:CG_50015_EP.1551905782.01949",
        if (strpos($curie, 'CG-PCER-AGENT:CG_') === 0)
        {
            $k = substr($curie, 17);
            $curie = substr($k, 0, strpos($k, '_'));
        }

        return $curie;
    }

    private function affiliateStatusForGeneOrVcep()
    {
        if ($this->affiliate_type === 'gcep') {
            if ($this->wg_status === 'Define Group') return 1;
            if ($this->wg_status === 'Expert Panel Approval') return 2;
        } else if ($this->affiliate_type === 'gcep') {
            if ($this->wg_status === 'Define Group') return 1;
            if ($this->wg_status === 'Classification Rules') return 2;
            if ($this->wg_status === 'Pilot Rules') return 3;
            if ($this->wg_status === 'Expert Panel Approval') return 4;
        }

        return null;
    }

    public function getActivityValue($activity)
    {
        return optional($this->activities->where('activity', $activity)->first())->activity_date;
    }

    public function getMembersByType($type)
    {
        return $this->members->filter( function($member) use ($type) {
            return $member->pivot->role === $type;
        })->map( function ($memb) {
            return [
                'user_name_full' => $memb->display_name,
                'user_name_first' => $memb->first_name,
                'user_name_last' => $memb->last_name,
                'id' => $memb->processwire_id,
                'email' => $memb->email,
                'user_photo' => $memb->profile_photo,
                'institution' => $memb->institution
            ];
        })->values()->toArray();
    }

    public function pushToProcessWire()
    {
        $this->load('activities');

        //map process wire fields
        $processWireFields = [
            'name' => $this->affiliate_id,
            'title' => $this->title,
            'title_short' => $this->title_short,
            'title_abbreviated' => $this->titlfe_abbreviated,
            'summary' => $this->summary,
            'body_1' => $this->description,
            'expert_panel_type' => $this->affiliate_type === 'gcep' ? [1] : [2] ,
            'affiliate_status_gene' => $this->affiliateStatusForGeneOrVcep(),
            'affiliate_status_gene_date_step_1' => $this->getActivityValue('affiliate_status_gene_date_step_1'), //status date wil come from activity
            'affiliate_status_gene_date_step_2' => $this->getActivityValue('affiliate_status_gene_date_step_2'),
            'affiliate_status_variant' => $this->affiliateStatusForGeneOrVcep(),
            'affiliate_status_variant_date_step_1' => $this->getActivityValue('affiliate_status_variant_date_step_1'), //status date wil come from activity
            'affiliate_status_variant_date_step_2' => $this->getActivityValue('affiliate_status_variant_date_step_2'),
            'affiliate_status_variant_date_step_3' => $this->getActivityValue('affiliate_status_variant_date_step_3'),
            'affiliate_status_variant_date_step_4' => $this->getActivityValue('affiliate_status_variant_date_step_4'),
            'ep_status_inactive' => $this->is_inactive ?? 0,
            'ep_status_inactive_date' => $this->inactive_date ?? '',
            'group_clinvar_org_id' => $this->group_clinvar_org_id,
            'url_clinvar' => $this->url_clinvar,
            'url_cspec' => $this->url_cspec,
            'url_curations' => $this->url_curations,
            'url_erepo' => $this->url_erepo,
            'relate_cdwg' => $this->cdwg_parent_name,
            'relate_user_leaderships' => $this->getMembersByType(Member::LEADER),
            'relate_user_coordinators' => $this->getMembersByType(Member::COORDINATOR),
            'relate_user_curators' => $this->getMembersByType(Member::CURATOR),
            'relate_user_committee' => $this->getMembersByType(Member::COMMITTEE),
            'relate_user_members' => $this->getMembersByType(Member::MEMBER),
            'relate_user_members_past' => $this->getMembersByType(Member::PAST_MEMBER),
            'metadata_search_terms' => $this->metadata_search_terms
        ];

        $response = $this->HttpRequest()->post($this->processWireUrl(), $processWireFields);

        return $response->body();
    }

    private function processWireUrl()
    {
        return sprintf('%s/api/panels/%s', config('processwire.url'), $this->affiliate_id);
    }

    public function getPanelFromPW($expert_panel)
    {
        if ($affliate_type = data_get($expert_panel, 'title')) {
            if ($affliate_type === 'Gene Curation') return 'gcep';
            if ($affliate_type === 'Variant Curation') return 'vcep';
        }

    }

    public function syncFromProcessWire()
    {
        $url = $this->processWireUrl();
        $response = Http::withoutVerifying()->get($url);

        if ($response->successful()) {
            $panelData = json_decode($response->body(), true);

            $this->affiliate_id = data_get($panelData, 'name');
            $this->title = data_get($panelData, 'title');
            $this->title_short = data_get($panelData, 'title_short');
            $this->title_abbreviated = data_get($panelData, 'title_abbreviated');
            $this->summary = data_get($panelData, 'summary');
            $this->description = data_get($panelData, 'body_1');
            if ($expertPanelData = data_get($panelData, 'expert_panel_type')) {
                $this->affiliate_type = $this->getPanelFromPW($expertPanelData);
            }

            $this->group_clinvar_org_id = data_get($panelData, 'group_clinvar_org_id');
            $this->is_inactive = data_get($panelData, 'ep_status_inactive');

            $this->url_clinvar = data_get($panelData, 'url_clinvar');
            $this->url_cspec = data_get($panelData, 'url_cspec');
            $this->url_curations = data_get($panelData, 'url_curations');
            $this->url_erepo = data_get($panelData, 'url_erepo');
            $this->cdwg_parent_name = data_get($panelData, 'relate_cdwg');

            if ($inactiveDate = data_get($panelData, 'ep_status_inactive_date')) {
                $this->inactive_date = Carbon::createFromTimestamp($inactiveDate);
            }

            if ($this->save()) {

                if ($dateTime = data_get($panelData, 'affiliate_status_gene_date_step_1')) {
                    //create activity for panel
                    $activity = $this->activities()->firstOrNew([
                        'activity' => 'affiliate_status_gene_date_step_1'
                    ]);

                    $activity->date = Carbon::createFromTimestamp($dateTime);
                    $activity->save();
                }

                if ($dateTime = data_get($panelData, 'affiliate_status_gene_date_step_2')) {
                    //create activity for panel
                    $activity = $this->activities()->firstOrNew([
                        'activity' => 'affiliate_status_gene_date_step_2'
                    ]);

                    $activity->activity_date = Carbon::createFromTimestamp($dateTime);
                    $activity->save();
                }

                if ($dateTime = data_get($panelData, 'affiliate_status_variant_date_step_1')) {
                    //create activity for panel
                    $activity = $this->activities()->firstOrNew([
                        'activity' => 'affiliate_status_variant_date_step_1'
                    ]);

                    $activity->activity_date = Carbon::createFromTimestamp($dateTime);
                    $activity->save();
                }

                if ($activityDate = data_get($panelData, 'affiliate_status_variant_date_step_2')) {
                    //create activity for panel
                    $activity = $this->activities()->firstOrNew([
                        'activity' => 'affiliate_status_variant_date_step_2'
                    ]);

                    $activity->activity_date = Carbon::createFromTimestamp($activityDate);
                    $activity->save();
                }


                if ($activityDate = data_get($panelData, 'affiliate_status_variant_date_step_3')) {
                    //create activity for panel
                    $activity = $this->activities()->firstOrNew([
                        'activity' => 'affiliate_status_variant_date_step_3'
                    ]);

                    $activity->activity_date = Carbon::createFromTimestamp($activityDate);
                    $activity->save();
                }

                if ($activityDate = data_get($panelData, 'affiliate_status_variant_date_step_4')) {
                    //create activity for panel
                    $activity = $this->activities()->firstOrNew([
                        'activity' => 'affiliate_status_variant_date_step_4'
                    ]);

                    $activity->activity_date = Carbon::createFromTimestamp($activityDate);
                    $activity->save();
                }

                $memberContainers = [
                    'leader' => 'relate_user_leaderships',
                    'coordinator' => 'relate_user_coordinator',
                    'curator' => 'relate_user_curators',
                    'committee' => 'relate_committee_curators',
                    'member' => 'relate_user_members',
                    'past_member' => 'relate_user_members_past'
                ];

                foreach ($memberContainers as $field => $container) {
                    if ($members = data_get($panelData, $container)) {
                        foreach ($members as $member) {
                            $memberObj = Member::firstOrNew([
                                'email' => data_get($member, 'email')
                            ]);

                            $memberObj->display_name = data_get($member, 'user_name_full', '');
                            $memberObj->first_name = data_get($member, 'user_name_first', '');
                            $memberObj->last_name = data_get($member, 'user_name_last', '') ?? '';
                            $memberObj->processwire_id = data_get($member, 'id');
                            $memberObj->email = data_get($member, 'email');

                            if ($institutions = data_get($member, 'relate_institutions')) {
                                $memberObj->institution = $institutions[0]['title'];
                            }

                            if ($photo = data_get($member, 'user_photo')) {
                                //Add user photo here ...
                                if ($basename = data_get($photo, 'basename')) {
                                    $photoUrl = sprintf('%s/sites/assets/%s/%s', config('processwire.url'), $member['id'], $basename);
                                    $memberObj->profile_photo = $photoUrl;
                                }
                            }

                            if ($memberObj->save()) {
                                $this->members()->syncWithoutDetaching([$memberObj->id, ['role' => $field]]);
                            }
                        }
                    }
                }

            }

        }
    }

    public function parser($data, $timestamp)
    {
        $panel = new static();
        return $panel->syncFromKafka($data, $timestamp);
    }

    public function syncFromKafka($data, $timestamp = null)
    {
        $eventType = data_get($data, 'event_type');

        $this->title_abbreviated = data_get($data, 'data.expert_panel.name');

        if ($affiliate_id = data_get($data, 'data.expert_panel.affiliation_id')) {
            $this->affiliate_id = $affiliate_id;
        }

        $this->gpm_id = data_get($data, 'data.expert_panel.id');
        $this->affiliate_type = data_get($data, 'data.expert_panel.type');

        $this->name = data_get($data, 'data.expert_panel.name');

        $this->save();

        switch ($eventType) {
            case 'ep_definition_approved':

                $activity = $this->activities()->firstOrNew([
                    'activity' => 'ep_definition_approved'
                ]);

                $activity->activity_date = Carbon::createFromTimestamp($timestamp);
                $activity->save();
                break;

            case 'vcep_draft_specifications_approved':
                //
                $activity = $this->activities()->firstOrNew([
                    'activity' => 'vcep_draft_specifications_approved'
                ]);

                $activity->activity_date = Carbon::createFromTimestamp($timestamp);
                $activity->save();
                break;

            case 'vcep_pilot_approved':

                $activity = $this->activities()->firstOrNew([
                    'activity' => 'vcep_pilot_approved'
                ]);

                $activity->activity_date = Carbon::createFromTimestamp($timestamp);
                $activity->save();

                break;

            case 'ep_final_approval':

                $activity = $this->activities()->firstOrNew([
                    'activity' => 'ep_final_approval'
                ]);

                $activity->activity_date = Carbon::createFromTimestamp($timestamp);
                $activity->save();
                break;
                //
            case 'member_removed':
                //
                if ($members = data_get($data, 'data.members')) {
                    foreach ($members as $member) {
                        $memberObj = $this->validateMemberFromKafka($member);

                        if (null !== $memberObj) {

                            if (count($member['group_roles'])) {
                                if ($member['group_roles'][0] === 'chair') {
                                    $userRole = 'leader';
                                } else {
                                    $userRole = $member['group_roles'][0];
                                }
                                $role = ['role' => $userRole];
                            }
                            $this->members()->detach($memberObj->id);
                        }
                    }
                }

                break;
            //
            case 'member_role_removed':
                //
                if ($members = data_get($data, 'data.members')) {
                    foreach ($members as $member) {
                        $memberObj = $this->validateMemberFromKafka($member);

                        if (null !== $memberObj) {
                            $groupRoles = json_decode($memberObj->groupRoles, true);

                            if (!is_array($groupRoles)) continue;

                            $currentRoles = array_diff($groupRoles, $member['group_roles']);

                            $role = [
                                'role' => count($currentRoles) ? $this->getPanelMembership($currentRoles) : '',
                                'group_roles' => json_encode($currentRoles)
                            ];

                            $this->members()->sync([$memberObj->id, $role]);

                        }
                    }

                }
            case 'member_added':
            case 'member_role_assigned':
            case 'member_unretired':
                if ($members = data_get($data, 'data.members')) {
                    foreach ($members as $member) {
                        $memberObj = $this->validateMemberFromKafka($member);

                        if (null !== $memberObj) {

                            $role = [
                                'role' => count($member['group_roles']) ? $this->getPanelMembership($member['group_roles']) : '',
                                'group_roles' => json_encode($member['group_roles'])
                            ];

                            $this->members()->sync([$memberObj->id, $role]);

                        }
                    }

                }
                break;
            case 'member_retired':
                if ($members = data_get($data, 'data.members')) {
                    foreach ($members as $member) {
                        $memberObj = $this->validateMemberFromKafka($member);

                        if (null !== $memberObj) {
                            $this->members()->syncWithoutDetaching([$memberObj->id, ['role' => 'past_member']]);
                        }
                    }
                }
                break;
            default:
                //

        }

        return $this;
    }

    protected function validateMemberFromKafka($member)
    {
        if ($gpm_id = data_get($member, 'id')) {
            $memberObj = Member::firstOrNew([
                'gpm_id' => $gpm_id
            ]);

            if ($memberObj->id) {
                return $memberObj;
            } else {
                $memberObj->first_name = data_get($member, 'first_name', '');
                $memberObj->last_name = data_get($member, 'last_name', '');
                $memberObj->email= data_get($member, 'email');
                $memberObj->gpm_id = data_get($member, 'id');

                $memberObj->save();

                return $memberObj;
            }
        }

        return null;
    }

    public function getPanelMembership($roles)
    {
        if (in_array('leader', $roles)) return 'leader';
        if (in_array('biocurator', $roles)) return 'curator';
        return 'member';
    }

}
