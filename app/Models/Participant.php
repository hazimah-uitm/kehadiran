<?php

namespace App\Models;

use App\Models\Program;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Participant extends Model
{
    use LogsActivity;
    use SoftDeletes;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected $fillable = [
        'program_id',
        'name',
        'ic_passport',
        'student_staff_id',
        'nationality',
        'phone_no',
        'institution',
        'participant_code',
        'qr_path',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    // public function attendances()
    // {
    //     return $this->hasMany(Attendance::class);
    // }
}
