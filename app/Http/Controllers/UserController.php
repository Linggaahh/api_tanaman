<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;

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
            'role' => 'required'
        ]);

        $user = UserModel::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'password' => bcrypt($request->password), 
            'gmail' => $request->gmail,
            'role' => $request->role
        ]);

        return response()->json(['message' => 'User berhasil ditambahkan', 'data' => $user], 201);
    }


    public function update(Request $request, $id)
    {
        $user = UserModel::find($id);
        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        $user->update([
            'nama' => $request->nama ?? $user->nama,
            'username' => $request->username ?? $user->username,
            'password' => $request->password ? bcrypt($request->password) : $user->password,
            'gmail' => $request->gmail ?? $user->gmail,
            'role' => $request->role ?? $user->role
        ]);

        return response()->json(['message' => 'User berhasil diupdate', 'data' => $user], 200);
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
            'username' => 'required',
            'password' => 'required'
        ]);

        $user = UserModel::where('username', $request->username)->first();

        if (!$user || !\Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Username atau password salah'], 401);
        }

        return response()->json([
            'message' => 'Login berhasil',
            'data' => [
                'id' => $user->id,
                'nama' => $user->nama,
                'username' => $user->username,
                'gmail' => $user->gmail,
                'role' => $user->role
    ]
], 200);
    }
}
