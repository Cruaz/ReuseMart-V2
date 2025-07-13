<?php

namespace App\Http\Controllers;

use App\Models\Penukaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PenukaranController extends Controller
{
    public function index(Request $request)
    {
        $query = Penukaran::with(['pembeli', 'merchandise']);

        if ($request->has('search') && $request->search != '') {
            $query->whereHas('merchandise', function ($q) use ($request) {
                $q->where('nama_merchandise', 'like', '%' . $request->search . '%');
            });
        }

        $perPage = $request->query('per_page', 10);
        $data = $query->paginate($perPage);

        return response([
            'message' => 'All Penukaran Retrieved',
            'data' => $data
        ], 200);
    }

    public function show(string $id)
    {
        $penukaran = Penukaran::with(['pembeli', 'merchandise'])->find($id);

        if ($penukaran) {
            return response([
                'message' => 'Penukaran Found',
                'data' => $penukaran
            ], 200);
        }

        return response([
            'message' => 'Penukaran Not Found',
            'data' => null
        ], 404);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'id_pembeli' => 'required|integer',
            'id_merchandise' => 'required|integer',
            'tanggal_penukaran' => 'required|date',
            'jumlah_poin_terpakai' => 'required|integer',
            'tanggal_pengambilan' => 'nullable|date',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $last = Penukaran::latest('id_penukaran')->first();
        $newId = $last ? $last->id_penukaran + 1 : 1;

        $requestData = $request->all();
        $requestData['id_penukaran'] = $newId;

        $penukaran = Penukaran::create($requestData);

        return response([
            'message' => 'Penukaran Created Successfully',
            'data' => $penukaran
        ], 201);
    }

    public function update(Request $request, string $id)
    {
        $penukaran = Penukaran::find($id);

        if (!$penukaran) {
            return response(['message' => 'Penukaran Not Found'], 404);
        }

        $validate = Validator::make($request->all(), [
            'id_pembeli' => 'sometimes|required|integer',
            'id_merchandise' => 'sometimes|required|integer',
            'tanggal_penukaran' => 'sometimes|required|date',
            'jumlah_poin_terpakai' => 'sometimes|required|integer',
            'tanggal_pengambilan' => 'nullable|date',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $penukaran->update($request->all());

        return response([
            'message' => 'Penukaran Updated Successfully',
            'data' => $penukaran
        ], 200);
    }

    public function destroy(string $id)
    {
        $penukaran = Penukaran::find($id);

        if (!$penukaran) {
            return response(['message' => 'Penukaran Not Found'], 404);
        }

        $penukaran->delete();

        return response([
            'message' => 'Penukaran Deleted Successfully'
        ], 200);
    }
}
