<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tag;
use Str;
use Validator;

class TagController extends Controller
{
    public function index(){
        $tag = Tag::latest()->get();
        $res = [
            'success' => true,
            'message' => 'Daftar Tag',
            'data' => $tag,
        ];
        return response()->json($res, 200);
    }

    public function store(request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_tag' => 'required|unique:tags',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success'=> false,
                'massage'=> 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $tag = new Tag();
            $tag->nama_tag = $request->nama_tag;
            $tag->slug = Str::slug($request->nama_tag);
            $tag->save();
            return response()->json([
                'success' => true,
                'message' => 'data berhasil dibuat',
                'data' => $tag,
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
                $tag = Tag::findOrfail($id);
                return response()->json([
                    'success' => true,
                    'message' => 'detail tag',
                    'data' => $tag,
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
                'nama_tag' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success'=> false,
                    'massage'=> 'Validasi gagal',
                    'errors' => $validator->errors(),
                ], 422);
            }

            try {
                $tag = Tag::findOrFail($id);
                $tag->nama_tag = $request->nama_tag;
                $tag->slug = Str::slug($request->nama_tag);
                $tag->save();
                return response()->json([
                    'success' => true,
                    'message' => 'data berhasil diperbarui',
                    'data' => $tag,
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
                    $tag = Tag::findOrfail($id);
                    $tag->delete();
                    return response()->json([
                        'success' => true,
                        'message' => 'tag' . $tag->nama_tag . ' berhasil di hapus',
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
