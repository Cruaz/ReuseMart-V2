<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jabatan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class JabatanController extends Controller
{
    public function index(Request $request)
    {   
        $query = Jabatan::query(); 
        if ($request->has('search') && $request->search != '') {
            $query->where('nama_jabatan', 'like', '%' . $request->search . '%');
        }
        $perPage = $request->query('per_page', 7);
        $jabatan = $query->paginate($perPage);

        return response([
            'message' => 'jabatan Retrieved',
            'data' => $jabatan
        ], 200);
    }

    public function getData(){
        $data = Jabatan::all();

        return response([
            'message' => 'All Jabatan Retrieved',
            'data' => $data
        ], 200);
    }

    public function store(Request $request)
    {
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'nama_jabatan' => 'required',
        ]);
        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $lastId = Jabatan::latest('id_jabatan')->first();
        $newId = $lastId ? $lastId->id_jabatan + 1 : 1;
        $storeData['id_jabatan'] = $newId;


        $jabatan = Jabatan::create($storeData);

        return response([
            'message' => 'Jabatan Added Successfully',
            'data' => $jabatan,
        ], 200);
    }

    public function show(string $id)
    {
        $jabatan = Jabatan::find($id);

        if($jabatan){
            return response([
                'message' => 'Jabatan Found',
                'data' => $jabatan
            ],200);
        }

        return response([
            'message' => 'Jabatan Not Found',
            'data' => null
        ],404);
    }

    public function update(Request $request, string $id)
    {
        $jabatan = Jabatan::find($id);
        if(is_null($jabatan)){
            return response([
                'message' => 'Jabatan Not Found',
                'data' => null
            ],404);
        }

        $updateData = $request->all();

        $validate = Validator::make($updateData,[
            'nama_jabatan' => 'required',
        ]);
        if ($validate->fails()) {
            return response(['message'=> $validate->errors()],400);
        }


        $jabatan->update($updateData);

        return response([
            'message' => 'Jabatan Updated Successfully',
            'data' => $jabatan,
        ],200);
    }

    public function destroy(string $id)
    {
        $jabatan = Jabatan::find($id);

        if(is_null($jabatan)){
            return response([
                'message' => 'Jabatan Not Found',
                'data' => null
            ],404);
        }

        if($jabatan->delete()){
            return response([
                'message' => 'Jabatan Deleted Successfully',
                'data' => $jabatan,
            ],200);
        }

        return response([
            'message' => 'Delete Jabatan Failed',
            'data' => null,
        ],400);
    }
}
