<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubActivityActuals extends Model
{
    protected $table = 'subactivityactuals';
    public $timestamps = true;
    protected $fillable = ['sub_id', 'week', 'project_id', 'user_id', 'task_hour', 'year'];

}
