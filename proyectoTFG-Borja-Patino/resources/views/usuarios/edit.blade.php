@extends('layouts.app')

@section('title', 'Editar usuario')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Editar usuario</h1>

    <form action="{{ route('usuarios.update', $usuario->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre', $usuario->nombre) }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $usuario->email) }}" required>
        </div>

        <div class="mb-3">
            <label for="rol" class="form-label">Rol</label>
            <select name="rol" id="rol" class="form-select" required>
                <option value="usuario" {{ old('rol', $usuario->rol) == 'usuario' ? 'selected' : '' }}>Usuario</option>
                <option value="admin" {{ old('rol', $usuario->rol) == 'admin' ? 'selected' : '' }}>Administrador</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="contraseña" class="form-label">Nueva contraseña (opcional)</label>
            <input type="password" name="contraseña" id="contraseña" class="form-control">
            <small class="text-muted">Déjalo vacío si no quieres cambiar la contraseña.</small>
        </div>

        <button type="submit" class="btn btn-primary">Guardar cambios</button>
        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
