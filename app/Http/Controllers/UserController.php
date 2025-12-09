<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(UserModel::all(), 200);
    }

    public function show($id)
    {
        $user = UserModel::find($id);
        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }
        return response()->json($user, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'username' => 'required|unique:user,username',
            'password' => 'required',
            'gmail' => 'required|email|unique:user,gmail',
            'role' => 'required',
            'profile_picture' => 'nullable|image|max:2048'
        ]);

        $user = UserModel::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'gmail' => $request->gmail,
            'role' => $request->role,
            'profile_picture' => $request->profile_picture ?? null
        ]);

        return response()->json(['message' => 'User berhasil ditambahkan', 'data' => $user], 201);
    }

    public function update(Request $request, $id)
    {
        $user = UserModel::find($id);
        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        $request->validate([
            'nama' => 'nullable|string',
            'gmail' => 'nullable|email',
            'password' => 'nullable|min:6',
            'profile_picture' => 'nullable|string'
        ]);

        $updateData = [];

        if ($request->has('nama')) {
            $updateData['nama'] = $request->nama;
        }

        if ($request->has('gmail')) {
            $updateData['gmail'] = $request->gmail;
        }

        if ($request->has('password') && !empty($request->password)) {
            $updateData['password'] = bcrypt($request->password);
        }

        if ($request->has('profile_picture')) {
            $updateData['profile_picture'] = $request->profile_picture;
        }

        $user->update($updateData);

        return response()->json([
            'message' => 'User berhasil diupdate',
            'data' => $user
        ], 200);
    }

    public function uploadProfilePicture(Request $request)
    {
        $request->validate([
            'id_user' => 'required|exists:user,id_user', // <-- GANTI KE id_user
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user = UserModel::find($request->id_user);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ], 404);
        }

        try {
            // Hapus foto lama jika ada
            if ($user->profile_picture && Storage::exists('public/' . $user->profile_picture)) {
                Storage::delete('public/' . $user->profile_picture);
            }

            // Upload foto baru
            $file = $request->file('profile_picture');
            $fileName = 'profile_' . $user->id_user . '_' . time() . '.' . $file->getClientOriginalExtension(); // <-- id_user
            $path = $file->storeAs('profile_pictures', $fileName, 'public');

            // Update database
            $user->profile_picture = $path;
            $user->save();

            // URL lengkap untuk gambar
            $imageUrl = url('storage/' . $path);

            return response()->json([
                'success' => true,
                'message' => 'Profile picture berhasil diupload',
                'data' => [
                    'profile_picture' => $path,
                    'profile_picture_url' => $imageUrl
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal upload profile picture: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $user = UserModel::find($id);
        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        $user->delete();
        return response()->json(['message' => 'User berhasil dihapus'], 200);
    }

    public function login(Request $request)
    {
        $request->validate([
            'gmail' => 'required',
            'password' => 'required'
        ]);

        $user = UserModel::where('gmail', $request->gmail)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'gmail atau password salah'], 401);
        }

        return response()->json([
            'message' => 'Login berhasil',
            'data' => [
                'id_user' => $user->id_user, // <-- GANTI INI
                'id' => $user->id_user,      // <-- TAMBAH INI JIKA FRONTEND PERLU 'id'
                'nama' => $user->nama,
                'username' => $user->username,
                'gmail' => $user->gmail,
                'role' => $user->role,
                'profile_picture' => $user->profile_picture
            ]
        ], 200);
    }

    public function logout(Request $request)
    {
        return response()->json(['message' => 'Logout berhasil'], 200);
    }
}
