@extends('layouts/app')
@section('titulo', 'lista de temarios')

@section('content')

    {{-- notificaciones --}}



    <h4 class="text-center text-secondary">LISTA DE TEMARIOS</h4>
    @if (session('CORRECTO'))
        <div class="alert alert-success"><i class="fas fa-check"></i> {{ session('CORRECTO') }}</div>
    @endif

    @if (session('INCORRECTO'))
        <div class="alert alert-error"><i class="fas fa-times"></i> {{ session('INCORRECTO') }}</div>
    @endif

    @if (session('DUPLICADO'))
        <div class="alert alert-error"><i class="fas fa-exclamation-triangle"></i> {{ session('DUPLICADO') }}</div>
    @endif

    {{-- mensaje de error de validacion de inputs --}}

    @error('curso')
        <div class="alert alert-error"><i class="fas fa-times"></i> {{ $message }}</div>
    @enderror
    @error('tema')
        <div class="alert alert-error"><i class="fas fa-times"></i> {{ $message }}</div>
    @enderror

    <div class="pb-1 pt-2">
        <a href="{{ route('temario.create') }}" class="btn btn-rounded btn-primary"><i class="fas fa-plus"></i>&nbsp;
            Registrar</a>

        <a href="{{ route('exportTemario.index') }}" class="btn btn-rounded btn-success"><i
                class="fas fa-file-excel"></i>&nbsp;
            Descargar Excel</a>
    </div>


    <section class="card">
        <div class="card-block">
            <table id="example" class="display table table-striped" cellspacing="0" width="100%">
                <thead class="table-primary">
                    <tr>
                        <th class="sorting_desc">ID</th>
                        <th>CURSO</th>
                        <th>TEMA</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($datos as $item)
                        <tr>
                            <td>{{ $item->id_temario }}</td>
                            <td>{{ $item->nombre }}</td>
                            <td>{{ $item->tema }}</td>
                            <td>

                                <a style="top: 0" href="" class="btn btn-sm btn-warning m-1" data-toggle="modal"
                                    data-target="#staticBackdrop{{ $item->id_temario }}"><i class="fas fa-edit"></i></a>

                                <form action="{{ route('temario.destroy', $item->id_temario) }}" method="POST"
                                    class="d-inline formulario-eliminar">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>

                            {{-- modal de modificar --}}
                            <!-- Modal -->
                            <div class="modal fade" id="staticBackdrop{{ $item->id_temario }}" data-backdrop="static"
                                data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div style="background: rgb(181, 211, 255)"
                                            class="px-4 py-3 w-100 d-flex justify-content-between">
                                            <h5 class="modal-title" id="staticBackdropLabel">Modificar Curso</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="p-3">
                                            <div>
                                                <form action="{{ route('temario.update', $item->id_temario) }}"
                                                    enctype="multipart/form-data" method="POST">
                                                    @csrf
                                                    @method('PUT')

                                                    <div class="fl-flex-label mb-5 col-12 col-lg-12">
                                                        <select name="curso" class="input input__text">
                                                            <option value="">Seleccionar curso...</option>
                                                            @foreach ($curso as $item2)
                                                                <option value="{{ $item2->id_curso }}"
                                                                    {{ $item->id_curso == $item2->id_curso ? 'selected' : '' }}>
                                                                    {{ $item2->nombre }}</option>
                                                            @endforeach
                                                        </select>

                                                    </div>

                                                    <div class="fl-flex-label mb-5 col-12 col-lg-12">
                                                        <input type="text" name="tema" class="input input__text"
                                                            id="tema" placeholder="Tema a tratar *"
                                                            value="{{ $item->tema }}">

                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Cerrar</button>
                                                        <button type="submit" class="btn btn-primary">Modificar</button>
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
