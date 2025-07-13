<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Pembeli;
use App\Models\Komisi;
use App\Models\Barang;
use App\Models\Penitip;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        $transaksi = Transaksi::with(['alamat', 'barang', 'pembeli', 'pegawai.jabatan'])->paginate($perPage);

        $formatted = $transaksi->getCollection()->transform(function ($item) {
            return [
                'id_transaksi' => $item->id_transaksi,
                'deskripsi_alamat' => $item->alamat ? $item->alamat->deskripsi_alamat : null,
                'tanggal_transaksi' => $item->tanggal_transaksi,
                'jadwal_pengiriman' => $item->jadwal_pengiriman,
                'harga_total_barang' => $item->harga_total_barang,
                'status_transaksi' => $item->status_transaksi,
                'opsi_pengiriman' => $item->opsi_pengiriman,
                'tanggal_pengambilan' => $item->tanggal_pengambilan,
                'tanggal_lunas' => $item->tanggal_lunas,
                'potongan_harga' => $item->potongan_harga,
                'harga_ongkir' => $item->harga_ongkir,
                'poin_pembeli' => $item->poin_pembeli,
                'bukti_pembayaran' => $item->bukti_pembayaran,
                'nomor_transaksi' => $item->nomor_transaksi,
                'barang' => $item->barang->map(function ($b) {
                    return [
                        'nama_barang' => $b->nama_barang,
                        'gambar_barang' => $b->gambar_barang,
                        'harga_barang' => $b->harga_barang,
                        'status_barang' => $b->status_barang,
                    ];
                }),
                'pembeli' => $item->pembeli ? [
                    'email' => $item->pembeli->email,
                    'username' => $item->pembeli->username ?? null,
                ] : null,
                'pegawai' => $item->pegawai ? [
                    'id_pegawai' => $item->pegawai->id_pegawai,
                    'username' => $item->pegawai->username,
                    'nama_jabatan' => $item->pegawai->jabatan->nama_jabatan ?? null,
                ] : null,
            ];
        });

        return response()->json([
            'data' => [
                'data' => $formatted,
                'current_page' => $transaksi->currentPage(),
                'last_page' => $transaksi->lastPage(),
                'per_page' => $transaksi->perPage(),
                'total' => $transaksi->total(),
            ]
        ]);
    }

    public function index2(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        $transaksi = Transaksi::with(['alamat', 'barang'])->where('status_transaksi', 'Menunggu Konfirmasi')->paginate($perPage);

        $formatted = $transaksi->getCollection()->transform(function ($item) {
            return [
                'id_transaksi' => $item->id_transaksi,
                'deskripsi_alamat' => $item->alamat ? $item->alamat->deskripsi_alamat : null,
                'tanggal_transaksi' => $item->tanggal_transaksi,
                'harga_total_barang' => $item->harga_total_barang,
                'status_transaksi' => $item->status_transaksi,
                'opsi_pengiriman' => $item->opsi_pengiriman,
                'tanggal_pengambilan' => $item->tanggal_pengambilan,
                'tanggal_lunas' => $item->tanggal_lunas,
                'potongan_harga' => $item->potongan_harga,
                'harga_ongkir' => $item->harga_ongkir,
                'poin_pembeli' => $item->poin_pembeli,
                'bukti_pembayaran' => $item->bukti_pembayaran,
                'nomor_transaksi' => $item->nomor_transaksi,
                'username' => $item->pembeli ? $item->pembeli->username : null,
                'barang' => $item->barang->map(function ($b) {
                    return [
                        'nama_barang' => $b->nama_barang,
                        'gambar_barang' => $b->gambar_barang,
                        'harga_barang' => $b->harga_barang,
                        'status_barang' => $b->status_barang,
                    ];
                }),
            ];
        });

        return response()->json([
            'data' => [
                'data' => $formatted,
                'current_page' => $transaksi->currentPage(),
                'last_page' => $transaksi->lastPage(),
                'per_page' => $transaksi->perPage(),
                'total' => $transaksi->total(),
            ]
        ]);
    }

    public function historyPembeli(Request $request)
    {
        $pembeli = Auth::guard('pembeli')->user();
        $transaksi = Transaksi::with(['alamat', 'barang'])
            ->where('id_pembeli', $pembeli->id_pembeli)
            ->paginate(7);

        return response()->json($transaksi);
    }

    public function historyPenitip(Request $request)
    {
        $penitip = Auth::guard('penitip')->user();
        $perPage = $request->input('per_page', 10);

        // Ambil transaksi yang memiliki barang dari penitip ini
        $transaksi = Transaksi::with(['barang.penitipanBarang.penitipan', 'komisi'])
            ->whereHas('barang.penitipanBarang.penitipan', function($query) use ($penitip) {
                $query->where('id_penitip', $penitip->id_penitip);
            })
            ->orderBy('tanggal_transaksi', 'desc')
            ->paginate($perPage);

        // Format data untuk response
        $formatted = $transaksi->getCollection()->map(function($item) {
            $barangWithKomisi = $item->barang->map(function($barang) use ($item) {
                // Default nilai komisi jika tidak ada
                $komisiHunter = 0;
                $komisiReuseMart = 0;
                $bonusPenitip = 0;
                
                // Jika ada data komisi
                if ($item->komisi) {
                    $komisiHunter = $item->komisi->komisi_hunter ?? 0;
                    $komisiReuseMart = $item->komisi->komisi_reusemart ?? 0;
                    $bonusPenitip = $item->komisi->bonus_penitip ?? 0;
                }
                
                $harga = $barang->harga_barang;
                $totalDiterima = $harga - $komisiHunter - $komisiReuseMart + $bonusPenitip;
                
                return [
                    'nama_barang' => $barang->nama_barang,
                    'harga_barang' => $barang->harga_barang,
                    'komisi' => [
                        'komisi_hunter' => $komisiHunter,
                        'komisi_reusemart' => $komisiReuseMart,
                        'bonus_penitip' => $bonusPenitip,
                    ],
                    'total_diterima_penitip' => $totalDiterima
                ];
            });

            return [
                'id_transaksi' => $item->id_transaksi,
                'tanggal_transaksi' => $item->tanggal_transaksi,
                'status_transaksi' => $item->status_transaksi,
                'barang' => $barangWithKomisi
            ];
        });

        return response()->json([
            'data' => $formatted,
            'current_page' => $transaksi->currentPage(),
            'last_page' => $transaksi->lastPage(),
            'per_page' => $transaksi->perPage(),
            'total' => $transaksi->total(),
        ]);
    }

    public function show($id)
    {
        $transaksi = Transaksi::with(['alamat', 'pegawai.jabatan'])->find($id);

        if (!$transaksi) {
            return response()->json(['message' => 'Transaksi not found'], 404);
        }

        $data = $transaksi->toArray();
        $data['deskripsi_alamat'] = $transaksi->alamat ? $transaksi->alamat->deskripsi : null;
        $data['jadwal_pengiriman'] = $transaksi->jadwal_pengiriman;
        $data['kurir'] = $transaksi->pegawai ? [
            'id_pegawai' => $transaksi->pegawai->id_pegawai,
            'username' => $transaksi->pegawai->username,
            'nama_jabatan' => $transaksi->pegawai->jabatan->nama_jabatan ?? null,
        ] : null;

        return response()->json(['data' => $data]);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $validate = Validator::make($data, [
            'id_pembeli' => 'required|string',
            'id_alamat' => 'required|string',
            'id_pegawai' => 'nullable|string',
            'tanggal_transaksi' => 'required|date',
            'jadwal_pengiriman' => 'nullable|date',
            'harga_total_barang' => 'required|numeric',
            'status_transaksi' => 'required|string',
            'tanggal_pengambilan' => 'nullable|date',
            'tanggal_lunas' => 'nullable|date',
            'opsi_pengiriman' => 'required|string',
            'potongan_harga' => 'nullable|numeric',
            'harga_ongkir' => 'nullable|numeric',
            'poin_pembeli' => 'nullable|integer',
            'bukti_pembayaran' => 'nullable|image',
            'nomor_transaksi' => 'nullable|string',
        ]);

        if ($validate->fails()) {
            return response()->json(['errors' => $validate->errors()], 400);
        }

        $lastId = Transaksi::latest('id_transaksi')->first();
        $newId = $lastId ? $lastId->id_transaksi + 1 : 1;
        $data['id_transaksi'] = $newId;

        $transaksi = Transaksi::create($data);

        return response()->json([
            'message' => 'Transaksi created successfully',
            'data' => $transaksi
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $transaksi = Transaksi::find($id);

        if (!$transaksi) {
            return response()->json(['message' => 'Transaksi not found'], 404);
        }

        $validate = Validator::make($request->all(), [
            'id_pembeli' => 'sometimes|required|string',
            'id_alamat' => 'sometimes|required|string',
            'id_pegawai' => 'nullable|string',
            'tanggal_transaksi' => 'sometimes|required|date',
            'jadwal_pengiriman' => 'nullable|date',
            'harga_total_barang' => 'sometimes|required|numeric',
            'status_transaksi' => 'sometimes|required|string',
            'tanggal_pengambilan' => 'nullable|date',
            'tanggal_lunas' => 'nullable|date',
            'opsi_pengiriman' => 'sometimes|required|string',
            'potongan_harga' => 'nullable|numeric',
            'harga_ongkir' => 'nullable|numeric',
            'poin_pembeli' => 'nullable|integer',
            'bukti_pembayaran' => 'nullable|image',
            'nomor_transaksi' => 'nullable|string',
        ]);

        if ($validate->fails()) {
            return response()->json(['errors' => $validate->errors()], 400);
        }

        $transaksi->update($request->all());

        return response()->json([
            'message' => 'Transaksi updated successfully',
            'data' => $transaksi
        ]);
    }

    public function updateConfirm(Request $request, $id)
    {
        $transaksi = Transaksi::find($id);

        if (!$transaksi) {
            return response()->json(['message' => 'Transaksi not found'], 404);
        }

        $transaksi->update([
            'status_transaksi' => 'Disiapkan'
        ]);

        if ($transaksi->poin_pembeli > 0) {
            $pembeli = Pembeli::find($transaksi->id_pembeli);
            if ($pembeli) {
                $pembeli->poin_pembeli += $transaksi->poin_pembeli;
                $pembeli->save();
            }
        }

        $this->sendNotificationToPenitip(
            $transaksi,
            'Barang Laku',
            'Barang Anda telah laku dan sedang disiapkan'
        );

        return response()->json(['message' => 'Transaksi berhasil dikonfirmasi']);
    }


    public function batalkanTransaksi(Request $request, $id)
    {
        $transaksi = Transaksi::find($id);

        if (!$transaksi) {
            return response()->json(['message' => 'Transaksi not found'], 404);
        }

        if ($transaksi->poin_spent > 0) {
            $pembeli = Pembeli::find($transaksi->id_pembeli);
            if ($pembeli) {
                $pembeli->poin_pembeli += $transaksi->poin_spent;
                $pembeli->save();
            }
        }

        $transaksi->barang()->update([
            'id_transaksi' => null,
            'status_barang' => null
        ]);

        $transaksi->delete();

        return response()->json([
            'message' => 'Transaksi berhasil dibatalkan dan barang dikembalikan',
            'data' => null
        ]);
    }

    public function destroy($id)
    {
        $transaksi = Transaksi::find($id);

        if (!$transaksi) {
            return response()->json(['message' => 'Transaksi not found'], 404);
        }

        $transaksi->delete();

        return response()->json(['message' => 'Transaksi deleted successfully']);
    }

    public function konfirmasiDiambil($id)
    {
        $transaksi = Transaksi::find($id);

        if (!$transaksi) {
            return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
        }

        $transaksi->status_transaksi = 'Diterima';
        $transaksi->tanggal_pengambilan = now();
        $transaksi->save();

        return response()->json(['message' => 'Status transaksi berhasil diperbarui'], 200);
    }

    public function konfirmasiKirim($id)
    {
        $transaksi = Transaksi::find($id);
        if (!$transaksi) {
            return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
        }

        if ($transaksi->status_transaksi !== 'Disiapkan') {
            return response()->json(['message' => 'Status transaksi tidak dapat diubah'], 400);
        }

        $transaksi->status_transaksi = 'Sedang Dikirim';
        $transaksi->save();

        $this->sendNotificationToPenitip(
            $transaksi,
            'Barang Dikirim',
            'Barang Anda sedang dikirim ke pembeli'
        );

        $this->sendNotificationToPembeli(
            $transaksi,
            'Barang Dikirim',
            'Barang pesanan Anda sedang dikirim'
        );

        return response()->json(['message' => 'Status transaksi diperbarui menjadi Sedang Dikirim']);
    }

    public function penjadwalan(Request $request, $id)
    {
        $transaksi = Transaksi::findOrFail($id);

        $transaksi->id_pegawai = $request->id_pegawai;
        $transaksi->jadwal_pengiriman = $request->jadwal_pengiriman;
        $transaksi->save();

        if ($transaksi->opsi_pengiriman == 1) {
            $this->sendNotificationToPenitip(
                $transaksi,
                'Jadwal Pengiriman',
                'Jadwal pengiriman telah ditetapkan: ' . $transaksi->jadwal_pengiriman
            );

            $this->sendNotificationToPembeli(
                $transaksi,
                'Jadwal Pengiriman',
                'Jadwal pengiriman telah ditetapkan: ' . $transaksi->jadwal_pengiriman
            );

            $this->sendNotificationToKurir(
                $transaksi,
                'Tugas Pengiriman',
                'Anda memiliki tugas pengiriman pada: ' . $transaksi->jadwal_pengiriman
            );
        } else {
            $this->sendNotificationToPenitip(
                $transaksi,
                'Jadwal Pengambilan',
                'Jadwal pengambilan telah ditetapkan: ' . $transaksi->jadwal_pengiriman
            );

            $this->sendNotificationToPembeli(
                $transaksi,
                'Jadwal Pengambilan',
                'Jadwal pengambilan telah ditetapkan: ' . $transaksi->jadwal_pengiriman
            );
        }

        return response()->json(['message' => 'Penjadwalan berhasil disimpan']);
    }

    public function penjadwalanPengambilan(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'jadwal_pengiriman' => 'required|date',
        ]);

        // Cari transaksi
        $transaksi = Transaksi::with('barang')->find($id);
        if (!$transaksi) {
            return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
        }

        // Set jadwal pengiriman baru
        $transaksi->jadwal_pengiriman = $request->jadwal_pengiriman;
        $transaksi->save();

        // Cek apakah sudah lewat 2 hari sejak jadwal pengiriman
        $now = \Carbon\Carbon::now();
        $jadwal = \Carbon\Carbon::parse($transaksi->jadwal_pengiriman);

        if ($now->diffInDays($jadwal, false) <= -2) {
            // Sudah lewat 2 hari

            // Ubah status transaksi
            $transaksi->status_transaksi = 'Hangus';
            $transaksi->save();

            // Ubah status semua barang terkait menjadi "barang untuk donasi"
            foreach ($transaksi->barang as $barang) {
                $barang->status_barang = 'barang untuk donasi';
                $barang->save();
            }

            return response()->json([
                'message' => 'Barang tidak diambil dalam 2 hari, status transaksi diubah ke Hangus dan barang untuk donasi.',
                'transaksi' => $transaksi
            ]);
        }

        $this->sendNotificationToPenitip(
            $transaksi,
            'Jadwal Pengambilan',
            'Jadwal pengambilan telah ditetapkan: ' . $transaksi->jadwal_pengiriman
        );

        $this->sendNotificationToPembeli(
            $transaksi,
            'Jadwal Pengambilan',
            'Jadwal pengambilan telah ditetapkan: ' . $transaksi->jadwal_pengiriman
        );

        return response()->json(['message' => 'Penjadwalan pengambilan berhasil ditambahkan']);
    }

    public function laporanPenitip(Request $request, $id_penitip)
    {
        $perPage = $request->input('per_page', 10);

        $barang = Barang::with(['transaksi.komisi', 'penitipanBarang.penitipan'])
            ->whereHas('penitipanBarang.penitipan', function($query) use ($id_penitip) {
                $query->where('id_penitip', $id_penitip);
            })
            ->whereNotNull('id_transaksi')
            ->orderBy('id_barang', 'desc')
            ->paginate($perPage);

        $formatted = $barang->getCollection()->map(function($item) {
            $komisi = $item->transaksi->komisi ?? null;
            
            $hargaJual = $item->harga_barang;
            $komisiHunter = $komisi->komisi_hunter ?? 0;
            $komisiReusemart = $komisi->komisi_reusemart ?? 0;
            $bonusPenitip = $komisi->bonus_penitip ?? 0;

            $hargaJualBersih = $hargaJual - $komisiHunter - $komisiReusemart;
            $pendapatan = $hargaJualBersih + $bonusPenitip;

            return [
                'kode_barang' => $item->id_barang,
                'nama_barang' => $item->nama_barang,
                'tanggal_masuk' => $item->penitipanBarang->first()?->penitipan?->tanggal_penitipan,
                'tanggal_laku' => $item->transaksi?->tanggal_transaksi,
                'harga_jual_bersih' => $hargaJualBersih,
                'bonus_terjual_cepat' => $bonusPenitip,
                'pendapatan' => $pendapatan,
            ];
        });

        return response()->json([
            'data' => [
                'data' => $formatted,
                'current_page' => $barang->currentPage(),
                'last_page' => $barang->lastPage(),
                'per_page' => $barang->perPage(),
                'total' => $barang->total(),
            ]
        ]);
    }

    public function historyKurirMobile(Request $request)
    {
        $user = $request->user();
        
        if (!$user || $user->id_jabatan != 6) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $transactions = Transaksi::with(['alamat', 'barang', 'pembeli'])
            ->where('id_pegawai', $user->id_pegawai)
            ->orderBy('tanggal_transaksi', 'desc')
            ->get();

        $formatted = $transactions->map(function ($item) {
            return [
                'id_transaksi' => $item->id_transaksi,
                'tanggal_transaksi' => $item->tanggal_transaksi,
                'status_transaksi' => $item->status_transaksi,
                'opsi_pengiriman' => $item->opsi_pengiriman,
                'harga_total_barang' => $item->harga_total_barang,
                'deskripsi_alamat' => $item->alamat ? $item->alamat->deskripsi_alamat : null,
                'pembeli' => $item->pembeli ? [
                    'username' => $item->pembeli->username,
                    'email' => $item->pembeli->email,
                ] : null,
            ];
        });

        return response()->json([
            'data' => $formatted
        ]);
    }

    public function historyHunterMobile(Request $request)
    {
        $user = $request->user();
        
        if (!$user || $user->id_jabatan != 5) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized - User is not a hunter'
            ], 401);
        }

        $hunter = Pegawai::where('id_pegawai', $user->id_pegawai)->first();
        
        if (!$hunter) {
            return response()->json([
                'success' => false,
                'message' => 'User is not registered as hunter'
            ], 403);
        }

        $transactions = Transaksi::with(['barang.penitipanBarang.penitipan', 'komisi'])
            ->where('status_transaksi', 'Selesai')
            ->whereHas('barang.penitipanBarang.penitipan', function($query) use ($hunter) {
                $query->where('peg_id_pegawai', $hunter->id_pegawai);
            })
            ->orderBy('tanggal_transaksi', 'desc')
            ->get();

        $formatted = $transactions->map(function ($item) {
            $barang = $item->barang->first();
            
            return [
                'id_transaksi' => $item->id_transaksi,
                'status_transaksi' => $item->status_transaksi,
                'barang' => $barang ? [
                    'nama_barang' => $barang->nama_barang,
                    'gambar_barang' => $barang->gambar_barang,
                ] : null,
                'komisi' => $item->komisi ? [
                    'komisi_hunter' => $item->komisi->komisi_hunter,
                ] : null,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $formatted
        ]);
    }

    public function completeTransaction($id)
    {
        $transaksi = Transaksi::with('barang.penitipanBarang.penitipan.penitip')->find($id);

        if (!$transaksi) {
            return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
        }

        if ($transaksi->status_transaksi !== 'Sedang Dikirim') {
            return response()->json(['message' => 'Hanya transaksi dengan status Sedang Dikirim yang bisa diupdate'], 400);
        }

        $transaksi->status_transaksi = 'Selesai';
        $transaksi->save();

        $tanggalDibuat = new \DateTime($transaksi->created_at);
        $tanggalSelesai = new \DateTime($transaksi->updated_at);
        $selisihHari = $tanggalDibuat->diff($tanggalSelesai)->days;

        foreach ($transaksi->barang as $barang) {
            $penitipanBarang = $barang->penitipanBarang->first();
            if ($penitipanBarang) {
                $penitipan = $penitipanBarang->penitipan;
                $penitip = $penitipan?->penitip;
                $pegawai = $penitipan?->pegawai;

                if ($penitip && $pegawai) {
                    $harga = $barang->harga_barang;

                    $komisiHunter = $harga * 0.20;
                    $komisiReuseMart = $harga * 0.10;

                    if ($penitipan->status_perpanjangan === 'Perpanjang') {
                        $komisiReuseMart += $harga * 0.05;
                    }

                    $bonusPenitip = 0;
                    $tanggalPenitipan = \Carbon\Carbon::parse($penitipan->tanggal_penitipan);
                    $tanggalTerjual = \Carbon\Carbon::parse($transaksi->tanggal_transaksi);

                    if ($tanggalTerjual->diffInDays($tanggalPenitipan) < 7) {
                        $bonusPenitip = $harga * 0.05;
                    }

                    $totalDiterimaPenitip = $harga - $komisiHunter - $komisiReuseMart + $bonusPenitip;

                    $penitip->saldo += $totalDiterimaPenitip;
                    $penitip->save();

                    \App\Models\Komisi::updateOrCreate(
                        ['id_transaksi' => $transaksi->id_transaksi],
                        [
                            'id_penitip' => $penitip->id_penitip,
                            'id_pegawai' => $pegawai->id_pegawai,
                            'komisi_hunter' => $komisiHunter,
                            'komisi_reusemart' => $komisiReuseMart,
                            'bonus_penitip' => $bonusPenitip,
                        ]
                    );
                }
            }
        }

        $this->sendNotificationToPenitip(
            $transaksi,
            'Transaksi Selesai',
            'Barang telah sampai ke pembeli'
        );

        $this->sendNotificationToPembeli(
            $transaksi,
            'Transaksi Selesai',
            'Barang pesanan Anda telah sampai'
        );

        return response()->json(['message' => 'Status transaksi berhasil diupdate ke Selesai']);
    }

    private function sendNotificationToPenitip($transaksi, $title, $body)
    {
        $barangs = $transaksi->barang()->with('penitipanBarang.penitipan.penitip')->get();


        foreach ($barangs as $barang) {
            foreach ($barang->penitipanBarang as $penitipanBarang) {
                if (
                    $penitipanBarang->penitipan &&
                    $penitipanBarang->penitipan->penitip
                ) {
                    $penitip = $penitipanBarang->penitipan->penitip;
                    $fcmToken = $penitip->fcm_token;

                    if ($fcmToken) {
                        $this->sendFCMNotification(
                            $fcmToken,
                            $title,
                            $body,
                            [
                                'transaction_id' => $transaksi->id_transaksi,
                                'type' => 'transaction_update'
                            ]
                        );
                    }
                }
            }
        }
    }

    private function sendNotificationToPembeli($transaksi, $title, $body)
    {
        if ($transaksi->pembeli && $transaksi->pembeli->fcm_token) {
            $this->sendFCMNotification(
                $transaksi->pembeli->fcm_token,
                $title,
                $body,
                [
                    'transaction_id' => $transaksi->id_transaksi,
                    'type' => 'transaction_update'
                ]
            );
        }
    }

    private function sendNotificationToKurir($transaksi, $title, $body)
    {
        if ($transaksi->pegawai && $transaksi->pegawai->fcm_token) {
            $this->sendFCMNotification(
                $transaksi->pegawai->fcm_token,
                $title,
                $body,
                [
                    'transaction_id' => $transaksi->id_transaksi,
                    'type' => 'delivery_task'
                ]
            );
        }
    }

    private function sendFCMNotification($token, $title, $body, $data = [])
    {
        try {
            $factory = (new Factory)->withServiceAccount(
                storage_path('app/firebase/reuseapp-2481c-firebase-adminsdk-fbsvc-7e296799b8.json')
            );

            $messaging = $factory->createMessaging();

            $message = CloudMessage::withTarget('token', $token)
                ->withNotification(Notification::create($title, $body))
                ->withData(array_merge([
                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                ], $data));

            $messaging->send($message);

            \Log::info("Notification sent to $token: $title - $body");

            return true;
        } catch (\Exception $e) {
            \Log::error("Failed to send FCM notification: " . $e->getMessage());
            return false;
        }
    }

    public function historyPembeliMobile(Request $request)
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $transactions = Transaksi::with(['barang'])
            ->where('id_pembeli', $user->id_pembeli)
            ->orderBy('tanggal_transaksi', 'desc')
            ->get();

        $formatted = $transactions->map(function ($item) {
            $barangNames = $item->barang->map(function ($barang) {
                return $barang->nama_barang;
            })->implode(', ');

            return [
                'id_transaksi' => $item->id_transaksi,
                'tanggal_transaksi' => $item->tanggal_transaksi,
                'harga_total_barang' => $item->harga_total_barang,
                'poin_pembeli' => $item->poin_pembeli,
                'nomor_transaksi' => $item->nomor_transaksi,
                'status_transaksi' => $item->status_transaksi,
                'nama_barang' => $barangNames,
            ];
        });

        return response()->json([
            'data' => $formatted
        ]);
    }
}
