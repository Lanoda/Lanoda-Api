<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'contact_id',
        'type_id',
        'title', 
        'body', 
        'created_at',
        'updated_at',
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
    protected $with = ['user', 'notetype', 'contact'];

    public function user() 
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function contact()
    {
        return $this->belongsTo('App\Contact', 'contact_id');
    }

    public function notetype()
    {
        return $this->belongsTo('App\NoteType', 'type_id');
    }
}
