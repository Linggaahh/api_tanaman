<?php

namespace App\Http\Controllers;

use App\Models\Tanaman;
use App\Models\Panen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
            'jumlah' => 'required|integer',
            'tgl_tanam' => 'required|date',
            'lama_panen' => 'required|string',
            'lokasi' => 'required|string|max:150',
            'status' => 'required|string|max:50',
            'Foto' => 'required|string|starts_with:data:image'


        ]);

        $tanaman = Tanaman::create([
            'nm_tanaman' => $request->nm_tanaman,
            'varietas' => $request->varietas,
            'jumlah' => $request->jumlah,
            'tgl_tanam' => $request->tgl_tanam,
            'lama_panen' => $request->lama_panen,
            'lokasi' => $request->lokasi,
            'status' => $request->status,
            'Foto' => $request->Foto
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
            'jumlah' => $request->jumlah ?? $tanaman->jumlah,
            'tgl_tanam' => $request->tgl_tanam ?? $tanaman->tgl_tanam,
            'lama_panen' => $request->lama_panen ?? $tanaman->lama_panen,
            'lokasi' => $request->lokasi ?? $tanaman->lokasi,
            'status' => $request->status ?? $tanaman->status,
            'Foto' => $request->Foto ?? $tanaman->Foto
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

    /**
     * Pindahkan tanaman ke tabel panen
     */
    public function panen(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $tanaman = Tanaman::find($id);

            if (!$tanaman) {
                return response()->json(['message' => 'Tanaman tidak ditemukan'], 404);
            }

            // Hitung selisih hari untuk menentukan kualitas
            $plantDate = new \DateTime($tanaman->tgl_tanam);
            $daysToHarvest = (int) preg_replace('/\D/', '', $tanaman->lama_panen);
            $targetDate = clone $plantDate;
            $targetDate->modify("+{$daysToHarvest} days");

            $today = new \DateTime();
            $daysLate = $today->diff($targetDate)->days;
            $isLate = $today > $targetDate;

            // Tentukan kualitas otomatis berdasarkan ketepatan waktu panen
            $kualitas = '';
            if (!$isLate) {
                $kualitas = 'Sangat Baik'; // Tepat waktu atau lebih cepat
            } elseif ($daysLate <= 3) {
                $kualitas = 'Baik'; // Terlambat 1-3 hari
            } elseif ($daysLate <= 7) {
                $kualitas = 'Sedang'; // Terlambat 4-7 hari
            } else {
                $kualitas = 'Kurang Baik'; // Terlambat lebih dari 7 hari
            }

            // Buat data panen baru
            $panen = Panen::create([
                'tgl_panen' => now()->format('Y-m-d'),
                'jenis_panen' => $tanaman->nm_tanaman . ' - ' . $tanaman->varietas,
                'jumlah' => $tanaman->jumlah,
                'kualitas' => $kualitas,
                'id_tumbuhan' => $tanaman->id_tanaman,
                'id_ternak' => null
            ]);


            $tanaman->delete();

            DB::commit();

            return response()->json([
                'message' => 'Tanaman berhasil dipanen dan dipindahkan ke data panen',
                'data' => $panen,
                'kualitas' => $kualitas
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat panen: ' . $e->getMessage());

            return response()->json([
                'message' => 'Terjadi kesalahan saat memproses panen',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
