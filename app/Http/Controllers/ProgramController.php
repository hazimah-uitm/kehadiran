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
            'str_mode' => 'Add',
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
            'title.required'          => 'Please enter the program name',
            'title.unique'            => 'Program name already exists',
            'program_code.required'   => 'Please enter the program code',
            'program_code.unique'     => 'Program code already exists',
            'start_date.required'     => 'Please enter the start date',
            'end_date.required'       => 'Please enter the end date',
            'end_date.after_or_equal' => 'The end date must be the same as or later than the start date',
            'venue.required'          => 'Please enter the program venue',
            'publish_status.required' => 'Please select a status',
        ]);

        $program = new Program();
        $program->fill($request->all());
        $program->save();

        return redirect()->route('program')->with('success', 'Information saved successfully');
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
            'title.required'          => 'Please enter the program name',
            'title.unique'            => 'Program name already exists',
            'program_code.required'   => 'Please enter the program code',
            'program_code.unique'     => 'Program code already exists',
            'start_date.required'     => 'Please enter the start date',
            'end_date.required'       => 'Please enter the end date',
            'end_date.after_or_equal' => 'The end date must be the same as or later than the start date',
            'venue.required'          => 'Please enter the program venue',
            'publish_status.required' => 'Please select a status',
        ]);

        $program = Program::findOrFail($id);
        $program->fill($request->all());
        $program->save();

        return redirect()->route('program')->with('success', 'Information updated successfully');
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

        return redirect()->route('program')->with('success', 'Information deleted successfully');
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

        return redirect()->route('program')->with('success', 'Information restored successfully');
    }

    public function forceDelete($id)
    {
        $program = Program::withTrashed()->findOrFail($id);
        $program->forceDelete();

        return redirect()->route('program.trash')->with('success', 'Information deleted permanently');
    }
}
