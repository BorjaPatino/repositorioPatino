@extends('layouts.app')

@section('title', 'Realizar pedido')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Realizar pedido</h1>

    @if($producto)
        {{-- Detalles del producto --}}
        <div class="card mb-4 p-3">
            <div class="card-body">
                <h3 class="card-title mb-4">Detalles del producto</h3>
                <div class="row g-3">
                {{-- Imagen a la izquierda --}}
                    <div class="col-md-4 text-center mb-4 mb-md-0 d-flex align-items-center justify-content-center">
                        @if($producto->imagen)
                            <div class="w-100" style="max-height: 300px;">
                                <img src="{{ asset($producto->imagen) }}" alt="Imagen del producto"
                                    class="img-fluid rounded shadow-sm w-100"
                                    style="max-height: 300px; object-fit: contain;">
                            </div>
                        @else
                            <p>No hay imagen disponible.</p>
                        @endif
                    </div>

                    {{-- Detalles a la derecha --}}
                    <div class="col-md-8">
                        <p class="card-text"><strong>Nombre:</strong> {{ $producto->nombre }}</p>
                        <p class="card-text"><strong>Descripción:</strong> {{ $producto->descripcion }}</p>
                        <p class="card-text"><strong>Categoría:</strong> {{ $producto->categoria->nombre ?? 'Sin categoría' }}</p>
                        <p class="card-text"><strong>Precio unitario:</strong> {{ $producto->precio }} €</p>
                        <p class="card-text"><strong>Stock disponible:</strong> {{ $producto->stock }}</p>
                        <p class="card-text"><strong>Fecha de creación:</strong> {{ $producto->fecha_creacion }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Formulario de pedido --}}
        <div class="card p-4 mb-4">
            <h3 class="mb-3">Formulario de pedido</h3>
            <form action="{{ route('pedidos.store') }}" method="POST">
                @csrf
                <input type="hidden" name="producto_id" value="{{ $producto->id }}">

                <div class="mb-3">
                    <label for="cantidad" class="form-label">Cantidad</label>
                    <input type="number" name="cantidad" id="cantidad" class="form-control" min="1" max="{{ $producto->stock }}" required>
                </div>

                <div class="mb-3">
                    <label for="direccion" class="form-label">Dirección</label>
                    <input type="text" name="direccion" id="direccion" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="ciudad" class="form-label">Ciudad</label>
                    <input type="text" name="ciudad" id="ciudad" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="provincia" class="form-label">Provincia</label>
                    <input type="text" name="provincia" id="provincia" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="codigo_postal" class="form-label">Código Postal</label>
                    <input type="text" name="codigo_postal" id="codigo_postal" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary">Confirmar pedido</button>
                <a href="{{ route('productos.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    @else
        <p>No se ha seleccionado ningún producto.</p>
    @endif
</div>
@endsection
