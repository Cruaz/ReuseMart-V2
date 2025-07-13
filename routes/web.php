<?php

use App\Http\Controllers\PembeliController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\PenitipController;
use App\Http\Controllers\OrganisasiController;
use App\Http\Controllers\RequestDonasiController;
use App\Http\Controllers\DiskusiController;
use App\Http\Controllers\AlamatPembeliController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PenitipanController;

Route::get('/', [BarangController::class, 'index']);

Route::get('/homeumum', [BarangController::class, 'index'])->name('homeumum');
Route::get('/barang/{id}', [BarangController::class, 'showDetailUmum'])->name('barang.detail');
Route::prefix('pembeli')->middleware(['auth:pembeli'])->group(function() {
    Route::get('/barang/{id}', [BarangController::class, 'showDetailPembeli'])->name('pembeli.barang.detail');
});
Route::get('/kategori/{slug}', [BarangController::class, 'filterByKategori'])->name('kategori.filter');

Route::get('/registerPembeli', function () {
    return view('pages.registerPembeli');
});

Route::get('/registerOrganisasi', function () {
    return view('pages.registerOrganisasi');
});

Route::get('/login', function() {
    return view('pages.login');
});

Route::post('/login', [AuthController::class, 'login']);

Route::get('/login', function() {
    return view('pages.login');
})->name('login');

Route::post('/loginPegawai', [AuthController::class, 'loginPegawai']);

Route::get('/loginPegawai', function() {
    return view('pages.loginPegawai');
})->name('loginPegawai');

Route::get('/homeAdmin', function() {
    return view('adminLayout');
});

Route::get('/homeGudang', function() {
    return view('homeGudang');
});
Route::get('/transaksiBarang', function() {
    return view('peg.daftarTransaksiBarang');
});
Route::get('/penitipan', function() {
    return view('peg.transaksiPenitipanBarang');
});
Route::get('/penitipanbarang/{id}/edit', [PenitipanController::class, 'edit'])->name('penitipan.edit');
Route::put('/penitipanbarang/{id}', [PenitipanController::class, 'update'])->name('penitipan.update');


Route::get('/homeOwner', function() {
    return view('own.reqDonasi');
});

Route::get('/laporanBarang', function() {
    return view('own.laporanBarang');
});

Route::get('/laporanPenitipan', function() {
    return view('own.laporanPenitipan');
});

Route::get('/laporanDonasi', function() {
    return view('own.laporanDonasi');
});

Route::get('/laporanRequest', function () {
    return view('own.laporanRequest');
});

Route::get('/listPenitipLaporan', function () {
    return view('own.listPenitipLaporan');
});

Route::get('/laporanKomisiProduk', function() {
    return view('own.laporanKomisiProduk');
});

Route::get('/laporanStok', function() {
    return view('own.laporanStok');
});

Route::get('/laporanBulanan', function() {
    return view('own.laporanBulanan');
})->name('laporan.bulanan');


Route::get('/reset-password', [ResetPasswordController::class, 'showResetForm'])->name('reset.form');
Route::post('/reset-password', [ResetPasswordController::class, 'processReset'])->name('password.reset');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showNewPasswordForm'])->name('password.reset');
Route::post('/reset-password-update', [ResetPasswordController::class, 'updatePassword'])->name('password.update');

Route::get('/reset-passwordPegawai', function () {
    return view('pages.reset-passwordPegawai');
});
Route::post('/reset-passwordPegawai', [ResetPasswordController::class, 'resetWithUsername']);

Route::get('/reset-success', function () {
    return view('pages.reset-success');
});

Route::get('/profilePenitip', [PenitipController::class, 'profile'])->middleware('auth:penitip');
Route::get('/historyPenitip', [PenitipController::class, 'history'])->middleware('auth:penitip');
Route::post('/pembeli/profile', [PembeliController::class, 'updateProfile'])->name('pembeli.profile.update');
Route::get('/pembeli/transaksi', [TransaksiController::class, 'historyPembeli'])->middleware('auth:pembeli')->name('pembeli.transaksi');
Route::get('/penitip/transaksi', [TransaksiController::class, 'historyPenitip'])->middleware('auth:penitip');

