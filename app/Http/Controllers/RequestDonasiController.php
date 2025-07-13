<?php

namespace App\Http\Controllers;

use App\Models\RequestDonasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RequestDonasiController extends Controller
{
   public function index(Request $request)
    {
        $query = RequestDonasi::query();

        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function ($query) use ($searchTerm) {
                $query->where('id_organisasi', 'like', '%' . $searchTerm . '%')
                    ->orWhere('tanggal_request', 'like', '%' . $searchTerm . '%')
                    ->orWhere('deskripsi_request', 'like', '%' . $searchTerm . '%');
            });
        }

        $perPage = $request->query('per_page', 10);
        $requests = $query->paginate($perPage);

        return response([
            'message' => 'All Request Retrieved',
            'data' => $requests
        ], 200);
    }

    public function unfulfilled(Request $request)
    {
        $query = RequestDonasi::where('status_request', '!=', 'terpenuhi')
            ->with('organisasi');

        if ($request->has('tahun')) {
            $query->whereYear('tanggal_request', $request->tahun);
        }

        if ($request->has('search') && $request->search != '') {
            $query->whereHas('organisasi', function($q) use ($request) {
                $q->where('username', 'like', '%' . $request->search . '%');
            });
        }

        $perPage = $request->input('per_page', 10);
        $data = $query->paginate($perPage);

        return response()->json([
            'message' => 'Unfulfilled Requests Retrieved',
            'data' => $data
        ], 200);
    }

    public function show(string $id)
    {
        try {
            $item = RequestDonasi::findOrFail($id);
            return response()->json([
                'message' => 'Request Donasi Found',
                'data' => $item
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Request Donasi Not Found',
                'data' => null
            ], 404);
        }
    }

    public function store(Request $request)
    {
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'id_organisasi' => 'required|integer|exists:organisasi,id_organisasi',
            'tanggal_request' => 'required|date',
            'status_request' => 'nullable|string',
            'deskripsi_request' => 'required|string',
        ]);

        if ($validate->fails()) {
            return response()->json(['message' => $validate->errors()], 400);
        }

        $lastId = RequestDonasi::latest('id_request')->first();
        $newId = $lastId ? $lastId->id_request + 1 : 1;
        $storeData['id_request'] = $newId;

        try {
            $item = RequestDonasi::create($storeData);
            return response()->json([
                'message' => 'Request Donasi Created Successfully',
                'data' => $item
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create Request Donasi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $item = RequestDonasi::findOrFail($id);

            $validate = Validator::make($request->all(), [
                'id_organisasi' => 'sometimes|required|integer|exists:organisasi,id_organisasi',
                'tanggal_request' => 'sometimes|required|date',
                'status_request' => 'sometimes|required|string',
                'deskripsi_request' => 'sometimes|required|string',
            ]);

            if ($validate->fails()) {
                return response()->json(['message' => $validate->errors()], 400);
            }

            $item->update($request->all());

            return response()->json([
                'message' => 'Request Donasi Updated Successfully',
                'data' => $item
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Request Donasi Not Found'], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update Request Donasi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $item = RequestDonasi::findOrFail($id);
            $item->delete();

            return response()->json([
                'message' => 'Request Donasi Deleted Successfully'
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Request Donasi Not Found'], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete Request Donasi',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
