@extends('layouts.app')

@section('title', 'Mis pedidos')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">
        @if(Auth::user()->rol === 'admin')
            Todos los pedidos
        @else
            Mis pedidos
        @endif
    </h1>

    <div id="alertas"></div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($pedidos->count() > 0)
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    @if(Auth::user()->rol === 'admin')
                        <th>Usuario</th>
                    @endif
                    <th>Producto</th>
                    <th>Precio (€)</th>
                    <th>Cantidad</th>
                    <th>Total (€)</th>
                    <th>Dirección</th>
                    <th>Ciudad</th>
                    <th>Provincia</th>
                    <th>Codigo postal</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pedidos as $pedido)
                    <tr>
                        @if(Auth::user()->rol === 'admin')
                            <td>{{ $pedido->usuario->nombre }}</td>
                        @endif
                        <td>{{ $pedido->producto->nombre ?? 'Producto eliminado' }}</td>
                        <td>{{ $pedido->producto->precio ?? '0' }} €</td>
                        <td>{{ $pedido->cantidad }}</td>
                        <td>{{ $pedido->total }} €</td>
                        <td>{{ $pedido->direccion }}</td>
                        <td>{{ $pedido->ciudad }}</td>
                        <td>{{ $pedido->provincia }}</td>
                        <td>{{ $pedido->codigo_postal }}</td>
                        <td>{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            @if(Auth::user()->rol === 'admin' || $pedido->usuario_id === Auth::id())
                                <a href="{{ route('pedidos.show', $pedido->id) }}" class="btn btn-info btn-sm">Ver</a>
                                <form action="{{ route('pedidos.destroy', $pedido->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar pedido?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                </form>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No hay pedidos registrados.</p>
    @endif
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    document.addEventListener('submit', function(e) {
        if (e.target && e.target.matches('form[action*="/pedidos/"]')) {
            e.preventDefault();
            const form = e.target;
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
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    const row = form.closest('tr');
                    if (row) row.remove();

                    const alertas = document.getElementById('alertas');
                    const alerta = document.createElement('div');
                    alerta.className = 'alert alert-success';
                    alerta.textContent = 'Pedido eliminado correctamente.';
                    alertas.innerHTML = '';
                    alertas.appendChild(alerta);

                    const filas = document.querySelectorAll('table tbody tr');
                    if (filas.length === 0) {
                        const tabla = document.querySelector('table');
                        if (tabla) tabla.remove();
                        
                        const vacio = document.createElement('p');
                        vacio.textContent = 'No hay pedidos registrados.';
                        document.querySelector('.container').appendChild(vacio);
                    }
                } else {
                    alert('Error al eliminar el pedido: ' + (data.message || 'Mensaje desconocido'));
                }
            })
            .catch(error => {
                console.error('Error en la petición AJAX:', error);
                alert('Ocurrió un error al intentar eliminar el pedido.');
            });
        }
    });
});

</script>
@endsection
