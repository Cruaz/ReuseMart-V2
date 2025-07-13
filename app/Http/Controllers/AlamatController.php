<?php

namespace App\Http\Controllers;

use App\Models\Alamat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AlamatController extends Controller
{
    public function index(Request $request)
    {   
        $query = Alamat::query(); 
        if ($request->has('search') && $request->search != '') {
            $query->where('id_alamat', 'like', '%' . $request->search . '%');
        }
        $perPage = $request->query('per_page', 7);
        $alamat = $query->paginate($perPage);

        return response([
            'message' => 'Alamat Retrieved',
            'data' => $alamat
        ], 200);
    }

    public function getData(){
        $data = Alamat::all();

        return response([
            'message' => 'All Alamat Retrieved',
            'data' => $data
        ], 200);
    }

    public function store(Request $request)
    {
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'id_pembeli' => 'required',
            'label_alamat' => 'required',
            'deskripsi_alamat' => 'required',
            'is_default' => 'required',
        ]);
        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $lastId = Alamat::latest('id_alamat')->first();
        $newId = $lastId ? $lastId->id_alamat + 1 : 1;
        $storeData['id_alamat'] = $newId;


        $alamat = Alamat::create($storeData);

        return response([
            'message' => 'Alamat Added Successfully',
            'data' => $alamat,
        ], 200);
    }

    public function show(string $id)
    {
        $alamat = Alamat::find($id);

        if($alamat){
            return response([
                'message' => 'Alamat Found',
                'data' => $alamat
            ],200);
        }

        return response([
            'message' => 'Alamat Not Found',
            'data' => null
        ],404);
    }

    public function update(Request $request, string $id)
    {
        $alamat = Alamat::find($id);
        if(is_null($alamat)){
            return response([
                'message' => 'Alamat Not Found',
                'data' => null
            ],404);
        }

        $updateData = $request->all();

        $validate = Validator::make($updateData,[
            'id_pembeli' => 'required',
            'label_alamat' => 'required',
            'deskripsi_alamat' => 'required',
            'is_default' => 'required',
        ]);
        if ($validate->fails()) {
            return response(['message'=> $validate->errors()],400);
        }


        $alamat->update($updateData);

        return response([
            'message' => 'Alamat Updated Successfully',
            'data' => $alamat,
        ],200);
    }

    public function destroy(string $id)
    {
        $alamat = Alamat::find($id);

        if(is_null($alamat)){
            return response([
                'message' => 'Alamat Not Found',
                'data' => null
            ],404);
        }

        if($alamat->delete()){
            return response([
                'message' => 'Alamat Deleted Successfully',
                'data' => $alamat,
            ],200);
        }

        return response([
            'message' => 'Delete Alamat Failed',
            'data' => null,
        ],400);
    }
}
