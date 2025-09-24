<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Participant;
use App\Models\Program;
use App\Models\Session;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

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
    Invalid Participant Code. <div class='text-dark'>
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

    private function sanitizeText($str, $max = 50)
    {
        $s = preg_replace('/[\r\n\t]+/', ' ', (string)$str);
        $s = trim($s);
        if (mb_strlen($s, 'UTF-8') > $max) {
            $s = mb_substr($s, 0, $max - 3, 'UTF-8') . '...';
        }
        return $s ?: '-';
    }

    public function exportProgram(Program $program, Request $request)
    {
        $search = $request->input('search');

        $q = Attendance::with('participant')
            ->where('program_id', $program->id)
            ->whereNull('session_id')
            ->orderBy('id', 'asc');

        if ($search) {
            $q->where(function ($sub) use ($search) {
                $sub->whereHas('participant', function ($qq) use ($search) {
                    $qq->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('ic_passport', 'LIKE', "%{$search}%")
                        ->orWhere('phone_no', 'LIKE', "%{$search}%")
                        ->orWhere('institution', 'LIKE', "%{$search}%")
                        ->orWhere('participant_code', 'LIKE', "%{$search}%");
                })->orWhere('participant_code', 'LIKE', "%{$search}%");
            });
        }

        $rows = $q->get();

        // Header + data
        $export = [];
        $export[] = [
            'Bil',
            'Nama Peserta',
            'Kod Peserta',
            'IC/Passport',
            'Telefon',
            'Institusi',
            'Direkod Pada',
        ];

        $i = 1;
        foreach ($rows as $r) {
            $p = $r->participant;
            $export[] = [
                $i++,
                $this->sanitizeText($p->name ?? '-'),
                $p->participant_code ?? ($r->participant_code ?? '-'),
                $this->sanitizeText($p->ic_passport ?? '-'),
                $this->sanitizeText($p->phone_no ?? '-'),
                $this->sanitizeText($p->institution ?? '-'),
                $r->created_at ? Carbon::parse($r->created_at)->format('d/m/Y H:i') : '-',
            ];
        }

        $progCode = preg_replace('/[^A-Za-z0-9]/', '', (string)($program->program_code ?: 'PRG')) ?: 'PRG';
        $dateStr  = Carbon::now()->format('Ymd_His');
        $filename = "{$progCode}_attendance_program_{$dateStr}";

        return Excel::create($filename, function ($excel) use ($export, $program) {
            $excel->setTitle('Kehadiran Program: ' . $program->title);
            $excel->sheet('Kehadiran', function ($sheet) use ($export) {
                $sheet->fromArray($export, null, 'A1', false, false);
                $sheet->row(1, function ($row) {
                    $row->setFontWeight('bold');
                });
                $sheet->setAutoSize(true);
                $lastCol = chr(ord('A') + count($export[0]) - 1);
                $sheet->setAutoFilter("A1:{$lastCol}1");
            });
        })->download('xlsx');
    }

    public function exportSession(Program $program, Session $session, Request $request)
    {
        abort_unless($session->program_id === $program->id, 404);

        $search = $request->input('search');

        $q = Attendance::with('participant')
            ->where('program_id', $program->id)
            ->where('session_id', $session->id)
            ->orderBy('id', 'asc');

        if ($search) {
            $q->where(function ($sub) use ($search) {
                $sub->whereHas('participant', function ($qq) use ($search) {
                    $qq->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('ic_passport', 'LIKE', "%{$search}%")
                        ->orWhere('phone_no', 'LIKE', "%{$search}%")
                        ->orWhere('institution', 'LIKE', "%{$search}%");
                })->orWhere('participant_code', 'LIKE', "%{$search}%");
            });
        }

        $rows = $q->get();

        $export = [];
        $export[] = [
            'No.',
            'Session',
            'Session Venue',
            'Name',
            'IC/Passport No.',
            'Staff/Student ID (UiTM)',
            'Phone No.',
            'Nationality',
            'Institution/Organization',
            'Recorded at',
        ];

        $i = 1;
        foreach ($rows as $r) {
            $p = $r->participant;
            $export[] = [
                $i++,
                $this->sanitizeText($session->title ?? '-'),
                $this->sanitizeText($session->venue ?? '-'),
                $this->sanitizeText($p->name ?? '-'),
                $this->sanitizeText($p->ic_passport ?? '-'),
                $this->sanitizeText($p->student_staff_id ?? '-'),
                $this->sanitizeText($p->phone_no ?? '-'),
                $this->sanitizeText($p->nationality ?? '-'),
                $this->sanitizeText($p->institution ?? '-'),
                $r->created_at ? Carbon::parse($r->created_at)->format('d/m/Y H:i') : '-',
            ];
        }

        $progCode = preg_replace('/[^A-Za-z0-9]/', '', (string)($program->program_code ?: 'PRG')) ?: 'PRG';
        $sessCode = preg_replace('/[^A-Za-z0-9]/', '', (string)($session->id));
        $dateStr  = Carbon::now()->format('Ymd_His');
        $filename = "{$progCode}_attendance_session{$sessCode}_{$dateStr}";

        return Excel::create($filename, function ($excel) use ($export, $program, $session) {
            $excel->setTitle('Attendance Session: ' . $session->title . ' (' . $program->title . ')');
            $excel->sheet('Attendance Session', function ($sheet) use ($export) {
                $sheet->fromArray($export, null, 'A1', false, false);
                $sheet->row(1, function ($row) {
                    $row->setFontWeight('bold');
                });
                $sheet->setAutoSize(true);
                $lastCol = chr(ord('A') + count($export[0]) - 1);
                $sheet->setAutoFilter("A1:{$lastCol}1");
            });
        })->download('xlsx');
    }
}
