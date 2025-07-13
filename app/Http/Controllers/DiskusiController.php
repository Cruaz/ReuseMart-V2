<?php

namespace App\Http\Controllers;

use App\Models\Diskusi;
use App\Models\Barang;
use App\Models\Pembeli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DiskusiController extends Controller
{
    public function index(string $id_barang)
    {
        $diskusis = Diskusi::where('id_barang', $id_barang)->get();

        if ($diskusis->isEmpty()) {
            return response([
                'message' => 'No Discussions Found',
                'data' => null
            ], 404);
        }

        return response([
            'message' => 'Discussions Retrieved',
            'data' => $diskusis
        ], 200);
    }

    public function indexAll(Request $request)
    {
        $diskusis = Diskusi::with(['pembeli', 'barang'])
                    ->when($request->unanswered, function($query) {
                        return $query->whereNull('jawaban_diskusi');
                    })
                    ->orderBy('id_diskusi', 'desc')
                    ->paginate(10);

        return response([
            'message' => 'All Discussions Retrieved',
            'data' => $diskusis
        ], 200);
    }

    public function show(string $id_diskusi)
    {
        $diskusi = Diskusi::find($id_diskusi);

        if (!$diskusi) {
            return response([
                'message' => 'Diskusi Not Found',
                'data' => null
            ], 404);
        }

        return response([
            'message' => 'Diskusi Found',
            'data' => $diskusi
        ], 200);
    }

    public function store(Request $request, string $id_barang)
    {
        $request->validate([
            'pertanyaan_diskusi' => 'required|string|max:500',
        ]);

        $barang = Barang::find($id_barang);
        if (!$barang) {
            return response(['message' => 'Barang Not Found'], 404);
        }

        $diskusi = new Diskusi();
        $diskusi->id_barang = $id_barang;
        $diskusi->id_pembeli = auth('pembeli')->id();
        $diskusi->pertanyaan_diskusi = $request->pertanyaan_diskusi;
        $diskusi->save();

        return redirect()->back()->with('success', 'Pertanyaan berhasil dikirim');
    }

    public function jawab(Request $request, string $id_diskusi)
    {
        $request->validate([
            'jawaban_diskusi' => 'required|string|max:1000',
        ]);

        $diskusi = Diskusi::find($id_diskusi);

        if (!$diskusi) {
            return response(['message' => 'Diskusi Not Found'], 404);
        }

        $diskusi->jawaban_diskusi = $request->jawaban_diskusi;
        $diskusi->save();

        return response([
            'message' => 'Jawaban Berhasil Dikirim',
            'data' => $diskusi
        ], 200);
    }

    public function destroy(string $id_diskusi)
    {
        $diskusi = Diskusi::find($id_diskusi);

        if (!$diskusi) {
            return response(['message' => 'Diskusi Not Found'], 404);
        }

        $diskusi->delete();

        return response([
            'message' => 'Diskusi Deleted Successfully'
        ], 200);
    }
}