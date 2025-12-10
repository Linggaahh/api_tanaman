<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengiriman;

class PengirimanController extends Controller
{
    
    public function index()
    {
        $pengiriman = Pengiriman::all();
        return response()->json($pengiriman);
    }

   
    public function show($id)
    {
        $pengiriman = Pengiriman::find($id);

        if (!$pengiriman) {
            return response()->json(['message' => 'Data pengiriman tidak ditemukan'], 404);
        }

        return response()->json($pengiriman);
    }


    public function store(Request $request)
    {
        $request->validate([
            'id_supply' => 'nullable|integer',
            'id_panen' => 'nullable|integer',
            'tgl_pengiriman' => 'required|date',
            'tujuan' => 'required|string|max:255',
            'jumlah_dikirim' => 'required|integer|min:1',
            'status_pengiriman' => 'required|in:pending,selesai',
            'id_kurir' => 'nullable|integer',
            'keterangan' => 'nullable|string'
        ]);

        $pengiriman = Pengiriman::create($request->all());

        return response()->json([
            'message' => 'Data pengiriman berhasil ditambahkan',
            'data' => $pengiriman
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $pengiriman = Pengiriman::find($id);

        if (!$pengiriman) {
            return response()->json(['message' => 'Data pengiriman tidak ditemukan'], 404);
        }

        $request->validate([
            'id_supply' => 'nullable|integer',
            'id_panen' => 'nullable|integer',
            'tgl_pengiriman' => 'nullable|date',
            'tujuan' => 'nullable|string|max:255',
            'jumlah_dikirim' => 'nullable|integer|min:1',
            'status_pengiriman' => 'nullable|in:pending,selesai',
            'id_kurir' => 'nullable|integer',
            'keterangan' => 'nullable|string'
        ]);

        $pengiriman->update($request->all());

        return response()->json([
            'message' => 'Data pengiriman berhasil diperbarui',
            'data' => $pengiriman
        ]);
    }

    public function destroy($id)
    {
        $pengiriman = Pengiriman::find($id);

        if (!$pengiriman) {
            return response()->json(['message' => 'Data pengiriman tidak ditemukan'], 404);
        }

        $pengiriman->delete();

        return response()->json(['message' => 'Data pengiriman berhasil dihapus']);
    }
}
