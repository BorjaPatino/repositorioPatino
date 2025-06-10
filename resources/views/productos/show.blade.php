@extends('layouts.app')

@section('title', 'Detalle del producto')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Detalle del producto</h1>

    <div class="card p-4">
        <div class="row">
            {{-- Imagen del producto --}}
            <div class="col-md-6 text-center mb-4 mb-md-0 d-flex align-items-center justify-content-center">
                @if($producto->imagen)
                    <div class="w-100" style="max-height: 500px;">
                        <img src="{{ asset($producto->imagen) }}" alt="{{ $producto->nombre }}" 
                             class="img-fluid rounded shadow-sm w-100"
                             style="max-height: 500px; object-fit: contain;">
                    </div>
                @else
                    <p>No hay imagen disponible.</p>
                @endif
            </div>

            {{-- Detalles del producto --}}
            <div class="col-md-6">
                <h3 class="card-title">{{ $producto->nombre }}</h3>
                <p class="card-text"><strong>Descripción:</strong> {{ $producto->descripcion }}</p>
                <p class="card-text"><strong>Precio:</strong> {{ number_format($producto->precio, 2) }} €</p>
                <p class="card-text"><strong>Stock:</strong> {{ $producto->stock }}</p>
                <p class="card-text"><strong>Categoría:</strong> {{ $producto->categoria->nombre ?? 'Sin categoría' }}</p>
                <p class="card-text"><strong>Fecha de creación:</strong> {{ $producto->created_at->format('d/m/Y H:i') }}</p>

                {{-- Botón Realizar pedido --}}
                <a href="{{ route('pedidos.create', ['producto_id' => $producto->id]) }}" class="btn btn-primary mt-2 w-100">Realizar pedido</a>
            
                <a href="{{ route('productos.index') }}" class="btn btn-dark mt-3 w-100"> Volver a la lista</a>
                
                </div>
        </div>
    </div>
</div>
@endsection
