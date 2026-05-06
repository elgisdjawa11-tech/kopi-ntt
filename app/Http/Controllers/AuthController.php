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
     * 2. Menampilkan Halaman Registrasi Dinamis
     * Fungsi-fungsi ini mengirimkan data 'role' dan 'title' ke satu file view yang sama.
     */
    public function regPelanggan() {
        return view('register', ['role' => 'pelanggan', 'title' => 'Pelanggan']);
    }

    public function regPengirim() {
        return view('register', ['role' => 'pengirim', 'title' => 'Pengirim (Kurir)']);
    }

    public function regAdmin() {
        return view('register', ['role' => 'admin', 'title' => 'Administrator']);
    }

    public function regPemilik() {
        return view('register', ['role' => 'pemilik', 'title' => 'Pemilik Toko']);
    }

    /**
     * 3. Proses Registrasi User Baru (Berlaku untuk semua role)
     */
    public function register(Request $request) 
    {
        // Validasi data input
        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'phone'    => 'required|numeric',
            'city'     => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'required|string' // Menangkap role dari input hidden di form
        ]);

        // Simpan data user baru ke database
        $user = User::create([
            'name'     => $request->name,
            'username' => $request->username,
            'phone'    => $request->phone,
            'city'     => $request->city,
            'password' => Hash::make($request->password),
            'role'     => $request->role, // Menyimpan role sesuai parameter dari rute
        ]);

        // Otomatis login setelah pendaftaran berhasil
        Auth::login($user);
        $request->session()->regenerate();

        // Mengarahkan ke dashboard masing-masing sesuai role
        return $this->redirectUserByRole($user)->with('success', 'Pendaftaran berhasil sebagai ' . $request->role);
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

        // Mencoba melakukan otentikasi
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Redirect user berdasarkan perannya
            return $this->redirectUserByRole(Auth::user());
        }

        return back()->with('error', 'Maaf, username atau password salah!');
    }

    /**
     * 5. Helper Fungsi untuk Mengarahkan User sesuai Role
     * Digunakan untuk menjaga agar setiap user masuk ke dashboard yang benar.
     */
    private function redirectUserByRole($user)
    {
        if ($user->role === 'pemilik') {
            return redirect()->route('admin.pemilik.dashboard');
        }

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role === 'pengirim') {
            return redirect()->route('pengirim.index');
        }

        // Default untuk pelanggan atau role lainnya
        return redirect()->route('home');
    }

    /**
     * 6. Proses Keluar (Logout)
     */
    public function logout(Request $request) 
    {
        Auth::logout();
        
        // Menghapus data session untuk keamanan
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar.');
    }
}