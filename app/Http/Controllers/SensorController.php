<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sensor;
use App\Models\Tanaman;
use App\Models\Kandang;

class SensorController extends Controller
{
    public function index()
    {
        $data = Sensor::with(['tanaman', 'kandang'])->get();
        return response()->json($data, 200);
    }

    public function show($id)
    {
        $sensor = Sensor::with(['tanaman', 'kandang'])->find($id);
        if (!$sensor) {
            return response()->json(['message' => 'Data sensor tidak ditemukan'], 404);
        }
        return response()->json($sensor, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_tanaman' => 'nullable|exists:tanaman,id_tanaman',
            'id_kandang' => 'nullable|exists:kandang,id_kandang',
            'lokal' => 'required|string|max:100',
            'kelembapan' => 'required|numeric|min:0|max:100',
            'produktivitas' => 'required|numeric|min:0',
            'eto_kesehatan' => 'required|string|max:100',
            'populasi' => 'required|integer|min:1',
            'waktu' => 'required|date'
        ]);

        $sensor = Sensor::create([
            'id_tanaman' => $request->id_tanaman,
            'id_kandang' => $request->id_kandang,
            'lokal' => $request->lokal,
            'kelembapan' => $request->kelembapan,
            'produktivitas' => $request->produktivitas,
            'eto_kesehatan' => $request->eto_kesehatan,
            'populasi' => $request->populasi,
            'waktu' => $request->waktu
        ]);

        return response()->json(['message' => 'Data sensor berhasil ditambahkan', 'data' => $sensor], 201);
    }

    public function update(Request $request, $id)
    {
        $sensor = Sensor::find($id);
        if (!$sensor) {
            return response()->json(['message' => 'Data sensor tidak ditemukan'], 404);
        }

        // Validasi foreign key jika diubah
        if ($request->has('id_tanaman')) {
            $request->validate(['id_tanaman' => 'nullable|exists:tanaman,id_tanaman']);
        }
        if ($request->has('id_kandang')) {
            $request->validate(['id_kandang' => 'nullable|exists:kandang,id_kandang']);
        }

        $sensor->update([
            'id_tanaman' => $request->id_tanaman ?? $sensor->id_tanaman,
            'id_kandang' => $request->id_kandang ?? $sensor->id_kandang,
            'lokal' => $request->lokal ?? $sensor->lokal,
            'kelembapan' => $request->kelembapan ?? $sensor->kelembapan,
            'produktivitas' => $request->produktivitas ?? $sensor->produktivitas,
            'eto_kesehatan' => $request->eto_kesehatan ?? $sensor->eto_kesehatan,
            'populasi' => $request->populasi ?? $sensor->populasi,
            'waktu' => $request->waktu ?? $sensor->waktu,
        ]);

        return response()->json(['message' => 'Data sensor berhasil diupdate', 'data' => $sensor], 200);
    }

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
