<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pakan;

class PakanController extends Controller
{
    public function index()
    {
        return response()->json(Pakan::all(), 200);
    }

    public function show($id)
    {
        $pakan = Pakan::find($id);
        if (!$pakan) {
            return response()->json(['message' => 'Pakan tidak ditemukan'], 404);
        }
        return response()->json($pakan, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nm_pakan' => 'required|string|max:100',
            'jumlah_stok' => 'required|integer',
            'tgl_beli' => 'required|date'
        ]);

        $pakan = Pakan::create([
            'nm_pakan' => $request->nm_pakan,
            'jumlah_stok' => $request->jumlah_stok,
            'tgl_beli' => $request->tgl_beli
        ]);

        return response()->json(['message' => 'Data pakan berhasil ditambahkan', 'data' => $pakan], 201);
    }

    public function update(Request $request, $id)
    {
        $pakan = Pakan::find($id);
        if (!$pakan) {
            return response()->json(['message' => 'Pakan tidak ditemukan'], 404);
        }

        $pakan->update([
            'nm_pakan' => $request->nm_pakan ?? $pakan->nm_pakan,
            'jumlah_stok' => $request->jumlah_stok ?? $pakan->jumlah_stok,
            'tgl_beli' => $request->tgl_beli ?? $pakan->tgl_beli,
        ]);

        return response()->json(['message' => 'Data pakan berhasil diupdate', 'data' => $pakan], 200);
    }

    public function destroy($id)
    {
        $pakan = Pakan::find($id);
        if (!$pakan) {
            return response()->json(['message' => 'Pakan tidak ditemukan'], 404);
        }

        $pakan->delete();
        return response()->json(['message' => 'Data pakan berhasil dihapus'], 200);
    }
}
