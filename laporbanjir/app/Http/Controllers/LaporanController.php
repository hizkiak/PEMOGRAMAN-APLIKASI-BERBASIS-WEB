<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LaporanController extends Controller
{
    public function index()
    {
        $laporans = Laporan::latest()->paginate(10);

        return view('laporan.index', compact('laporans'));
    }

    public function create()
    {
        return view('laporan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pelapor' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'lokasi' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:255',
            'tinggi_air' => 'required|integer|min:0',
            'keterangan' => 'required|string',
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $validated['foto'] = $request->file('foto')
            ->store('foto-banjir', 'public');

        $validated['status'] = 'Menunggu';

        Laporan::create($validated);

        return redirect()->route('laporan.create')
            ->with('success', 'Laporan banjir berhasil dikirim.');
    }

    public function show(Laporan $laporan)
    {
        return view('laporan.show', compact('laporan'));
    }

    public function edit(Laporan $laporan)
    {
        return view('laporan.edit', compact('laporan'));
    }

    public function update(Request $request, Laporan $laporan)
    {
        $validated = $request->validate([
            'nama_pelapor' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'lokasi' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:255',
            'tinggi_air' => 'required|integer|min:0',
            'keterangan' => 'required|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'required|in:Menunggu,Diproses,Selesai',
        ]);

        if ($request->hasFile('foto')) {
            $fotoLama = $laporan->foto;
            $validated['foto'] = $request->file('foto')
                ->store('foto-banjir', 'public');

            if ($fotoLama) {
                Storage::disk('public')->delete($fotoLama);
            }
        }

        $laporan->update($validated);

        return redirect()->route('laporan.index')
            ->with('success', 'Laporan berhasil diperbarui.');
    }

    public function destroy(Laporan $laporan)
    {
        if ($laporan->foto) {
            Storage::disk('public')->delete($laporan->foto);
        }

        $laporan->delete();

        return redirect()->route('laporan.index')
            ->with('success', 'Laporan berhasil dihapus.');
    }
}
