<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Participant;
use App\Models\Program;
use App\Models\Session;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function public($sessionId)
    {
        $session = Session::where('publish_status', 1)->findOrFail($sessionId);

        // Return view borang kehadiran awam
        return view('pages.public.attendance.checkin', compact('session'));
    }

    public function storePublic(Request $request, $sessionId)
    {
        $request->validate([
            'participant_code' => 'required|string|max:191',
        ], [
            'participant_code.required' => 'Please enter participant code or scan the QR code.',
        ]);

        $session = Session::with('program')
            ->where('publish_status', 1)
            ->findOrFail($sessionId);

        if ((int) ($session->program->publish_status ?? 0) !== 1) {
            return back()->with('error', 'This program is not yet published.');
        }

        $code = trim($request->input('participant_code'));

        $participant = Participant::where('program_id', $session->program->id)
            ->where(function ($q) use ($code) {
                $q->where('participant_code', $code)
                    ->orWhere('ic_passport', $code);
            })
            ->first();

        if (!$participant) {
            $registerUrl = route('public.participant.create', $session->program->id);
            return back()->withErrors([
                'participant_code' => 'Invalid participant code for this program. Please contact the organising secretariat or register here: ' . $registerUrl
            ])->withInput();
        }

        $attendance = Attendance::firstOrCreate(
            [
                'program_id'     => $session->program->id,
                'session_id'     => $session->id,
                'participant_id' => $participant->id,
            ],
            [
                'participant_code' => $participant->participant_code,
            ]
        );

        if ($attendance->wasRecentlyCreated) {
            return back()->with('success', 'Thank you. Your attendance has been recorded.');
        }

        return back()->with('info', 'You have already checked in.');
    }


    public function indexProgram(Program $program, Request $request)
    {
        $perPage = $request->input('perPage', 20);
        $attendances = Attendance::with('participant')
            ->where('program_id', $program->id)
            ->whereNull('session_id')
            ->latest('created_at')
            ->paginate($perPage);

        return view('pages.attendance.index', compact('program', 'attendances') + ['session' => null, 'perPage' => $perPage]);
    }

    public function indexSession(Program $program, Session $session, Request $request)
    {
        abort_unless($session->program_id === $program->id, 404);
        $perPage = $request->input('perPage', 20);
        $attendances = Attendance::with('participant')
            ->where('program_id', $program->id)
            ->where('session_id', $session->id)
            ->latest('created_at')
            ->paginate($perPage);

        return view('pages.attendance.index', compact('program', 'session', 'attendances', 'perPage'));
    }

    public function searchProgram(Program $program, Request $request)
    {
        $perPage = $request->input('perPage', 20);
        $search  = $request->input('search');

        $q = Attendance::with('participant')
            ->where('program_id', $program->id)
            ->whereNull('session_id');

        if ($search) {
            $q->whereHas('participant', function ($qq) use ($search) {
                $qq->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('participant_code', 'LIKE', "%{$search}%")
                    ->orWhere('ic_passport', 'LIKE', "%{$search}%");
            })->orWhere('participant_code', 'LIKE', "%{$search}%");
        }

        $attendances = $q->latest('created_at')->paginate($perPage);

        return view('pages.attendance.index', compact('program', 'attendances') + ['session' => null, 'perPage' => $perPage]);
    }

    public function searchSession(Program $program, Session $session, Request $request)
    {
        abort_unless($session->program_id === $program->id, 404);
        $perPage = $request->input('perPage', 20);
        $search  = $request->input('search');

        $q = Attendance::with('participant')
            ->where('program_id', $program->id)
            ->where('session_id', $session->id);

        if ($search) {
            $q->whereHas('participant', function ($qq) use ($search) {
                $qq->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('participant_code', 'LIKE', "%{$search}%")
                    ->orWhere('ic_passport', 'LIKE', "%{$search}%");
            })->orWhere('participant_code', 'LIKE', "%{$search}%");
        }

        $attendances = $q->latest('created_at')->paginate($perPage);

        return view('pages.attendance.index', compact('program', 'session', 'attendances', 'perPage'));
    }

    // Borang attendance (by program / sesi)
    public function createProgram(Program $program)
    {
        return view('pages.public.attendance.checkin', [
            'program'   => $program,
            'session'   => null,
            'postRoute' => route('attendance.store.program', $program->id),
        ]);
    }

    public function createSession(Program $program, Session $session)
    {
        abort_unless($session->program_id === $program->id, 404);

        return view('pages.public.attendance.checkin', [
            'program'   => $program,
            'session'   => $session,
            'postRoute' => route('attendance.store.session', [$program->id, $session->id]),
        ]);
    }

    // Simpan attendance (by program)
    public function storeProgram(Request $request, Program $program)
    {
        $request->validate(
            ['participant_code' => 'required'],
            ['participant_code.required' => 'Please enter participant code or scan the QR code.']
        );

        $code = trim($request->participant_code);

        $participant = Participant::where('program_id', $program->id)
            ->where(function ($q) use ($code) {
                $q->where('participant_code', $code)
                    ->orWhere('ic_passport', $code);
            })
            ->first();

        if (!$participant) {
            $registerUrl = route('public.participant.create', $program->id);
            $checkUrl = route('public.participant.check', $program->id);

            return back()
                ->withErrors(['participant_code' => 'Registration not found for this program.'])
                ->with('error', "
    Invalid Participant Code. <div class='text-muted'>
        Please ensure you enter your <strong>Participant Code</strong>, not your IC/Passport number:
        <ul class='mb-0'>
            <li><a href=\"{$checkUrl}\" target=\"_blank\">Check your Participant Code here</a> if you already registered.</li>
            <li><a href=\"{$registerUrl}\" target=\"_blank\">Register here</a> if you have not registered.</li>
        </ul>
    </div>
")
                ->withInput();
        }

        $attendance = Attendance::firstOrCreate(
            [
                'program_id'     => $program->id,
                'session_id'     => null,
                'participant_id' => $participant->id,
            ],
            [
                'participant_code' => $participant->participant_code,
            ]
        );

        if ($attendance->wasRecentlyCreated) {
            return back()->with('success', 'Thank you. Your attendance has been recorded.');
        }

        return back()->with('info', 'You have already checked in.');
    }


    // Simpan attendance (by session)
    public function storeSession(Request $request, Program $program, Session $session)
    {
        abort_unless($session->program_id === $program->id, 404);

        $request->validate(
            ['participant_code' => 'required'],
            ['participant_code.required' => 'Please enter participant code or scan the QR code.']
        );

        $code = trim($request->participant_code);

        $participant = Participant::where('program_id', $program->id)
            ->where(function ($q) use ($code) {
                $q->where('participant_code', $code)
                    ->orWhere('ic_passport', $code);
            })
            ->first();

        if (!$participant) {
            $registerUrl = route('public.participant.create', $program->id);
            $checkUrl = route('public.participant.check', $program->id);

            return back()
                ->withErrors(['participant_code' => 'Registration not found for this program.'])
                ->with('error', "
    Invalid Participant Code. <div class='text-muted'>
        Please ensure you enter your <strong>Participant Code</strong>, not your IC/Passport number:
        <ul class='mb-0'>
            <li><a href=\"{$checkUrl}\" target=\"_blank\">Check your Participant Code here</a> if you already registered.</li>
            <li><a href=\"{$registerUrl}\" target=\"_blank\">Register here</a> if you have not registered.</li>
        </ul>
    </div>
")
                ->withInput();
        }

        $attendance = Attendance::firstOrCreate(
            [
                'program_id'     => $program->id,
                'session_id'     => $session->id,
                'participant_id' => $participant->id,
            ],
            [
                'participant_code' => $participant->participant_code,
            ]
        );

        if ($attendance->wasRecentlyCreated) {
            return back()->with('success', 'Thank you. Your attendance has been recorded.');
        }

        return back()->with('info', 'You have already checked in.');
    }
}
