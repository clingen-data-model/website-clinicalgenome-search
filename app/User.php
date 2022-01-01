<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Display;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, Notifiable, SoftDeletes;
    use Display;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'lastname', 'firstname', 'email', 'password', 'avatar', 'credentials',
        'organization', 'profile', 'preferences', 'api_token', 'device_token',
        'activation_token', 'role', 'status', 'type'
    ];

    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'activation_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'preferences' => 'array',
        'profile' => 'array',
    ];

    public const STATUS_INITIALIZED = 0;
    public const STATUS_ACTIVE = 1;


    /*
     * The genes that this user is following
     */
    public function genes()
    {
       return $this->belongsToMany('App\Gene');
    }


    /*
     * The groups that this user is following
     */
    public function groups()
    {
       return $this->belongsToMany('App\Group');
    }


    /*
     * The groups that this user is following
     */
    public function owngroups()
    {
       return $this->hasMany('App\Group');
    }


    /*
     * The panels that this user is following
     */
    public function panels()
    {
       return $this->belongsToMany('App\Panel');
    }

    /*
     * The notification preferences for this user
     */
    public function notification()
    {
       return $this->hasOne('App\Notification');
    }


    /*
     * The reports owned by this user
     */
    public function titles()
    {
       return $this->hasMany('App\Title');
    }


    /*
     * The filters owned by this user
     */
    public function filters()
    {
       return $this->hasMany('App\Filter');
    }



    /**
     * Adjust full name when first name is changed
     *
     * @param  string  $value
     * @return void
     */
    public function setFirstNameAttribute($value)
    {
        $this->attributes['firstname'] = $value;
        $this->attributes['name'] = $this->attributes['firstname'];

        if (isset($this->attributes['lastname']))
            $this->attributes['name'] .= " " . $this->attributes['lastname'];
    }


    /**
     * Adjust full name when last name is changed
     *
     * @param  string  $value
     * @return void
     */
    public function setLastNameAttribute($value)
    {
        $this->attributes['lastname'] = $value;
        $this->attributes['name'] = $this->attributes['firstname'] . " " . $this->attributes['lastname'];
    }


    /**
     * Query scope by device cookie
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeCookie($query, $cookie)
    {
       return $query->where('device_token', $cookie);
    }


    /**
     * Query scope by device cookie
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeEmail($query, $email)
    {
       return $query->where('email', $email);
    }


    /**
     * Add an interest item to the profile
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function addInterest($item)
    {
        if ($this->profile === null)
        {
            $this->profile = ['interests' => [$item]];
            return true;
        }

        if (!isset($this->profile['interests']))
        {
            $this->profile['interests'] = [$item];
            return true;
        }

        if (!in_array($item, $this->profile['interests']))
        {
            $profile = $this->profile;
            array_push($profile['interests'], $item);
            $this->profile = $profile;
        }

        return true;
    }


    /**
     * remove an interest item from the profile
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function removeInterest($item)
    {
        if ($this->profile === null)
            return true;

        if (!isset($this->profile['interests']))
            return true;

        if (!in_array($item, $this->profile['interests']))
            return true;

        $profile = $this->profile;
        if (($key = array_search($item, $profile['interests'])) !== false)
             unset($profile['interests'][$key]);
        $profile['interests'] = array_values($profile['interests']);
        $this->profile = $profile;

        return true;
    }


    /**
     * Add a new group for this user
     */
    public function addGroup($name)
    {
        $group = Group::name($name)->first();

        if ($group === null)
            return false;

        $this->groups()->attach($group->id);

        return true;
    }


    /**
     * Remova a group for this user
     */
    public function removeGroup($name)
    {
        $group = Group::name($name)->first();

        if ($group === null)
            return false;

        $this->groups()->detach($group->id);

        return true;
    }
}
