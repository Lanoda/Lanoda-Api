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
        'image_id',
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

    /**
     * The related objects that should be loaded as well.
     *
     * @var array
     */
    protected $with = [];

    public function image()
    {
        return $this->belongsTo('App\Image', 'image_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function notes()
    {
        return $this->hasMany('App\Note');
    }
}
