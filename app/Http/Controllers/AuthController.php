<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Pembeli;
use App\Models\Penitip;
use App\Models\Organisasi;
use App\Models\Pegawai;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $organisasi = Organisasi::where('email', $credentials['email'])->first();
        $penitip = Penitip::where('email', $credentials['email'])->first();
        $pembeli = Pembeli::where('email', $credentials['email'])->first();

        if ($organisasi && Hash::check($credentials['password'], $organisasi->password)) {
            Auth::guard('organisasi')->login($organisasi);
            session(['role' => 'organisasi']);
            return redirect('/homeOrganisasi');
        }

        if ($penitip && Hash::check($credentials['password'], $penitip->password)) {
            Auth::guard('penitip')->login($penitip);
            session(['role' => 'penitip']);
            return redirect('/homePenitip');
        }

        if ($pembeli && Hash::check($credentials['password'], $pembeli->password)) {
            Auth::guard('pembeli')->login($pembeli);
            session(['role' => 'pembeli']);
            return redirect('/homePembeli');
        }

        if (!$organisasi && !$penitip && !$pembeli) {
            return back()->with('error', 'Email tidak ditemukan di sistem');
        }

        return back()->with('error', 'Password salah');
    }

    public function loginMobile(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $penitip = Penitip::where('email', $credentials['email'])->first();
        $pembeli = Pembeli::where('email', $credentials['email'])->first();

        if ($penitip && Hash::check($credentials['password'], $penitip->password)) {
            Auth::guard('penitip')->login($penitip);
            $token = $penitip->createToken('mobile-token')->plainTextToken;
            return response()->json([
                'success' => true,
                'role' => 'penitip',
                'token' => $token,
                'user_id' => $penitip->id_penitip
            ]);
        }

        if ($pembeli && Hash::check($credentials['password'], $pembeli->password)) {
            Auth::guard('pembeli')->login($pembeli);
            $token = $pembeli->createToken('mobile-token')->plainTextToken;
            session(['role' => 'pembeli']);
            return response()->json([
                'success' => true,
                'role' => 'pembeli',
                'token' => $token,
                'user_id' => $pembeli->id_pembeli
            ]);
        }

        if (!$penitip && !$pembeli) {
             return response()->json([
                'success' => false,
                'message' => 'Email tidak ditemukan di sistem'
            ], 401);
        }

         return response()->json([
            'success' => false,
            'message' => 'Password salah'
        ], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response([
            'message' => 'Logged out'
        ]);
    }

    protected function respondWithToken($role, $user)
    {
        session([
            'logged_in' => true,
            'role' => $role,
            'user' => $user,
        ]);

        switch ($role) {
            case 'pembeli':
                return redirect()->route('homePembeli');
            case 'penitip':
                return redirect()->route('homePenitip');
            case 'organisasi':
                return redirect()->route('homeOrganisasi');
            default:
                return redirect()->route('homeumum');
        }
    }

    public function loginPegawai(Request $request)
    {
        $credentials = $request->only('username', 'password');

        $pegawai = Pegawai::where('username', $credentials['username'])->first();

        if (!$pegawai) {
            return back()->with('error', 'Username tidak ditemukan di sistem');
        }

        if (!Hash::check($credentials['password'], $pegawai->password)) {
            return back()->with('error', 'Password salah');
        }

        Auth::guard('pegawai')->login($pegawai);
        session(['role' => $pegawai->jabatan->nama_jabatan]);

        switch ($pegawai->jabatan->nama_jabatan) {
            case 'Owner':
                return redirect('/homeOwner');
            case 'Admin':
                return redirect('/jabatan');
            case 'Gudang':
                return redirect('/homeGudang');
            case 'Customer Service':
                return redirect('/homeCs');
            default:
                return back()->with('error', 'Jabatan tidak dikenali');
        }
    }

    public function loginPegawaiMobile(Request $request)
    {
        $credentials = $request->only('username', 'password');

        $pegawai = Pegawai::where('username', $credentials['username'])->first();

        if (!$pegawai) {
            return response()->json([
                'success' => false,
                'message' => 'Username tidak ditemukan di sistem'
            ], 401);
        }

        if (!Hash::check($credentials['password'], $pegawai->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password salah'
            ], 401);
        }

        Auth::guard('pegawai')->login($pegawai);
        $token = $pegawai->createToken('mobile-token')->plainTextToken;
        
        return response()->json([
            'success' => true,
            'role' => $pegawai->jabatan->nama_jabatan,
            'token' => $token,
        ]);
    }

    protected function getRedirectPath($jabatan)
    {
        switch ($jabatan) {
            case 'Owner': return '/homeOwner';
            case 'Admin': return '/jabatan';
            case 'Gudang': return '/homeGudang';
            case 'Customer Service': return '/homeCs';
            case 'Kurir': return '/homeKurir';
            case 'Hunter': return '/homeHunter';
            default: return '/';
        }
    }

    public function updateFcmToken(Request $request)
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $fcmToken = $request->fcm_token;
        
        $user->fcm_token = $fcmToken;
        $user->save();

        return response()->json(['message' => 'FCM token updated']);
    }
}
