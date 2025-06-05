@extends('layouts/app')
@section('titulo', "info empresa")

@section('content')


{{-- notificaciones --}}


@if (session('CORRECTO'))
<script>
    $(function notificacion(){
    new PNotify({
        title:"CORRECTO",
        type:"success",
        text:"{{session('CORRECTO')}}",
        styling:"bootstrap3"
    });		
});
</script>
@endif



@if (session('INCORRECTO'))
<script>
    $(function notificacion(){
    new PNotify({
        title:"INCORRECTO",
        type:"error",
        text:"{{session('INCORRECTO')}}",
        styling:"bootstrap3"
    });		
});
</script>
@endif

@if (session('AVISO'))
<script>
    $(function notificacion(){
    new PNotify({
        title:"AVISO",
        type:"error",
        text:"{{session('AVISO')}}",
        styling:"bootstrap3"
    });		
});
</script>
@endif


<h4 class="text-center text-secondary">CAMBIAR CONTRASEÑA</h4>

<div class="mb-0 col-12 bg-white p-4 pt-0">
    <form action="{{route('backup.update')}}" method="POST">
        @csrf
        <div class="row pt-3">
            <div class="fl-flex-label mb-5 p-2 col-12">
                <input type="password" name="claveActual" class="input input__text" placeholder="Contraseña actual"
                    value="">
                @error('claveActual')
                <small class="error error__text">{{$message}}</small>
                @enderror
            </div>
            <div class="fl-flex-label mb-4 p-2 col-12">
                <input type="password" name="claveNuevo" class="input input__text" placeholder="Nueva contraseña"
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