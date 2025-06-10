@extends('layouts.app')

@section('title', 'Detalle del pedido')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Detalle del pedido #{{ $pedido->id }}</h1>

    <div class="card mb-4">
        <div class="card-body">
        {{-- 🖥 Detalles del producto --}}
            <h4 class="mb-3">🖥 Detalles del producto</h4>
            <div class="row g-3 align-items-center">
                {{-- Imagen a la izquierda --}}
                <div class="col-md-4 text-center mb-3 mb-md-0 d-flex align-items-center justify-content-center">
                    @if($pedido->producto->imagen)
                        <div class="w-100" style="max-height: 300px;">
                            <img src="{{ asset($pedido->producto->imagen) }}" alt="Imagen del producto"
                                class="img-fluid rounded shadow-sm w-100"
                                style="max-height: 300px; object-fit: contain;">
                        </div>
                    @else
                        <p>No hay imagen disponible.</p>
                    @endif
                </div>

                {{-- Detalles a la derecha --}}
                <div class="col-md-8">
                    <p><strong>Nombre:</strong> {{ $pedido->producto->nombre }}</p>
                    <p><strong>Descripción:</strong> {{ $pedido->producto->descripcion }}</p>
                    <p><strong>Categoría:</strong> {{ $pedido->producto->categoria->nombre ?? 'Sin categoría' }}</p>
                    <p><strong>Precio unitario:</strong> {{ $pedido->producto->precio }} €</p>
                    <p><strong>Cantidad pedida:</strong> {{ $pedido->cantidad }}</p>
                    <p><strong>Total del pedido:</strong> {{ $pedido->total }} €</p>
                </div>
            </div>
            
            <hr>

            {{-- 🚚 Detalles de envío --}}
            <h4 class="mb-3 mt-4">🚚 Detalles de envío</h4>
            <p><strong>Dirección:</strong> {{ $pedido->direccion }}</p>
            <p><strong>Ciudad:</strong> {{ $pedido->ciudad }}</p>
            <p><strong>Provincia:</strong> {{ $pedido->provincia }}</p>
            <p><strong>Código Postal:</strong> {{ $pedido->codigo_postal }}</p>
            <p><strong>Fecha del pedido:</strong> {{ $pedido->created_at->format('d/m/Y H:i') }}</p>

            {{-- 👤 Detalles del usuario --}}
            
            <hr>
            <h4 class="mb-3 mt-4">👤 Detalles del usuario</h4>
            <p><strong>Nombre:</strong> {{ $pedido->usuario->nombre }}</p>
            <p><strong>Email:</strong> {{ $pedido->usuario->email }}</p>
                
        </div>
    </div>

    <a href="{{ route('pedidos.index') }}" class="btn btn-secondary">← Volver al listado</a>
</div>
@endsection
