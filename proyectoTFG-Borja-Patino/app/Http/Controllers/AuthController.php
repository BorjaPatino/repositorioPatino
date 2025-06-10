<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'contraseña' => 'required',
        ]);

        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['contraseña']])) {
            $request->session()->regenerate();

            $usuario = Auth::user();
            if ($usuario->rol === 'admin') {
                return redirect()->intended('/productos');
            } else {
                return redirect()->intended('/productos');
            }
        }

        return back()->withErrors([
            'email' => 'El correo o contraseña introducidos no son correctos.',
        ])->withInput($request->except('contraseña'));
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $datos = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:usuarios',
            'contraseña' => 'required|string|min:8|confirmed',
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico no es válido.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'contraseña.required' => 'La contraseña es obligatoria.',
            'contraseña.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'contraseña.confirmed' => 'La confirmación de la contraseña no coincide.',
        ]);

        $usuario = Usuario::create([
            'nombre' => $datos['nombre'],
            'email' => $datos['email'],
            'contraseña' => Hash::make($datos['contraseña']),
            'rol' => 'usuario',
        ]);

        Auth::login($usuario);

        return redirect('login');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function dashboard()
    {
        return view('dashboard');
    }

    public function adminDashboard()
    {
        return view('admin.dashboard');
    }
}