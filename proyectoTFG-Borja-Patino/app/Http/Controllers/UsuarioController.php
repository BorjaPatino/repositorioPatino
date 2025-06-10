<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{

    private function checkAdmin()
    {
        if (Auth::user()->rol !== 'admin') {
            abort(403, 'Acceso solo para administradores');
        }
    }


    public function index()
    {
        $this->checkAdmin();

        $usuarios = Usuario::all();
        return view('usuarios.index', compact('usuarios'));
    }

 
    public function edit(Usuario $usuario)
    {
        $this->checkAdmin();

        return view('usuarios.edit', compact('usuario'));
    }

    public function update(Request $request, Usuario $usuario)
    {
        $this->checkAdmin();

        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $usuario->id,
            'rol' => 'required|in:admin,usuario',
            'contraseña' => 'nullable|min:8'
        ]);

        $usuario->nombre = $request->nombre;
        $usuario->email = $request->email;
        $usuario->rol = $request->rol;

        if ($request->filled('contraseña')) {
            $usuario->contraseña = Hash::make($request->contraseña);
        }

        $usuario->save();

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente.');
    }


    public function destroy(Usuario $usuario)
    {
        $this->checkAdmin();

        if ($usuario->pedidos()->exists()) {
            return redirect()->route('usuarios.index')
                ->with('error', 'No se puede eliminar el usuario porque tiene pedidos registrados.');
        }

        $usuario->delete();
        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado correctamente.');
    }
}
