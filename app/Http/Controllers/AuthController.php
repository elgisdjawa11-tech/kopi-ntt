<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * 1. Menampilkan Halaman Login
     * Jika user sudah login, langsung diarahkan sesuai rolenya.
     */
    public function showLogin() 
    {
        if (Auth::check()) {
            return $this->redirectUserByRole(Auth::user());
        }
        return view('admin.login');
    }

    /**
     * 2. Menampilkan Halaman Registrasi Pelanggan
     * CATATAN: Jika kamu tidak bisa melihat halaman registrasi,
     * pastikan kamu sudah LOGOUT terlebih dahulu.
     */
    public function showRegister() 
    {
        // Logika ini yang membuat halaman registrasi "hilang" jika kamu sudah login
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('register');
    }

    /**
     * 3. Proses Registrasi Pelanggan Baru
     */
    public function register(Request $request) 
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'phone'    => 'required|numeric',
            'city'     => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'username' => $request->username,
            'phone'    => $request->phone,
            'city'     => $request->city,
            'password' => Hash::make($request->password),
            'role'     => 'pelanggan', // Default role untuk pendaftar baru
        ]);

        // Otomatis login setelah daftar
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('home')->with('success', 'Pendaftaran berhasil! Selamat datang di Kopi NTT.');
    }

    /**
     * 4. Proses Login
     */
    public function login(Request $request) 
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Redirect user berdasarkan rolenya di database
            return $this->redirectUserByRole(Auth::user());
        }

        return back()->with('error', 'Maaf, username atau password salah!');
    }

    /**
     * 5. Helper Fungsi untuk Mengarahkan User sesuai Role
     * Memastikan Admin, Pemilik, dan Kurir masuk ke pintu yang benar.
     */
    private function redirectUserByRole($user)
    {
        if ($user->role === 'pemilik') {
            return redirect()->route('admin.laporan.penjualan');
        }

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role === 'pengirim') {
            return redirect()->route('pengirim.index');
        }

        // Jika role pelanggan atau lainnya, lempar ke home
        return redirect()->route('home');
    }

    /**
     * 6. Proses Keluar (Logout)
     */
    public function logout(Request $request) 
    {
        Auth::logout();
        
        // Bersihkan session agar tidak ada data yang nyangkut
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar dari sistem.');
    }
}