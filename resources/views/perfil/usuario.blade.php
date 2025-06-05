@extends('layouts/app')
@section('titulo', "info empresa")

@section('content')

<style>
    img.logo{
        width: 130px;
        height: 130px;
        border-radius: 50%;        
        box-shadow: 0px 0px 20px rgb(226, 226, 226);
        margin-top: -20px;
        margin-bottom: 40px;    
        object-fit: cover;   
    }
    .logo{
        font-size: 130px;
        color: rgb(228, 228, 228);
    }
    .img{
        background: rgb(247, 251, 255);
    }
</style>

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


<h4 class="text-center text-secondary">MI PERFIL</h4>

<div class="mb-0 col-12 bg-white p-5 pt-0">
    @foreach ($sql as $item)

    
        <div class="d-flex justify-content-around align-items-center flex-wrap gap-5 pt-5 pb-3 mb-3 img">
            <div class="text-center">
                @if ($item->foto==null)
                    <p class="logo">
                        <i class="far fa-frown"></i>
                    </p>
                @else                    
                <img class="logo" src="{{asset("foto/usuario/$item->foto")}}" alt="">
                @endif
            </div>
            <div>
                <h6 class="text-dark font-weight-bold">Modificar imagen</h6>
                <form action="{{route('profile.updateImg')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{$item->id_usuario}}">
                    <div class="alert alert-secondary">Selecciona una imagen no muy <b>pesado</b> y en un formato <b>válido</b> ...!</div>
                    <div class="fl-flex-label mb-4 col-12">
                        <input type="file" name="foto" class="input form-control-file input__text" value="">
                    </div>
                    <div class="d-flex justify-content-end gap-4">
                        <div class="text-right mt-0">
                            <button type="submit" class="btn btn-rounded btn-success"><i class="fas fa-save"></i>&nbsp;&nbsp; Modificar perfil</button>
                        </div>
                        <div class="text-right mt-0">
                            <a href="{{route('profile.destroy',$item->id_usuario)}}" class="btn btn-rounded btn-danger"><i class="fas fa-trash"></i>&nbsp;&nbsp; Eliminar foto</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    


        <form action="{{route('profile.update',$item->id_usuario)}}" method="POST">
            @csrf
            <div class="row">
                <input type="hidden" name="id" value="{{$item->id_usuario}}">
                <div class="fl-flex-label mb-4 col-12 col-lg-6">
                    <input type="number" name="dni" class="input input__text" id="dni" placeholder="Dni"
                        value="{{$item->dni}}">
                </div>
                <div class="fl-flex-label mb-4 col-12 col-lg-6">
                    <input type="text" name="nombre" class="input input__text" id="nombre" placeholder="Nombre"
                        value="{{$item->nombre}}">
                </div>
                <div class="fl-flex-label mb-4 col-12 col-lg-6">
                    <input type="text" name="apellido" class="input input__text" id="apellido" placeholder="Apellido"
                        value="{{$item->apellido}}">
                </div>
                <div class="fl-flex-label mb-4 col-12 col-lg-6">
                    <input type="text" name="usuario" class="input input__text" placeholder="Usuario *"
                        value="{{old('usuario',$item->usuario)}}">
                    @error('usuario')
                    <small class="error error__text">{{$message}}</small>
                    @enderror
                </div>
    
                <div class="fl-flex-label mb-4 col-12 col-lg-6">
                    <input type="text" name="telefono" class="input input__text" placeholder="Telefono"
                        value="{{$item->telefono}}">
                </div>
                <div class="fl-flex-label mb-4 col-12 col-lg-6">
                    <input type="text" name="direccion" class="input input__text" placeholder="Dirección"
                        value="{{$item->direccion}}">
                </div>
                <div class="fl-flex-label mb-4 col-12 col-lg-6">
                    <input type="email" name="correo" class="input input__text" placeholder="Correo *"
                        value="{{old('correo',$item->correo)}}">
                    @error('correo')
                    <small class="error error__text">{{$message}}</small>
                    @enderror
                </div>
    
                <div class="text-right mt-0">
                    <button type="submit" class="btn btn-rounded btn-primary"><i class="fas fa-save"></i> Guardar</button>
                </div>
            </div>
            
        </form>


    @endforeach
</div>




@endsection