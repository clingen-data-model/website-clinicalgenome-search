<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'api_token', 'device_token'
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
