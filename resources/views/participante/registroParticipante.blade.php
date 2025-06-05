@extends('layouts/app')
@section('titulo', 'registrar participantes')

@section('content')


    {{-- notificaciones --}}




    <h4 class="text-center text-secondary">REGISTRO DE PARTICIPANTES</h4>

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
        <form action="{{ route('participante.store') }}" enctype="multipart/form-data" method="POST">
            @csrf
            <div class="row">


                <div class="fl-flex-label mb-4 col-12 col-lg-12">
                    <label>Seleccionar curso *</label>
                    <select required name="curso" class="input input__text">
                        <option value="">Seleccionar curso...</option>
                        @foreach ($curso as $item)
                            <option value="{{ $item->id_curso }}" {{ old('curso') == $item->id_curso ? 'selected' : '' }}>
                                {{ $item->nombre }}</option>
                        @endforeach
                    </select>
                    @error('curso')
                        <small class="error error__text">{{ $message }}</small>
                    @enderror
                </div>

                <div class="fl-flex-label mb-4 col-12 col-lg-6">
                    <input required type="number" name="dni" class="input input__text" id="dni"
                        placeholder="DNI del participante" value="{{ old('dni') }}">
                    @error('dni')
                        <small class="error error__text">{{ $message }}</small>
                    @enderror
                </div>

                <div class="fl-flex-label mb-4 col-12 col-lg-6">
                    <input required type="text" name="nombre" class="input input__text" id="nombre"
                        placeholder="Nombres del participante" value="{{ old('nombre') }}">
                    @error('nombre')
                        <small class="error error__text">{{ $message }}</small>
                    @enderror
                </div>

                <div class="fl-flex-label mb-4 col-12 col-lg-6">
                    <input required type="text" name="apellido" class="input input__text" id="apellido"
                        placeholder="Apellidos del participante" value="{{ old('apellido') }}">
                    @error('apellido')
                        <small class="error error__text">{{ $message }}</small>
                    @enderror
                </div>

                <div class="fl-flex-label mb-4 col-12 col-lg-6">
                    <input type="email" name="correo" class="input input__text" id="correo"
                        placeholder="Correo del participante" value="{{ old('correo') }}">
                    @error('correo')
                        <small class="error error__text">{{ $message }}</small>
                    @enderror
                </div>

                <div class="fl-flex-label mb-4 col-12 col-lg-6">
                    <label>Participó como * </label>
                    <select required name="participa" class="input input__text" id="opciones" onchange="mostrarInput()">
                        <option value="">Seleccionar...</option>
                        <option {{ old('participa') == 'asistente' ? 'selected' : '' }} value=1>Asistente
                        </option>
                        <option {{ old('participa') == 'ponente' ? 'selected' : '' }} value=2>Ponente</option>
                        <option {{ old('participa') == 'otro' ? 'selected' : '' }} value="otro">Otro</option>
                    </select>
                    @error('participa')
                        <small class="error error__text">{{ $message }}</small>
                    @enderror
                </div>
                <div class="fl-flex-label mb-4 col-12 col-lg-6">
                    <label>programa de estudio *</label>
                    <select required name="programa" class="input input__text" id="opciones2" onchange="mostrarInput2()">
                        <option value="">Seleccionar...</option>
                        <option {{ old('participa') == 'asistente' ? 'selected' : '' }} value=3>Minas</option>
                        <option {{ old('participa') == 'ponente' ? 'selected' : '' }} value=4>Economica</option>
                        <option {{ old('participa') == 'otro' ? 'selected' : '' }} value="otro">Otro</option>
                    </select>
                    @error('participa')
                        <small class="error error__text">{{ $message }}</small>
                    @enderror
                </div>

                <!-- <div class="fl-flex-label mb-4 col-12 col-lg-6">
                    <label>tipo de participante *</label>
                    <select required name="tipo" class="input input__text" id="opciones3" onchange="mostrarInput()">
                        <option value="">Seleccionar...</option>
                        <option {{ old('participa') == 'asistente' ? 'selected' : '' }} value="asistente">
                        </option>
                        <option {{ old('participa') == 'ponente' ? 'selected' : '' }} value="ponente">Ponente</option>
                        <option {{ old('participa') == 'otro' ? 'selected' : '' }} value="otro">Otro</option>
                    </select>
                    @error('participa')
                        <small class="error error__text">{{ $message }}</small>
                    @enderror
                </div>  -->

                <div class="fl-flex-label mb-4 col-12 col-lg-6">
                    <input required type="text" name="codigo" class="input input__text" id="codigo"
                        placeholder="Código del participante" value="{{ old('codigo') }}">
                    @error('codigo')
                        <small class="error error__text">{{ $message }}</small>
                    @enderror
                </div>

                <div class="fl-flex-label mb-4 col-12 col-lg-6" id="campoOtro" style="display: none;">
                    <label>Seleccionar certificado escaneado(PDF)</label>
                    <input type="file" name="certificado" class="input input__text" id="certificado"
                        value="{{ old('certificado') }}">
                    @error('certificado')
                        <small class="error error__text">{{ $message }}</small>
                    @enderror
                </div>


                <div class="text-right mt-0">
                    <a href="{{ route('participante.index') }}" class="btn btn-rounded btn-secondary m-2"><i
                            class="fas fa-caret-left"></i> Atras</a>
                    <button type="submit" class="btn btn-rounded btn-primary"><i class="fas fa-save"></i> Guardar</button>
                </div>
            </div>
        </form>
        <hr>


        <div class="">
            <fieldset>
                <legend class="text-dark font-weight-bold">Importar Datos desde Excel
                    <a href="{{ route('exportModeloParticipante.index') }}" class="btn btn-success"
                        style="background: rgb(0, 168, 59)"><i class="fas fa-download"></i>&nbsp;
                        Descargar Formato</a>
                </legend>

                <div class="alert alert-warning text-dark"><i class="fas fa-exclamation-triangle"></i>&nbsp;&nbsp;Se
                    recomienda descargar el FORMATO y No modificar los
                    ENCABEZADOS del archivo.
                </div>

                <div class="alert alert-warning text-dark"><i class="fas fa-exclamation-triangle"></i>&nbsp;&nbsp;Se
                    recomienda NO tener registros duplicados en la columna DNI_DEL_PARTICIPANTE.
                </div>

                <form action="{{ route('importParticipante.index') }}" enctype="multipart/form-data" method="POST">
                    @csrf
                    <div class="fl-flex-label mb-4 mt-2 col-12">
                        <select required name="id_curso" class="input input__text">
                            <option value="">Seleccionar curso...</option>
                            @foreach ($curso as $item)
                                <option value="{{ $item->id_curso }}"
                                    {{ old('id_curso') == $item->id_curso ? 'selected' : '' }}>
                                    {{ $item->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4 mt-2 col-12 d-flex justify-content-between">
                        <label class="col-12">DATOS: seleccionar archivo excel
                            <input required type="file" name="dato" class="input input__text"
                                value="{{ old('dato') }}"></label>
                    </div>
                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="invalidCheck2" name="reemplazar">
                            <label class="form-check-label" style="color: red" for="invalidCheck2">
                                Deseo reemplazar el registro anterior <b>(se eliminará a los participantes del curso que has
                                    seleccionado y REEMPLAZARÁ con este nuevo registro subido)</b>
                            </label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary px-5 mb-2 py-3"><i class="fas fa-upload"></i> Subir
                        Datos</button>
                    @error('id_curso')
                        <div class="alert alert-danger"><i class="fas fa-times"></i> Debes seleccionar un curso</div>
                    @enderror
                    @error('dato')
                        <div class="alert alert-danger"><i class="fas fa-times"></i> {{ $message }}</div>
                    @enderror
                </form>

            </fieldset>
        </div>
    </div>




    <script>
        window.onload = function() {
            mostrarInput();
            mostrarInput2();
        }

        function mostrarInput() {
            var select = document.getElementById("opciones");

            var campoOtro = document.getElementById("campoOtro");

            if (select.value == "otro") {
                campoOtro.style.display = "block";
            } else {
                campoOtro.style.display = "none";
            }
        }

        function mostrarInput2() {
            var select = document.getElementById("opciones2");
            var campoOtro = document.getElementById("campoOtro2");
        }
    </script>


@endsection
