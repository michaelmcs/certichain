@extends('layouts/app')
@section('titulo', 'lista de usuarios')

@section('content')

    {{-- notificaciones --}}



    <h4 class="text-center text-secondary">LISTA DE USUARIOS REGISTRADOS</h4>
    @if (session('CORRECTO'))
        <div class="alert alert-success"><i class="fas fa-check"></i> {{ session('CORRECTO') }}</div>
    @endif

    @if (session('INCORRECTO'))
        <div class="alert alert-error"><i class="fas fa-times"></i> {{ session('INCORRECTO') }}</div>
    @endif

    @if (session('DUPLICADO'))
        <div class="alert alert-error"><i class="fas fa-exclamation-triangle"></i> {{ session('DUPLICADO') }}</div>
    @endif

    @error('curso')
        <div class="alert alert-error"><i class="fas fa-times"></i> {{ $message }}</div>
    @enderror
    @error('dni')
        <div class="alert alert-error"><i class="fas fa-times"></i> {{ $message }}</div>
    @enderror
    @error('nombre')
        <div class="alert alert-error"><i class="fas fa-times"></i> {{ $message }}</div>
    @enderror
    @error('apellido')
        <div class="alert alert-error"><i class="fas fa-times"></i> {{ $message }}</div>
    @enderror
    @error('correo')
        <div class="alert alert-error"><i class="fas fa-times"></i> {{ $message }}</div>
    @enderror

    @error('foto')
        <div class="alert alert-error"><i class="fas fa-times"></i> {{ $message }}</div>
    @enderror

    <div class="pb-1 pt-2">
        <a href="{{ route('usuario.create') }}" class="btn btn-rounded btn-primary"><i class="fas fa-plus"></i>&nbsp;
            Registrar</a>
    </div>


    <section class="card">
        <div class="card-block">
            <table id="example" class="display table table-striped" cellspacing="0" width="100%">
                <thead class="table-primary">
                    <tr>
                        <th class="sorting_desc">ID</th>
                        <th>USUARIO</th>
                        <th>DNI</th>
                        <th>NOMBRES</th>
                        <th>TELEFONO</th>
                        <th>CORREO</th>
                        <th>FOTO</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($datos as $item)
                        <tr>
                            <td>{{ $item->id_usuario }}</td>
                            <td>{{ $item->usuario }}</td>
                            <td>{{ $item->dni }}</td>
                            <td>{{ $item->nombres }}</td>
                            <td>{{ $item->telefono }}</td>
                            <td>{{ $item->correo }}</td>
                            <td>
                                @if ($item->foto == '')
                                    <a href=""data-toggle="modal"
                                        data-target="#staticBackdropfoto{{ $item->id_usuario }}">
                                        Agregar foto
                                    </a>
                                @else
                                    <a href=""data-toggle="modal"
                                        data-target="#staticBackdropfoto{{ $item->id_usuario }}">
                                        <img style="width: 50px"
                                            src="{{asset("foto/usuario/$item->foto")}}" alt="">
                                    </a>
                                @endif
                            </td>
                            <td>

                                <a style="top: 0" href="" class="btn btn-sm btn-warning m-1" data-toggle="modal"
                                    data-target="#staticBackdrop{{ $item->id_usuario }}"><i class="fas fa-edit"></i></a>

                                <form action="{{ route('usuario.destroy', $item->id_usuario) }}" method="POST"
                                    class="d-inline formulario-eliminar">
                                    @csrf
                                    @method('delete')
                                    {{-- <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash-alt"></i>
                                    </button> --}}
                                </form>

                                <a href="#" class="btn btn-sm btn-danger eliminar" data-id="{{ $item->id_usuario }}">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>

                            {{-- modal de modificar --}}
                            <!-- Modal -->
                            <div class="modal fade" id="staticBackdrop{{ $item->id_usuario }}" data-backdrop="static"
                                data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div style="background: rgb(181, 211, 255)"
                                            class="px-4 py-3 w-100 d-flex justify-content-between">
                                            <h5 class="modal-title" id="staticBackdropLabel">Modificar Usuario</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="p-3">
                                            <div>
                                                <form action="{{ route('usuario.update', $item->id_usuario) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')

                                                    <div class="fl-flex-label mb-4 col-12 col-lg-12">
                                                        <input type="text" name="usuario" class="input input__text"
                                                            placeholder="Nombre de usuario *"
                                                            value="{{ old('usuario', $item->usuario) }}">
                                                        @error('usuario')
                                                            <small class="error error__text">{{ $message }}</small>
                                                        @enderror
                                                    </div>

                                                    <div class="fl-flex-label mb-4 col-12 col-lg-12">
                                                        <input type="number" name="dni" class="input input__text"
                                                            placeholder="DNI del usuario *"
                                                            value="{{ old('dni', $item->dni) }}">
                                                        @error('dni')
                                                            <small class="error error__text">{{ $message }}</small>
                                                        @enderror
                                                    </div>

                                                    <div class="fl-flex-label mb-4 col-12 col-lg-12">
                                                        <input type="text" name="nombre" class="input input__text"
                                                            placeholder="Nombres y Apellidos *"
                                                            value="{{ old('nombre', $item->nombres) }}">
                                                        @error('nombre')
                                                            <small class="error error__text">{{ $message }}</small>
                                                        @enderror
                                                    </div>

                                                    <div class="fl-flex-label mb-4 col-12 col-lg-12">
                                                        <input type="text" name="telefono" class="input input__text"
                                                            placeholder="telefono del usuario"
                                                            value="{{ old('telefono', $item->telefono) }}">
                                                        @error('telefono')
                                                            <small class="error error__text">{{ $message }}</small>
                                                        @enderror
                                                    </div>

                                                    <div class="fl-flex-label mb-4 col-12 col-lg-12">
                                                        <input type="email" name="correo" class="input input__text"
                                                            placeholder="Correo del usuario *"
                                                            value="{{ old('correo', $item->correo) }}">
                                                        @error('correo')
                                                            <small class="error error__text">{{ $message }}</small>
                                                        @enderror
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal"><i class="fas fa-times"></i> Cerrar</button>
                                                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Modificar</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- modal de modificar foto --}}
                            <!-- Modal -->
                            <div class="modal fade" id="staticBackdropfoto{{ $item->id_usuario }}"
                                data-backdrop="static" data-keyboard="false" tabindex="-1"
                                aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div style="background: rgb(181, 211, 255)"
                                            class="px-4 py-3 w-100 d-flex justify-content-between">
                                            <h5 class="modal-title" id="staticBackdropLabel">Foto de perfil del usuario
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="p-3">
                                            <div>
                                                <div class="text-center">
                                                    <img style="width: 150px"
                                                        src="{{asset("foto/usuario/$item->foto")}}"
                                                        alt="">
                                                </div>
                                                <form action="{{ route('modificarFoto.update', $item->id_usuario) }}"
                                                    enctype="multipart/form-data" method="POST">
                                                    @csrf
                                                    <div class="fl-flex-label mb-4 col-12 col-lg-12">
                                                        <input required type="file" name="foto" class="input input__text"
                                                            value="{{ old('foto') }}">
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal"><i class="fas fa-times"></i> Cerrar</button>
                                                        <a href="{{ route('eliminarFoto.delete', $item->id_usuario) }}"
                                                            class="btn btn-danger"><i class="fas fa-trash-alt"></i> Eliminar imagen</a>
                                                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Modificar</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>


@endsection
