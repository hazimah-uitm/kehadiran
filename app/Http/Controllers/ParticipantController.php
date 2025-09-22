<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Program;
use App\Models\Participant;
use BaconQrCode\Encoder\QrCode;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
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
            return back()->withErrors(['ic_passport' => 'Please try again later.'])->withInput();
        }
        Cache::put($key, 1, now()->addSeconds(2));

        $program = Program::where('publish_status', 1)->findOrFail($programId);

        $request->validate([
            'ic_passport' => 'required|string|max:191',
        ], [
            'ic_passport.required' => 'Please enter IC/Passport No.',
            'ic_passport.max'      => 'IC/Passport cannot exceed 191 characters.',
        ]);

        $participant = Participant::where('program_id', $program->id)
            ->where('ic_passport', $request->ic_passport)
            ->first();

        if (!$participant) {
            $registerUrl = route('public.participant.create', $program->id);
            return back()->withErrors(['ic_passport' => 'No record found for this program.'])
                ->with('error', "
    No record found for this program. <pclass='text-dark'>
        <a href=\"{$registerUrl}\" target=\"_blank\">Click here to register</a>.
    </p>
")->withInput();
        }

        if (!$participant->participant_code) {
            return back()->withErrors(['ic_passport' => 'Participant code not yet available.']);
        }

        if (empty($participant->qr_path) || !Storage::disk('public')->exists($participant->qr_path)) {
            $this->regenerateParticipantQr($program->id, $participant);
        }

        return redirect()->route('public.participant.show', [$program->id, $participant->id]);
    }


    private function regenerateParticipantQr($programId, Participant $participant)
    {
        $dir  = "qrcodes/{$programId}";
        $file = "{$dir}/participant_{$participant->id}.png";

        if (!Storage::disk('public')->exists($dir)) {
            Storage::disk('public')->makeDirectory($dir);
        }

        $code = $participant->participant_code;
        if (!$code) {
            throw new \RuntimeException('Participant code not available for regeneration.');
        }

        $this->makeQrWithCaption($code, $participant->name, $file);

        $participant->update(['qr_path' => $file]);
    }

    public function showPublic($programId, $participantId)
    {
        $program = Program::where('publish_status', 1)->findOrFail($programId);

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
            'save_route' => route('public.participant.store', $program->id),
            'str_mode'   => 'Participant Registration',
        ]);
    }

    public function storePublic($programId, Request $request)
    {
        $program = Program::with('sessions')
            ->where('publish_status', 1)
            ->findOrFail($programId);


        $request->merge([
            'ic_passport' => preg_replace('/[\s-]+/', '', $request->ic_passport),
        ]);

        $checkUrl = route('public.participant.check', $program->id);

        $request->validate([
            'name'             => 'required|string|max:191',
            'ic_passport'      => 'required|string|max:191|unique:participants,ic_passport,NULL,id,program_id,' . $program->id,
            'student_staff_id' => 'nullable|string|max:191',
            'nationality'      => 'nullable|string|max:191',
            'phone_no'         => 'nullable|string|max:30',
            'institution'      => 'nullable|string|max:191',
        ], [
            'name.required'        => 'Please enter the full name',
            'ic_passport.required' => 'Please enter IC/Passport number',
            'ic_passport.unique'   => 'This IC/Passport already exists in this program. Please check the participant Code <a href="' . $checkUrl . '" target="_blank">here</a>',
            'ic_passport.max'      => 'IC/Passport cannot exceed 191 characters',
            'phone_no.max'         => 'Phone number cannot exceed 30 characters',
        ]);

        $prefix = $this->sanitizePrefix($program->program_code ?: 'PRG');

        [$participant, $code] = DB::transaction(function () use ($program, $request, $prefix) {
            $participant = Participant::create([
                'program_id'       => $program->id,
                'name'             => $request->name,
                'ic_passport'      => $request->ic_passport,
                'student_staff_id' => $request->student_staff_id,
                'nationality'      => $request->nationality,
                'phone_no'         => $request->phone_no,
                'institution'      => $request->institution,
            ]);

            $count = Participant::withTrashed()
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

            // JANA QR + KAPSYEN (nama & kod) — ganti versi lama
            $this->makeQrWithCaption($code, $participant->name, $file);

            $participant->update([
                'participant_code' => $code,
                'qr_path'          => $file,
            ]);

            return [$participant, $code];
        });

        return redirect()
            ->route('public.participant.show', [$program->id, $participant->id])
            ->with('success', "Registration successful. Participant Code: {$code}");
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
            'save_route' => route('admin.participant.store', ['program' => $program->id]),
            'str_mode'   => 'Add',
        ]);
    }

    public function store(Program $program, Request $request)
    {
        $request->merge([
            'ic_passport' => preg_replace('/[\s-]+/', '', $request->ic_passport),
        ]);

        $request->validate([
            'name'             => 'required|string|max:191',
            'ic_passport'      => 'required|string|max:191|unique:participants,ic_passport,NULL,id,program_id,' . $program->id,
            'student_staff_id' => 'nullable|string|max:191',
            'nationality'      => 'nullable|string|max:191',
            'phone_no'         => 'nullable|string|max:30',
            'institution'      => 'nullable|string|max:191',
        ], [
            'name.required'        => 'Please enter the full name',
            'ic_passport.required' => 'Please enter IC/Passport number',
            'ic_passport.unique'   => 'This IC/Passport already exists in this program',
            'ic_passport.max'      => 'IC/Passport cannot exceed 191 characters',
            'phone_no.max'         => 'Phone number cannot exceed 30 characters',
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

            // JANA QR + KAPSYEN (nama & kod) — ganti versi lama
            $this->makeQrWithCaption($code, $participant->name, $file);

            $participant->update([
                'participant_code' => $code,
                'qr_path'          => $file,
            ]);


            return [$participant, $code];
        });

        return redirect()->route('admin.participant', $program->id)
            ->with('success', "Participant details saved successfully. Participant Code: {$code}");
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
            'save_route'  => route('admin.participant.update', [$program->id, $participant->id]),
            'str_mode'    => 'Edit',
        ]);
    }

    public function update(Program $program, Participant $participant, Request $request)
    {
        if ($participant->program_id !== $program->id) abort(404);

        $validated = $request->validate([
            'name'             => 'required|string|max:191',
            'ic_passport'      => 'required|string|max:191|unique:participants,ic_passport,' . $participant->id . ',id,program_id,' . $program->id,
            'student_staff_id' => 'nullable|string|max:191',
            'nationality'      => 'nullable|string|max:191',
            'phone_no'         => 'nullable|string|max:30',
            'institution'      => 'nullable|string|max:191',
        ]);

        // Simpan nama lama untuk banding
        $oldName = $participant->name;

        $participant->update($validated);

        // Kalau nama berubah, regenerate QR baru
        if ($oldName !== $participant->name && $participant->participant_code) {
            $dir  = "qrcodes/{$program->id}";
            $file = "{$dir}/participant_{$participant->id}.png";

            if (!Storage::disk('public')->exists($dir)) {
                Storage::disk('public')->makeDirectory($dir);
            }

            $this->makeQrWithCaption($participant->participant_code, $participant->name, $file);

            $participant->update(['qr_path' => $file]);
        }

        return redirect()->route('admin.participant', $program->id)
            ->with('success', 'Participant details updated successfully');
    }

    public function destroy(Program $program, Participant $participant)
    {
        if ($participant->program_id !== $program->id) abort(404);

        $participant->delete();

        return redirect()->route('admin.participant', $program->id)
            ->with('success', 'Participant details deleted successfully');
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

        return redirect()->route('admin.participant.trash', $program->id)
            ->with('success', 'Participant details restored successfully');
    }

    public function forceDelete(Program $program, $id)
    {
        $item = Participant::withTrashed()
            ->where('program_id', $program->id)
            ->where('id', $id)
            ->firstOrFail();

        $item->forceDelete();

        return redirect()->route('admin.participant.trash', $program->id)
            ->with('success', 'Participant details deleted permanently');
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

    private function makeQrWithCaption(string $code, string $name, string $destPath, int $size = 300): void
    {
        // 1) Generate QR PNG (bytes) — encode kod sahaja
        $qrPng = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')
            ->size($size)->margin(1)->generate($code);

        // 2) Buat resource imej GD daripada QR
        $qr = imagecreatefromstring($qrPng);
        if ($qr === false) {
            throw new \RuntimeException('Failed to create image from QR data.');
        }

        $qrW = imagesx($qr);
        $qrH = imagesy($qr);

        // 3) Tetapan kanvas & teks
        $paddingX = 20;     // kiri/kanan
        $paddingTop = 20;   // atas QR
        $gap = 10;          // jarak antara QR dan teks
        $lineGap = 6;

        // Kita sediakan dua baris: Nama & Kod
        // Elak terlalu panjang: potong nama (optional)
        $name = trim($name);
        if (mb_strlen($name, 'UTF-8') > 60) {
            $name = mb_substr($name, 0, 57, 'UTF-8') . '...';
        }
        $lines = [$name, $code];

        // Cuba guna TTF (lebih cantik & sokong UTF-8).
        $fontPath = resource_path('fonts/DejaVuSans.ttf'); // letak fail font ni
        $useTtf = file_exists($fontPath);

        // Anggar tinggi teks
        if ($useTtf) {
            $fontSize = 12; // px-ish
            // Kira tinggi total teks (lebih kurang)
            $lineHeights = [];
            $maxTextWidth = 0;
            foreach ($lines as $text) {
                // bbox = [llx,lly,lrx,lry,urx,ury,ulx,uly]
                $bbox = imagettfbbox($fontSize, 0, $fontPath, $text);
                $textWidth = $bbox[2] - $bbox[0];
                $textHeight = ($bbox[1] - $bbox[7]); // tinggi positif
                $lineHeights[] = $textHeight;
                $maxTextWidth = max($maxTextWidth, $textWidth);
            }
            $textBlockHeight = array_sum($lineHeights) + $lineGap * (count($lines) - 1);
            $canvasW = max($qrW + 2 * $paddingX, $maxTextWidth + 2 * $paddingX);
        } else {
            // Fallback tanpa TTF (bitmap font 5)
            $font = 5;
            $lineHeights = array_fill(0, count($lines), imagefontheight($font));
            $textWidths = array_map(function ($t) use ($font) {
                return imagefontwidth($font) * strlen($t);
            }, $lines);
            $textBlockHeight = array_sum($lineHeights) + $lineGap * (count($lines) - 1);
            $maxTextWidth = max($textWidths);
            $canvasW = max($qrW + 2 * $paddingX, $maxTextWidth + 2 * $paddingX);
        }

        $canvasH = $paddingTop + $qrH + $gap + $textBlockHeight + $paddingTop;

        // 4) Cipta kanvas putih, salin QR dan tulis teks
        $im = imagecreatetruecolor($canvasW, $canvasH);
        $white = imagecolorallocate($im, 255, 255, 255);
        $black = imagecolorallocate($im, 0, 0, 0);
        imagefilledrectangle($im, 0, 0, $canvasW, $canvasH, $white);

        // Letak QR center
        $qrX = (int)(($canvasW - $qrW) / 2);
        imagecopy($im, $qr, $qrX, $paddingTop, 0, 0, $qrW, $qrH);
        imagedestroy($qr);

        // Tulis teks (center)
        $textY = $paddingTop + $qrH + $gap;
        foreach ($lines as $idx => $text) {
            if ($useTtf) {
                $bbox = imagettfbbox($fontSize, 0, $fontPath, $text);
                $textWidth = $bbox[2] - $bbox[0];
                $textHeight = ($bbox[1] - $bbox[7]);
                $x = (int)(($canvasW - $textWidth) / 2);
                $y = (int)($textY + $textHeight); // baseline
                imagettftext($im, $fontSize, 0, $x, $y, $black, $fontPath, $text);
                $textY += $textHeight + $lineGap;
            } else {
                $font = 5;
                $textWidth = imagefontwidth($font) * strlen($text);
                $textHeight = imagefontheight($font);
                $x = (int)(($canvasW - $textWidth) / 2);
                $y = (int)$textY;
                imagestring($im, $font, $x, $y, $text, $black);
                $textY += $textHeight + $lineGap;
            }
        }

        // 5) Simpan ke Storage (public)
        ob_start();
        imagepng($im);
        $pngOut = ob_get_clean();
        imagedestroy($im);

        Storage::disk('public')->put($destPath, $pngOut);
    }

    public function export(Program $program, Request $request)
    {
        $search = $request->input('search');

        $query = Participant::where('program_id', $program->id)->orderBy('id', 'asc');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('ic_passport', 'LIKE', "%{$search}%")
                    ->orWhere('phone_no', 'LIKE', "%{$search}%")
                    ->orWhere('institution', 'LIKE', "%{$search}%");
            });
        }

        $rows = $query->get([
            'id',
            'name',
            'ic_passport',
            'phone_no',
            'institution',
            'nationality',
            'student_staff_id',
            'created_at',
        ]);

        // Sediakan array untuk Excel (header + data)
        $export = [];
        $export[] = [
            'No.',
            'Name',
            'IC/Passport No.',
            'Staf/Student ID (UiTM)',
            'Phone No.',
            'Nationality',
            'Institution/Organization',
            'Registration Date',
        ];

        $i = 1;
        foreach ($rows as $r) {
            $export[] = [
                $i++,
                $r->name,
                $r->ic_passport,
                $r->student_staff_id ?: '-',
                $r->phone_no ?: '-',
                $r->nationality ?: '-',
                $r->institution ?: '-',
                $r->created_at ? Carbon::parse($r->created_at)->format('d/m/Y H:i') : '-',
            ];
        }

        // Nama fail: gabung kod/tajuk program + tarikh
        $progCode = $this->sanitizePrefix($program->program_code ?: 'PRG');
        $dateStr  = Carbon::now()->format('Ymd_His');
        $filename = "{$progCode}_participants_{$dateStr}";

        // Generate & download (xlsx)
        return Excel::create($filename, function ($excel) use ($export, $program) {
            $excel->setTitle('Participant List: ' . $program->title);
            $excel->sheet('Participants', function ($sheet) use ($export) {
                $sheet->fromArray($export, null, 'A1', false, false);

                // Cantikkan header
                $sheet->row(1, function ($row) {
                    $row->setFontWeight('bold');
                });

                // Auto-size columns
                $lastCol = chr(ord('A') + count($export[0]) - 1);
                $sheet->setAutoSize(true);
                $sheet->setAutoFilter("A1:{$lastCol}1");
            });
        })->download('xlsx');
    }
}
