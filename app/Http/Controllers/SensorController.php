<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use Illuminate\Http\Request;

class SensorController extends Controller
{
    
    public function index()
    {
        $sensor = Sensor::with(['tanaman', 'kandang'])->get();

        // ✅ Return langsung array tanpa wrapper
        return response()->json($sensor, 200);
    }
    


    // Tampilkan data sensor berdasarkan ID
    public function show($id)
    {
        $sensor = Sensor::with(['tanaman', 'kandang'])->find($id);
        if (!$sensor) {
            return response()->json(['message' => 'Data sensor tidak ditemukan'], 404);
        }
        return response()->json($sensor, 200);
    }

    // Tambah data sensor baru
    public function store(Request $request)
    {
        $request->validate([
            'id_tanaman' => 'nullable|exists:tanaman,id_tanaman',
            'id_kandang' => 'nullable|exists:kandang,id_kandang',
            'lokasi' => 'required|string|max:100',
            'populasi' => 'required|integer|min:1',
            'suhu' => 'required|numeric|min:-50|max:100',
            'kelembapan' => 'required|numeric|min:0|max:100',
            'produktivitas' => 'required|numeric|min:0',
            'status_kesehatan' => 'required|string|max:100',
            'waktu' => 'required|date'
        ]);

        $sensor = Sensor::create([
            'id_tanaman' => $request->id_tanaman,
            'id_kandang' => $request->id_kandang,
            'lokasi' => $request->lokasi,
            'populasi' => $request->populasi,
            'suhu' => $request->suhu,
            'kelembapan' => $request->kelembapan,
            'produktivitas' => $request->produktivitas,
            'status_kesehatan' => $request->status_kesehatan,
            'waktu' => $request->waktu
        ]);

        return response()->json([
            'message' => 'Data sensor berhasil ditambahkan',
            'data' => $sensor
        ], 201);
    }

    // Update data sensor berdasarkan ID
    public function update(Request $request, $id)
    {
        $sensor = Sensor::find($id);
        if (!$sensor) {
            return response()->json(['message' => 'Data sensor tidak ditemukan'], 404);
        }

        $sensor->update([
            'id_tanaman' => $request->id_tanaman ?? $sensor->id_tanaman,
            'id_kandang' => $request->id_kandang ?? $sensor->id_kandang,
            'lokasi' => $request->lokasi ?? $sensor->lokasi,
            'populasi' => $request->populasi ?? $sensor->populasi,
            'suhu' => $request->suhu ?? $sensor->suhu,
            'kelembapan' => $request->kelembapan ?? $sensor->kelembapan,
            'produktivitas' => $request->produktivitas ?? $sensor->produktivitas,
            'status_kesehatan' => $request->status_kesehatan ?? $sensor->status_kesehatan,
            'waktu' => $request->waktu ?? $sensor->waktu,
        ]);

        return response()->json([
            'message' => 'Data sensor berhasil diupdate',
            'data' => $sensor
        ], 200);
    }

    // Hapus data sensor berdasarkan ID
    public function destroy($id)
    {
        $sensor = Sensor::find($id);
        if (!$sensor) {
            return response()->json(['message' => 'Data sensor tidak ditemukan'], 404);
        }

        $sensor->delete();
        return response()->json(['message' => 'Data sensor berhasil dihapus'], 200);
    }
}
