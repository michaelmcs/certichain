@extends('layouts/app')
@section('titulo', 'info empresa')

@section('content')

    <style>
        img.logo {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            box-shadow: 0px 0px 20px rgb(226, 226, 226);
            margin-top: -20px;
            margin-bottom: 40px;
            object-fit: cover;
        }

        .logo {
            font-size: 130px;
            color: rgb(228, 228, 228);
        }

        .img {
            background: rgb(247, 251, 255);
        }
    </style>

    {{-- notificaciones --}}



    @if (session('CORRECTO'))
        <div class="alert alert-success"><i class="fas fa-check"></i> {{ session('CORRECTO') }}</div>
    @endif

    @if (session('INCORRECTO'))
        <div class="alert alert-error"><i class="fas fa-times"></i> {{ session('INCORRECTO') }}</div>
    @endif

    <h4 class="text-center text-secondary">MI PERFIL</h4>

    <div class="mb-0 col-12 bg-white p-5 pt-4">
        @foreach ($sql as $item)
            <div class="d-flex justify-content-around align-items-center flex-wrap gap-5 pt-5 pb-3 mb-3 img">
                <div class="text-center">
                    @if ($item->foto == null)
                        <p class="logo">
                            <i class="far fa-frown"></i>
                        </p>
                    @else
                        <img class="logo" src="{{ asset("foto/usuario/$item->foto") }}" alt="">
                    @endif
                </div>
                <div>
                    <h6 class="text-dark font-weight-bold">Modificar imagen</h6>
                    <form action="{{ route('perfil.updatePerfil') }}" method="POST" enctype="multipart/form-data"
                        id="miForm">
                        @csrf
                        <input type="hidden" name="id" value="{{ $item->id_usuario }}">
                        <div class="alert alert-secondary">Selecciona una imagen no muy <b>pesado</b> y en un formato
                            <b>válido</b> ...!
                        </div>
                        <div class="fl-flex-label mb-4 col-12">
                            <input required type="file" name="foto" class="input form-control-file input__text"
                                value="">
                        </div>
                    </form>
                    <div class="d-flex justify-content-end gap-4">
                        <div class="text-right mt-0">
                            <button form="miForm" type="submit" class="btn btn-rounded btn-success"><i
                                    class="fas fa-save"></i>&nbsp;&nbsp; Modificar perfil</button>
                        </div>
                        <div class="text-right mt-0">
                            <form action="{{ route('perfil.deletePerfil', $item->id_usuario) }}" method="get"
                                class="d-inline formulario-eliminar">
                            </form>
                            <a href="" data-id="{{ $item->id_usuario }}"
                                class="btn btn-rounded btn-danger eliminar"><i class="fas fa-trash"></i>&nbsp;&nbsp;
                                Eliminar
                                foto</a>
                        </div>
                    </div>
                </div>
            </div>



            <form action="{{ route('perfil.update') }}" method="POST">
                @csrf
                <div class="row">
                    <input type="hidden" name="id" value="{{ $item->id_usuario }}">
                    <div class="fl-flex-label mb-4 col-12 col-lg-6">
                        <input type="number" name="dni" class="input input__text" id="dni" placeholder="Dni"
                            value="{{ $item->dni }}">
                        @error('dni')
                            <small class="error error__text">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="fl-flex-label mb-4 col-12 col-lg-6">
                        <input type="text" name="nombre" class="input input__text" id="nombre" placeholder="Nombre"
                            value="{{ $item->nombres }}">
                        @error('nombre')
                            <small class="error error__text">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="fl-flex-label mb-4 col-12 col-lg-6">
                        <input type="text" name="usuario" class="input input__text" placeholder="Usuario *"
                            value="{{ old('usuario', $item->usuario) }}">
                        @error('usuario')
                            <small class="error error__text">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="fl-flex-label mb-4 col-12 col-lg-6">
                        <input type="text" name="telefono" class="input input__text" placeholder="Telefono"
                            value="{{ $item->telefono }}">
                    </div>

                    <div class="fl-flex-label mb-4 col-12 col-lg-6">
                        <input type="email" name="correo" class="input input__text" placeholder="Correo *"
                            value="{{ old('correo', $item->correo) }}">
                        @error('correo')
                            <small class="error error__text">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="text-right mt-0">
                        <button type="submit" class="btn btn-rounded btn-primary"><i class="fas fa-save"></i>
                            Guardar</button>
                    </div>
                </div>

            </form>
        @endforeach
    </div>




@endsection
