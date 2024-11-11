<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArtikelResource;
use App\Models\Artikel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ArtikelController extends Controller
{
    public function index()
    {
        $artikel = Artikel::latest()->paginate(5);

        return new ArtikelResource(true, 'List Data Artikel', $artikel);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required',
            'isi' => 'required', 
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $artikel = Artikel::create([
            'judul' => $request->judul,
            'isi' => $request->isi,
        ]);

        return new ArtikelResource(true, 'Data Artikel Berhasil Ditambahkan!', $artikel);
    }

    public function show($id)
    {
        $artikel = Artikel::find($id);

        if (!$artikel) {
            return new ArtikelResource(false, 'Data Artikel Tidak Ditemukan!', null);
        }

        return new ArtikelResource(true, 'Detail Data Artikel!', $artikel);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required',
            'isi' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $artikel = Artikel::find($id);

        if (!$artikel) {
            return new ArtikelResource(false, 'Data Artikel Tidak Ditemukan!', null);
        }

        $artikel->update([
            'judul' => $request->judul,
            'isi' => $request->isi,
        ]);

        return new ArtikelResource(true, 'Data Artikel Berhasil Diupdate!', $artikel);
    }

    public function destroy($id)
    {
        $artikel = Artikel::find($id);  

        $artikel->delete(); 

        return new ArtikelResource(true, 'Data Artikel Berhasil Dihapus!', null);
    }
}
