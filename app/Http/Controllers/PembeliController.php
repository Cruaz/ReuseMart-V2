<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembeli;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class PembeliController extends Controller
{
    public function register(Request $request)
    {
        $registrationData = $request->all();

        $validate = Validator::make($registrationData, [
            'username' => 'required',
            'email' => 'required|email:rfc,dns|unique:pembeli',
            'password' => 'required|min:8',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()->first()], 400);
        }

        $registrationData['password'] = Hash::make($request->password);

        $pembeli = Pembeli::create($registrationData);

        return response([
            'message' => 'Register Success',
            'pembeli' => $pembeli
        ], 200);
    }

    // public function login(Request $request)
    // {
    //     $loginData = $request->all();

    //     $validate = Validator::make($loginData, [
    //         'email' => 'required|email:rfc,dns',
    //         'password' => 'required|min:8',
    //     ]);
        
    //     if ($validate->fails()) {
    //         return response(['message' => $validate->errors()->first()], 400);
    //     }

    //     if (!Auth::guard('pembeli')->attempt($loginData)) {
    //         return response(['message' => 'Invalid email & password match'], 401);
    //     }
        
    //     $pembeli = Auth::guard('pembeli')->user();
    //     $token = $pembeli->createToken('Authentication Token')->plainTextToken;

    //     return response([
    //         'message' => 'Authenticated',
    //         'pembeli' => $pembeli,
    //         'token_type' => 'Bearer',
    //         'access_token' => $token
    //     ]);
    // }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        Auth::guard('pembeli')->logout();

        return response([
            'message' => 'Logged out'
        ]);
    }

    public function index(Request $request)
    {   
        $query = Pembeli::query(); 
        if ($request->has('search') && $request->search != '') {
            $query->where('username', 'like', '%' . $request->search . '%');
        }
        $perPage = $request->query('per_page', 7);
        $pembeli = $query->paginate($perPage);

        return response([
            'message' => 'Pembeli Retrieved',
            'data' => $pembeli
        ], 200);
    }

    public function getData(){
        $data = Pembeli::all();

        return response([
            'message' => 'All Pembeli Retrieved',
            'data' => $data
        ], 200);
    }

    public function store(Request $request)
    {
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'username' => 'required',
            'email' => 'required',
            'password' => 'required',
            'foto' => 'nullable|image',
        ]);
        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $lastId = Pembeli::latest('id_pembeli')->first();
        $newId = $lastId ? $lastId->id_pembeli + 1 : 1;
        $storeData['id_pembeli'] = $newId;
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

        $pembeli = Pembeli::create($storeData);

        return response([
            'message' => 'Pembeli Added Successfully',
            'data' => $pembeli,
        ], 200);
    }

    public function show(string $id)
    {
        $pembeli = Pembeli::find($id);

        if($pembeli){
            return response([
                'message' => 'Pembeli Found',
                'data' => $pembeli
            ],200);
        }

        return response([
            'message' => 'Pembeli Not Found',
            'data' => null
        ],404);
    }

    public function update(Request $request, string $id)
    {
        $pembeli = Pembeli::find($id);
        if(is_null($pembeli)){
            return response([
                'message' => 'Pembeli Not Found',
                'data' => null
            ],404);
        }

        $updateData = $request->all();

        $validate = Validator::make($updateData,[
            'email' => 'required',
            'username' => 'required',
            'password' => 'required',
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
            if ($pembeli->foto) {
                Storage::disk('public')->delete('Galery/' . $pembeli->foto);
            }
            
            $updateData['foto'] = $uploadedImageResponse;
        }
        $updateData['password'] = Hash::make($updateData['password']);

        $pembeli->update($updateData);

        return response([
            'message' => 'Pembeli Updated Successfully',
            'data' => $pembeli,
        ],200);
    }

    public function updateProfile(Request $request)
    {
        $pembeli = Auth::guard('pembeli')->user();


        $updateData = $request->all();
        $updateData['id_pembeli'] = auth('pembeli')->user()->id_pembeli;

        $validate = Validator::make($updateData,[
            'email' => 'required|email',
            'username' => 'required',
            'password' => 'sometimes|required|min:8',
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
            if ($pembeli->foto) {
                Storage::disk('public')->delete('Galery/' . $pembeli->foto);
            }
            
            $updateData['foto'] = $uploadedImageResponse;
        }
        if (!empty($updateData['password'])) {
            $updateData['password'] = Hash::make($updateData['password']);
        } else {
            unset($updateData['password']);
        }

        $pembeli->update($updateData);

        return response([
            'message' => 'Pembeli Updated Successfully',
            'data' => $pembeli,
        ],200);
    }

    public function destroy(string $id)
    {
        $pembeli = Pembeli::find($id);

        if(is_null($pembeli)){
            return response([
                'message' => 'Pembeli Not Found',
                'data' => null
            ],404);
        }

        if($pembeli->delete()){
            return response([
                'message' => 'Pembeli Deleted Successfully',
                'data' => $pembeli,
            ],200);
        }

        return response([
            'message' => 'Delete Pembeli Failed',
            'data' => null,
        ],400);
    }

    public function profile()
    {
        $pembeli = Auth::guard('pembeli')->user();

        return view('users.profilePembeli', compact('pembeli'));
    }

    public function history()
    {
        $pembeli = Auth::guard('pembeli')->user();

        return view('users.historyPembeli', compact('pembeli'));
    }

    public function getUserProfile(Request $request)
    {
        $user = $request->user();
        
        return response()->json([
            'success' => true,
            'data' => [
                'poin_pembeli' => $user->poin_pembeli,
                // tambahkan data user lainnya jika diperlukan
            ],
            'message' => 'User profile retrieved successfully'
        ]);
    }

    public function profileMobile(Request $request)
    {
        $user = $request->user();
        
        return response()->json([
            'username' => $user->username,
            'email' => $user->email,
            'poin_pembeli' => $user->poin_pembeli,
        ]);
    }
}
