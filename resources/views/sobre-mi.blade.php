@extends('layouts.app')

@section('title', 'Sobre mí')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Sobre mí</h1>
    <div class="row">
        <div class="col-md-4">
            <div class="card mb-3 text-center">
                <div class="card-body">
                    <img src="{{ asset('imagenes/borja.jpg') }}" alt="Foto de Borja" class="img-thumbnail mb-3" style="max-width: 100%;">
                    <h4 class="card-title mb-0">Borja Patiño del Valle</h4>
                    <hr>
                    <p class="mb-0">C/ San Bartolomé 11</p>
                    <p class="mb-0">Quintanar de la Orden, 45800 – Toledo</p>
                    <hr>
                    <p class="mb-0"><strong>Teléfono:</strong> 654 471 586</p>
                    <p><strong>Email:</strong> borjapatinodelvalle@gmail.com</p>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Carta de presentación</h5>
                    <p class="card-text">
                        Soy una persona comprometida con el aprendizaje constante en el ámbito de la informática.
                        A lo largo de mi formación he adquirido conocimientos sólidos en sistemas, redes, ciberseguridad y desarrollo web,
                        lo que me ha permitido tener una visión completa del sector tecnológico. Me considero responsable, trabajador y con
                        una gran capacidad para adaptarme y trabajar en equipo. Mi objetivo es seguir creciendo profesionalmente y aportar
                        valor en cualquier proyecto en el que participe.
                    </p>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Perfil</h5>
                    <p class="card-text">Responsable, serio, trabajador, disciplinado.</p>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Formación Académica</h5>
                    <ul class="mb-0">
                        <li>ESO</li>
                        <li>Sistemas Microinformáticos y Redes (CFGM)</li>
                        <li>Administración de Sistemas Informáticos en Red (CFGS)</li>
                        <li>Ciberseguridad en entornos de las tecnologías de la información (CEGS)</li>
                        <li>Desarrollo de Aplicaciones Web (CFGS)</li>
                        <li>Inglés básico</li>
                    </ul>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Competencias</h5>
                    <ul class="mb-0">
                        <li>Adaptabilidad</li>
                        <li>Flexibilidad horaria</li>
                        <li>Trabajo en equipo</li>
                        <li>Disponibilidad geográfica</li>
                    </ul>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Datos de interés</h5>
                    <ul class="mb-0">
                        <li>Coche propio</li>
                        <li>Disponibilidad para viajar</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
