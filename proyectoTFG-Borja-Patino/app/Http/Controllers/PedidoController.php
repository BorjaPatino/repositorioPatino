<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PedidoController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();

        if ($usuario->rol === 'admin') {
            $pedidos = Pedido::with('producto', 'usuario')->get();
        } else {
            $pedidos = Pedido::with('producto')->where('usuario_id', $usuario->id)->get();
        }

        return view('pedidos.index', ['pedidos' => $pedidos]);
    }


    public function create(Request $request)
    {
        $producto = null;

        if ($request->has('producto_id')) {
            $producto = Producto::find($request->producto_id);
        }

        return view('pedidos.create', compact('producto'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
            'direccion' => 'required|string|max:255',
            'ciudad' => 'required|string|max:100',
            'provincia' => 'required|string|max:100',
            'codigo_postal' => 'required|string|max:10',
        ]);

        $producto = Producto::findOrFail($request->producto_id);

        if ($producto->stock < $request->cantidad) {
            return redirect()->back()->with('error', 'No hay suficiente stock disponible.');
        }

        $total = $producto->precio * $request->cantidad;

        Pedido::create([
            'usuario_id' => Auth::id(),
            'producto_id' => $producto->id,
            'cantidad' => $request->cantidad,
            'total' => $total,
            'fecha_pedido' => now(),
            'direccion' => $request->direccion,
            'ciudad' => $request->ciudad,
            'provincia' => $request->provincia,
            'codigo_postal' => $request->codigo_postal,
        ]);

        $producto->stock -= $request->cantidad;
        $producto->save();

        return redirect()->route('productos.index')->with('success', 'Pedido realizado con éxito.');
    }

    public function show($id)
    {
        $pedido = Pedido::with('producto.categoria', 'usuario')->findOrFail($id);
        return view('pedidos.show', compact('pedido'));
    }


    public function destroy($id)
    {
        $pedido = Pedido::findOrFail($id);

        if (Auth::user()->rol !== 'admin' && $pedido->usuario_id !== Auth::id()) {
            abort(403);
        }

         if ($pedido->producto) {
            $pedido->producto->stock += $pedido->cantidad;
            $pedido->producto->save();
        }

        $pedido->delete();

        if(request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect('/pedidos')->with('success', 'Pedido eliminado correctamente.');
    }
}
