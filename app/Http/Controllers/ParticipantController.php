<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Program;
use App\Models\Participant;
use BaconQrCode\Encoder\QrCode;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode as FacadesQrCode;

class ParticipantController extends Controller
{
    public function checkForm($programId)
    {
        // Program mesti published
        $program = Program::where('publish_status', 1)->findOrFail($programId);

        return view('pages.public.participant.check', compact('program'));
    }

    public function checkSubmit($programId, Request $request)
    {
        // ringankan brute-force: extra cache-based cooldown ringan (optional)
        $key = 'check_ic_' . $request->ip();
        if (Cache::has($key)) {
            return back()->withErrors(['ic_passport' => 'Cuba lagi sebentar lagi.'])->withInput();
        }
        Cache::put($key, 1, now()->addSeconds(2));

        $program = Program::where('publish_status', 1)->findOrFail($programId);

        $request->validate([
            'ic_passport' => 'required|string|max:191',
        ], [
            'ic_passport.required' => 'Sila masukkan IC/Passport.',
            'ic_passport.max'      => 'IC/Passport tidak boleh melebihi 191 aksara.',
        ]);

        $participant = Participant::where('program_id', $program->id)
            ->where('ic_passport', $request->ic_passport)
            ->first();

        if (!$participant) {
            return back()->withErrors(['ic_passport' => 'Rekod tidak ditemui untuk program ini.'])->withInput();
        }

        // Pastikan QR wujud; kalau hilang, jana semula menggunakan participant_code sedia ada
        if (!$participant->participant_code) {
            // fallback: kalau entah macam mana tiada kod (kasus lama), kau boleh generate atau block
            return back()->withErrors(['ic_passport' => 'Kod peserta belum tersedia. Sila hubungi urus setia.']);
        }

        if (empty($participant->qr_path) || !Storage::disk('public')->exists($participant->qr_path)) {
            $this->regenerateParticipantQr($program->id, $participant);
        }

        // Redirect ke paparan peserta (public)
        return redirect()->route('public.participant.show', [$program->id, $participant->id]);
    }

    /** Helper: jana semula QR peserta jika fail tiada */
    private function regenerateParticipantQr($programId, Participant $participant)
    {
        $dir  = "qrcodes/{$programId}";
        $file = "{$dir}/participant_{$participant->id}.png";

        if (!Storage::disk('public')->exists($dir)) {
            Storage::disk('public')->makeDirectory($dir);
        }

        $png = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')
            ->size(300)->margin(1)->generate($participant->participant_code);

        Storage::disk('public')->put($file, $png);

        $participant->update(['qr_path' => $file]);
    }

    public function showPublic($programId, $participantId)
    {
        // Program mesti published
        $program = Program::where('publish_status', 1)->findOrFail($programId);

        // Peserta mestilah milik program ini
        $participant = Participant::where('program_id', $program->id)
            ->findOrFail($participantId);

        return view('pages.public.participant.show', compact('program', 'participant'));
    }

    public function createPublic($programId)
    {
        $program = Program::with('sessions')
            ->where('publish_status', 1)
            ->findOrFail($programId);

        return view('pages.public.participant.form', [
            'program'    => $program,
            'save_route' => route('participant.public.store', $program->id),
            'str_mode'   => 'Pendaftaran Peserta',
        ]);
    }

    public function storePublic($programId, Request $request)
    {
        $program = Program::with('sessions')
            ->where('publish_status', 1)
            ->findOrFail($programId);

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

        $prefix = $this->sanitizePrefix($program->program_code ?: 'PRG');

        [$participant, $code] = DB::transaction(function () use ($program, $request, $prefix) {
            $participant = \App\Models\Participant::create([
                'program_id'       => $program->id,
                'name'             => $request->name,
                'ic_passport'      => $request->ic_passport,
                'student_staff_id' => $request->student_staff_id,
                'nationality'      => $request->nationality,
                'phone_no'         => $request->phone_no,
                'institution'      => $request->institution,
            ]);

            $count = \App\Models\Participant::withTrashed()
                ->where('program_id', $program->id)
                ->whereNotNull('participant_code')
                ->count();

            $next = $count + 1;
            $code = $prefix . '-' . str_pad($next, 3, '0', STR_PAD_LEFT);

            $dir  = "qrcodes/{$program->id}";
            $file = "{$dir}/participant_{$participant->id}.png";
            if (!Storage::disk('public')->exists($dir)) {
                Storage::disk('public')->makeDirectory($dir);
            }
            $png = FacadesQrCode::format('png')->size(300)->margin(1)->generate($code);
            Storage::disk('public')->put($file, $png);

            $participant->update([
                'participant_code' => $code,
                'qr_path'          => $file,
            ]);

            return [$participant, $code];
        });

        return redirect()
            ->route('public.participant.show', [$program->id, $participant->id])
            ->with('success', "Pendaftaran berjaya. Kod Peserta: {$code}");
    }


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
