<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organisasi;
use App\Models\Pegawai;
use App\Models\Pembeli;
use App\Models\Penitip;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ResetPasswordController extends Controller
{
    public function showResetForm() {
        return view('pages.reset-password');
    }

    public function showNewPasswordForm($token)
    {
        return view('pages.new-password', ['token' => $token]);
    }

    public function processReset(Request $request) {
        $request->validate([
            'email' => 'required|email'
        ]);

        $email = $request->email;

        $organisasi = Organisasi::where('email', $email)->first();
        if ($organisasi) {
            $status = Password::broker('organisasi')->sendResetLink(['email' => $email]);
            return $this->returnResetResponse($status);
        }

        $pembeli = Pembeli::where('email', $email)->first();
        if ($pembeli) {
            $status = Password::broker('pembeli')->sendResetLink(['email' => $email]);
            return $this->returnResetResponse($status);
        }

        $penitip = Penitip::where('email', $email)->first();
        if ($penitip) {
            $status = Password::broker('penitip')->sendResetLink(['email' => $email]);
            return $this->returnResetResponse($status);
        }

        $pegawai = Pegawai::where('email_pegawai', $email)->first();
        if ($pegawai) {
            $defaultPassword = date('dmY', strtotime($pegawai->tanggal_lahir));
            $pegawai->password = Hash::make($defaultPassword);
            $pegawai->save();

            return redirect('/login')->with('success', 'Password pegawai berhasil direset ke tanggal lahir!');
        }

        return back()->withErrors(['email' => 'Email tidak ditemukan di sistem']);
    }

    private function returnResetResponse($status) {
        return $status === Password::RESET_LINK_SENT
            ? redirect('/reset-success')
            : back()->withErrors(['email' => __($status)]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $email = $request->email;
        $credentials = $request->only('email', 'password', 'password_confirmation', 'token');

        foreach (['organisasi', 'pembeli', 'penitip'] as $broker) {
            $status = Password::broker($broker)->reset(
                $credentials,
                function ($user, $password) {
                    $user->password = Hash::make($password);
                    $user->setRememberToken(Str::random(60));
                    $user->save();

                    event(new PasswordReset($user));
                }
            );

            if ($status === Password::PASSWORD_RESET) {
                return redirect()->route('login')->with('status', __($status));
            }
        }

        // return back()->withErrors(['email' => [__($status)]]);
        return back()->withErrors(['email' => 'Tolong gunakan email yang sebeumnya']);
    }

    public function resetWithUsername(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
        ]);

        $pegawai = Pegawai::where('username', $request->username)->first();

        if (!$pegawai) {
            return redirect()->back()->with('error', 'Username pegawai tidak ditemukan.');
        }

        if (!$pegawai->tanggal_lahir_pegawai) {
            return redirect()->back()->with('error', 'Tanggal lahir pegawai tidak tersedia.');
        }

        $newPasswordPlain = Carbon::parse($pegawai->tanggal_lahir_pegawai)->format('dmY');

        $pegawai->password = Hash::make($newPasswordPlain);
        $pegawai->save();
        return redirect('/loginPegawai')->with('success', 'Password berhasil direset ke tanggal lahir.');
    }

    Public function resetWithId(Request $request)
    {
        $request->validate([
            'id_pegawai' => 'required',
        ]);

        $pegawai = Pegawai::where('id_pegawai', $request->id_pegawai)->first();

        if (!$pegawai) {
            return redirect()->back()->with('error', 'ID pegawai tidak ditemukan.');
        }

        if (!$pegawai->tanggal_lahir_pegawai) {
            return redirect()->back()->with('error', 'Tanggal lahir pegawai tidak tersedia.');
        }

        $newPasswordPlain = Carbon::parse($pegawai->tanggal_lahir_pegawai)->format('dmY');

        $pegawai->password = Hash::make($newPasswordPlain);
        $pegawai->save();
    }
}
