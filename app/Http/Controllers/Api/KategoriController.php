<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kategori;
use Str;
use Validator;

class KategoriController extends Controller
{
    public function index(){
        $kategori = Kategori::latest()->get();
        $res = [
            'success' => true,
            'message' => 'Daftar Kategori',
            'data' => $kategori,
        ];
        return response()->json($res, 200);

    }
    public function store(request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_kategori' => 'required|unique:kategoris',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success'=> false,
                'massage'=> 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $kategori = new Kategori();
            $kategori->nama_kategori = $request->nama_kategori;
            $kategori->slug = Str::slug($request->nama_kategori);
            $kategori->save();
            return response()->json([
                'success' => true,
                'message' => 'data berhasil dibuat',
                'data' => $kategori,
            ], 201);
         } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'terjadi kesalahan',
                    'errors' => $e->getMessage(),
                ], 500);
            }
        }

        public function show($id)
        {
            try {
                $kategori = Kategori::findOrfail($id);
                return response()->json([
                    'success' => true,
                    'message' => 'detail kategori',
                    'data' => $kategori,
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
                'nama_kategori' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success'=> false,
                    'massage'=> 'Validasi gagal',
                    'errors' => $validator->errors(),
                ], 422);
            }

            try {
                $kategori = Kategori::findOrFail($id);
                $kategori->nama_kategori = $request->nama_kategori;
                $kategori->slug = Str::slug($request->nama_kategori);
                $kategori->save();
                return response()->json([
                    'success' => true,
                    'message' => 'data berhasil diperbarui',
                    'data' => $kategori,
                ], 200);
             } catch (\Exception $e) {
                    return response()->json([
                        'success' => false,
                        'message' => 'terjado kesalahan',
                        'errors' => $e->getMessage(),
                    ], 500);
                }
            }
            public function destroy($id)
        {
            try {
                $kategori = Kategori::findOrfail($id);
                $kategori->delete();
                return response()->json([
                    'success' => true,
                    'message' => 'kategori' . $kategori->nama_kategori . ' berhasil di hapus',
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

