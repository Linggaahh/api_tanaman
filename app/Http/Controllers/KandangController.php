<?php

namespace App\Http\Controllers;

use App\Models\Kandang;
use Illuminate\Http\Request;
use App\Models\Panen;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        // ✅ Tambahkan logging untuk debugging
        Log::info('Kandang Store Request:', $request->all());

        $request->validate([
            'nm_kandang' => 'required|string|max:100',
            'kapasitas' => 'required|integer',
            'jumlah_hewan' => 'required|integer',
            'jenis_hewan' => 'required|string|max:100',
            'Hasil_Produksi' => 'required|string|max:255',
            'Jml_produksi' => 'nullable|integer',
            'keterangan' => 'required|string|max:255',
            'tgl_produksi' => 'nullable|date', // ✅ Format: YYYY-MM-DD
            'lama_produksi' => 'nullable|integer'
        ]);

        $kandang = Kandang::create([
            'nm_kandang' => $request->nm_kandang,
            'kapasitas' => $request->kapasitas,
            'jumlah_hewan' => $request->jumlah_hewan,
            'jenis_hewan' => $request->jenis_hewan,
            'Hasil_Produksi' => $request->Hasil_Produksi,
            'Jml_produksi' => $request->Jml_produksi ?? 0, // ✅ Default 0
            'keterangan' => $request->keterangan,
            'tgl_produksi' => $request->tgl_produksi, // ✅ Pastikan terisi
            'lama_produksi' => $request->lama_produksi // ✅ Pastikan terisi
        ]);

        // ✅ Log hasil create
        Log::info('Kandang Created:', $kandang->toArray());

        return response()->json(['message' => 'Data kandang berhasil ditambahkan', 'data' => $kandang], 201);
    }

    public function update(Request $request, $id)
    {
        $kandang = Kandang::find($id);
        if (!$kandang) {
            return response()->json(['message' => 'Kandang tidak ditemukan'], 404);
        }

        // ✅ Logging untuk debugging
        Log::info('Kandang Update Request:', $request->all());

        $kandang->update([
            'nm_kandang' => $request->nm_kandang ?? $kandang->nm_kandang,
            'kapasitas' => $request->kapasitas ?? $kandang->kapasitas,
            'jumlah_hewan' => $request->jumlah_hewan ?? $kandang->jumlah_hewan,
            'jenis_hewan' => $request->jenis_hewan ?? $kandang->jenis_hewan,
            'Hasil_Produksi' => $request->Hasil_Produksi ?? $kandang->Hasil_Produksi,
            'Jml_produksi' => $request->Jml_produksi ?? $kandang->Jml_produksi,
            'keterangan' => $request->keterangan ?? $kandang->keterangan,
            'tgl_produksi' => $request->tgl_produksi ?? $kandang->tgl_produksi,
            'lama_produksi' => $request->lama_produksi ?? $kandang->lama_produksi // ✅ FIX INI!
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


    public function panen(Request $request, $id)
    {
        try {
            Log::info('Panen Kandang Request:', [
                'id_kandang' => $id,
                'data' => $request->all()
            ]);

            // Validasi input
            $request->validate([
                'kualitas' => 'required|string|max:50',
                'jumlah_produksi' => 'required|integer|min:1'
            ]);

            // Cari kandang
            $kandang = Kandang::find($id);
            if (!$kandang) {
                return response()->json([
                    'message' => 'Kandang tidak ditemukan'
                ], 404);
            }

            DB::beginTransaction();

            // ✅ Simpan ke tabel panen sesuai struktur Anda
            $panen = Panen::create([
                'tgl_panen' => now()->format('Y-m-d'),
                'jenis_panen' => $kandang->Hasil_Produksi, // Contoh: "Telur", "Susu"
                'jumlah' => $request->jumlah_produksi,
                'kualitas' => $request->kualitas,
                'id_tumbuhan' => null, // Karena ini panen ternak
                'id_ternak' => $kandang->id_kandang // ✅ ID kandang/ternak
            ]);

            // ✅ Kandang TIDAK dihapus, hanya reset data produksi
            $kandang->update([
                'tgl_produksi' => now()->format('Y-m-d'), // Reset tanggal produksi
                'Jml_produksi' => 0 // Reset jumlah produksi
            ]);

            DB::commit();

            Log::info('Panen Kandang Success:', [
                'panen_id' => $panen->id_panen,
                'kandang_id' => $kandang->id_kandang
            ]);

            return response()->json([
                'message' => 'Produksi berhasil dipanen! Data kandang tetap tersimpan.',
                'data' => [
                    'panen' => $panen,
                    'kandang' => $kandang
                ]
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Panen Kandang Error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'Terjadi kesalahan saat memproses panen',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}