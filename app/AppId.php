<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppId extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_app_string', 'user_id',
    ];

    protected $table = "app_id";
}
