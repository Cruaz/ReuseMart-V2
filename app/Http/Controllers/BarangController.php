<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Diskusi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Exception;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Barang::whereNull('id_transaksi')
                ->where(function ($query) {
                    $query->whereDoesntHave('donasi') // Barang yang tidak ada di tabel donasi
                        ->orWhereHas('donasi', function ($donasiQuery) {
                            $donasiQuery->where('status_donasi', '!=', 'Disetujui'); // Donasi tidak disetujui
                        });
                });

            if ($request->has('search') && $request->search != '') {
                $query->where('nama_barang', 'like', '%' . $request->search . '%');
            }

            $perPage = $request->input('per_page', 8);
            $barang = $query->paginate($perPage);

            if ($request->expectsJson()) {
                return response()->json(['data' => $barang]);
            }

            return view('pages.homeUmum', ['barang' => $barang]);

        } catch (Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function indexMobile(Request $request)
    {
        try {
            $query = Barang::whereNull('id_transaksi')
                ->where(function ($query) {
                    $query->whereDoesntHave('donasi') // Barang yang tidak ada di tabel donasi
                        ->orWhereHas('donasi', function ($donasiQuery) {
                            $donasiQuery->where('status_donasi', '!=', 'Disetujui'); // Donasi tidak disetujui
                        });
                });

            if ($request->has('search') && $request->search != '') {
                $query->where('nama_barang', 'like', '%' . $request->search . '%');
            }

            $perPage = $request->input('per_page', 8);
            $barang = $query->paginate($perPage);


            return response()->json(['data' => $barang]);

        } catch (Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function homePembeli(Request $request)
    {
        $query = Barang::whereNull('id_transaksi')
            ->where(function ($query) {
                $query->whereDoesntHave('donasi')
                    ->orWhereHas('donasi', function ($donasiQuery) {
                        $donasiQuery->where('status_donasi', '!=', 'Disetujui');
                    });
            });

        if ($request->has('search') && $request->search != '') {
            $query->where('nama_barang', 'like', '%' . $request->search . '%');
        }

        $perPage = $request->input('per_page', 8);
        $barang = $query->paginate($perPage);

        if ($request->expectsJson()) {
            return response()->json(['data' => $barang]);
        }

        return view('pages.homePembeli', ['barang' => $barang]);
    }

    public function store(Request $request)
    {
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'id_transaksi' => 'nullable',
            'nama_barang' => 'required|string',
            'harga_barang' => 'required|numeric',
            'kategori_barang' => 'required|string',
            'status_garansi_barang' => 'required|string',
            'tanggal_habis_garansi' => 'nullable|date',
            'deskripsi_barang' => 'nullable|string',
            'review_barang' => 'nullable|string',
            'berat_barang' => 'required|numeric',
            'gambar_barang' => 'nullable|image',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $lastId = Barang::latest('id_barang')->first();
        $newId = $lastId ? 'BRG' . str_pad((int) substr($lastId->id_barang, 3) + 1, 3, '0', STR_PAD_LEFT) : 'BRG001';
        $storeData['id_barang'] = $newId;

        $uploadFolder = 'Barang';
        $image = $request->file('gambar_barang');
        if ($image) {
            $image_uploaded_path = $image->store($uploadFolder, 'public');
            $uploadedImageResponse = basename($image_uploaded_path);
            $storeData['gambar_barang'] = $uploadedImageResponse;
        } else {
            $storeData['gambar_barang'] = null;
        }

        $barang = Barang::create($storeData);

        return response([
            'message' => 'Barang Added Successfully',
            'data' => $barang,
        ], 200);
    }

    public function update(Request $request, string $id)
    {
        $barang = Barang::find($id);
        if (is_null($barang)) {
            return response(['message' => 'Barang Not Found'], 404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'id_transaksi' => 'nullable',
            'nama_barang' => 'sometimes|required|string',
            'harga_barang' => 'sometimes|required|numeric',
            'kategori_barang' => 'sometimes|required|string',
            'status_garansi_barang' => 'sometimes|required|string',
            'tanggal_habis_garansi' => 'nullable|date',
            'deskripsi_barang' => 'nullable|string',
            'review_barang' => 'nullable|string',
            'berat_barang' => 'sometimes|required|numeric',
            'gambar_barang' => 'nullable|image',
        ]);

        if ($request->hasFile('gambar_barang')) {
            $uploadFolder = 'Barang';
            $image = $request->file('gambar_barang');
            $image_uploaded_path = $image->store($uploadFolder, 'public');
            $uploadedImageResponse = basename($image_uploaded_path);
            if ($barang->gambar_barang) {
                Storage::disk('public')->delete('Barang/' . $barang->gambar_barang);
            }
            
            $updateData['gambar_barang'] = $uploadedImageResponse;
        }

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $barang->update($updateData);

        return response([
            'message' => 'Barang Updated Successfully',
            'data' => $barang,
        ], 200);
    }

    public function destroy(string $id)
    {
        $barang = Barang::find($id);

        if (is_null($barang)) {
            return response(['message' => 'Barang Not Found'], 404);
        }

        $barang->delete();

        return response([
            'message' => 'Barang Deleted Successfully'
        ], 200);
    }

    public function filterByKategori($slug, Request $request)
    {
        $kategoriMap = [
            'elektronik' => 'Elektronik & Gadget',
            'pakaian' => 'Pakaian & Aksesori',
            'perabotan' => 'Perabotan Rumah Tangga',
            'buku' => 'Buku, Alat Tulis, & Peralatan Sekolah',
            'hobi' => 'Hobi, Mainan, & Koleksi',
            'bayi' => 'Perlengkapan Bayi & Anak',
            'otomotif' => 'Otomotif & Aksesori',
            'taman' => 'Perlengkapan Taman & Outdoor',
            'kantor' => 'Peralatan Kantor & Industri',
            'kecantikan' => 'Kosmetik & Perawatan Diri',
        ];

        if (!array_key_exists($slug, $kategoriMap)) {
            abort(404, 'Kategori tidak ditemukan');
        }

        $namaKategori = $kategoriMap[$slug];

        // Terapkan filter yang sama seperti di index()
        $query = Barang::whereNull('id_transaksi')
            ->where('kategori_barang', $namaKategori)
            ->where(function ($query) {
                $query->whereDoesntHave('donasi')
                    ->orWhereHas('donasi', function ($donasiQuery) {
                        $donasiQuery->where('status_donasi', '!=', 'Disetujui');
                    });
            });

        if ($request->has('search') && $request->search != '') {
            $query->where('nama_barang', 'like', '%' . $request->search . '%');
        }

        $barang = $query->paginate(8);

        return view('pages.homeUmum', compact('barang', 'slug'));
    }


    public function filterKategoriPembeli(Request $request, $slug)
    {
        // Mapping slug ke nama kategori sesuai yang digunakan di database
        $slugToKategori = [
            'elektronik' => 'Elektronik & Gadget',
            'pakaian' => 'Pakaian & Aksesori',
            'perabotan' => 'Perabotan Rumah Tangga',
            'buku' => 'Buku, Alat Tulis, & Peralatan Sekolah',
            'hobi' => 'Hobi, Mainan, & Koleksi',
            'bayi' => 'Perlengkapan Bayi & Anak',
            'otomotif' => 'Otomotif & Aksesori',
            'taman' => 'Perlengkapan Taman & Outdoor',
            'kantor' => 'Peralatan Kantor & Industri',
            'kecantikan' => 'Kosmetik & Perawatan Diri',
        ];

        // Query barang yang belum terjual dan bukan donasi yang disetujui
        $query = Barang::whereNull('id_transaksi')
            ->where(function ($query) {
                $query->whereDoesntHave('donasi')
                    ->orWhereHas('donasi', function ($donasiQuery) {
                        $donasiQuery->where('status_donasi', '!=', 'Disetujui');
                    });
            });

        // Filter berdasarkan kategori jika bukan 'all'
        if ($slug !== 'all' && isset($slugToKategori[$slug])) {
            $query->where('kategori_barang', $slugToKategori[$slug]);
        }

        // Filter berdasarkan pencarian nama barang
        if ($request->has('search') && $request->search != '') {
            $query->where('nama_barang', 'like', '%' . $request->search . '%');
        }

        // Pagination
        $perPage = $request->input('per_page', 8);
        $barang = $query->paginate($perPage);

        // Kirim slug juga ke view supaya bisa highlight kategori aktif
        return view('pages.homePembeli', [
            'barang' => $barang,
            'slug' => $slug
        ]);
    }

    public function showDetailUmum($id)
    {
        $item = Barang::with('diskusi.pembeli')->findOrFail($id);
        $diskusi = Diskusi::with('pembeli')->where('id_barang', $id)->orderByDesc('id_diskusi')->get();

        return view('pages.detailBarang', compact('item', 'diskusi'));
    }

    public function showDetailPembeli($id)
    {
        $item = Barang::with('diskusi.pembeli')->findOrFail($id);
        $diskusi = Diskusi::with('pembeli')->where('id_barang', $id)->orderByDesc('id_diskusi')->get();

        return view('pages.detailBarangPembeli', compact('item', 'diskusi'));
    }

    public function kategoriLaporan(Request $request)
    {
        $year = $request->input('tahun', date('Y'));

        $soldItemsQuery = DB::table('barang')
            ->join('transaksi', 'barang.id_transaksi', '=', 'transaksi.id_transaksi')
            ->whereYear('transaksi.tanggal_transaksi', $year)
            ->whereIn('transaksi.status_transaksi', ['Diterima', 'Sedang Dikirim', 'Diambil Sendiri', 'Selesai'])
            ->select('barang.kategori_barang', DB::raw('count(*) as total'))
            ->groupBy('barang.kategori_barang');
        
        $soldItems = $soldItemsQuery->get()->pluck('total', 'kategori_barang')->toArray();

        $failedItemsQuery = DB::table('barang')
            ->leftJoin('transaksi', 'barang.id_transaksi', '=', 'transaksi.id_transaksi')
            ->where(function($query) use ($year) {
                $query->where(function ($q) use ($year) {
                    $q->whereNull('barang.id_transaksi')
                    ->whereRaw('? = ?', [$year, date('Y')]); // agar ikut tahun
                })
                ->orWhere(function($q) use ($year) {
                    $q->where('transaksi.status_transaksi', 'Hangus')
                    ->whereYear('transaksi.tanggal_transaksi', $year);
                });
            })
            ->select('barang.kategori_barang', DB::raw('count(*) as total'))
            ->groupBy('barang.kategori_barang');
        
        $failedItems = $failedItemsQuery->get()->pluck('total', 'kategori_barang')->toArray();

        $allCategories = DB::table('barang')
            ->select('kategori_barang')
            ->distinct()
            ->pluck('kategori_barang')
            ->toArray();

        $totalTerjual = array_sum($soldItems);
        $totalGagal = array_sum($failedItems);
        $totalSemua = $totalTerjual + $totalGagal;

        $result = [];
        foreach ($allCategories as $category) {
            $result[] = [
                'kategori' => $category,
                'terjual' => $soldItems[$category] ?? 0,
                'gagal' => $failedItems[$category] ?? 0,
                'total' => ($soldItems[$category] ?? 0) + ($failedItems[$category] ?? 0)
            ];
        }
        
        $result[] = [
            'kategori' => '<strong>TOTAL SEMUA</strong>',
            'terjual' => '<strong>' . $totalTerjual . '</strong>',
            'gagal' => '<strong>' . $totalGagal . '</strong>',
            'total' => '<strong>' . $totalSemua . '</strong>'
        ];

        return response()->json([
            'message' => 'Laporan kategori berhasil diambil',
            'data' => [
                'data' => $result,
                'current_page' => 1,
                'last_page' => 1,
                'total' => count($result)
            ]
        ], 200);
    }

    public function laporanStok(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));
        $perPage = $request->input('per_page', 10);

        $query = Barang::with([
                'penitipanBarang.penitipan.penitip',
                'penitipanBarang.penitipan.hunter',
                'pegawai.jabatan' // relasi ke jabatan lewat pegawai
            ])
            ->whereHas('penitipanBarang.penitipan', function ($q) use ($tahun) {
                $q->whereYear('tanggal_penitipan', $tahun);
            });

        $data = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function laporanKomisiProduk(Request $request)
    {
        // Validasi input bulan dan tahun, jika tidak ada, gunakan bulan dan tahun saat ini
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));
        $perPage = $request->input('per_page', 10);

        // Query untuk mengambil data komisi produk
        $query = DB::table('komisi')
            ->join('transaksi', 'komisi.id_transaksi', '=', 'transaksi.id_transaksi')
            ->join('barang', 'transaksi.id_transaksi', '=', 'barang.id_transaksi')
            ->leftJoin('penitipanbarang', 'barang.id_barang', '=', 'penitipanbarang.id_barang')
            ->leftJoin('penitipan', 'penitipanbarang.id_penitipan', '=', 'penitipan.id_penitipan')
            ->select(
                'barang.id_barang as id', // Menggunakan alias 'id' agar sesuai dengan frontend
                'barang.id_barang as kode_produk',
                'barang.nama_barang as nama_produk',
                'barang.harga_barang as harga_jual',
                'penitipan.tanggal_penitipan as tanggal_masuk',
                'transaksi.tanggal_transaksi as tanggal_laku',
                'komisi.komisi_hunter',
                'komisi.komisi_reusemart as komisi_reuse_mart', // Menggunakan alias agar sesuai dengan frontend
                'komisi.bonus_penitip'
            )
            ->where('transaksi.status_transaksi', 'Selesai') // Hanya ambil transaksi yang sudah selesai
            ->whereMonth('transaksi.tanggal_transaksi', $bulan)
            ->whereYear('transaksi.tanggal_transaksi', $tahun)
            ->orderBy('transaksi.tanggal_transaksi', 'desc');

        // Lakukan paginasi
        $data = $query->paginate($perPage);

        // Kembalikan response dalam format JSON yang diharapkan oleh frontend
        return response()->json([
            'message' => 'Laporan Komisi Produk berhasil diambil',
            'data' => $data
        ], 200);
    }

    public function laporanPenjualanBulanan(Request $request)
    {
        try {
            $tahun = $request->input('tahun', date('Y'));
            
            $bulanIndo = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ];

            $dataPenjualan = [];
            $totalBarang = 0;
            $totalPenjualan = 0;

            foreach ($bulanIndo as $monthNum => $monthName) {
                $penjualan = DB::table('barang')
                    ->join('transaksi', 'barang.id_transaksi', '=', 'transaksi.id_transaksi')
                    ->whereYear('transaksi.tanggal_transaksi', $tahun)
                    ->whereMonth('transaksi.tanggal_transaksi', $monthNum)
                    ->whereIn('transaksi.status_transaksi', ['Selesai', 'Diterima'])
                    ->select(
                        DB::raw('COUNT(barang.id_barang) as JUMLAH_TERJUAL'),
                        DB::raw('SUM(barang.harga_barang) as penjualan_kotor')
                    )
                    ->first();

                $dataPenjualan[$monthName] = [
                    'JUMLAH_TERJUAL' => $penjualan->JUMLAH_TERJUAL ?? 0,
                    'penjualan_kotor' => $penjualan->penjualan_kotor ?? 0
                ];

                $totalBarang += $penjualan->JUMLAH_TERJUAL ?? 0;
                $totalPenjualan += $penjualan->penjualan_kotor ?? 0;
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'bulanIndo' => $bulanIndo,
                    'dataPenjualan' => $dataPenjualan,
                    'totalBarang' => $totalBarang,
                    'totalPenjualan' => $totalPenjualan,
                    'tahun' => $tahun,
                    'tanggalCetak' => now()->translatedFormat('j F Y')
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
}