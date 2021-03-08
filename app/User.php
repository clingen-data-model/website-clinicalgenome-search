<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

use App\Traits\Display;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
    use Display;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'lastname', 'firstname', 'email', 'password', 'avatar', 'credentials',
        'organization', 'profile', 'preferences', 'api_token', 'device_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
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


    /*
     * The genes that this user is following
     */
    public function genes()
    {
       return $this->belongsToMany('App\Gene');
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
}
