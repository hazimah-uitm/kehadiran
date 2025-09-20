<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = (int) $request->input('perPage', 12);
        $perPage = in_array($perPage, [6, 12, 24, 48]) ? $perPage : 12;

        $programs = Program::query()
            ->orderByDesc('start_date')
            ->paginate($perPage);

        return view('home', compact('programs', 'perPage'));
    }
}
