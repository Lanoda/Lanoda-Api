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
        'user_id',
        'url_name',
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
    protected $hidden = ['url_name'];

    /**
     * The related objects that should be loaded as well.
     *
     * @var array
     */
    protected $with = ['user'];

    public function user() 
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
