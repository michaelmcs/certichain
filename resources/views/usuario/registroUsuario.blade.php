@extends('layouts/app')
@section('titulo', 'registrar usuarios')

@section('content')


    {{-- notificaciones --}}




    <h4 class="text-center text-secondary">REGISTRO DE USUARIOS</h4>

    @if (session('CORRECTO'))
        <div class="alert alert-success"><i class="fas fa-check"></i> {{ session('CORRECTO') }}</div>
    @endif

    @if (session('INCORRECTO'))
        <div class="alert alert-error"><i class="fas fa-times"></i> {{ session('INCORRECTO') }}</div>
    @endif

    @if (session('DUPLICADO'))
        <div class="alert alert-error"><i class="fas fa-exclamation-triangle"></i> {{ session('DUPLICADO') }}</div>
    @endif


    <div class="mb-0 col-12 bg-white p-5">
        <form action="{{ route('usuario.store') }}" enctype="multipart/form-data" method="POST">
            @csrf
            <div class="row">
                <div class="fl-flex-label mb-4 col-12 col-lg-6">
                    <input type="text" name="usuario" class="input input__text" placeholder="Nombre de usuario *"
                        value="{{ old('usuario') }}">
                    @error('usuario')
                        <small class="error error__text">{{ $message }}</small>
                    @enderror
                </div>

                <div class="fl-flex-label mb-4 col-12 col-lg-6">
                    <input type="password" name="password" class="input input__text" placeholder="Contraseña *"
                        value="{{ old('password') }}">
                    @error('password')
                        <small class="error error__text">{{ $message }}</small>
                    @enderror
                </div>

                <div class="fl-flex-label mb-4 col-12 col-lg-6">
                    <input type="number" name="dni" class="input input__text" placeholder="DNI del usuario *"
                        value="{{ old('dni') }}">
                    @error('dni')
                        <small class="error error__text">{{ $message }}</small>
                    @enderror
                </div>

                <div class="fl-flex-label mb-4 col-12 col-lg-6">
                    <input type="text" name="nombre" class="input input__text" placeholder="Nombres y Apellidos *"
                        value="{{ old('nombre') }}">
                    @error('nombre')
                        <small class="error error__text">{{ $message }}</small>
                    @enderror
                </div>

                <div class="fl-flex-label mb-4 col-12 col-lg-6">
                    <input type="text" name="telefono" class="input input__text" placeholder="telefono del usuario"
                        value="{{ old('telefono') }}">
                    @error('telefono')
                        <small class="error error__text">{{ $message }}</small>
                    @enderror
                </div>

                <div class="fl-flex-label mb-4 col-12 col-lg-6">
                    <input type="email" name="correo" class="input input__text" placeholder="Correo del usuario *"
                        value="{{ old('correo') }}">
                    @error('correo')
                        <small class="error error__text">{{ $message }}</small>
                    @enderror
                </div>

                <div class="fl-flex-label mb-4 col-12 col-lg-6">
                    <input type="file" name="foto" class="input input__text" value="{{ old('foto') }}">
                    @error('foto')
                        <small class="error error__text">{{ $message }}</small>
                    @enderror
                </div>


                <div class="text-right mt-0">
                    <a href="{{ route('usuario.index') }}" class="btn btn-rounded btn-secondary m-2"><i class="fas fa-caret-left"></i> Atras</a>
                    <button type="submit" class="btn btn-rounded btn-primary"><i class="fas fa-save"></i> Guardar</button>
                </div>
            </div>
        </form>

    </div>






@endsection
