<?php

namespace App\Http\Controllers;

use App\Models\Komisi;
use Illuminate\Http\Request;

class KomisiController extends Controller
{
    // Menampilkan semua data komisi
    public function index()
    {
        $data = Komisi::with(['pegawai', 'penitip'])->get();

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    // Menyimpan data komisi baru
    public function store(Request $request)
    {
        $request->validate([
            'id_transaksi' => 'required|unique:komisi,id_transaksi',
            'id_penitip' => 'required|exists:penitip,id_penitip',
            'id_pegawai' => 'nullable|exists:pegawai,id_pegawai',
            'komisi_hunter' => 'required|numeric|min:0',
            'komisi_reusemart' => 'required|numeric|min:0',
            'bonus_penitip' => 'required|numeric|min:0',
        ]);

        $komisi = Komisi::create($request->all());

        return response()->json([
            'success' => true,
            'data' => $komisi,
            'message' => 'Data komisi berhasil ditambahkan.'
        ]);
    }

    // Menampilkan satu data komisi
    public function show($id_transaksi)
    {
        $komisi = Komisi::with(['pegawai', 'penitip'])->findOrFail($id_transaksi);

        return response()->json([
            'success' => true,
            'data' => $komisi,
        ]);
    }

    // Mengubah data komisi
    public function update(Request $request, $id_transaksi)
    {
        $komisi = Komisi::findOrFail($id_transaksi);

        $request->validate([
            'id_penitip' => 'sometimes|exists:penitip,id_penitip',
            'id_pegawai' => 'sometimes|exists:pegawai,id_pegawai',
            'komisi_hunter' => 'sometimes|numeric|min:0',
            'komisi_reusemart' => 'sometimes|numeric|min:0',
            'bonus_penitip' => 'sometimes|numeric|min:0',
        ]);

        $komisi->update($request->all());

        return response()->json([
            'success' => true,
            'data' => $komisi,
            'message' => 'Data komisi berhasil diperbarui.'
        ]);
    }

    // Menghapus data komisi
    public function destroy($id_transaksi)
    {
        $deleted = Komisi::destroy($id_transaksi);

        if (!$deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data komisi berhasil dihapus.'
        ]);
    }
    public function totalKomisiHunter($id_pegawai)
{
    // Validasi hanya untuk pegawai dengan ID 7 dan 8
    if (!in_array($id_pegawai, [7, 8])) {
        return response()->json([
            'success' => false,
            'message' => 'Anda tidak memiliki akses ke data komisi ini',
        ], 403);
    }

    $totalKomisi = Komisi::where('id_pegawai', $id_pegawai)
                        ->sum('komisi_hunter');

    return response()->json([
        'success' => true,
        'total_komisi' => $totalKomisi,
        'id_pegawai' => $id_pegawai,
    ]);
}
}
