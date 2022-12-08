<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormEvent extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'form_id', 'user_id', 'type', 'message',
    ];

    protected $table = "form_events";
}
