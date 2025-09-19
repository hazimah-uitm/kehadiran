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
            'participant_code.required' => 'Sila masukkan Kod Peserta atau No. IC',
        ]);

        $session = Session::with('program')
            ->where('publish_status', 1)
            ->findOrFail($sessionId);

        if ((int) ($session->program->publish_status ?? 0) !== 1) {
            return back()->with('error', 'Program belum diterbitkan.');
        }

        $code = trim($request->input('participant_code'));

        // Benarkan cari ikut participant_code ATAU ic_passport
        $participant = Participant::where('program_id', $session->program->id)
            ->where(function ($q) use ($code) {
                $q->where('participant_code', $code)
                    ->orWhere('ic_passport', $code);
            })
            ->first();

        if (!$participant) {
            return back()
                ->withErrors(['participant_code' => 'Peserta tidak ditemui untuk program ini.'])
                ->withInput();
        }

        Attendance::firstOrCreate(
            [
                'program_id'     => $session->program->id,
                'session_id'     => $session->id,
                'participant_id' => $participant->id,
            ],
            [
                'participant_code' => $participant->participant_code, // simpan untuk rujukan pantas
            ]
        );

        return back()->with('success', 'Kehadiran berjaya direkodkan untuk: ' . $participant->name);
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
        return view('pages.attendance.form', [
            'program'   => $program,
            'session'   => null,
            'postRoute' => route('attendance.store.program', $program->id),
        ]);
    }

    public function createSession(Program $program, Session $session)
    {
        abort_unless($session->program_id === $program->id, 404);

        return view('pages.attendance.form', [
            'program'   => $program,
            'session'   => $session,
            'postRoute' => route('attendance.store.session', [$program->id, $session->id]),
        ]);
    }

    // Simpan attendance (by program)
    public function storeProgram(Request $request, Program $program)
    {
        $request->validate(['participant_code' => 'required']);

        $participant = Participant::where('program_id', $program->id)
            ->where('participant_code', $request->participant_code)
            ->first();

        if (!$participant) {
            return back()->withErrors(['participant_code' => 'Kod peserta tidak sah'])->withInput();
        }

        Attendance::firstOrCreate([
            'program_id'     => $program->id,
            'session_id'     => null,
            'participant_id' => $participant->id,
        ], [
            'participant_code' => $request->participant_code,
        ]);

        return back()->with('success', 'Kehadiran berjaya direkodkan.');
    }

    // Simpan attendance (by session)
    public function storeSession(Request $request, Program $program, Session $session)
    {
        abort_unless($session->program_id === $program->id, 404);
        $request->validate(['participant_code' => 'required']);

        $participant = Participant::where('program_id', $program->id)
            ->where('participant_code', $request->participant_code)
            ->first();

        if (!$participant) {
            return back()->withErrors(['participant_code' => 'Kod peserta tidak sah'])->withInput();
        }

        Attendance::firstOrCreate([
            'program_id'     => $program->id,
            'session_id'     => $session->id,
            'participant_id' => $participant->id,
        ], [
            'participant_code' => $request->participant_code,
        ]);

        return back()->with('success', 'Kehadiran sesi berjaya direkodkan.');
    }
}
