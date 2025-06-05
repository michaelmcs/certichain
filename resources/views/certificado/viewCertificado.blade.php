@extends('layouts/app')
@section('titulo', 'lista de Certificado')

@section('content')

    {{-- notificaciones --}}



    <h4 class="text-center text-secondary">VISTA PREVIA DEL MODELO DE CERTIFICADO</h4>
    @if (session('CORRECTO'))
        <div class="alert alert-success"><i class="fas fa-check"></i> {{ session('CORRECTO') }}</div>
    @endif

    @if (session('INCORRECTO'))
        <div class="alert alert-error"><i class="fas fa-times"></i> {{ session('INCORRECTO') }}</div>
    @endif

    @if (session('DUPLICADO'))
        <div class="alert alert-error"><i class="fas fa-exclamation-triangle"></i> {{ session('DUPLICADO') }}</div>
    @endif



    @error('modelo')
        <div class="alert alert-error"><i class="fas fa-times"></i> {{ $message }}</div>
    @enderror

    @error('curso')
        <div class="alert alert-error"><i class="fas fa-times"></i> {{ $message }}</div>
    @enderror


    @foreach ($sql as $item)
        <div class="alert alert-info mb-0"><b>Curso: </b>{{ $item->nombre }}</div>
        <div class="col-12">
            <div class="text-center">
                @if ($item->modelo != '')
                    <img class="img-fluid mt-2 m-auto mb-2" style="width: 300px"
                        src="{{ asset("modelo_certificados/$item->modelo") }}" alt="">
                @else
                    <div class="alert alert-secondary">No existe modelo de certificado</div>
                @endif
                <form
                    action="{{ $item->id_certificado == '' ? route('certificado.store') : route('certificado.add', $item->id_certificado) }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    @if ($item->id_certificado != '')
                        @method('PUT')
                    @endif
                    <div class="row col-12">
                        <input type="hidden" value="{{ $item->modelo }}" name="txtid">
                        <input type="hidden" name="curso" value="{{ $item->id_curso }}" name="txtid">
                        <div class="fl-flex-label mb-4 col-12 col-lg-12">
                            <label class="text-left">Seleccionar modelo</label>
                            <input required type="file" name="modelo" class="input input__text"
                                value="{{ old('modelo') }}">
                        </div>

                    </div>

                    <div class="modal-footer">
                        <a href="{{ route('certificado.index') }}" class="btn btn-secondary"><i
                                class="fas fa-caret-left"></i> Atras</a>


                        @if ($item->modelo != '')
                            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Modificar</button>
                            <a href="{{ route('certificado.show', $item->id_certificado) }}" class="btn btn-primary"><i
                                    class="fas fa-wrench"></i> Configurar</a>
                        @else
                            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Agregar</button>
                        @endif
                    </div>
                </form>

                @if ($item->modelo != '')
                    <div class="text-right px-3">
                        <form action="{{ route('certificado.eliminarModelo', $item->id_certificado) }}" method="get"
                            class="d-inline formulario-eliminar">
                        </form>
                        <a href="#" class="btn btn-danger eliminar text-right" data-id="{{ $item->id_certificado }}">
                            <i class="fas fa-trash-alt"></i> Eliminar este modelo
                        </a>
                    </div>
                @endif
            </div>

        </div>
    @endforeach




@endsection
