<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Actuals extends Model
{
    protected $table = 'activities_actual';
    public $timestamps = true;
    protected $fillable = ['year', 'week', 'project_id', 'user_id', 'actuals'];
}
