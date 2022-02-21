<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;
    use HasApiTokens;

    protected $table = 'users';
    protected $casts = ["last_login" => "datetime"];
    public $timestamps = true;
    protected $guarded = ['id'];
    protected $hidden = [
        'remember_token', 'password',
      ];

    public function activities()
    {
        return $this->hasMany(\App\Activity::class, 'user_id');
    }

    public function managers()
    {
        return $this->belongsToMany(self::class, 'users_users', 'user_id', 'manager_id')->withPivot('manager_type')->withTimestamps();
    }

    public function employees()
    {
        return $this->belongsToMany(self::class, 'users_users', 'manager_id', 'user_id')->withPivot('manager_type')->withTimestamps();
    }

    public function projects()
    {
        return $this->hasMany(\App\Project::class, 'created_by_user_id');
    }

    public function update_password($password, $toDB = false)
    {
        $this->password = Hash::make($password);
        if ($toDB) {
            $this->save();
        }

        return $this;
    }

    public function clusters()
    {
        return $this->hasMany(\App\Cluster::class);
    }

    public function comments()
    {
        return $this->hasMany(\App\Comment::class);
    }
}
