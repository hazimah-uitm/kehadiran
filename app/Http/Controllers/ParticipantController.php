<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Program;
use App\Models\Participant;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode as FacadesQrCode;

class ParticipantController extends Controller
{
    public function index(Program $program, Request $request)
    {
        $perPage = $request->input('perPage', 10);

        $participants = Participant::where('program_id', $program->id)
            ->latest()
            ->paginate($perPage);

        return view('pages.participant.index', compact('program', 'participants', 'perPage'));
    }

    public function create(Program $program)
    {
        return view('pages.participant.form', [
            'program'    => $program,
            'save_route' => route('participant.store', ['program' => $program->id]),
            'str_mode'   => 'Tambah',
        ]);
    }

    public function store(Program $program, Request $request)
    {
        $request->validate([
            'name'             => 'required|string|max:191',
            'ic_passport'      => 'required|string|max:191|unique:participants,ic_passport,NULL,id,program_id,' . $program->id,
            'student_staff_id' => 'nullable|string|max:191',
            'nationality'      => 'nullable|string|max:191',
            'phone_no'         => 'nullable|string|max:30',
            'institution'      => 'nullable|string|max:191',
        ], [
            'name.required'        => 'Sila isi nama peserta',
            'ic_passport.required' => 'Sila isi IC/Passport',
            'ic_passport.unique'   => 'IC/Passport ini telah wujud dalam program ini',
            'ic_passport.max'      => 'IC/Passport tidak boleh melebihi 191 aksara',
            'phone_no.max'         => 'No. Telefon tidak boleh melebihi 30 aksara',
        ]);

        // Pastikan ada program_code; kalau tak ada, fallback ke 'PRG'
        $prefix = $this->sanitizePrefix($program->program_code ?: 'PRG');

        // Transaksi supaya penomboran tak berlaga bila concurrent
        [$participant, $code] = DB::transaction(function () use ($program, $request, $prefix) {

            // 1) Simpan peserta asas
            $participant = Participant::create([
                'program_id'       => $program->id,
                'name'             => $request->name,
                'ic_passport'      => $request->ic_passport,
                'student_staff_id' => $request->student_staff_id,
                'nationality'      => $request->nationality,
                'phone_no'         => $request->phone_no,
                'institution'      => $request->institution,
            ]);

            // 2) Dapatkan nombor seterusnya (tak recycle walau ada soft-deleted)
            //    Pilihan A (ringkas): kira bilangan yang sudah ADA kod
            $count = Participant::withTrashed()
                ->where('program_id', $program->id)
                ->whereNotNull('participant_code')
                ->count();

            $next = $count + 1; // peserta baharu = nombor seterusnya
            $code = $prefix . '-' . str_pad($next, 3, '0', STR_PAD_LEFT);

            // 3) Jana QR (encode KOD SAHAJA)
            $dir  = "qrcodes/{$program->id}";
            $file = "{$dir}/participant_{$participant->id}.png";
            if (!Storage::disk('public')->exists($dir)) {
                Storage::disk('public')->makeDirectory($dir);
            }
            $png = FacadesQrCode::format('png')->size(300)->margin(1)->generate($code);
            Storage::disk('public')->put($file, $png);

            // 4) Update participant dgn code & QR path
            $participant->update([
                'participant_code' => $code,
                'qr_path'          => $file,
            ]);

            return [$participant, $code];
        });

        return redirect()->route('participant', $program->id)
            ->with('success', "Peserta berjaya disimpan. Kod: {$code}");
    }

    public function show(Program $program, Participant $participant)
    {
        if ($participant->program_id !== $program->id) abort(404);

        return view('pages.participant.view', compact('program', 'participant'));
    }

    public function edit(Program $program, Participant $participant)
    {
        if ($participant->program_id !== $program->id) abort(404);

        return view('pages.participant.form', [
            'program'     => $program,
            'participant' => $participant,
            'save_route'  => route('participant.update', [$program->id, $participant->id]),
            'str_mode'    => 'Kemas Kini',
        ]);
    }

    public function update(Program $program, Participant $participant, Request $request)
    {
        if ($participant->program_id !== $program->id) abort(404);

        $request->validate([
            'name'             => 'required|string|max:191',
            'ic_passport'      => 'required|string|max:191|unique:participants,ic_passport,' . $participant->id . ',id,program_id,' . $program->id,
            'student_staff_id' => 'nullable|string|max:191',
            'nationality'      => 'nullable|string|max:191',
            'phone_no'         => 'nullable|string|max:30',
            'institution'      => 'nullable|string|max:191',
        ], [
            'name.required'        => 'Sila isi nama peserta',
            'ic_passport.required' => 'Sila isi IC/Passport',
            'ic_passport.unique'   => 'IC/Passport ini telah wujud dalam program ini',
            'ic_passport.max'      => 'IC/Passport tidak boleh melebihi 191 aksara',
            'phone_no.max'         => 'No. Telefon tidak boleh melebihi 30 aksara',
        ]);

        // Kod & QR TIDAK diubah masa edit
        $participant->update($request->only([
            'name',
            'ic_passport',
            'student_staff_id',
            'nationality',
            'phone_no',
            'institution'
        ]));

        return redirect()->route('participant', $program->id)
            ->with('success', 'Peserta berjaya dikemaskini');
    }

    public function destroy(Program $program, Participant $participant)
    {
        if ($participant->program_id !== $program->id) abort(404);

        $participant->delete();

        return redirect()->route('participant', $program->id)
            ->with('success', 'Peserta berjaya dihapuskan');
    }

    // --- Trash ---
    public function trashList(Program $program, Request $request)
    {
        $perPage = $request->input('perPage', 10);

        $trashList = Participant::onlyTrashed()
            ->where('program_id', $program->id)
            ->latest('deleted_at')
            ->paginate($perPage);

        return view('pages.participant.trash', compact('program', 'trashList', 'perPage'));
    }

    public function restore(Program $program, $id)
    {
        $item = Participant::onlyTrashed()
            ->where('program_id', $program->id)
            ->where('id', $id)
            ->firstOrFail();

        $item->restore();

        return redirect()->route('participant.trash', $program->id)
            ->with('success', 'Peserta berjaya dikembalikan');
    }

    public function forceDelete(Program $program, $id)
    {
        $item = Participant::withTrashed()
            ->where('program_id', $program->id)
            ->where('id', $id)
            ->firstOrFail();

        $item->forceDelete();

        return redirect()->route('participant.trash', $program->id)
            ->with('success', 'Peserta berjaya dihapuskan sepenuhnya');
    }

    // --- Carian ---
    public function search(Program $program, Request $request)
    {
        $search  = $request->input('search');
        $perPage = $request->input('perPage', 10);

        $query = Participant::where('program_id', $program->id);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('ic_passport', 'LIKE', "%{$search}%")
                    ->orWhere('phone_no', 'LIKE', "%{$search}%")
                    ->orWhere('institution', 'LIKE', "%{$search}%")
                    ->orWhere('participant_code', 'LIKE', "%{$search}%");
            });
        }

        $participants = $query->latest()->paginate($perPage);

        return view('pages.participant.index', compact('program', 'participants', 'perPage'));
    }

    // --- Util ---
    private function sanitizePrefix($prefix)
    {
        // buang bukan alnum, upper-case, trim
        $prefix = preg_replace('/[^A-Za-z0-9]/', '', (string) $prefix);
        return strtoupper(substr($prefix ?: 'PRG', 0, 20));
    }
}
