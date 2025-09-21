<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Program;
use App\Models\Session;

class SessionController extends Controller
{
    // Senarai sesi untuk 1 program
    public function index(Program $program, Request $request)
    {
        $perPage = $request->input('perPage', 10);

        $sessions = Session::where('program_id', $program->id)
            ->latest()
            ->paginate($perPage);

        return view('pages.session.index', [
            'program'  => $program,
            'sessions' => $sessions,
            'perPage'  => $perPage,
        ]);
    }

    public function create(Program $program)
    {
        return view('pages.session.form', [
            'program'   => $program,
            'save_route' => route('session.store', $program->id),
            'str_mode'  => 'Add',
        ]);
    }

    public function store(Program $program, Request $request)
    {
        $request->validate([
            'title'          => 'required|string|max:191',
            'venue'          => 'required|string|max:191',
            'start_time'     => 'required|date',
            'end_time'       => 'required|date|after_or_equal:start_time',
            'publish_status' => 'required|in:1,0',
        ], [
            'title.required'          => 'Please enter the session name',
            'venue.required'          => 'Please enter the session venue',
            'start_time.required'     => 'Please enter the start date & time',
            'end_time.required'       => 'Please enter the end date & time',
            'end_time.after_or_equal' => 'Must be the same as or later than the start time',
            'publish_status.required' => 'Please select a status',
        ]);

        $session = new Session();
        $session->program_id    = $program->id;
        $session->title         = $request->title;
        $session->venue         = $request->venue;
        $session->start_time    = $request->start_time;
        $session->end_time      = $request->end_time;
        $session->publish_status = $request->publish_status;
        $session->save();

        return redirect()
            ->route('session', $program->id)
            ->with('success', 'Session details saved successfully');
    }

    public function show(Program $program, Session $session)
    {
        // pastikan sesi belong kepada program
        if ($session->program_id !== $program->id) abort(404);

        return view('pages.session.view', [
            'program' => $program,
            'session' => $session,
        ]);
    }

    public function edit(Program $program, Session $session)
    {
        if ($session->program_id !== $program->id) abort(404);

        return view('pages.session.form', [
            'program'    => $program,
            'session'    => $session,
            'save_route' => route('session.update', [$program->id, $session->id]),
            'str_mode'   => 'Edit',
        ]);
    }

    public function update(Program $program, Session $session, Request $request)
    {
        if ($session->program_id !== $program->id) abort(404);

        $request->validate([
            'title'          => 'required|string|max:191',
            'venue'          => 'required|string|max:191',
            'start_time'     => 'required|date',
            'end_time'       => 'required|date|after_or_equal:start_time',
            'publish_status' => 'required|in:1,0',
        ], [
            'title.required'          => 'Please enter the session name',
            'venue.required'          => 'Please enter the session venue',
            'start_time.required'     => 'Please enter the start date & time',
            'end_time.required'       => 'Please enter the end date & time',
            'end_time.after_or_equal' => 'Must be the same as or later than the start time',
            'publish_status.required' => 'Please select a status',
        ]);

        $session->title          = $request->title;
        $session->venue          = $request->venue;
        $session->start_time     = $request->start_time;
        $session->end_time       = $request->end_time;
        $session->publish_status = $request->publish_status;
        $session->save();

        return redirect()
            ->route('session', $program->id)
            ->with('success', 'Session details updated successfully');
    }

    public function destroy(Program $program, Session $session)
    {
        if ($session->program_id !== $program->id) abort(404);

        $session->delete();

        return redirect()
            ->route('session', $program->id)
            ->with('success', 'Session details deleted successfully');
    }

    // --- Trash / Restore ---

    public function trashList(Program $program, Request $request)
    {
        $perPage = $request->input('perPage', 10);

        $trashList = Session::onlyTrashed()
            ->where('program_id', $program->id)
            ->latest('deleted_at')
            ->paginate($perPage);

        return view('pages.session.trash', [
            'program'   => $program,
            'trashList' => $trashList,
            'perPage'   => $perPage,
        ]);
    }

    public function restore(Program $program, $id)
    {
        $restored = Session::onlyTrashed()
            ->where('program_id', $program->id)
            ->where('id', $id)
            ->firstOrFail();

        $restored->restore();

        return redirect()
            ->route('session.trash', $program->id)
            ->with('success', 'Session details restored successfully');
    }

    public function forceDelete(Program $program, $id)
    {
        $session = Session::withTrashed()
            ->where('program_id', $program->id)
            ->where('id', $id)
            ->firstOrFail();

        $session->forceDelete();

        return redirect()
            ->route('session.trash', $program->id)
            ->with('success', 'Session details deleted permanently');
    }

    // --- Optional: Carian ---
    public function search(Program $program, Request $request)
    {
        $search  = $request->input('search');
        $perPage = $request->input('perPage', 10);

        $query = Session::where('program_id', $program->id);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('venue', 'LIKE', "%{$search}%");
            });
        }

        $sessions = $query->latest()->paginate($perPage);

        return view('pages.session.index', [
            'program'  => $program,
            'sessions' => $sessions,
            'perPage'  => $perPage,
        ]);
    }
}
