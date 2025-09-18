<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Program;
use App\Models\Participant;

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

        Participant::create([
            'program_id'       => $program->id,
            'name'             => $request->name,
            'ic_passport'      => $request->ic_passport,
            'student_staff_id' => $request->student_staff_id,
            'nationality'      => $request->nationality,
            'phone_no'         => $request->phone_no,
            'institution'      => $request->institution,
        ]);

        return redirect()->route('participant', $program->id)->with('success', 'Peserta berjaya disimpan');
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

        $participant->update($request->only([
            'name',
            'ic_passport',
            'student_staff_id',
            'nationality',
            'phone_no',
            'institution'
        ]));

        return redirect()->route('participant', $program->id)->with('success', 'Peserta berjaya dikemaskini');
    }

    public function destroy(Program $program, Participant $participant)
    {
        if ($participant->program_id !== $program->id) abort(404);

        $participant->delete();

        return redirect()->route('participant', $program->id)->with('success', 'Peserta berjaya dihapuskan');
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

        return redirect()->route('participant.trash', $program->id)->with('success', 'Peserta berjaya dikembalikan');
    }

    public function forceDelete(Program $program, $id)
    {
        $item = Participant::withTrashed()
            ->where('program_id', $program->id)
            ->where('id', $id)
            ->firstOrFail();

        $item->forceDelete();

        return redirect()->route('participant.trash', $program->id)->with('success', 'Peserta berjaya dihapuskan sepenuhnya');
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
                    ->orWhere('institution', 'LIKE', "%{$search}%");
            });
        }

        $participants = $query->latest()->paginate($perPage);

        return view('pages.participant.index', compact('program', 'participants', 'perPage'));
    }
}
