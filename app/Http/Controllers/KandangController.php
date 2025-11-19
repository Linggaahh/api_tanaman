<?php

namespace App\Http\Controllers;

use App\Models\Kandang;
use Illuminate\Http\Request;

class KandangController extends Controller
{
    public function index()
    {
        return response()->json(Kandang::all(), 200);
    }

    public function show($id)
    {
        $kandang = Kandang::find($id);
        if (!$kandang) {
            return response()->json(['message' => 'Kandang tidak ditemukan'], 404);
        }
        return response()->json($kandang, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nm_kandang' => 'required|string|max:100',
            'kapasitas' => 'required|integer',
            'jumlah_hewan' => 'required|integer',
            'jenis_hewan' => 'required|string|max:100',
            'Hasil_Produksi' => 'required|string|max:255',
            'Jml_produksi' => 'nullable|integer',
            'foto_hasil' => 'nullable|image|max:2048',
            'keterangan' => 'required|string|max:255'
        ]);

        $kandang = Kandang::create([
            'nm_kandang' => $request->nm_kandang,
            'kapasitas' => $request->kapasitas,
            'jumlah_hewan' => $request->jumlah_hewan,
            'jenis_hewan' => $request->jenis_hewan,
            'Hasil_Produksi' => $request->Hasil_Produksi,
            'Jml_produksi' => $request->Jml_produksi,
            'foto_hasil' => $request->foto_hasil,
            'keterangan' => $request->keterangan
        ]);

        return response()->json(['message' => 'Data kandang berhasil ditambahkan', 'data' => $kandang], 201);
    }

    public function update(Request $request, $id)
    {
        $kandang = Kandang::find($id);
        if (!$kandang) {
            return response()->json(['message' => 'Kandang tidak ditemukan'], 404);
        }

        $kandang->update([
            'nm_kandang' => $request->nm_kandang ?? $kandang->nm_kandang,
            'kapasitas' => $request->kapasitas ?? $kandang->kapasitas,
            'jumlah_hewan' => $request->jumlah_hewan ?? $kandang->jumlah_hewan,
            'jenis_hewan' => $request->jenis_hewan ?? $kandang->jenis_hewan,
            'Hasil_Produksi' => $request->Hasil_Produksi ?? $kandang->Hasil_Produksi,
            'Jml_produksi' => $request->Jml_produksi ?? $kandang->Jml_produksi,
            'foto_hasil' => $request->foto_hasil ?? $kandang->foto_hasil,
            'keterangan' => $request->keterangan ?? $kandang->keterangan,
        ]);

        return response()->json(['message' => 'Data kandang berhasil diupdate', 'data' => $kandang], 200);
    }

    public function destroy($id)
    {
        $kandang = Kandang::find($id);
        if (!$kandang) {
            return response()->json(['message' => 'Kandang tidak ditemukan'], 404);
        }

        $kandang->delete();
        return response()->json(['message' => 'Data kandang berhasil dihapus'], 200);
    }
}