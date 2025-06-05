@extends('layouts/app')
@section('titulo', 'registrar cursos')

@section('content')


    {{-- notificaciones --}}




    <h4 class="text-center text-secondary">REGISTRO DE CURSOS</h4>

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
        <form action="{{ route('curso.store') }}" enctype="multipart/form-data" method="POST">
            @csrf
            <div class="row">

                <div class="fl-flex-label mb-4 col-12 col-lg-12">
                    <input required type="text" name="nombre" class="input input__text" id="nombre"
                        placeholder="Nombre del curso" value="{{ old('nombre') }}">
                    @error('nombre')
                        <small class="error error__text">{{ $message }}</small>
                    @enderror
                </div>

                <div class="fl-flex-label mb-4 col-12 col-lg-12">
                    <input required type="text" name="hora" class="input input__text" id="hora"
                        placeholder="Horas del curso" value="{{ old('hora') }}">
                    @error('hora')
                        <small class="error error__text">{{ $message }}</small>
                    @enderror
                </div>

                <div class="fl-flex-label mb-4 col-12">
                    <textarea required class="input input__text" name="descripcion" cols="30" rows="3"
                        placeholder="Descripcion del curso">{{ old('descripcion') }}</textarea>
                    @error('descripcion')
                        <small class="error error__text">{{ $message }}</small>
                    @enderror
                </div>

                <div class="fl-flex-label mb-4 col-12 col-lg-6">
                    <label class="label2">Fecha de inicio del curso</label>
                    <input required type="date" name="inicio" class="input input__text" value="{{ old('inicio') }}">
                    @error('inicio')
                        <small class="error error__text">{{ $message }}</small>
                    @enderror
                </div>

                <div class="fl-flex-label mb-4 col-12 col-lg-6">
                    <label class="label2">Fecha de finalización del curso</label>
                    <input required type="date" name="termino" class="input input__text" value="{{ old('termino') }}">
                    @error('termino')
                        <small class="error error__text">{{ $message }}</small>
                    @enderror
                </div>

                <div class="fl-flex-label mb-4 col-12 col-lg-6">
                    <label>Seleccionar estado del curso *</label>
                    <select required name="estado" class="input input__select">
                        <option value="">Seleccionar... </option>
                        <option {{ old('estado') == 'proximamente' ? 'selected' : '' }} value="proximamente">Próximamente
                        </option>
                        <option {{ old('estado') == 'encurso' ? 'selected' : '' }} value="encurso">En curso</option>
                        <option {{ old('estado') == 'finalizado' ? 'selected' : '' }} value="finalizado">Finalizado
                        </option>
                    </select>
                    @error('estado')
                        <small class="error error__text">{{ $message }}</small>
                    @enderror
                </div>

                <div class="text-right mt-0">
                    <a href="{{ route('curso.index') }}" class="btn btn-rounded btn-secondary m-2"><i class="fas fa-caret-left"></i> Atras</a>
                    <button type="submit" class="btn btn-rounded btn-primary"><i class="fas fa-save"></i> Guardar</button>
                </div>
            </div>

        </form>
    </div>




@endsection
