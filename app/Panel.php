<?php

namespace App;

use App\Concerns\HttpClient;
use App\Services\PanelImportService;
use App\Services\PanelIncrementalService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Display;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Artisan;
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
          'status' => 'integer',
	  'parent_id' => 'integer',
	  'icon_url' => 'string',
	  'caption' => 'string'
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
                            'url_erepo', 'gpm_id', 'parent_id', 'icon_url', 'caption'];

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
        return $this->belongsToMany(Member::class)->withPivot(['role', 'group_roles']);
    }

    public function parent()
    {
        return $this->belongsTo(Panel::class, 'parent_id');
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
    // Normalize $type to an array of lower-cased role names
    if (is_array($type)) {
        $types = array_map('strtolower', $type);
    } else {
        $types = [strtolower($type)];
    }

    return $this->members
        ->filter(function ($member) use ($types) {
            $role = $member->pivot->role ?? '';

            // case-insensitive match against any of the types
            return in_array(strtolower($role), $types, true);
        })
        ->map(function ($memb) {
            $inst = $memb->institution ? json_decode($memb->institution, true) : [];

            return [
                'user_name_full'              => $memb->display_name,
                'user_name_first'             => $memb->first_name,
                'user_name_last'              => $memb->last_name,
                'user_title'                  => '',
                'user_url'                    => '',
                'relate_institutions'         => is_array($inst) && isset($inst['name']) ? $inst['name'] : '',
                'email'                       => $memb->email,
                'user_photo'                  => $memb->profile_photo,
                'user_bio'                    => $memb->biography,
                'user_professional_attributes'=> $memb->credentials,
                'gpm_id'                      => $memb->gpm_id,
            ];
        })
        ->values()
        ->toArray();
}



    public function getProcessWireData()
    {
        $this->load('activities');

        //map process wire fields
        $processWireFields = [
            'name' => $this->affiliate_id,
            'title' => $this->title,
            'title_short' => $this->title_short,
            'title_abbreviated' => $this->title_abbreviated,
            'summary' => $this->description,
            'body_1' => $this->summary,
            'expert_panel_type' => $this->affiliate_type === 'gcep' ? [1] : [2] ,
            'affiliate_status_gene' => $this->getProcessWirePanelStatus(),
            'affiliate_status_variant' => $this->getProcessWirePanelStatus(),
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
            'relate_user_experts' => $this->getMembersByType('expert'),
            'relate_user_curators' => $this->getMembersByType(Member::CURATOR),
            'relate_user_committee' => $this->getMembersByType(Member::COMMITTEE),
            'relate_user_members' => $this->getMembersByType(Member::MEMBER),
            'relate_user_members_past' => $this->getMembersByType(Member::PAST_MEMBER),
            'metadata_search_terms' => $this->metadata_search_terms,
            'gpm_id' => $this->gpm_id
        ];

        if ($this->affiliate_type === 'gcep') {
            $processWireFields['affiliate_status_gene_date_step_1'] = $this->getActivityValue('ep_definition_approved');
            $processWireFields['affiliate_status_gene_date_step_2'] = $this->getActivityValue('ep_final_approval');
        } else {
            $processWireFields['affiliate_status_variant_date_step_1'] = $this->getActivityValue('ep_definition_approved');
            $processWireFields['affiliate_status_variant_date_step_2'] = $this->getActivityValue('vcep_draft_specifications_approved');
            $processWireFields['affiliate_status_variant_date_step_3'] = $this->getActivityValue('vcep_pilot_approved');
            $processWireFields['affiliate_status_variant_date_step_4'] = $this->getActivityValue('ep_final_approval');
        }

        return $processWireFields;
    }

    public function pushToProcessWire()
    {
        $response = $this->HttpRequest()->post($this->processWireUrl(), $this->getProcessWireData());

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
        $url = $this->processWireUrl() . '/' . $this->gpm_id;
        $response = Http::withoutVerifying()->get($url);

        $responseData = json_decode($response->body(), true);

        $memberGroups = [
            'relate_user_leaderships',
            'relate_user_coordinators',
            'relate_user_curators',
            'relate_user_committee',
            'relate_user_members',
            'relate_user_members_past',
        ];

        $gpmIds = [];

        foreach ($memberGroups as $group) {
            if ($data = data_get($responseData, $group)) {
                foreach ($data as $d) {
                    $gpmIds[] = [
                        'group' => $group,
                        'gpm_id' => $d['gpm_id']
                    ];
                }
            }
        }

        $ids = array_map(function ($id) {
            return $id['gpm_id'];
        }, $gpmIds);


        $members = $this->members->pluck('gpm_id')->toArray();

        $removedMembers = array_diff($ids, $members);

        if (count($removedMembers)) {
            $membersToRemove = array_filter($gpmIds, function ($gpmId)  use ($removedMembers) {
                return in_array($gpmId['gpm_id'], $removedMembers);
            });

            $dataToSend = [
                'action' => 'remove-members',
                'members' => $membersToRemove
            ];

            $response = $this->HttpRequest()->post($this->processWireUrl(), $dataToSend);
        }


        //$removedMembers = $this->members()->whereNotIn('')

        //forea


        if ($response->successful()) {
            $panelData = json_decode($response->body(), true);

            $this->affiliate_id = data_get($panelData, 'name');
            $this->title = data_get($panelData, 'title');
            $this->title_short = data_get($panelData, 'title_short');
            $this->title_abbreviated = data_get($panelData, 'title_abbreviated');
            $this->summary = data_get($panelData, 'body_1');
            $this->description = data_get($panelData, 'summary');
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
                        'activity' => 'ep_definition_approved'
                    ]);

                    $activity->date = Carbon::createFromTimestamp($dateTime);
                    $activity->save();
                }

                if ($dateTime = data_get($panelData, 'affiliate_status_gene_date_step_2')) {
                    //create activity for panel
                    $activity = $this->activities()->firstOrNew([
                        'activity' => 'ep_final_approval'
                    ]);

                    $activity->activity_date = Carbon::createFromTimestamp($dateTime);
                    $activity->save();
                }

                if ($dateTime = data_get($panelData, 'affiliate_status_variant_date_step_1')) {
                    //create activity for panel
                    $activity = $this->activities()->firstOrNew([
                        'activity' => 'ep_definition_approved'
                    ]);

                    $activity->activity_date = Carbon::createFromTimestamp($dateTime);
                    $activity->save();
                }

                if ($activityDate = data_get($panelData, 'affiliate_status_variant_date_step_2')) {
                    //create activity for panel
                    $activity = $this->activities()->firstOrNew([
                        'activity' => 'vcep_draft_specifications_approved'
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
                        'activity' => 'ep_final_approval'
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
        $panel = app(PanelIncrementalService::class)->syncFromKafka($data);
        if (null !== $panel) {
            Artisan::call('processwire:panels', ['panel_id' => $panel->id]);
        }

    }

    public function getUrlCspecAttribute($value)
    {
        if ($this->affiliate_type !== 'vcep') return NULL;
        if (!$this->affiliate_id) return NULL;
        return 'https://cspec.genome.network/cspec/ui/svi/affiliation/'.$this->affiliate_id;
    }

    public function getUrlClinvarAttribute($value)
    {
        if ($this->affiliate_type !== 'vcep') return null;
        if (!$this->group_clinvar_org_id) return null;
        return 'https://www.ncbi.nlm.nih.gov/clinvar/submitters/'.$this->group_clinvar_org_id;
    }

    public function getUrlCurationsAttribute($value)
    {
        if ($this->affiliate_type !== 'gcep') return null;
        if (!$this->affiliate_id) return null;
        return 'https://search.clinicalgenome.org/kb/affiliate/'.$this->affiliate_id;
    }

    public function syncFromKafka($data, $timestamp = null)
    {
        $schema = data_get($data, 'schema_version');

        $eventType = data_get($data, 'event_type');

        $this->firstOrNew([
            'gpm_id' => data_get('group.id')
        ]);

        dd($this);


//        if ($members = data_get($data, 'members')) {
//            collect($members)->each( function ($member) {
//                $memberObj = Member::firstOrNew([
//                   'gpm_id' => $member['id']
//                ]);
//
//                $memberObj->first_name = $member['first_name'];
//                $memberObj->last_name = $member['last_name'];
//                $memberObj->email = $member['email'];
//
//                $memberObj->save();
//
//                //should we create memberships;
//            });
//        }

        switch ($eventType) {

            case 'group_checkpoint_event':
                app(PanelImportService::class)->create($data);
                break;

            case 'ep_definition_approved':

                $activity = $this->activities()->firstOrNew([
                    'activity' => 'ep_definition_approved'
                ]);

                $activity->activity_date = Carbon::parse(data_get($data, 'date'));
                $activity->save();

                if ($members = data_get($data, 'data.members')) {
                    foreach ($members as $member) {
                        $memberObj = $this->validateMemberFromKafka($member);

                        if (null !== $memberObj) {

                            $role = [
                                'role' => $memberObj->panelPosition($member['group_roles']),
                                'group_roles' => json_encode($member['group_roles'])
                            ];

                            $this->members()->syncWithoutDetaching([$memberObj->id => $role]);

                        }
                    }

                }
                break;

            case 'ep_info_updated':
                if ($shortName = data_get($data, 'data.expert_panel.short_name')) {
                    $this->title_short = $shortName;
                }

                if ($longName = data_get($data, 'data.expert_panel.long_name')) {
                    $this->title = $longName;
                }

                if ($urlCspec = data_get($data, 'data.expert_panel.cspec_url')) {
                    $this->url_cspec = $urlCspec;
                }

                if ($clinvarUrl = data_get($data, 'data.expert_panel.clinvar_url')) {
                    $this->url_clinvar = $clinvarUrl ;
                }

                if ($clinvarId = data_get($data, 'data.expert_panel.clinvar_id')) {
                    $this->group_clinvar_org_id = $clinvarId;
                }

                if ($summary = data_get($data, 'data.scope_description')) {
                    $this->summary = $summary;
                }

                if ($status = data_get($data, 'active')) {
                    if ($status === 'active') {
                        $this->is_inactive = false;
                    } else {
                        $this->is_inactive = true;
                    }
                }

                $this->save();

                break;
            case 'vcep_draft_specifications_approved':
                //
                $activity = $this->activities()->firstOrNew([
                    'activity' => 'vcep_draft_specifications_approved'
                ]);

                $activity->activity_date = Carbon::parse(data_get($data, 'date'));
                $activity->save();
                break;

            case 'vcep_pilot_approved':

                $activity = $this->activities()->firstOrNew([
                    'activity' => 'vcep_pilot_approved'
                ]);

                $activity->activity_date = Carbon::parse(data_get($data, 'date'));
                $activity->save();

                break;

            case 'group_description_updated':
                if ($status = data_get($data, 'data.new_status')) {
                    $this->summary = $status;
                    $this->save();
                }
            case 'ep_final_approval':

                $activity = $this->activities()->firstOrNew([
                    'activity' => 'ep_final_approval'
                ]);

                $activity->activity_date = Carbon::parse(data_get($data, 'date'));
                $activity->save();
                break;
            //
            case 'member_removed':
                //
                if ($members = data_get($data, 'data.members')) {
                    foreach ($members as $member) {
                        $memberObj = $this->validateMemberFromKafka($member);

                        if (null !== $memberObj) {
                            $r = $this->members()->detach($memberObj->id);
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

                            $this->members()->detach([$memberObj->id, $role]);

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
                                'role' => $memberObj->panelPosition($member['group_roles']),
                                'group_roles' => json_encode($member['group_roles'])
                            ];

                            $this->members()->syncWithoutDetaching([$memberObj->id => $role]);

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

    public function findOrCreateWorkingGroup($data)
    {
        $panel = Panel::firstOrNew([
            'gpm_id' => $data['uuid']
        ]);

        $panel->wg_status = $data['status'];
        $panel->name = $data['name'];
        $panel->title = $data['name'];
        $panel->summary = $data['description'];

        $panel->save();

        return $panel;
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

    public function getDataFromProcessWire()
    {
        //$response = Http::get()
    }

    public function getPanelMembership($roles)
    {
        if (in_array('leader', $roles)) return 'leader';
        if (in_array('biocurator', $roles)) return 'curator';
        return 'member';
    }

    public function getProcessWirePanelStatus()
    {
        $values = [
            'gcep' => [
                1 => 'ep_definition_approved',
                2 => 'ep_final_approval'
            ],
            'vcep' => [
                1 => 'ep_definition_approved',
                2 => 'vcep_draft_specifications_approved',
                3 => 'vcep_pilot_approved',
                4 => 'ep_final_approval'
            ]
        ];
        $activities = $this->activities;
        $activityValues = $values[$this->affiliate_type];

        $status = 1;

        foreach ($activityValues as $index => $value) {
            $activity = $this->activities->where('activity', $value)->first();
            if (null !== $activity) $status = $index;
        }

        return $status;
    }

}
