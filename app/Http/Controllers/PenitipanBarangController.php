<?php

namespace App\Http\Controllers;

use App\Models\PenitipanBarang;
use App\Models\Barang;
use Illuminate\Http\Request;

class PenitipanBarangController extends Controller
{
    // Menampilkan semua relasi penitipan-barang
    public function index()
    {
        $data = PenitipanBarang::with(['penitipan', 'barang'])->get();

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    // Menambahkan hubungan penitipan dengan barang
    public function store(Request $request)
    {
        $request->validate([
            'id_penitipan' => 'required|exists:penitipan,id_penitipan',
            'id_barang' => 'required|exists:barang,id_barang',
        ]);

        // Cek duplikasi (karena primary key gabungan)
        $exists = PenitipanBarang::where('id_penitipan', $request->id_penitipan)
            ->where('id_barang', $request->id_barang)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Relasi sudah ada.'
            ], 409);
        }

        $pb = PenitipanBarang::create([
            'id_penitipan' => $request->id_penitipan,
            'id_barang' => $request->id_barang,
        ]);

        return response()->json([
            'success' => true,
            'data' => $pb,
            'message' => 'Barang berhasil ditambahkan ke penitipan.'
        ]);
    }

    // Menampilkan satu entri
    public function show($id_penitipan, $id_barang)
    {
        $pb = PenitipanBarang::with(['penitipan', 'barang'])
            ->where('id_penitipan', $id_penitipan)
            ->where('id_barang', $id_barang)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $pb,
        ]);
    }

    // Menghapus relasi penitipan-barang
    public function destroy($id_penitipan, $id_barang)
    {
        $deleted = PenitipanBarang::where('id_penitipan', $id_penitipan)
            ->where('id_barang', $id_barang)
            ->delete();

        if (!$deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Relasi berhasil dihapus.'
        ]);
    }
}
