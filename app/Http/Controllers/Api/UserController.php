<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;

class UserController extends Controller
{
    public function index(){
        $user = User::latest()->get();
        $res = [
            'success' => true,
            'message' => 'Daftar user',
            'data' => $user,
        ];
        return response()->json($res, 200);

    }
    public function store(request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success'=> false,
                'massage'=> 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->save();
            return response()->json([
                'success' => true,
                'message' => 'data berhasil dibuat',
                'data' => $user,
            ], 201);
         } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'terjado kesalahan',
                    'errors' => $e->getMessage(),
                ], 500);
            }
        }

        public function show($id)
        {
            try {
                $user = User::findOrfail($id);
                return response()->json([
                    'success' => true,
                    'message' => 'detail user',
                    'data' => $user,
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => true,
                    'message' => 'data tidak ditemukan',
                    'data' => $e->getMessage(),
                ], 404);
            }
        }
        public function update(request $request, $id)
        {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|unique:users',
                'password' => 'required|min:8',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success'=> false,
                    'massage'=> 'Validasi gagal',
                    'errors' => $validator->errors(),
                ], 422);
            }

            try {
                $user = User::findOrFail($id);
                $user->name = $request->name;
                $user->email = $request->email;
                $user->password = bcrypt($request->password);
                $user->save();
                return response()->json([
                    'success' => true,
                    'message' => 'data berhasil diperbarui',
                    'data' => $user,
                ], 200);
             } catch (\Exception $e) {
                    return response()->json([
                        'success' => false,
                        'message' => 'terjadI kesalahan',
                        'errors' => $e->getMessage(),
                    ], 500);
                }
            }
            public function destroy($id)
        {
            try {
                $user = User::findOrfail($id);
                $user->delete();
                return response()->json([
                    'success' => true,
                    'message' => 'user' . $user->name . ' berhasil di hapus',
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => true,
                    'message' => 'data tidak ditemukan',
                    'data' => $e->getMessage(),
                ], 404);
            }
        }
}
