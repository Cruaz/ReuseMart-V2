<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Barang;
use App\Models\Donasi;
use App\Models\Pembeli;
use App\Models\TempTransaksi;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $query = Cart::with('barang')->where('id_pembeli', $request->query('id_pembeli'));

        $perPage = $request->query('per_page', 10);
        $cart = $query->paginate($perPage);

        return response([
            'message' => 'All Cart Retrieved',
            'data' => $cart
        ], 200);
    }

    public function show(string $id_cart)
    {
        $cart = Cart::find($id_cart);

        if ($cart) {
            return response([
                'message' => 'Cart Found',
                'data' => $cart
            ], 200);
        }

        return response([
            'message' => 'Cart Not Found',
            'data' => null
        ], 404);
    }

    public function checkout()
    {
        $pembeliId = auth('pembeli')->id();
        $pembeli = Pembeli::find($pembeliId);
        $cartItems = Cart::with('barang')->where('id_pembeli', $pembeliId)->get();

        $totalHarga = $cartItems->sum(function ($item) {
            return $item->barang->harga_barang ?? 0;
        });

        $ongkir = ($totalHarga >= 1500000) ? 0 : 100000;
        $basePoin = floor($totalHarga / 10000);
        $bonusPoin = ($totalHarga > 500000) ? floor($basePoin * 0.2) : 0;
        $totalPoin = $basePoin + $bonusPoin;
        
        $totalPembayaran = $totalHarga + $ongkir;

        return view('pages.checkout', compact('pembeli', 'cartItems', 'totalHarga', 'ongkir', 'totalPembayaran', 'totalPoin'));
    }

    public function store(Request $request, string $id_barang)
    {
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'id_barang' => 'required',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $barang = Barang::find($storeData['id_barang']);
        if (!$barang) {
           return redirect()->back()->with('error', 'Barang not found');
        }

        if (!is_null($barang->id_transaksi)) {
            return redirect()->back()->with('error', 'Barang sudah terjual');
        }

        $donasiDisetujui = Donasi::where('id_barang', $barang->id_barang)
            ->where('status_donasi', 'Disetujui')
            ->exists();

        if ($donasiDisetujui) {
             return redirect()->back()->with('error', 'Barang sudah didonasikan dan disetujui');
        }

        $idPembeli = auth('pembeli')->id();
        $existingCart = Cart::where('id_barang', $barang->id_barang)
            ->where('id_pembeli', $idPembeli)
            ->first();

        if ($existingCart) {
            return redirect()->back()->with('error', 'Barang sudah ada di cart Anda');
        }

        $lastId = Cart::latest('id_cart')->first();
        $newId = $lastId ? $lastId->id_cart + 1 : 1;
        $storeData['id_cart'] = $newId;
        $storeData['id_pembeli'] = auth('pembeli')->id();
        $cart = Cart::create($storeData);

        return redirect()->back()->with('success', 'Barang berhasil disimpan');
    }

    public function destroy(string $id_cart)
    {
        $cart = Cart::find($id_cart);

        if (is_null($cart)) {
            return response(['message' => 'Cart Not Found'], 404);
        }

        $cart->delete();

        return response([
            'message' => 'Cart Deleted Successfully'
        ], 200);
    }

    public function process(Request $request)
    {
        $pembeli = auth('pembeli')->user();
        $poinDitukar = $request->input('poin_ditukar', 0);
        $potonganHarga = $poinDitukar * 1000;

        $ongkir = ($request->input('opsi_pengiriman') == 1) ? $request->input('harga_ongkir', 0) : 0;

        if ($poinDitukar > 0) {
            $pembeli->poin_pembeli -= $poinDitukar;
            $pembeli->save();
        }

        $alamat = json_decode(auth('pembeli')->user()->alamat, true);
        $selectedAlamatId = $request->input('id_alamat');
        $foundAlamat = collect($alamat)->firstWhere('id_alamat', $selectedAlamatId);

        if (!$foundAlamat && $request->input('opsi_pengiriman') == 1) {
            return response()->json(['error' => 'Alamat tidak valid'], 400);
        }

        $transaksi = Transaksi::create([
            'id_pembeli' => auth('pembeli')->id(),
            'id_alamat' => $request->input('id_alamat'),
            'tanggal_transaksi' => Carbon::now(),
            'harga_total_barang' => $request->input('total_pembayaran'),
            'opsi_pengiriman' => $request->input('opsi_pengiriman'),
            'potongan_harga' => $potonganHarga,
            'harga_ongkir' => $ongkir,
            'status_transaksi' => 'Menunggu Pembayaran',
            'poin_pembeli' => $request->input('total_poin'),
            'poin_spent' => $poinDitukar,
            'bukti_pembayaran' => null,
            'created_at' => Carbon::now(),
        ]);

        $transaksi->update([
            'nomor_transaksi' => date('Y') . '.' . date('m') . '.' . str_pad($transaksi->id_transaksi, 4, '0', STR_PAD_LEFT)
        ]);

        $carts = Cart::where('id_pembeli', auth('pembeli')->id())->get();

        foreach ($carts as $cart) {
            $cart->barang()->update(['id_transaksi' => $transaksi->id_transaksi]);
        }

        $carts = Cart::where('id_pembeli', auth('pembeli')->id())->get();

        foreach ($carts as $cart) {
            $cart->barang()->update([
                'id_transaksi' => $transaksi->id_transaksi,
                'status_barang' => 'Sold Out'
            ]);
        }

        Cart::where('id_pembeli', auth('pembeli')->id())->delete();

        return response()->json([
            'success' => true,
            'transaction_id' => $transaksi->id_transaksi
        ]);
    }

    public function uploadPaymentProof(Request $request)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'transaction_id' => 'required|numeric'
        ]);

        $transaksi = Transaksi::findOrFail($request->transaction_id);
        $pembeli = auth('pembeli')->user();

        if ($transaksi->id_pembeli != auth('pembeli')->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $uploadFolder = 'Galery';
        $image_uploaded_path = $request->file('bukti_pembayaran')->store($uploadFolder, 'public');
        $uploadedImageResponse = basename($image_uploaded_path);

        $transaksi->update([
            'bukti_pembayaran' => $uploadedImageResponse,
            'status_transaksi' => 'Menunggu Konfirmasi'
        ]);

        return redirect()->route('payment.confirmation', $transaksi->id_transaksi);
    }

    public function cancelTransaction(Request $request)
    {
        $pembeli = Auth::guard('pembeli')->user();
        $idPembeli = $pembeli->id_pembeli;
        $totalPoinToReturn = 0;
        
        $expiredTransactions = Transaksi::where('id_pembeli', $idPembeli)
            ->whereNull('bukti_pembayaran')
            ->where('created_at', '<=', now()->subSeconds(60))
            ->get();

        foreach ($expiredTransactions as $transaction) {
            $totalPoinToReturn += $transaction->poin_spent;

            Barang::where('id_transaksi', $transaction->id_transaksi)
                ->update([
                    'id_transaksi' => null,
                    'status_barang' => null
                ]);
        }

        if ($totalPoinToReturn > 0) {
            $pembeli->poin_pembeli += $totalPoinToReturn;
            $pembeli->save();
        }

        $deleted = $expiredTransactions->count() > 0 ? 
            Transaksi::whereIn('id_transaksi', $expiredTransactions->pluck('id_transaksi'))->delete() : 0;

        return response()->json(['success' => $deleted > 0]);
    }

    public function paymentConfirmation($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        return view('pages.payment_confirmation', compact('transaksi'));
    }
}
