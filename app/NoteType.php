<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NoteType extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'icon',
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

    public function notes() 
    {
        return $this->hasMany('App\Note');
    }
}
