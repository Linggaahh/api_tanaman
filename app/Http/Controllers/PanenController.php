<?php

namespace App\Http\Controllers;

use App\Models\Panen;
use Illuminate\Http\Request;

class PanenController extends Controller
{
    public function index()
    {
        return response()->json(Panen::all(), 200);
    }

    public function show($id)
    {
        $panen = Panen::find($id);
        if (!$panen) {
            return response()->json(['message' => 'Data panen tidak ditemukan'], 404);
        }
        return response()->json($panen, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tgl_panen' => 'required|date',
            'jenis_panen' => 'required|string|max:100',
            'jumlah' => 'required|integer|min:1',
            'kualitas' => 'required|string|max:100',
            'id_tumbuhan' => 'nullable|integer',
            'id_ternak' => 'nullable|integer'
        ]);

        $panen = Panen::create([
            'tgl_panen' => $request->tgl_panen,
            'jenis_panen' => $request->jenis_panen,
            'jumlah' => $request->jumlah,
            'kualitas' => $request->kualitas,
            'id_tumbuhan' => $request->id_tumbuhan,
            'id_ternak' => $request->id_ternak
        ]);

        return response()->json(['message' => 'Data panen berhasil ditambahkan', 'data' => $panen], 201);
    }

    public function update(Request $request, $id)
    {
        $panen = Panen::find($id);
        if (!$panen) {
            return response()->json(['message' => 'Data panen tidak ditemukan'], 404);
        }

        $panen->update([
            'tgl_panen' => $request->tgl_panen ?? $panen->tgl_panen,
            'jenis_panen' => $request->jenis_panen ?? $panen->jenis_panen,
            'jumlah' => $request->jumlah ?? $panen->jumlah,
            'kualitas' => $request->kualitas ?? $panen->kualitas,
            'id_tumbuhan' => $request->id_tumbuhan ?? $panen->id_tumbuhan,
            'id_ternak' => $request->id_ternak ?? $panen->id_ternak,
        ]);

        return response()->json(['message' => 'Data panen berhasil diupdate', 'data' => $panen], 200);
    }

    public function destroy($id)
    {
        $panen = Panen::find($id);
        if (!$panen) {
            return response()->json(['message' => 'Data panen tidak ditemukan'], 404);
        }

        $panen->delete();
        return response()->json(['message' => 'Data panen berhasil dihapus'], 200);
    }

}
