<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname', 
        'middlename', 
        'lastname',
        'phone',
        'email',
        'address',
        'age',
        'birthday',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    protected $with = array('user');

    public function user() 
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
