<?php

namespace App\Http\Controllers;

use App\Models\Penitip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class PenitipController extends Controller
{
    public function index(Request $request)
{
    $query = Penitip::query();

    if ($request->has('search') && $request->search != '') {
        $searchTerm = $request->search;
        $query->where(function ($query) use ($searchTerm) {
            $query->where('username', 'like', '%' . $searchTerm . '%')
                  ->orWhere('email', 'like', '%' . $searchTerm . '%')
                  ->orWhere('nik', 'like', '%' . $searchTerm . '%');
        });
    }

    $perPage = $request->query('per_page', 10);
    $penitips = $query->paginate($perPage);

    return response([
        'message' => 'All Penitips Retrieved',
        'data' => $penitips
    ], 200);
}

    public function show(string $id)
    {
        $penitip = Penitip::find($id);

        if ($penitip) {
            return response([
                'message' => 'Penitip Found',
                'data' => $penitip
            ], 200);
        }

        return response([
            'message' => 'Penitip Not Found',
            'data' => null
        ], 404);
    }

    public function store(Request $request)
    {
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'username' => 'required|string',
            'email' => 'required|email|unique:penitip,email',
            'password' => 'required|string|min:8',
            'nik' => 'required|string',
            'foto' => 'nullable|image',
            'saldo' => 'nullable|numeric',
            'poin_performa' => 'nullable|integer',
            'status_badge' => 'nullable|string',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $lastId = Penitip::latest('id_penitip')->first();
        $newId = $lastId ? $lastId->id_penitip + 1 : 1;
        $storeData['id_penitip'] = $newId;
        $storeData['password'] = Hash::make($storeData['password']);

        $uploadFolder = 'Galery';
        $image = $request->file('foto');
        if ($image) {
            $image_uploaded_path = $image->store($uploadFolder, 'public');
            $uploadedImageResponse = basename($image_uploaded_path);
            $storeData['foto'] = $uploadedImageResponse;
        } else {
            $storeData['foto'] = null;
        }

        $penitip = Penitip::create($storeData);

        return response([
            'message' => 'Penitip Added Successfully',
            'data' => $penitip,
        ], 200);
    }

    public function update(Request $request, string $id)
    {
        $penitip = Penitip::find($id);
        if (is_null($penitip)) {
            return response(['message' => 'Penitip Not Found'], 404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'username' => 'sometimes|required|string',
            'email' => 'sometimes|required|email|unique:penitip,email,' . $id . ',id_penitip',
            'password' => 'sometimes|nullable|string|min:8',
            'nik' => 'required|string',
            'foto' => 'nullable|image',
            'saldo' => 'nullable|numeric',
            'poin_performa' => 'sometimes|nullable|integer',
            'status_badge' => 'sometimes|nullable|string',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        if (isset($updateData['password'])) {
            $updateData['password'] = Hash::make($updateData['password']);
        }

        if ($request->hasFile('foto')) {
            $uploadFolder = 'Galery';
            $image = $request->file('foto');
            $image_uploaded_path = $image->store($uploadFolder, 'public');
            $uploadedImageResponse = basename($image_uploaded_path);
            if ($penitip->foto) {
                Storage::disk('public')->delete('Galery/' . $penitip->foto);
            }
            
            $updateData['foto'] = $uploadedImageResponse;
        }
        $updateData['password'] = Hash::make($updateData['password']);
        $penitip->update($updateData);

        return response([
            'message' => 'Penitip Updated Successfully',
            'data' => $penitip,
        ], 200);
    }

    public function destroy(string $id)
    {
        $penitip = Penitip::find($id);

        if (is_null($penitip)) {
            return response(['message' => 'Penitip Not Found'], 404);
        }

        $penitip->delete();

        return response([
            'message' => 'Penitip Deleted Successfully'
        ], 200);
    }

    public function profile()
    {
        $penitip = Auth::guard('penitip')->user();

        return view('users.profilePenitip', compact('penitip'));
    }

    public function history()
    {
        $penitip = Auth::guard('penitip')->user();

        return view('users.historyPenitip', compact('penitip'));
    }

    public function profileMobile(Request $request)
    {
        $penitip = $request->user();
        
        if (!$penitip) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json([
            'username' => $penitip->username,
            'email' => $penitip->email,
            'nik' => $penitip->nik,
            'foto' => $penitip->foto,
            'saldo' => $penitip->saldo,
        ]);
    }
}
