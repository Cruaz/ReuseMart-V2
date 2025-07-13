<?php

namespace App\Http\Controllers;

use App\Models\Penitipan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PenitipanController extends Controller
{
    public function index()
    {
        $penitipan = Penitipan::with('barang', 'penitip')->paginate(7);
    
        return response()->json([
            'success' => true,
            'data' => $penitipan
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'id_pegawai' => 'required|integer',
            'peg_id_pegawai' => 'required|integer',
            'tanggal_penitipan' => 'required|date',
            'masa_penitipan' => 'required|integer',
            'batas_pengambilan' => 'required|date',
            'tanggal_konfirmasi_pengambilan' => 'nullable|date',
        ]);

        $penitip = Auth::guard('penitip')->user();

        $lastId = Penitipan::latest('id_penitipan')->first();
        $newId = $lastId ? $lastId->id_penitipan + 1 : 1;

        $penitipan = $penitip->penitipan()->create([
            'id_penitipan' => $newId,
            'id_penitip' => $penitip->id_penitip,
            'id_pegawai' => $request->id_pegawai,
            'peg_id_pegawai' => $request->peg_id_pegawai,
            'tanggal_penitipan' => $request->tanggal_penitipan,
            'masa_penitipan' => $request->masa_penitipan,
            'batas_pengambilan' => $request->batas_pengambilan,
            'tanggal_konfirmasi_pengambilan' => $request->tanggal_konfirmasi_pengambilan,
        ]);

        return response()->json([
            'success' => true,
            'data' => $penitipan,
            'message' => 'Penitipan berhasil ditambahkan!'
        ]);
    }

    public function show($id)
    {
        $penitip = Auth::guard('penitip')->user();
        $penitipan = $penitip->penitipan()->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $penitipan,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_pegawai' => 'integer',
            'peg_id_pegawai' => 'integer',
            'tanggal_penitipan' => 'date',
            'masa_penitipan' => 'integer',
            'batas_pengambilan' => 'date',
            'tanggal_konfirmasi_pengambilan' => 'nullable|date',
        ]);

        $penitip = Auth::guard('penitip')->user();
        $penitipan = $penitip->penitipan()->findOrFail($id);

        $penitipan->update($request->all());

        return response()->json([
            'success' => true,
            'data' => $penitipan,
        ]);
    }

    public function destroy($id)
    {
        $penitip = Auth::guard('penitip')->user();
        $penitipan = $penitip->penitipan()->findOrFail($id);

        $penitipan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data penitipan berhasil dihapus'
        ]);
    }

   public function homePenitip()
{
    $penitipan = \App\Models\Penitipan::with('penitipanBarang.barang')
        ->where('id_penitip', auth('penitip')->id())
        ->get();

    return view('pages.homePenitip', compact('penitipan'));
}

public function perpanjang($id)
{
    $penitip = auth('penitip')->user();

    // Cari data penitipan milik penitip yang sedang login
    $penitipan = $penitip->penitipan()->findOrFail($id);

    // Ambil masa_penitipan yang lama (kalau null, pakai tanggal hari ini)
    $currentMasa = $penitipan->masa_penitipan ? Carbon::parse($penitipan->masa_penitipan) : now();

    // Tambah 30 hari
    $newMasa = $currentMasa->copy()->addDays(30);
    $penitipan->masa_penitipan = $newMasa->format('Y-m-d');

    // Set batas_pengambilan = masa_penitipan + 7 hari
    $penitipan->batas_pengambilan = $newMasa->copy()->addDays(7)->format('Y-m-d');

    $penitipan->save();

    return redirect()->back()->with('success', 'Masa penitipan berhasil diperpanjang 30 hari dan batas pengambilan diperbarui.');
}
public function konfirmasi(Request $request, $id)
{
    $request->validate([
        'tanggal_konfirmasi_pengambilan' => 'required|date',
    ]);

    $penitip = auth('penitip')->user();

    $penitipan = $penitip->penitipan()->findOrFail($id);

    $penitipan->tanggal_konfirmasi_pengambilan = $request->tanggal_konfirmasi_pengambilan;

    // Jika ada field status konfirmasi, update juga, misal 'status_konfirmasi' = true
    if (isset($penitipan->status_konfirmasi)) {
        $penitipan->status_konfirmasi = true;
    }

    $penitipan->save();

    return redirect()->back()->with('success', 'Konfirmasi pengambilan berhasil disimpan.');
}

