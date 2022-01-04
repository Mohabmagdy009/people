<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubActivityTypes extends Model
{
    protected $table = 'SubActivityTypes';
    public $timestamps = true;
    protected $fillable = ['name', 'type'];
}
