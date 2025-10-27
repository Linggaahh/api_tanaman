<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supply;
use App\Models\Panen;

class SupplyController extends Controller
{
    public function index()
    {
        
        $data = Supply::with('panen')->get();
        return response()->json($data, 200);
    }

    public function show($id)
    {
        $supply = Supply::with('panen')->find($id);
        if (!$supply) {
            return response()->json(['message' => 'Data supply tidak ditemukan'], 404);
        }
        return response()->json($supply, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_panen' => 'required|exists:panen,id_panen',
            'pengiriman' => 'required|string|max:100',
            'tgl_kirim' => 'required|date',
            'jumlah_kirim' => 'required|integer|min:1',
            'jenis_produk' => 'required|string|max:100'
        ]);

        $supply = Supply::create([
            'id_panen' => $request->id_panen,
            'pengiriman' => $request->pengiriman,
            'tgl_kirim' => $request->tgl_kirim,
            'jumlah_kirim' => $request->jumlah_kirim,
            'jenis_produk' => $request->jenis_produk
        ]);

        return response()->json(['message' => 'Data supply berhasil ditambahkan', 'data' => $supply], 201);
    }

    public function update(Request $request, $id)
    {
        $supply = Supply::find($id);
        if (!$supply) {
            return response()->json(['message' => 'Data supply tidak ditemukan'], 404);
        }

        // Validasi opsional jika id_panen diubah
        if ($request->has('id_panen')) {
            $request->validate(['id_panen' => 'exists:panen,id_panen']);
        }

        $supply->update([
            'id_panen' => $request->id_panen ?? $supply->id_panen,
            'pengiriman' => $request->pengiriman ?? $supply->pengiriman,
            'tgl_kirim' => $request->tgl_kirim ?? $supply->tgl_kirim,
            'jumlah_kirim' => $request->jumlah_kirim ?? $supply->jumlah_kirim,
            'jenis_produk' => $request->jenis_produk ?? $supply->jenis_produk,
        ]);

        return response()->json(['message' => 'Data supply berhasil diupdate', 'data' => $supply], 200);
    }

    public function destroy($id)
    {
        $supply = Supply::find($id);
        if (!$supply) {
            return response()->json(['message' => 'Data supply tidak ditemukan'], 404);
        }

        $supply->delete();
        return response()->json(['message' => 'Data supply berhasil dihapus'], 200);
    }
}
