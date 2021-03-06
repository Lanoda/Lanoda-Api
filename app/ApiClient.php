<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApiClient extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['client_id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['client_secret'];
}
