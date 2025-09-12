<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Position extends Model
{
    use LogsActivity;
    use SoftDeletes;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected $fillable = [
        'title',
        'grade',
        'publish_status',
    ];

    public function getPublishStatusAttribute()
    {
        return $this->attributes['publish_status'] ? 'Aktif' : 'Tidak Aktif';
    }

    public function user()
    {
        return $this->hasMany(User::class);
    }
}
