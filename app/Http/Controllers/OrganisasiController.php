<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organisasi;
use App\Models\RequestDonasi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class OrganisasiController extends Controller
{
    public function register(Request $request)
    {
        $registrationData = $request->all();

        $validate = Validator::make($registrationData, [
            'username' => 'required',
            'email' => 'required|email:rfc,dns|unique:organisasi',
            'password' => 'required|min:8',
            'alamat_organisasi' => 'required',
            'foto' => 'required|image'
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()->first()], 400);
        }

        $uploadFolder = 'Galery';
        $image = $request->file('foto');
        if ($image) {
            $image_uploaded_path = $image->store($uploadFolder, 'public');
            $uploadedImageResponse = basename($image_uploaded_path);
            $registrationData['foto'] = $uploadedImageResponse;
        }

        $registrationData['password'] = bcrypt($request->password);

        $organisasi = Organisasi::create($registrationData);

        return response([
            'message' => 'Register Success',
            'data' => $organisasi
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        Auth::guard('organisasi')->logout();

        return response([
            'message' => 'Logged out'
        ]);
    }

    public function index(Request $request)
    {   
        $query = Organisasi::query(); 
        if ($request->has('search') && $request->search != '') {
            $query->where('username', 'like', '%' . $request->search . '%')
            ->orWhere('email', 'like', '%' . $request->search . '%')
            ->orWhere('alamat_organisasi', 'like', '%' . $request->search . '%')
            ->orWhere('id_organisasi', 'like', '%' . $request->search . '%');
        }
        $perPage = $request->query('per_page', 7);
        $organisasi = $query->paginate($perPage);

        return response([
            'message' => 'Organisasi Retrieved',
            'data' => $organisasi
        ], 200);
    }

    public function getData(){
        $data = Organisasi::all();

        return response([
            'message' => 'All Organisasi Retrieved',
            'data' => $data
        ], 200);
    }

    public function store(Request $request)
    {
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'email' => 'required',
            'username' => 'required',
            'password' => 'required',
            'foto' => 'nullable|image',
            'alamat_organisasi' => 'required',
        ]);
        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $lastId = Organisasi::latest('id_organisasi')->first();
        $newId = $lastId ? $lastId->id_organisasi + 1 : 1;
        $storeData['id_organisasi'] = $newId;
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

        $organisasi = Organisasi::create($storeData);

        return response([
            'message' => 'Organisasi Added Successfully',
            'data' => $organisasi,
        ], 200);
    }

    public function show(string $id)
    {
        $organisasi = Organisasi::find($id);

        if($organisasi){
            return response([
                'message' => 'Organisasi Found',
                'data' => $organisasi
            ],200);
        }

        return response([
            'message' => 'Organisasi Not Found',
            'data' => null
        ],404);
    }

    public function update(Request $request, string $id)
    {
        $organisasi = Organisasi::find($id);
        if(is_null($organisasi)){
            return response([
                'message' => 'Organisasi Not Found',
                'data' => null
            ],404);
        }

        $updateData = $request->all();

        $validate = Validator::make($updateData,[
            'email' => 'required',
            'username' => 'required',
            'password' => 'required',
            'alamat_organisasi' => 'required',
            'foto' => 'nullable|image',
        ]);
        if ($validate->fails()) {
            return response(['message'=> $validate->errors()],400);
        }

        if ($request->hasFile('foto')) {
            $uploadFolder = 'Galery';
            $image = $request->file('foto');
            $image_uploaded_path = $image->store($uploadFolder, 'public');
            $uploadedImageResponse = basename($image_uploaded_path);
            if ($organisasi->foto) {
                Storage::disk('public')->delete('Galery/' . $organisasi->foto);
            }
            
            $updateData['foto'] = $uploadedImageResponse;
        }
        $updateData['password'] = Hash::make($updateData['password']);
        $organisasi->update($updateData);

        return response([
            'message' => 'Organisasi Updated Successfully',
            'data' => $organisasi,
        ],200);
    }

    public function destroy(string $id)
    {
        $organisasi = Organisasi::find($id);

        if(is_null($organisasi)){
            return response([
                'message' => 'Organisasi Not Found',
                'data' => null
            ],404);
        }

        if($organisasi->delete()){
            return response([
                'message' => 'Organisasi Deleted Successfully',
                'data' => $organisasi,
            ],200);
        }

        return response([
            'message' => 'Delete Organisasi Failed',
            'data' => null,
        ],400);
    }

    public function getOrganisasiRequests(Request $request)
    {
        $organisasi = Auth::guard('organisasi')->user();
        
        $query = RequestDonasi::where('id_organisasi', $organisasi->id_organisasi);
        
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('deskripsi_request', 'like', '%'.$request->search.'%')
                ->orWhere('status_request', 'like', '%'.$request->search.'%');
            });
        }
        
        $perPage = $request->query('per_page', 7);
        $data = $query->paginate($perPage);
        
        return response()->json([
            'message' => 'Organisasi Requests Retrieved',
            'data' => $data
        ], 200);
    }

    public function requestDonasi()
    {
        $organisasi = Auth::guard('organisasi')->user();
        return view('org.requestDonasi', compact('organisasi'));
    }
}
