@extends('layouts/app')
@section('titulo', "info empresa")

@section('content')


{{-- notificaciones --}}



<h4 class="text-center text-secondary">CAMBIAR CONTRASEÑA</h4>


@if (session('CORRECTO'))
<div class="alert alert-success"><i class="fas fa-check"></i> {{ session('CORRECTO') }}</div>
@endif

@if (session('INCORRECTO'))
<div class="alert alert-error"><i class="fas fa-times"></i> {{ session('INCORRECTO') }}</div>
@endif

<div class="mb-0 col-12 bg-white p-4 pt-0">
    <form action="{{route("password.update")}}" method="POST">
        @csrf
        <div class="row pt-3">
            <div class="fl-flex-label mb-5 p-2 col-12">
                <input required type="password" name="claveActual" class="input input__text" placeholder="Contraseña actual"
                    value="">
                @error('claveActual')
                <small class="error error__text">{{$message}}</small>
                @enderror
            </div>
            <div class="fl-flex-label mb-4 p-2 col-12">
                <input required type="password" name="claveNuevo" class="input input__text" placeholder="Nueva contraseña"
                    value="">
                @error('claveNuevo')
                <small class="error error__text">{{$message}}</small>
                @enderror
            </div>


            <div class="text-right mt-0">
                <button type="submit" class="btn btn-rounded btn-primary"><i class="fas fa-save"></i> Cambiar</button>
            </div>
        </div>
    </form>
</div>




@endsection