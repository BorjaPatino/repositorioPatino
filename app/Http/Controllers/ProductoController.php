<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductoController extends Controller
{

    private function checkAdmin()
    {
        if (Auth::user()->rol !== 'admin') {
            abort(403, 'Acceso solo para administradores');
        }
    }

    public function index(Request $request)
    {
        $categoriaId = $request->input('categoria_id');

        $productos = Producto::with('categoria')
            ->when($categoriaId, function ($query, $categoriaId) {
                return $query->where('categoria_id', $categoriaId);
            })
            ->get();

        $categorias = Categoria::all();

        return view('productos.index', [
            'productos' => $productos,
            'categorias' => $categorias,
            'categoriaSeleccionada' => $categoriaId,
        ]);
    }



    public function show($id)
    {
        $producto = Producto::with('categoria')->findOrFail($id);
        return view('productos.show', ['producto' => $producto]);
    }


    public function create()
    {
        $this->checkAdmin();
        $categorias = Categoria::all();
        return view('productos.create', ['categorias' => $categorias]);
    }


    public function store(Request $request)
    {
        $this->checkAdmin();
        
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'imagen' => 'nullable|image|max:2048',
            'categoria_id' => 'required|exists:categorias,id',
        ], [
            'imagen.image' => 'La imagen debe ser un archivo de imagen válido (jpg, png, etc).',
        ]);

        $producto = new Producto();
        $producto->nombre = $request->input('nombre');
        $producto->descripcion = $request->input('descripcion');
        $producto->precio = $request->input('precio');
        $producto->stock = $request->input('stock');
        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $nombre = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('imagenes'), $nombre);
            $producto->imagen = 'imagenes/' . $nombre;
        }
        $producto->categoria_id = $request->input('categoria_id');
        $producto->save();

        return redirect('/productos')->with('success', 'Producto creado con éxito.');
    }


    public function edit($id)
    {
        $this->checkAdmin();
        $producto = Producto::findOrFail($id);
        $categorias = Categoria::all();
        return view('productos.edit', ['producto' => $producto, 'categorias' => $categorias]);
    }

    
    public function update(Request $request, $id)
    {
        $this->checkAdmin();
        
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'imagen' => 'nullable|image|max:2048',
            'categoria_id' => 'required|exists:categorias,id',
        ], [
            'imagen.image' => 'La imagen debe ser un archivo de imagen válido (jpg, png, etc).',
        ]);

        $producto = Producto::find($id);
        $producto->nombre = $request->input('nombre');
        $producto->descripcion = $request->input('descripcion');
        $producto->precio = $request->input('precio');
        $producto->stock = $request->input('stock');
        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $nombre = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('imagenes'), $nombre);
            $producto->imagen = 'imagenes/' . $nombre;
        }
        
        $producto->categoria_id = $request->input('categoria_id');
        $producto->save();

        return redirect('/productos')->with('success', 'Producto actualizado con éxito.');
    }

    
    public function destroy($id)
    {
        $this->checkAdmin();
        $producto = Producto::findOrFail($id);
        $producto->delete();

        if(request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect('/productos')->with('success', 'Producto eliminado con éxito.');
    }

    public function filtrarPorCategoria(Request $request)
    {
        $categoriaId = $request->input('categoria_id');
        $productos = Producto::with('categoria')
        ->when($categoriaId, fn($query) => $query->where('categoria_id', $categoriaId))
        ->get();

        return response()->json($productos);
    }
}