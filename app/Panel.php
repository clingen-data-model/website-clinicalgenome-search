<?php

namespace App;

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
          'description' => 'string',
          'clinvar_org_id' => 'string',
          'url_clinvar' => 'string',
          'url_spec' => 'string',
          'url_curations' => 'string',
          'url_erepo' => 'string',
          'expert_panel_status' => 'string',
          'status_define_group_date' => 'string',
          'status_class_rules_date' => 'string',
          'status_pilot_rules_date' => 'string',
          'status_approval_date' => 'string',
          'status_inactive_date' => 'string',
          'is_inactive' => 'boolean'
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
                           'summary', 'type', 'status', 'description', 'clinvar_org_id',
                            'url_clinvar', 'url_spec', 'url_curations', 'url_erepo', 'expert_panel_status',
                            'status_define_group_date', 'status_class_rules_date', 'status_pilot_rules_date',
                            'status_approval_date', 'status_inactive_date', 'is_inactive'];

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

    public function members()
    {
        return $this->belongsToMany(Member::class);
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

    public function pushToProcessWire()
    {
        //get the full data of the panel ...
        $data = [
            'title' => $this->title,
            'title_short' => $this->title_short,
            'title_abbreviated' => $this->title_abbreviated,
            'summary' => $this->description,
            'body_1' => $this->summary,
            'repeater_callout_rich_media_3' => [],
            'expert_panel_type' =>  $this->type,
            'affiliate_status_gene' => $this->type === self::TYPE_GCEP ? $this->expert_panel_status : '',
            'affiliate_status_gene_date_step_1' => $this->type === self::TYPE_GCEP ? $this->status_define_group_date : null,
            'affiliate_status_gene_date_step_2' => $this->type === self::TYPE_GCEP ? $this->status_approval_date : null,
            'affiliate_status_variant' => $this->type === self::TYPE_VCEP ? $this->expert_panel_status : '',
            'affiliate_status_variant_date_step_1' => $this->type === self::TYPE_VCEP ? $this->status_define_group_date : null,
            'affiliate_status_variant_date_step_2' => $this->type === self::TYPE_VCEP ? $this->status_class_group_date : null,
            'affiliate_status_variant_date_step_3' => $this->type === self::TYPE_VCEP ? $this->status_pilot_group_date : null,
            'affiliate_status_variant_date_step_4' => $this->type === self::TYPE_VCEP ? $this->status_approval_date : null,
            'ep_status_inactive' => $this->is_inactive,
            'ep_status_inactive_date' => $this->status_inactive_date,
            'group_clinvar_org_id' => $this->clinvar_org_id,
            'url_clinvar' => $this->url_clinvar,
            'url_cspec' => $this->url_spec,
            'url_curations' => $this->url_curations,
            'url_erepo' => $this->url_erepo,
            'relate_cdwg' => $this->cdwg_parent_name,
            'relate_user_leaderships' => [],
            'relate_user_coordinators' => [],
            'relate_user_curators' => [],
            'relate_user_committee' => [],
            'relate_user_members' => [],
            'relate_user_members_past' => [],
            'metadata_search_terms' => ''
        ];


        $url = sprintf('%s/%s', config('processwire.url'), $this->affiliate_id);
        echo $url;

        $response = Http::withoutVerifying()->asForm()->post($url, $data);
        dd($response->body());
    }
}
