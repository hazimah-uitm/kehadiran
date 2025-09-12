<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\File;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10);

        $activities = Activity::latest()->paginate($perPage);

        return view('pages.activity-log.index', [
            'activities' => $activities,
            'perPage' => $perPage,
        ]);
    }

    public function showDebugLogs()
    {
        $logFilePath = storage_path('logs/laravel.log');
        
        if (File::exists($logFilePath)) {
            $logs = File::get($logFilePath);
            $logEntries = explode(PHP_EOL, $logs);

            // Filter for debug entries
            $debugLogs = array_filter($logEntries, function($logEntry) {
                return strpos($logEntry, 'local.DEBUG') !== false;
            });
        } else {
            $debugLogs = ['Log file not found.'];
        }

        return view('pages.activity-log.debug', ['logs' => $debugLogs]);
    }
}
