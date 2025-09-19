<?php

namespace App\Models;

use App\Models\Participant;
use App\Models\Program;
use App\Models\Session;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Attendance extends Model
{
    use LogsActivity;
    use SoftDeletes;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected $fillable = [
        'program_id',
        'session_id',
        'participant_id',
        'participant_code',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }
    public function session()
    {
        return $this->belongsTo(Session::class);
    }
    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }
}