Route::get('/profilePembeli', [PembeliController::class, 'profile'])->middleware('auth:pembeli');

Route::get('/api/organisasi/requests', [OrganisasiController::class, 'getOrganisasiRequests'])->middleware('auth:organisasi');

Route::get('/dashboard', function () {
    return view('dashboards.dashboard');
});

Route::get('/jabatan', function () {
    return view('dashboards.jabatan');
});

Route::get('/pegawai', function () {
    return view('dashboards.pegawai');
});

Route::get('/organisasi', function () {
    return view('dashboards.organisasi');
});

Route::get('/merchandise', function () {
    return view('dashboards.merchandise');
});

Route::get('/pembeli', function () {
    return view('dashboards.pembeli');
});

Route::get('/alamat', function () {
    return view('dashboards.alamat');
});

Route::get('/barang', function () {
    return view('dashboards.barang');
});

Route::get('/penitip', function () {
    return view('cs.penitip');
});

Route::get('/penukaran', function () {
    return view('cs.penukaran');
});

Route::get('/requestDonasi', function () {
    return view('org.requestDonasi');
});

Route::get('/klaim', function () {
    return view('cs.penukaran');
});

Route::get('/konfirmasiTransaksi', function () {
    return view('cs.konfirmasiTransaksi');
});

Route::get('/jawabDiskusi', function () {
    return view('cs.jawabDiskusi');
});

Route::get('/donasi', function () {
    return view('own.donasi');
});

Route::get('/reqDonasi', function () {
    return view('own.reqDonasi');
});

Route::get('/historyDonasi', function () {
    return view('own.historyDonasi');
});

Route::get('/homePembeli', [BarangController::class, 'homePembeli'])->name('homePembeli');
Route::get('/pembeli/kategori/{slug}', [BarangController::class, 'filterKategoriPembeli'])->name('kategori.filter.pembeli');

Route::get('/homePenitip', [PenitipanController::class, 'homePenitip'])->middleware('auth:penitip')->name('homePenitip');

Route::get('/alokasi', function () {
    return view('own.alokasi');
});

Route::get('/homeCs', function () {
    return view('cs.penitip');
});
Route::get('/homeOrganisasi', function () {
    return view('org.requestDonasi');
});


Route::get('/editAlamat', function () {
    return view('users.editAlamat');
});

Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');

Route::get('/editAlamat', [AlamatPembeliController::class, 'index'])->name('pembeli.editAlamat');
Route::post('/pembeli/alamat', [AlamatPembeliController::class, 'store'])->middleware('auth:pembeli');
Route::put('/pembeli/alamat/{id}', [AlamatPembeliController::class, 'update'])->middleware('auth:pembeli');
Route::delete('/pembeli/alamat/{id}', [AlamatPembeliController::class, 'destroy'])->middleware('auth:pembeli');
Route::post('/pembeli/alamat/{id}/set-default', [AlamatPembeliController::class, 'setDefault'])->middleware('auth:pembeli');

Route::post('/diskusi/{id_barang}', [DiskusiController::class, 'store'])->middleware('auth:pembeli')->name('diskusi.store');
Route::post('/cart/{id_barang}', [CartController::class, 'store'])->middleware('auth:pembeli')->name('cart.store');

Route::post('/checkout/process', [CartController::class, 'process'])->middleware('auth:pembeli')->name('checkout.process');
Route::post('/checkout/upload-proof', [CartController::class, 'uploadPaymentProof'])->middleware('auth:pembeli')->name('checkout.upload-proof');
Route::post('/checkout/cancelTransaction', [CartController::class, 'cancelTransaction'])->middleware('auth:pembeli')->name('cancel.transaction');
Route::get('/checkout/paymentConfirmation/{orderId}', [CartController::class, 'paymentConfirmation'])->middleware('auth:pembeli')->name('payment.confirmation');

Route::post('/penitipan/{id}/perpanjang', [PenitipanController::class, 'perpanjang'])->middleware('auth:penitip')->name('penitipan.perpanjang');

Route::post('/penitipan/{id}/konfirmasi', [PenitipanController::class, 'konfirmasi'])->name('penitipan.konfirmasi');
Route::get('/penitipan-saya', [PenitipanController::class, 'daftarPenitipan'])->name('penitipan.daftar');
