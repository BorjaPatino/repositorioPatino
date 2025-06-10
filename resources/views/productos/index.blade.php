@extends('layouts.app')

@section('title', 'Lista de productos')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Productos</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    

    @auth
        @if(auth()->user()->rol === 'admin')
            <a href="{{ route('productos.create') }}" class="btn btn-primary mb-3"> Nuevo producto</a>
        @endif
    @endauth

    {{-- Filtro por categoría --}}
    <form id="filtro-form" class="row g-3 align-items-end mb-4">
        <div class="col-md-4">
            <label for="categoria_id" class="form-label">Filtrar por categoría</label>
            <select name="categoria_id" id="categoria_id" class="form-select">
                <option value="">-- Todas --</option>
                @foreach($categorias as $categoria)
                    <option value="{{ $categoria->id }}" {{ $categoriaSeleccionada == $categoria->id ? 'selected' : '' }}>
                        {{ $categoria->nombre }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">Filtrar</button>
            <button type="button" id="reset-btn" class="btn btn-secondary">Reset</button>
        </div>

    </form>

    {{-- Vista en tarjetas estilo tienda --}}
    @if($productos->count() > 0)
    <div id="contenedor-productos">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
            @foreach($productos as $producto)
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        @if($producto->imagen)
                            <a href="{{ route('productos.show', $producto->id) }}">
                                <img src="{{ asset($producto->imagen) }}" class="card-img-top" alt="{{ $producto->nombre }}" style="object-fit: cover; height: 300px;">
                            </a>
                        @endif
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">
                                <a href="{{ route('productos.show', $producto->id) }}" class="text-decoration-none text-dark">
                                    {{ $producto->nombre }}
                                </a>
                            </h5>
                            <p class="card-text fw-bold mb-2">{{ $producto->precio }} €</p>
                            <p class="card-text text-muted">{{ $producto->categoria->nombre ?? 'Sin categoría' }}</p>

                            <div class="mt-auto">
                                <a href="{{ route('productos.show', $producto->id) }}" class="btn btn-info btn-sm w-100 mb-1">Ver</a>
                                <a href="{{ route('pedidos.create', ['producto_id' => $producto->id]) }}" class="btn btn-success btn-sm w-100 mb-1">Realizar pedido</a>

                                @auth
                                    @if(auth()->user()->rol === 'admin')
                                        <a href="{{ route('productos.edit', $producto->id) }}" class="btn btn-warning btn-sm w-100 mb-1">Editar</a>

                                        <form action="{{ route('productos.destroy', $producto->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm w-100">Eliminar</button>
                                        </form>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @else
        <p>No hay productos registrados aún.</p>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('filtro-form');
    const contenedor = document.getElementById('contenedor-productos');
    const resetBtn = document.getElementById('reset-btn');

    function renderizarProductos(productos) {
        let html = '';
        
        if (productos.length === 0) {
            html = '<p>No hay productos registrados en esta categoría.</p>';
        } else {
            html += '<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">';
            
            productos.forEach(producto => {
                html += `
                    <div class="col">
                        <div class="card h-100 shadow-sm">
                            ${producto.imagen ? 
                                `<a href="/productos/${producto.id}">
                                    <img src="/${producto.imagen}" class="card-img-top" alt="${producto.nombre}" style="object-fit: cover; height: 300px;">
                                </a>` 
                                : ''
                            }
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">
                                    <a href="/productos/${producto.id}" class="text-decoration-none text-dark">
                                        ${producto.nombre}
                                    </a>
                                </h5>
                                <p class="card-text fw-bold mb-2">${producto.precio} €</p>
                                <p class="card-text text-muted">${producto.categoria?.nombre ?? 'Sin categoría'}</p>
                                <div class="mt-auto">
                                    <a href="/productos/${producto.id}" class="btn btn-info btn-sm w-100 mb-1">Ver</a>
                                    <a href="/pedidos/create?producto_id=${producto.id}" class="btn btn-success btn-sm w-100 mb-1">Realizar pedido</a>
                                    @auth
                                        @if(Auth::user()->rol === 'admin')
                                            <a href="/productos/${producto.id}/edit" class="btn btn-warning btn-sm w-100 mb-1">Editar</a>
                                            <form action="/productos/${producto.id}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este producto?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm w-100">Eliminar</button>
                                            </form>
                                        @endif
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>`;
            });
            
            html += '</div>';
        }
        
        contenedor.innerHTML = html;
    }

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        const categoriaId = document.getElementById('categoria_id').value;
        
        fetch(`/productos-filtrados?categoria_id=${categoriaId}`)
            .then(res => res.json())
            .then(productos => renderizarProductos(productos))
            .catch(err => {
                console.error(err);
                contenedor.innerHTML = '<p>Error al filtrar productos.</p>';
            });
    });

    resetBtn.addEventListener('click', function () {
        document.getElementById('categoria_id').value = '';
        
        fetch(`/productos-filtrados`)
            .then(res => res.json())
            .then(productos => renderizarProductos(productos))
            .catch(err => {
                console.error(err);
                contenedor.innerHTML = '<p>Error al cargar productos.</p>';
            });
    });

    document.addEventListener('submit', function(e) {
        if (e.target && e.target.matches('form[action*="/productos/"]')) {
            e.preventDefault();
            
            const form = e.target;
            
            if (!confirm('¿Estás seguro de eliminar este producto?')) return;
            
            const csrfToken = form.querySelector('input[name="_token"]').value;
            
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: new URLSearchParams({
                    '_method': 'DELETE',
                    '_token': csrfToken
                })
            })
            .then(response => {
                if (!response.ok) throw new Error('Error en la respuesta');
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    const card = form.closest('.col');
                    card.remove();

                    const contenedor = document.getElementById('contenedor-productos');
                    if (!contenedor.querySelector('.col')) {
                        contenedor.innerHTML = '<p>No hay productos registrados.</p>';
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al eliminar el producto');
            });
        }
    });
});


</script>

@endsection
