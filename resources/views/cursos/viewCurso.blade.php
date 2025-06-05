@extends('layouts/app')
@section('titulo', 'lista de cursos')

@section('content')

    {{-- notificaciones --}}
    <script>
        function confirmarEliminar() {
            let res = confirm(
                "POR TU SEGURIDAD: Estas seguro de eliminar...? Ten en cuenta que tambien se borrarán los registros asociados a este(participantes,modelo de certificado,etc)"
            )
            return res;
        }
    </script>


    <h4 class="text-center text-secondary">DATOS DEL CURSO REGISTRADO</h4>




    @foreach ($sql as $item)
        <div class="container bg-light p-3">
            <div>
                @if (session('CORRECTO'))
                    <div class="alert alert-success"><i class="fas fa-check"></i> {{ session('CORRECTO') }}</div>
                @endif

                @if (session('INCORRECTO'))
                    <div class="alert alert-error"><i class="fas fa-times"></i> {{ session('INCORRECTO') }}</div>
                @endif

                @if (session('DUPLICADO'))
                    <div class="alert alert-error"><i class="fas fa-exclamation-triangle"></i> {{ session('DUPLICADO') }}
                    </div>
                @endif
            </div>
            <form action="{{ route('curso.update', $item->id_curso) }}" enctype="multipart/form-data" method="POST"
                id="actualizar">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="fl-flex-label mb-4 col-12">
                        <input required type="text" name="nombre" class="input input__text" id="nombre"
                            placeholder="Nombre del curso *" value="{{ old('nombre', $item->nombre) }}">
                        @error('nombre')
                            <small class="error error__text">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="fl-flex-label mb-4 col-12">
                        <input required type="text" name="hora" class="input input__text" id="hora"
                            placeholder="Horas del curso" value="{{ old('hora', $item->horas) }}">
                        @error('hora')
                            <small class="error error__text">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="fl-flex-label mb-4 col-12">
                        <textarea required class="input input__text" name="descripcion" cols="30" rows="3"
                            placeholder="Descripcion del producto">{{ old('descripcion', $item->descripcion) }}</textarea>
                        @error('descripcion')
                            <small class="error error__text">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="fl-flex-label mb-4 col-12">
                        <label class="label2">Fecha de inicio del curso</label>
                        <input required type="date" name="inicio" class="input input__text"
                            value="{{ old('inicio', $item->inicio) }}">
                        @error('inicio')
                            <small class="error error__text">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="fl-flex-label mb-4 col-12">
                        <label class="label2">Fecha de finalización del curso</label>
                        <input required type="date" name="termino" class="input input__text"
                            value="{{ old('termino', $item->termino) }}">
                        @error('termino')
                            <small class="error error__text">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="fl-flex-label mb-4 col-12 col-lg-12">
                        <label>Seleccionar estado del curso</label>
                        <select required name="estado" class="input input__select">
                            <option {{ $item->estado == 'proximamente' ? 'selected' : '' }} value="proximamente">
                                Próximamente
                            </option>
                            <option {{ $item->estado == 'encurso' ? 'selected' : '' }} value="encurso">En curso</option>
                            <option {{ $item->estado == 'finalizado' ? 'selected' : '' }} value="finalizado">Finalizado
                            </option>
                        </select>
                        @error('estado')
                            <small class="error error__text">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

            </form>
            <div class="modal-footer">
                <a href="{{ route('curso.index') }}" class="btn btn-secondary"><i class="fas fa-caret-left"></i> Atras</a>
                <form onsubmit="return confirmarEliminar()" action="{{ route('curso.destroy', $item->id_curso) }}"
                    method="POST" id="eliminar" class="d-inline formulario-eliminar">
                    @csrf
                    @method('delete')
                    {{-- <button type="submit" class="btn btn-sm btn-danger">
                        <i class="fas fa-trash-alt"></i>
                    </button> --}}
                </form>

                <a href="#" class="btn btn-danger eliminar" data-id="{{ $item->id_curso }}"><i class="fas fa-trash-alt"></i> Eliminar</a>
                <button form="actualizar" type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Modificar</button>
            </div>
        </div>
    @endforeach






@endsection