public function daftarPenitipan(Request $request)
{
    $penitipId = auth('penitip')->id();
    $searchTerm = $request->input('search');

    $penitipan = Penitipan::with('penitipanBarang.barang')
        ->where('id_penitip', $penitipId)
        ->where(function ($query) use ($searchTerm) {
            $query->where('id_penitipan', 'like', "%{$searchTerm}%")
                  ->orWhere('tanggal_penitipan', 'like', "%{$searchTerm}%")
                  ->orWhere('masa_penitipan', 'like', "%{$searchTerm}%")
                  ->orWhere('batas_pengambilan', 'like', "%{$searchTerm}%")
                  ->orWhere('tanggal_konfirmasi_pengambilan', 'like', "%{$searchTerm}%")
                  ->orWhereHas('penitipanBarang.barang', function ($q) use ($searchTerm) {
                      $q->where('nama_barang', 'like', "%{$searchTerm}%")
                        ->orWhere('harga_barang', 'like', "%{$searchTerm}%")
                        ->orWhere('tanggal_habis_garansi', 'like', "%{$searchTerm}%");
                  });
        })
        ->orderBy('tanggal_penitipan', 'desc') // ganti created_at
        ->get();

    return view('pages.homePenitip', compact('penitipan'));
}

public function laporanPenitipan(Request $request)
{
    $query = Penitipan::with([
        'penitipanBarang.barang' => function($query) {
            $query->select('id_barang', 'nama_barang');
        },
        'penitip' => function($query) {
            $query->select('id_penitip', 'username');
        }
    ]);

    if ($request->has('tahun')) {
        $query->whereYear('tanggal_penitipan', $request->tahun);
    }

    $perPage = $request->input('per_page', 18);
    $data = $query->paginate($perPage);

    return response()->json([
        'message' => 'Laporan Penitipan Retrieved',
        'data' => $data
    ], 200);
}

    public function historyPenitipMobile(Request $request)
    {
        // Mendapatkan user penitip yang terotentikasi
        $penitip = $request->user();

        if (!$penitip) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Mengambil data penitipan beserta relasi barang-barangnya
        $penitipanHistory = Penitipan::with('barang.transaksi') // Eager load barang dan relasi transaksinya
                    ->where('id_penitip', $penitip->id_penitip)
                    ->orderBy('tanggal_penitipan', 'desc')
                    ->get();

        // flatMap digunakan untuk mengubah struktur data dari beberapa penitipan (yang masing-masing punya banyak barang)
        // menjadi satu daftar riwayat barang yang flat.
        $history = $penitipanHistory->flatMap(function ($penitipan) {
            // Untuk setiap record penitipan, kita lakukan mapping pada barang-barangnya
            return $penitipan->barang->map(function ($barang) use ($penitipan) {
                // Cek apakah barang sudah terjual (memiliki relasi ke transaksi)
                $transaksi = $barang->transaksi;

                // Logika untuk pendapatan (asumsi komisi 20% untuk Reusemart, 80% untuk penitip)
                // Sesuaikan logika ini dengan aturan bisnis Anda.
                $pendapatan = $transaksi ? ($barang->harga_barang * 0.80) : 0;

                return [
                    'kode_barang' => $barang->id_barang,
                    'nama_barang' => $barang->nama_barang,
                    // Gunakan tanggal_penitipan dari model Penitipan
                    'tanggal_masuk' => $penitipan->tanggal_penitipan,
                    // Ambil tanggal dari relasi transaksi jika ada
                    'tanggal_laku' => $transaksi ? Carbon::parse($transaksi->tanggal_transaksi)->toDateString() : 'Belum terjual',
                    'harga_jual_bersih' => $transaksi ? (int)$barang->harga_barang : 0,
                    'pendapatan' => (int)$pendapatan,
                ];
            });
        });

        // Mengembalikan data dalam format yang sesuai dengan ekspektasi Flutter
        return response()->json([
            'data' => [
                'data' => $history
            ]
        ]);
    }
}
