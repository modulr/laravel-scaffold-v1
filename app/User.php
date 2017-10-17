<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Storage;

class User extends Authenticatable
{
    use Notifiable;

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar', 'active', 'group_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $appends = ['avatar_url'];

    public function getAvatarUrlAttribute()
    {
        return Storage::url('avatars/'.$this->id.'/'.$this->avatar);
    }

    public function profilePersonal()
    {
        return $this->hasOne(\App\Models\Profile\ProfilePersonal::class);
    }

    public function profileContact()
    {
        return $this->hasMany(\App\Models\Profile\ProfileContact::class);
    }

    public function profileEducation()
    {
        return $this->hasMany(\App\Models\Profile\ProfileEducation::class);
    }

    public function profileFamily()
    {
        return $this->hasMany(\App\Models\Profile\ProfileFamily::class);
    }

    public function profilePlace()
    {
        return $this->hasMany(\App\Models\Profile\ProfilePlace::class);
    }

    public function profileWork()
    {
        return $this->hasOne(\App\Models\Profile\ProfileWork::class);
    }

    public function news()
    {
        return $this->hasMany(News::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function group()
    {
        return $this->belongsTo(\App\Models\Users\Group::class);
    }

    public function project()
    {
      return $this->hasMany(Project::class);
    }

    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }
}
