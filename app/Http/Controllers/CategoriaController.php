<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoriaController extends Controller
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
        $categorias = Categoria::all();
        return view('categorias.index', ['categorias' => $categorias]);
    }


    public function create()
    {
        $this->checkAdmin();
        return view('categorias.create');
    }


    public function store(Request $request)
    {
        $this->checkAdmin();
        
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $categoria = new Categoria();
        $categoria->nombre = $request->input('nombre');
        $categoria->save();

        return redirect('/categorias')->with('success', 'Categoría creada con éxito.');
    }


    public function edit($id)
    {
        $this->checkAdmin();
        $categoria = Categoria::findOrFail($id);
        return view('categorias.edit', ['categoria' => $categoria]);
    }


    public function update(Request $request, $id)
    {
        $this->checkAdmin();
        
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $categoria = Categoria::findOrFail($id);
        $categoria->nombre = $request->input('nombre');
        $categoria->save();

        return redirect('/categorias')->with('success', 'Categoría actualizada con éxito.');
    }


    public function destroy($id)
    {
        $this->checkAdmin();
        $categoria = Categoria::findOrFail($id);

        if ($categoria->productos()->count() > 0) {
            return redirect('/categorias')->with('error', 'No se puede eliminar una categoría que tiene productos asociados.');
        }
        
        $categoria->delete();

        return redirect('/categorias')->with('success', 'Categoría eliminada con éxito.');
    }
}