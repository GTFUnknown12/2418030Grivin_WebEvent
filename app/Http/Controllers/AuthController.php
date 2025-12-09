<?php

namespace App\Http\Controllers;

use App\Models\Pembeli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Find user by username
        $pembeli = Pembeli::where('username', $request->username)->first();

        // Check credentials
        if (!$pembeli || !Hash::check($request->password, $pembeli->password)) {
            return back()->withErrors([
                'username' => 'Username atau password salah.',
            ])->withInput();
        }

        // Login user
        Auth::guard('pembeli')->login($pembeli);
        Session::put('nama_pembeli', $pembeli->nama_pembeli);
        Session::put('user_id', $pembeli->id_pembeli);

        // Check if user is admin and redirect accordingly
        if ($pembeli->isAdmin()) {
            return redirect()->route('admin.dashboard')->with('success', 'Welcome back, Admin!');
        }

        return redirect()->route('index.user')->with('success', 'Login successful!');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama_pembeli' => 'required|string|max:255',
            'username' => 'required|string|unique:pembelis|max:255',
            'password' => 'required|string|min:8|confirmed',
            'alamat' => 'required|string|max:500',
            'email' => 'required|email|unique:pembelis|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tanggal_lahir' => 'required|date',
        ]);

        $pembeli = Pembeli::create([
            'nama_pembeli' => $request->nama_pembeli,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'alamat' => $request->alamat,
            'email' => $request->email,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_lahir' => $request->tanggal_lahir,
            'is_admin' => false, // Always false for registration
        ]);

        // Auto login after registration
        Auth::guard('pembeli')->login($pembeli);
        Session::put('nama_pembeli', $pembeli->nama_pembeli);
        Session::put('user_id', $pembeli->id_pembeli);

        return redirect()->route('index.user')->with('success', 'Registration successful! Welcome to CwnXtech!');
    }

    public function logout(Request $request)
    {
        Auth::guard('pembeli')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        Session::forget(['nama_pembeli', 'user_id']);

        return redirect()->route('login')->with('success', 'You have been logged out.');
    }
}