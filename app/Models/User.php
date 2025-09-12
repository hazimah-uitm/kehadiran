<?php

namespace App\Models;

use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, LogsActivity, HasRoles, SoftDeletes;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'staff_id', 
        'email', 
        'password',
        'position_id',
        'campus_id',
        'office_phone_no',
        'publish_status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function getPublishStatusAttribute()
    {
        return $this->attributes['publish_status'] ? 'Aktif' : 'Tidak Aktif';
    }

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }
}
