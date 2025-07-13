<?php

use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrganisasiController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\PembeliController;
use App\Http\Controllers\AlamatController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\MerchandiseController;
use App\Http\Controllers\PenitipController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RequestDonasiController;
use App\Http\Controllers\DonasiController;
use App\Http\Controllers\DiskusiController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PenitipanController;
use App\Http\Controllers\PenitipanBarangController;


Route::prefix('pembeli')->group(function() {
    Route::post('/register', [PembeliController::class, 'register']);
    Route::post('/logout', [PembeliController::class, 'logout'])->middleware('auth:pembeli');
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/loginPegawai', [AuthController::class, 'loginPegawai'])->name('loginPegawai');

Route::post('/loginMobile', [AuthController::class, 'loginMobile']);
Route::post('/loginPegawaiMobile', [AuthController::class, 'loginPegawaiMobile']);

Route::post('/reset-passwordPegawai', [ResetPasswordController::class, 'resetWithId']);

Route::prefix('organisasi')->group(function() {
    Route::post('/register', [OrganisasiController::class, 'register']);
    Route::post('/logout', [OrganisasiController::class, 'logout'])->middleware('auth:organisasi');
});

Route::prefix('pembeli')->middleware(['auth:pembeli'])->group(function() {
    Route::get('/alamat', [AlamatPembeliController::class, 'index']);
    Route::post('/alamat', [AlamatPembeliController::class, 'store']);
    Route::get('/alamat/{id}', [AlamatPembeliController::class, 'show']);
    Route::put('/alamat/{id}', [AlamatPembeliController::class, 'update']);
    Route::delete('/alamat/{id}', [AlamatPembeliController::class, 'destroy']);
    Route::post('/alamat/{id}/set-default', [AlamatPembeliController::class, 'setDefault']);
});

Route::get('/pegawai', [PegawaiController::class, 'index']);
Route::post('/pegawai', [PegawaiController::class, 'store']);
Route::post('/pegawai/{id}', [PegawaiController::class, 'update']);
Route::delete('/pegawai/{id}', [PegawaiController::class, 'destroy']);
Route::get('/kurir', [PegawaiController::class, 'getKurir']);

Route::get('/organisasi', [OrganisasiController::class, 'index']);
Route::post('/organisasi', [OrganisasiController::class, 'store']);
Route::post('/organisasi/{id}', [OrganisasiController::class, 'update']);
Route::delete('/organisasi/{id}', [OrganisasiController::class, 'destroy']);

Route::get('/pembeli', [PembeliController::class, 'index']);
Route::post('/pembeli', [PembeliController::class, 'store']);
Route::post('/pembeli/{id}', [PembeliController::class, 'update']);
Route::delete('/pembeli/{id}', [PembeliController::class, 'destroy']);

Route::get('/alamat', [AlamatController::class, 'index']);
Route::post('/alamat', [AlamatController::class, 'store']);
Route::post('/alamat/{id}', [AlamatController::class, 'update']);
Route::delete('/alamat/{id}', [AlamatController::class, 'destroy']);

Route::get('/barang', [BarangController::class, 'index']);
Route::get('/barangMobile', [BarangController::class, 'indexMobile']);
Route::post('/barang', [BarangController::class, 'store']);
Route::post('/barang/{id}', [BarangController::class, 'update']);
Route::delete('/barang/{id}', [BarangController::class, 'destroy']);
Route::get('/barang/kategoriLaporan', [BarangController::class, 'kategoriLaporan']);
Route::get('/barang/stok', [BarangController::class, 'laporanStok']);
Route::get('/barang/komisiProduk', [BarangController::class, 'laporanKomisiProduk']);
Route::get('/laporan/penjualanBulanan', [BarangController::class, 'laporanPenjualanBulanan']);

Route::get('/jabatan', [JabatanController::class, 'index']);
Route::post('/jabatan', [JabatanController::class, 'store']);
Route::post('/jabatan/{id}', [JabatanController::class, 'update']);
Route::delete('/jabatan/{id}', [JabatanController::class, 'destroy']);


Route::get('/merchandise', [MerchandiseController::class, 'index']);
Route::get('/merchandiseMobile', [MerchandiseController::class, 'indexMobile']);
Route::post('/claimMerchandise', [MerchandiseController::class, 'claimMerchandise']);
Route::post('/merchandise', [MerchandiseController::class, 'store']);
Route::post('/merchandise/{id}', [MerchandiseController::class, 'update']);
Route::delete('/merchandise/{id}', [MerchandiseController::class, 'destroy']);

Route::get('/penitip', [PenitipController::class, 'index']);
Route::post('/penitip', [PenitipController::class, 'store']);
Route::post('/penitip/{id}', [PenitipController::class, 'update']);
Route::delete('/penitip/{id}', [PenitipController::class, 'destroy']);


Route::get('/request', [RequestDonasiController::class, 'index']);
Route::post('/request', [RequestDonasiController::class, 'store']);
Route::post('/request/{id}', [RequestDonasiController::class, 'update']);
Route::delete('/request/{id}', [RequestDonasiController::class, 'destroy']);

Route::get('/donasi', [DonasiController::class, 'index']);
Route::post('/donasi', [DonasiController::class, 'store']);
Route::post('/donasi/{id}', [DonasiController::class, 'update']);
Route::delete('/donasi/{id}', [DonasiController::class, 'destroy']);

Route::get('/donasi/approved', [DonasiController::class, 'approved']);

Route::get('diskusi/{id_barang}', [DiskusiController::class, 'index'])->name('diskusi.index');
Route::get('diskusi/{id_diskusi}', [DiskusiController::class, 'show'])->name('diskusi.show');
Route::get('/diskusi', [DiskusiController::class, 'indexAll']);
Route::post('/diskusi/jawab/{id_diskusi}', [DiskusiController::class, 'jawab']);
Route::delete('/diskusi/{id_diskusi}', [DiskusiController::class, 'destroy']);

Route::get('/transaksi', [TransaksiController::class, 'index']);
Route::get('/transaksi2', [TransaksiController::class, 'index2']);
Route::post('/transaksi', [TransaksiController::class, 'store']);
Route::post('/transaksi/{id}', [TransaksiController::class, 'update']);
Route::delete('/transaksi/{id}', [TransaksiController::class, 'destroy']);
Route::put('/transaksi/{id}/konfirmasi', [TransaksiController::class, 'updateConfirm']);
Route::delete('/transaksi/{id}/cancel', [TransaksiController::class, 'batalkanTransaksi']);
Route::put('/transaksi/{id}/konfirmasiDiambil', [TransaksiController::class, 'konfirmasiDiambil']);
Route::put('/transaksi/{id}/konfirmasiKirim', [TransaksiController::class, 'konfirmasiKirim']);
Route::post('/transaksi/{id}/penjadwalan', [TransaksiController::class, 'penjadwalan']);
Route::post('/transaksi/{id}/penjadwalanPengambilan', [TransaksiController::class, 'penjadwalanPengambilan']);

Route::prefix('penitip')->middleware(['auth:penitip'])->group(function() {
    Route::get('/penitipan', [PenitipanController::class, 'index']);
    Route::post('/penitipan', [PenitipanController::class, 'store']);
    Route::get('/penitipan/{id}', [PenitipanController::class, 'show']);
    Route::put('/penitipan/{id}', [PenitipanController::class, 'update']);
    Route::delete('/penitipan/{id}', [PenitipanController::class, 'destroy']);
});

Route::get('/penitipan', [PenitipanController::class, 'index']);
Route::post('/penitipan', [PenitipanController::class, 'store']);
Route::post('/penitipan/{id}', [PenitipanController::class, 'update']);
Route::delete('/penitipan/{id}', [PenitipanController::class, 'destroy']);
Route::get('/penitipan/laporanPenitipan', [PenitipanController::class, 'laporanPenitipan']);

Route::get('/penitipanBarang', [PenitipanBarangController::class, 'index']);
Route::post('/penitipanBarang', [PenitipanBarangController::class, 'store']);
Route::post('/penitipanBarang/{id}', [PenitipanBarangController::class, 'update']);
Route::delete('/penitipanBarang/{id}', [PenitipanBarangController::class, 'destroy']);

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');

Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');

Route::get('/penitipanbarang', [PenitipanController::class, 'index']);
Route::post('/penitipanbarang', [PenitipanController::class, 'store']);
Route::post('/penitipanbarang/{id}', [PenitipanController::class, 'update']);
Route::delete('/penitipanbarang/{id}', [PenitipanController::class, 'destroy']);

Route::get('/donasi/approvedLaporan', [DonasiController::class, 'approvedLaporan']);
Route::get('/request/unfulfilled', [RequestDonasiController::class, 'unfulfilled']);
Route::get('/penitip/{id}/laporan', [TransaksiController::class, 'laporanPenitip']);

Route::middleware('auth:sanctum')->post('/update-fcm-token', [AuthController::class, 'updateFcmToken']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profileKurirMobile', [PegawaiController::class, 'profileKurirMobile']);
    Route::get('/profileHunterMobile', [PegawaiController::class, 'profileHunterMobile']);
    Route::get('/profilePembeliMobile', [PembeliController::class, 'profileMobile']);
    Route::get('/profilePenitipMobile', [PenitipController::class, 'profileMobile']);
    Route::get('/komisi-hunter-total', [PegawaiController::class, 'getTotalKomisiHunter']);
    Route::get('/historyKurirMobile', [TransaksiController::class, 'historyKurirMobile']);
    Route::get('/historyHunterMobile', [TransaksiController::class, 'historyHunterMobile']);
    Route::get('/historyPembeliMobile', [TransaksiController::class, 'historyPembeliMobile']);
    Route::get('/historyPenitipMobile', [PenitipanController::class, 'historyPenitipMobile']);
    Route::put('/transaksi/{id}/complete', [TransaksiController::class, 'completeTransaction']);
    Route::get('/pembeli/getUserProfile', [PembeliController::class, 'getUserProfile']);
    Route::post('/reset-fcm-token', [PegawaiController::class, 'resetFcmToken']);
});