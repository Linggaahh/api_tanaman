<?php

namespace App\Http\Controllers;
use App\Models\Tanaman;
use Illuminate\Http\Request;

class TanamanController extends Controller
{
  
    public function index()
    {
        return response()->json(Tanaman::all(), 200);
    }


    public function show($id)
    {
        $tanaman = Tanaman::find($id);
        if (!$tanaman) {
            return response()->json(['message' => 'Tanaman tidak ditemukan'], 404);
        }
        return response()->json($tanaman, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nm_tanaman' => 'required|string|max:100',
            'varietas' => 'required|string|max:100',
            'tgl_tanam' => 'required|date',
            'lama_panen' => 'required|integer',
            'lokasi' => 'required|string|max:150',
            'status' => 'required|string|max:50'
        ]);

        $tanaman = Tanaman::create([
            'nm_tanaman' => $request->nm_tanaman,
            'varietas' => $request->varietas,
            'tgl_tanam' => $request->tgl_tanam,
            'lama_panen' => $request->lama_panen,
            'lokasi' => $request->lokasi,
            'status' => $request->status
        ]);

        return response()->json(['message' => 'Data tanaman berhasil ditambahkan', 'data' => $tanaman], 201);
    }

   
    public function update(Request $request, $id)
    {
        $tanaman = Tanaman::find($id);
        if (!$tanaman) {
            return response()->json(['message' => 'Tanaman tidak ditemukan'], 404);
        }

        $tanaman->update([
            'nm_tanaman' => $request->nm_tanaman ?? $tanaman->nm_tanaman,
            'varietas' => $request->varietas ?? $tanaman->varietas,
            'tgl_tanam' => $request->tgl_tanam ?? $tanaman->tgl_tanam,
            'lama_panen' => $request->lama_panen ?? $tanaman->lama_panen,
            'lokasi' => $request->lokasi ?? $tanaman->lokasi,
            'status' => $request->status ?? $tanaman->status,
        ]);

        return response()->json(['message' => 'Data tanaman berhasil diupdate', 'data' => $tanaman], 200);
    }

    public function destroy($id)
    {
        $tanaman = Tanaman::find($id);
        if (!$tanaman) {
            return response()->json(['message' => 'Tanaman tidak ditemukan'], 404);
        }

        $tanaman->delete();
        return response()->json(['message' => 'Data tanaman berhasil dihapus'], 200);
    }
}
