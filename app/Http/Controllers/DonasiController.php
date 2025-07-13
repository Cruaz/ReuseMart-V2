<?php

namespace App\Http\Controllers;

use App\Models\Donasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DonasiController extends Controller
{
    public function index(Request $request)
    {
        $query = Donasi::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('nama_penerima', 'like', '%' . $request->search . '%');
        }

        $perPage = $request->input('per_page', 10);
        $data = $query->with('request')->paginate($perPage); // Memuat relasi request

        return response()->json([
            'message' => 'All Donasi Retrieved',
            'data' => $data
        ], 200);
    }

    public function approved(Request $request)
    {
        $query = Donasi::where('status_donasi', 'disetujui');

        if ($request->has('search') && $request->search != '') {
            $query->where('nama_penerima', 'like', '%' . $request->search . '%');
        }

        $perPage = $request->input('per_page', 10);
        $data = $query->with('request')->paginate($perPage);

        return response()->json([
            'message' => 'Approved Donasi Retrieved',
            'data' => $data
        ], 200);
    }

    public function approvedLaporan(Request $request)
    {
        $query = Donasi::where('status_donasi', 'disetujui')
            ->with([
                'barang',
                'barang.penitipanBarang.penitipan.penitip',
                'request.organisasi'
            ]);
        if ($request->has('tahun')) {
            $query->whereYear('tanggal_donasi', $request->tahun);
        }

        if ($request->has('search') && $request->search != '') {
            $query->where('nama_penerima', 'like', '%' . $request->search . '%');
        }

        // $query->whereDate('tanggal_donasi', '>=', '2025-07-01');

        $perPage = $request->input('per_page', 10);
        $data = $query->paginate($perPage);

        return response()->json([
            'message' => 'Approved Donasi Retrieved',
            'data' => $data
        ], 200);
    }



    public function show(string $id)
    {
        try {
            $item = Donasi::with('request')->findOrFail($id);
            return response()->json([
                'message' => 'Donasi Found',
                'data' => $item
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Donasi Not Found',
                'data' => null
            ], 404);
        }
    }

    public function store(Request $request)
    {
        $storeData = $request->only([
            'id_request',
            'tanggal_donasi',
            'nama_penerima',
            'status_donasi'
        ]);

        $validate = Validator::make($storeData, [
            'id_request' => 'required|exists:requestdonasi,id_request',
            'tanggal_donasi' => 'required|date',
            'nama_penerima' => 'required|string',
            'status_donasi' => 'required|string',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'message' => 'Validation Failed',
                'errors' => $validate->errors()
            ], 422);
        }

        // Generate id_barang (format: BRG001, BRG002, ...)
        $last = Donasi::orderByRaw('CAST(SUBSTRING(id_barang, 4) AS UNSIGNED) DESC')->first();
        $newId = $last ? 'BRG' . str_pad((int) substr($last->id_barang, 3) + 1, 3, '0', STR_PAD_LEFT) : 'BRG001';
        $storeData['id_barang'] = $newId;

        try {
            $item = Donasi::create($storeData);
            return response()->json([
                'message' => 'Donasi Created Successfully',
                'data' => $item
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to Create Donasi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $item = Donasi::findOrFail($id);

            $updateData = $request->only([
                'id_request',
                'tanggal_donasi',
                'nama_penerima',
                'status_donasi'
            ]);

            $validate = Validator::make($updateData, [
                'id_request' => 'sometimes|required|exists:requestdonasi,id_request',
                'tanggal_donasi' => 'sometimes|required|date',
                'nama_penerima' => 'sometimes|required|string',
                'status_donasi' => 'sometimes|required|string',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'message' => 'Validation Failed',
                    'errors' => $validate->errors()
                ], 422);
            }

            $item->update($updateData);

            return response()->json([
                'message' => 'Donasi Updated Successfully',
                'data' => $item
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Donasi Not Found'], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to Update Donasi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $item = Donasi::findOrFail($id);
            $item->delete();

            return response()->json([
                'message' => 'Donasi Deleted Successfully'
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Donasi Not Found'], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to Delete Donasi',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
