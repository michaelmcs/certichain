@extends('layouts/inicio')
@section('contenido')
    <div class="container">
        <div class="alert alert-success text-center">BÚSQUEDA DE CERTIFICADOS</div>
        <form action="{{ route('busqueda.buscar') }}" class="form" method="GET">
            @method('GET')
            <p class="title">Ingrese su DNI o código</p>
            <input id="dni" required type="text" name="dni" placeholder="Ingrese su DNI o código">
            <div class="group__button">
                <button id="entrada" class="entrada"><i class="fa-solid fa-magnifying-glass"></i>
                    Buscar Certificado</button>
            </div>
        </form>
    </div>
    <div class="p-4">
        @yield('resultado')
    </div>
@endsection
