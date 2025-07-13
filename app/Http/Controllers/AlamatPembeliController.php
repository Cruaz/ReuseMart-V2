<?php

namespace App\Http\Controllers;

use App\Models\Alamat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AlamatPembeliController extends Controller
{
    public function index()
    {
        $pembeli = Auth::guard('pembeli')->user();
        $alamat = $pembeli->alamat()->get();
        
        return view('users.editAlamat', compact('alamat'));
    }

    public function store(Request $request)
    {
        $storeData = $request->all();
        $storeData['id_pembeli'] = auth('pembeli')->user()->id_pembeli;
        
        $request->validate([
            'label_alamat' => 'required|string',
            'deskripsi_alamat' => 'required|string',
            'is_default' => 'nullable|boolean'
        ]);

        $pembeli = Auth::guard('pembeli')->user();
        
        if ($request->is_default) {
            $pembeli->alamat()->update(['is_default' => false]);
        }

        $lastId = Alamat::latest('id_alamat')->first();
        $newId = $lastId ? $lastId->id_alamat + 1 : 1;

        $alamat = $pembeli->alamat()->create([
            'id_alamat' => $newId,
            'id_pembeli' => $pembeli->id_pembeli, 
            'label_alamat' => $request->label_alamat,
            'deskripsi_alamat' => $request->deskripsi_alamat,
            'is_default' => $request->is_default ?? false
        ]);

        return response()->json([
            'success' => true,
            'data' => $alamat,
            'message' => 'Alamat berhasil ditambahkan!'
        ]);
    }

    public function show($id)
    {
        $pembeli = Auth::guard('pembeli')->user();
        $alamat = $pembeli->alamat()->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $alamat
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'label_alamat' => 'string',
            'deskripsi_alamat' => 'string',
            'is_default' => 'nullable|boolean'
        ]);

        $pembeli = Auth::guard('pembeli')->user();
        $alamat = $pembeli->alamat()->findOrFail($id);
        
        if ($request->is_default) {
            $pembeli->alamat()->where('id_alamat', '!=', $id)->update(['is_default' => false]);
        }

        $alamat->update($request->all());

        return response()->json([
            'success' => true,
            'data' => $alamat
        ]);
    }

    public function destroy($id)
    {
        $pembeli = Auth::guard('pembeli')->user();
        $alamat = $pembeli->alamat()->findOrFail($id);
        
        if ($alamat->is_default) {
            $newDefault = $pembeli->alamat()
                ->where('id_alamat', '!=', $id)
                ->first();
            
            if ($newDefault) {
                $newDefault->update(['is_default' => true]);
            }
        }

        $alamat->delete();

        return response()->json([
            'success' => true,
            'message' => 'Alamat deleted successfully'
        ]);
    }

    public function setDefault($id)
    {
        $pembeli = Auth::guard('pembeli')->user();
        $alamat = $pembeli->alamat()->findOrFail($id);

        $pembeli->alamat()->where('id_alamat', '!=', $id)->update(['is_default' => false]);
        
        $alamat->update(['is_default' => true]);

        return response()->json([
            'success' => true,
            'data' => $alamat
        ]);
    }
}