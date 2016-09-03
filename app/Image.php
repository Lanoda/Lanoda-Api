<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'url', 
        'mime_type', 
        'size',
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

    public function users() 
    {
        return $this->hasMany('App\User');
    }

    public function contacts()
    {
        return $this->hasMany('App\Contact');
    }
}
