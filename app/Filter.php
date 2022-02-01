<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Display;

use Uuid;
use Cookie;

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
class Filter extends Model
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
          'display_name' => 'string|nullable',
          'screen' => 'integer',
          'screen_name' => 'string|nullable',
          'description' => 'string|nullable',
          'settings' => 'json',
          'default' => 'integer',
          'type' => 'integer',
          'status' => 'integer'
	];

	/**
     * Map the json attributes to associative arrays.
     *
     * @var array
     */
	protected $casts = [
            'settings' => 'array'
		];

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['ident', 'name', 'display_name', 'screen', 'screen_name', 'description',
                            'settings', 'default', 'type', 'status'];

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
    public const STATUS_ACTIVE = 1;

     /*
     * Status strings for display methods
     *
     * */
     protected $status_strings = [
            0 => 'Initialized',
            1 => 'Active',
	 		9 => 'Deleted'
    ];

    public const SCREEN_CURATED_GENES = 1;
    public const SCREEN_VALIDITY_CURATIONS = 2;
    public const SCREEN_VALIDITY_EPS = 3;
    public const SCREEN_VALIDITY_EP_CURATIONS = 4;
    public const SCREEN_DOSAGE_CURATIONS = 5;
    public const SCREEN_DOSAGE_CNVS = 6;
    public const SCREEN_DOSAGE_REGION_SEARCH = 7;
    public const SCREEN_DOSAGE_REGION_REFRESH = 8;
    public const SCREEN_ALL_GENES = 9;
    public const SCREEN_ALL_DISEASES = 10;
    public const SCREEN_ALL_DRUGS = 11;
    public const SCREEN_ACTIONABILITY_CURATIONS = 12;

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
     * The owner of this filter
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
     * Query scope by symbol name
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeName($query, $name)
    {
        return $query->where('name', $name);
    }


    /**
     * Query scope by screen id
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeScreen($query, $id)
    {
        return $query->where('screen', $id);
    }


     /**
     * Query scope by default
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeDefault($query)
    {
        return $query->where('default', 1);
    }

    /**
     * Return a formatted url from settings
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function getcookieAttribute()
    {
        if ($this->settings === null)
            return '';

        return $this->screen . '%%' . $this->ident;
    }

    /**
     * Return a formatted url from settings
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function getUrlAttribute()
    {
        if ($this->settings === null)
            return '';

        return http_build_query($this->settings);
    }


    /**
     * Return parameter array from url
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public static function parseSettings($url)
    {
        $parts = parse_url($url);

        if (empty($parts['query']))
            return [];

        parse_str($parts['query'], $settings);

        return $settings;
    }


    /**
     * Return parameter array from url
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function isUrl($url)
    {
        if (empty($url))
            return false;

        $url_settings = self::parseSettings($url);

        if (empty($url_settings))
            return false;

        $t_settings = $this->getUrlAttribute();

        if (empty($t_settings))
            return false;

        parse_str($t_settings, $my_settings);

        // don't check the items not provided
        foreach (['page', 'size', 'sort', 'order', 'col_search', 'col_search_val'] as $key)
        {
            if (!isset($url_settings[$key]) && isset($my_settings[$key]))
                return false;

            if (isset($url_settings[$key]) && !isset($my_settings[$key]))
                return false;

            if (!isset($url_settings[$key]) && !isset($my_settings[$key]))
                continue;

            if ($url_settings[$key] != $my_settings[$key])
                return false;
        }

        // only check tracked parts for now
        return true;
    }


    /**
     * Get current filter ident for screen
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public static function getBookmark($request, $user, $scrid)
    {
        if (empty($scrid))
            return null;

        $cookie = $request->cookie('clingen_preferences');
        if ($cookie === null)
            return null;

        $screens = explode(';;', $cookie);
        if (empty($screens))
            return null;

        foreach ($screens as $screen)
        {
            if (strpos($screen, $scrid . '%%') == 0)
            {
                $cookie = explode('%%', $screen);

                if ($user !== null && $cookie[0] == $scrid && isset($cookie[1]))
                    return $user->filters()->ident($cookie[1])->first();
            }
        }

        return null;
    }


    /**
     * Put current bookmark id for screen
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public static function setBookmark($request, $scrid, $filter)
    {
        if (empty($scrid))
            return false;

        $cookie = $request->cookie('clingen_preferences');

        if ($cookie === null && $filter !== null)
        {
            Cookie::queue('clingen_preferences',$filter->cookie, 0);
            return true;
        }

        $screens = explode(';;', $cookie);
        if (empty($screens) && $filter !== null)
        {
            Cookie::queue('clingen_preferences',$filter->cookie, 0);
            return true;
        }

        $newcookie = [];
        $found = false;

        foreach ($screens as $screen)
        {
            if (strpos($screen, $scrid . '%%') === 0)
            {
                $found = true;
                if ($filter !== null)
                    $newcookie[] = $filter->cookie;
            }
            else
                $newcookie[] = $screen;
        }

        if (!$found)
        {
            $newcookie[] = $filter->cookie;
        }

        Cookie::queue('clingen_preferences',implode(';;', $newcookie), 0);

        return true;
    }


    public static function preferences($request, $user, $screen)
    {
        // Is user logged in and are there query params?
        $applicable = ($user !== null && $request->getPathInfo() == $request->getRequestUri());

        $filter = self::getBookmark($request, $user, $screen);

        // first see if there is a current bookmark for the page
        /*$cookie = $request->cookie('clingen_preferences');
        //dd($cookie);

        if ($cookie !== null)
        {
            $cookie = explode('%%', $cookie);

            if ($cookie[0] == $screen && isset($cookie[1]))
                $filter = $user->filters()->ident($cookie[1])->first();
        }*/

        if ($applicable)
        {
            // if no current, check if there is a page default
            if ($filter === null)
                $filter = $user->filters()->screen($screen)->default()->first();

            // set found filter to to the current page
            if ($filter !== null)
            {
                self::setBookmark($request, $screen, $filter);
                //Cookie::queue('clingen_preferences',$filter->cookie, 0);
                return redirect($request->url() . '?' . $filter->url);
            }
        }
        else
        {
            // if there is a current page, is it still the same?
            if ($filter !== null)
            {
                if ($filter->isUrl($request->fullUrl()) === false)
                    self::setBookmark($request, $screen, null);
                    //Cookie::queue('clingen_preferences','', 0);
            }
        }

        return $filter;
    }
}
