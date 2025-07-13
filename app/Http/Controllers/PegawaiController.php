<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class PegawaiController extends Controller
{
    public function index(Request $request)
    {
        $query = Pegawai::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('username', 'like', '%' . $request->search . '%');
            
                //   ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        $perPage = $request->query('per_page', 10);
        $pegawai = $query->paginate($perPage);

        return response([
            'message' => 'All Pegawai Retrieved',
            'data' => $pegawai
        ], 200);
    }

    public function show(string $id)
    {
        $pegawai = Pegawai::find($id);

        if ($pegawai) {
            return response([
                'message' => 'Pegawai Found',
                'data' => $pegawai
            ], 200);
        }

        return response([
            'message' => 'Pegawai Not Found',
            'data' => null
        ], 404);
    }

    public function store(Request $request)
    {
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'id_jabatan' => 'required',
            'username' => 'required|string|unique:pegawai',
            'password' => 'required|string|min:6',
            'tanggal_lahir_pegawai' => 'required|date',
            'nomor_telepon_pegawai' => 'required|string',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $lastId = Pegawai::latest('id_pegawai')->first();
        $newId = $lastId ? $lastId->id_pegawai + 1 : 1;
        $storeData['id_pegawai'] = $newId;
        $storeData['password'] = Hash::make($storeData['password']);

        $pegawai = Pegawai::create($storeData);

        return response([
            'message' => 'Pegawai Added Successfully',
            'data' => $pegawai,
        ], 200);
    }

    public function update(Request $request, string $id)
    {
        $pegawai = Pegawai::find($id);
        if (is_null($pegawai)) {
            return response(['message' => 'Pegawai Not Found'], 404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'id_jabatan' => 'sometimes|required',
            'username' => 'sometimes|required|string|unique:pegawai,username,' . $id . ',id_pegawai',
            'password' => 'sometimes|required|string|min:6',
            'tanggal_lahir_pegawai' => 'sometimes|required|date',
            'nomor_telepon_pegawai' => 'sometimes|required|string',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        if (isset($updateData['password'])) {
            $updateData['password'] = Hash::make($updateData['password']);
        }
        $updateData['password'] = Hash::make($updateData['password']);
        $pegawai->update($updateData);

        return response([
            'message' => 'Pegawai Updated Successfully',
            'data' => $pegawai,
        ], 200);
    }

    public function destroy(string $id)
    {
        $pegawai = Pegawai::find($id);

        if (is_null($pegawai)) {
            return response(['message' => 'Pegawai Not Found'], 404);
        }

        $pegawai->delete();

        return response([
            'message' => 'Pegawai Deleted Successfully'
        ], 200);
    }

    public function getKurir()
    {
        $kurir = Pegawai::with('jabatan')
            ->where('id_jabatan', 6)
            ->get()
            ->map(function ($item) {
                return [
                    'id_pegawai' => $item->id_pegawai,
                    'username' => $item->username,
                    'nama_jabatan' => $item->jabatan->nama_jabatan ?? null,
                ];
            });

        // Langsung kirim array (bukan bungkus dalam ['data' => ...])
        return response()->json($kurir);
    }

    public function profileKurirMobile(Request $request)
    {
        $user = $request->user();
        
        if (!$user || $user->id_jabatan != 6) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json([
            'username' => $user->username,
            'tanggal_lahir_pegawai' => $user->tanggal_lahir_pegawai,
            'nomor_telepon_pegawai' => $user->nomor_telepon_pegawai,
        ]);
    }
    
    public function profileHunterMobile(Request $request)
    {
        $user = $request->user();
        
        if (!$user || $user->id_jabatan != 5) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json([
            'username' => $user->username,
            'tanggal_lahir_pegawai' => $user->tanggal_lahir_pegawai,
            'nomor_telepon_pegawai' => $user->nomor_telepon_pegawai,
        ]);
    }

    public function getTotalKomisiHunter()
    {
        $user = auth()->user();

        $totalKomisi = DB::table('komisi')
            ->where('id_pegawai', $user->id_pegawai)
            ->sum('komisi_hunter');

        return response()->json(['total_komisi_hunter' => $totalKomisi]);
    }

    public function resetFcmToken(Request $request)
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user->update(['fcm_token' => null]);

        return response()->json(['message' => 'FCM token reset successfully']);
    }
}
