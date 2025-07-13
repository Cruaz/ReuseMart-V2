<?php

namespace App\Http\Controllers;

use App\Models\Merchandise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MerchandiseController extends Controller
{
    public function index(Request $request)
    {
        $query = Merchandise::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('nama_merchandise', 'like', '%' . $request->search . '%');
        }

        $perPage = $request->query('per_page', 10);
        $merchandises = $query->paginate($perPage);

        return response([
            'message' => 'All Merchandise Retrieved',
            'data' => $merchandises
        ], 200);
    }

    public function indexMobile(Request $request)
    {
        $query = Merchandise::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('nama_merchandise', 'like', '%' . $request->search . '%');
        }

        $perPage = $request->query('per_page', 10);
        $merchandises = $query->paginate($perPage);

        return response([
            'message' => 'All Merchandise Retrieved',
            'data' => $merchandises
        ], 200);
    }

    // Tambahkan di MerchandiseController.php
    public function claimMerchandise(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'merchandise_id' => 'required|integer',
            'user_id' => 'required|integer',
            'points_used' => 'required|integer',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $merchandise = Merchandise::find($request->merchandise_id);
        if (!$merchandise) {
            return response(['success' => false, 'message' => 'Merchandise not found'], 404);
        }

        if ($merchandise->stok_merchandise <= 0) {
            return response(['success' => false, 'message' => 'Stock is empty'], 400);
        }

        // Kurangi stok
        $merchandise->stok_merchandise -= 1;
        $merchandise->save();

        // Di sini Anda perlu menambahkan logika untuk mengurangi poin user
        // Contoh:
        // $user = User::find($request->user_id);
        // $user->poin -= $request->points_used;
        // $user->save();

        return response([
            'success' => true,
            'message' => 'Merchandise claimed successfully',
            'data' => $merchandise
        ], 200);
    }

    public function show(string $id)
    {
        $merchandise = Merchandise::find($id);

        if ($merchandise) {
            return response([
                'message' => 'Merchandise Found',
                'data' => $merchandise
            ], 200);
        }

        return response([
            'message' => 'Merchandise Not Found',
            'data' => null
        ], 404);
    }

    public function store(Request $request)
    {
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'nama_merchandise' => 'required|string',
            'poin_redeem' => 'required|integer',
            'stok_merchandise' => 'required|integer',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $lastId = Merchandise::latest('id_merchandise')->first();
        $newId = $lastId ? $lastId->id_merchandise + 1 : 1;
        $storeData['id_merchandise'] = $newId;

        $merchandise = Merchandise::create($storeData);

        return response([
            'message' => 'Merchandise Added Successfully',
            'data' => $merchandise,
        ], 200);
    }

    public function update(Request $request, string $id)
    {
        $merchandise = Merchandise::find($id);
        if (is_null($merchandise)) {
            return response(['message' => 'Merchandise Not Found'], 404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'nama_merchandise' => 'sometimes|required|string',
            'poin_redeem' => 'sometimes|required|integer',
            'stok_merchandise' => 'sometimes|required|integer',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $merchandise->update($updateData);

        return response([
            'message' => 'Merchandise Updated Successfully',
            'data' => $merchandise,
        ], 200);
    }

    public function destroy(string $id)
    {
        $merchandise = Merchandise::find($id);

        if (is_null($merchandise)) {
            return response(['message' => 'Merchandise Not Found'], 404);
        }

        $merchandise->delete();

        return response([
            'message' => 'Merchandise Deleted Successfully'
        ], 200);
    }
}
