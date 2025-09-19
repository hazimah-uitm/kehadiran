<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Program;

class ProgramController extends Controller
{
    public function publicIndex(Request $request)
    {
        $perPage = (int) $request->input('perPage', 12);

        $programs = Program::with(['sessions' => function ($q) {
            $q->where('publish_status', 1)
                ->orderBy('start_time', 'asc');
        }])
            ->where('publish_status', 1)
            // Optional: tunjuk yg sedang/akan berlangsung dulu
            ->orderBy('start_date', 'desc')
            ->paginate($perPage);

        return view('welcome', compact('programs', 'perPage'));
    }

    public function publicShow($programId)
    {
        $program = Program::with('sessions')
            ->where('publish_status', 1)
            ->findOrFail($programId);

        // Return view borang kehadiran awam
        return view('pages.public.program.show', compact('program'));
    }

    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10);

        $programList = Program::withCount('sessions')->paginate($perPage);

        return view('pages.program.index', [
            'programList' => $programList,
            'perPage' => $perPage,
        ]);
    }

    public function create()
    {
        return view('pages.program.form', [
            'save_route' => route('program.store'),
            'str_mode' => 'Tambah',
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'          => 'required|unique:programs',
            'program_code'          => 'required|unique:programs',
            'description'    => 'nullable',
            'start_date'     => 'required|date',
            'end_date'       => 'required|date|after_or_equal:start_date',
            'venue'          => 'required',
            'publish_status' => 'required|in:1,0',
        ], [
            'title.required'          => 'Sila isi nama program',
            'title.unique'            => 'Nama program telah wujud',
            'program_code.required'          => 'Sila isi Kod Program',
            'program_code.unique'            => 'Kod Program telah wujud',
            'start_date.required'     => 'Sila isi tarikh mula',
            'end_date.required'       => 'Sila isi tarikh tamat',
            'end_date.after_or_equal' => 'Tarikh tamat mesti selepas atau sama dengan tarikh mula',
            'venue.required'          => 'Sila isi tempat program',
            'publish_status.required' => 'Sila pilih status',
        ]);

        $program = new Program();
        $program->fill($request->all());
        $program->save();

        return redirect()->route('program')->with('success', 'Maklumat berjaya disimpan');
    }

    public function show($id)
    {
        $program = Program::findOrFail($id);

        return view('pages.program.view', [
            'program' => $program,
        ]);
    }

    public function edit($id)
    {
        return view('pages.program.form', [
            'save_route' => route('program.update', $id),
            'str_mode'   => 'Kemas Kini',
            'program'    => Program::findOrFail($id),
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title'          => 'required|unique:programs,title,' . $id,
            'program_code'   => 'required|unique:programs,program_code,' . $id,
            'description'    => 'nullable',
            'start_date'     => 'required|date',
            'end_date'       => 'required|date|after_or_equal:start_date',
            'venue'          => 'required',
            'publish_status' => 'required|in:1,0',
        ], [
            'title.required'          => 'Sila isi nama program',
            'title.unique'            => 'Nama program telah wujud',
            'program_code.required'          => 'Sila isi Kod Program',
            'program_code.unique'            => 'Kod Program telah wujud',
            'start_date.required'     => 'Sila isi tarikh mula',
            'end_date.required'       => 'Sila isi tarikh tamat',
            'end_date.after_or_equal' => 'Tarikh tamat mesti selepas atau sama dengan tarikh mula',
            'venue.required'          => 'Sila isi tempat program',
            'publish_status.required' => 'Sila pilih status',
        ]);

        $program = Program::findOrFail($id);
        $program->fill($request->all());
        $program->save();

        return redirect()->route('program')->with('success', 'Maklumat berjaya dikemaskini');
    }

    public function search(Request $request)
    {
        $search = $request->input('search');

        if ($search) {
            $programList = Program::where('title', 'LIKE', "%$search%")
                ->latest()
                ->paginate(10);
        } else {
            $programList = Program::latest()->paginate(10);
        }

        return view('pages.program.index', [
            'programList' => $programList,
        ]);
    }

    public function destroy($id)
    {
        $program = Program::findOrFail($id);
        $program->delete();

        return redirect()->route('program')->with('success', 'Maklumat berjaya dihapuskan');
    }

    public function trashList()
    {
        $trashList = Program::onlyTrashed()->latest()->paginate(10);

        return view('pages.program.trash', [
            'trashList' => $trashList,
        ]);
    }

    public function restore($id)
    {
        Program::withTrashed()->where('id', $id)->restore();

        return redirect()->route('program')->with('success', 'Maklumat berjaya dikembalikan');
    }

    public function forceDelete($id)
    {
        $program = Program::withTrashed()->findOrFail($id);
        $program->forceDelete();

        return redirect()->route('program.trash')->with('success', 'Maklumat berjaya dihapuskan sepenuhnya');
    }
}
