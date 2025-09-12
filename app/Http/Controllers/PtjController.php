<?php

namespace App\Http\Controllers;

use App\Models\Ptj;
use Illuminate\Http\Request;

class PtjController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10);

        $ptjList = Ptj::latest()->paginate($perPage);

        return view('pages.ptj.index', [
            'ptjList' => $ptjList,
            'perPage' => $perPage,
        ]);
    }

    public function create()
    {
        return view('pages.ptj.form', [
            'save_route' => route('ptj.store'),
            'str_mode' => 'Tambah',
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:ptjs',
            'type' => 'required',
            'publish_status' => 'required|in:1,0',
        ], [
            'name.required'     => 'Sila isi nama ptj',
            'name.unique' => 'Nama ptj telah wujud',
            'publish_status.required' => 'Sila isi status pengguna',
        ]);

        $ptj = new ptj();

        $ptj->fill($request->all());
        $ptj->save();

        return redirect()->route('ptj')->with('success', 'Maklumat berjaya disimpan');
    }

    public function show($id)
    {
        $ptj = Ptj::findOrFail($id);

        return view('pages.ptj.view', [
            'ptj' => $ptj,
        ]);
    }

    public function edit(Request $request, $id)
    {
        return view('pages.ptj.form', [
            'save_route' => route('ptj.update', $id),
            'str_mode' => 'Kemas Kini',
            'ptj' => Ptj::findOrFail($id),
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:ptjs,name,' . $id,
            'type'       => 'required',
            'publish_status' => 'required|in:1,0',
        ], [
            'name.required'     => 'Sila isi nama ptj',
            'type.required'     => 'Sila isi jenis ptj',
            'name.unique' => 'Nama ptj telah wujud',
            'publish_status.required' => 'Sila isi status pengguna',
        ]);

        $ptj = Ptj::findOrFail($id);

        $ptj->fill($request->all());
        $ptj->save();

        return redirect()->route('ptj')->with('success', 'Maklumat berjaya dikemaskini');
    }

    public function search(Request $request)
    {
        $search = $request->input('search');

        if ($search) {
            $ptjList = Ptj::where('name', 'LIKE', "%$search%")
                ->latest()
                ->paginate(10);
        } else {
            $ptjList = Ptj::latest()->paginate(10);
        }

        return view('pages.ptj.index', [
            'ptjList' => $ptjList,
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $ptj = Ptj::findOrFail($id);

        $ptj->delete();

        return redirect()->route('ptj')->with('success', 'Maklumat berjaya dihapuskan');
    }

    public function trashList()
    {
        $trashList = Ptj::onlyTrashed()->latest()->paginate(10);

        return view('pages.ptj.trash', [
            'trashList' => $trashList,
        ]);
    }

    public function restore($id)
    {
        Ptj::withTrashed()->where('id', $id)->restore();

        return redirect()->route('ptj')->with('success', 'Maklumat berjaya dikembalikan');
    }


    public function forceDelete($id)
    {
        $ptj = Ptj::withTrashed()->findOrFail($id);

        $ptj->forceDelete();

        return redirect()->route('ptj.trash')->with('success', 'Maklumat berjaya dihapuskan sepenuhnya');
    }
}
