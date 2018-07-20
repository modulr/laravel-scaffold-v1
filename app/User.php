<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Storage;
use Laratrust\Traits\LaratrustUserTrait;
use App\Models\Events\Reservation;

class User extends Authenticatable
{
    use LaratrustUserTrait;
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

    protected $appends = ['avatar_url', 'hasPermission'];

    public function getAvatarUrlAttribute()
    {
        return Storage::url('avatars/'.$this->id.'/'.$this->avatar);
    }

    public function getHasPermissionAttribute()
    {
        $permissions = [];
        foreach (Permission::all() as $permission) {
            if ($this->can($permission->name)) {
                $permissions[$permission->name] = true;
            } else {
                $permissions[$permission->name] = false;
            }
        }
        return $permissions;
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
        return $this->hasMany(\App\Models\News\News::class);
    }

    public function tasks()
    {
        return $this->hasMany(\App\Models\Tasks\Task::class);
    }

    public function ownEvents()
    {
        return $this->hasMany(\App\Models\Events\Event::class, 'owner_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
