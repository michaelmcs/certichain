@extends('layouts/app')
@section('titulo', 'lista de participantes')

@section('content')

    {{-- notificaciones --}}

    <script>
        function eliminar() {
            let res = confirm("¿estás seguro de eliminar?")
            return res
        }
    </script>

    <h4 class="text-center text-secondary">DATOS DEL PARTICIPANTE</h4>

    <div>
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
        @error('codigo')
            <div class="alert alert-error"><i class="fas fa-times"></i> {{ $message }}</div>
        @enderror
        @error('participa')
            <div class="alert alert-error"><i class="fas fa-times"></i> {{ $message }}</div>
        @enderror
        @error('certificado')
            <div class="alert alert-error"><i class="fas fa-times"></i> {{ $message }}</div>
        @enderror
    </div>

    <div class="bg-white">
        @foreach ($sql as $item)
            <form action="{{ route('participante.update', $item->id_participante) }}" id="actualizar"
                enctype="multipart/form-data" method="POST">
                @csrf
                @method('PUT')

                <div class="fl-flex-label mb-4 col-12 col-lg-12">
                    <select name="curso" class="input input__text">
                        <option value="">Seleccionar curso...</option>
                        @foreach ($curso as $item2)
                            <option value="{{ $item2->id_curso }}"
                                {{ $item->id_curso == $item2->id_curso ? 'selected' : '' }}>
                                {{ $item2->nombre }}</option>
                        @endforeach
                    </select>

                </div>

                <div class="fl-flex-label mb-4 col-12 col-lg-12">
                    <input type="number" name="dni" class="input input__text" id="dni"
                        placeholder="DNI del participante *" value="{{ $item->dni }}">

                </div>

                <div class="fl-flex-label mb-4 col-12 col-lg-12">
                    <input type="text" name="nombre" class="input input__text" id="nombre"
                        placeholder="Nombres del participante *" value="{{ $item->nombre }}">

                </div>

                <div class="fl-flex-label mb-4 col-12 col-lg-12">
                    <input type="text" name="apellido" class="input input__text" id="apellido"
                        placeholder="Apellidos del participante *" value="{{ $item->apellido }}">

                </div>

                <div class="fl-flex-label mb-4 col-12 col-lg-12">
                    <input type="email" name="correo" class="input input__text" id="correo"
                        placeholder="Correo del participante" value="{{ $item->correo }}">
                </div>

                <div class="fl-flex-label mb-4 col-12 col-lg-12">
                    <label>Participó como</label>
                    <select name="participa" class="input input__text">
                        <option {{ $item->participo_como == 'asistente' ? 'selected' : '' }} value="asistente">Asistente
                        </option>
                        <option {{ $item->participo_como == 'ponente' ? 'selected' : '' }} value="ponente">Ponente</option>
                        <option {{ $item->participo_como == 'otro' ? 'selected' : '' }} value="otro">Otro</option>
                    </select>
                </div>

                <div class="fl-flex-label mb-4 col-12 col-lg-12">
                    <input type="text" name="codigo" class="input input__text" id="codigo"
                        placeholder="Código del participante" value="{{ $item->codigo }}">
                </div>

                @if ($item->participo_como == 'otro')
                    @if ($item->certificado != '')
                        <div class="fl-flex-label mb-4 col-12 col-lg-12">
                            <a style="top: 0" class="btn btn-secondary" data-toggle="modal"
                                data-target="#cambiar{{ $item->id_participante }}">Ver
                                certificado</a>
                        </div>
                    @else
                        <div class="fl-flex-label mb-4 col-12 col-lg-12">
                            <a style="top: 0" class="btn btn-secondary" data-toggle="modal"
                                data-target="#cambiar{{ $item->id_participante }}"><i class="fas fa-plus"></i> Agregar
                                certificado</a>
                        </div>
                    @endif
                @endif


            </form>
            <div class="modal-footer">
                <a href="{{ route('participante.index') }}" class="btn btn-secondary"><i class="fas fa-caret-left"></i> Atras</a>
                @if ($item->participo_como == 'otro')
                    <a style="top: 0" href="" class="btn btn-primary m-1" data-toggle="modal"
                        data-target="#staticBackdropQR{{ $item->id_participante }}"><i class="fas fa-qrcode"></i> Generar
                        QR</a>
                @endif
                <form action="{{ route('participante.destroy', $item->id_participante) }}" method="POST" id="eliminar"
                    class="d-inline formulario-eliminar">
                    @csrf
                    @method('delete')
                    {{-- <button type="submit" class="btn btn-sm btn-danger">
                    <i class="fas fa-trash-alt"></i>
                </button> --}}
                </form>

                <a href="#" class="btn btn-danger eliminar" data-id="{{ $item->id_participante }}"><i class="fas fa-trash-alt"></i> Eliminar</a>
                <button form="actualizar" type="submit" class="btn btn-primary"><i class="fas fa-save"></i>
                    Modificar</button>
            </div>


            <div class="modal fade" id="cambiar{{ $item->id_participante }}" data-backdrop="static"
                data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div style="background: rgb(181, 211, 255)"
                            class="px-4 py-3 w-100 d-flex justify-content-between">
                            <h5 class="modal-title" id="staticBackdropLabel">Mi Certificado</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="p-3">
                            <div>
                                <form action="{{ route('participante.modCer', $item->id_participante) }}"
                                    enctype="multipart/form-data" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <input type="hidden" name="curso" value="{{ $item->id_curso }}">
                                    <input type="hidden" name="dni" value="{{ $item->dni }}">
                                    <input type="hidden" name="codigo" value="{{ $item->codigo }}">
                                    <input type="hidden" name="apellido" value="{{ $item->apellido }}">
                                    <input type="hidden" name="nombre" value="{{ $item->nombre }}">

                                    @if ($item->certificado != '')
                                        <iframe src="{{ asset("certificados/$item->certificado") }}" frameborder="0"
                                            width="100%"></iframe>
                                        <div class="text-center mb-2">
                                            <a href='{{ asset('certificados/' . $item->certificado) }}'
                                                class="btn btn-success px-4" download="certificado{{ $item->dni }}"><i
                                                    class="fas fa-file-pdf"></i> Descargar certificado</a>
                                        </div>
                                    @endif


                                    <div class="fl-flex-label mb-4 col-12 col-lg-12">
                                        <label>Selecionar un certificado</label>
                                        <input required type="file" name="certificado" class="input input__text"
                                            value="{{ $item->codigo }}">
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i
                                                class="fas fa-times"></i> Cerrar</button>

                                        @if ($item->certificado != '')
                                            <a onclick="return eliminar()"
                                                href="{{ route('participante.eliCer', $item->id_participante) }}"
                                                class="btn btn-danger"><i class="fas fa-trash-alt"></i> Eliminar este
                                                certificado</a>
                                        @endif

                                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>
                                            Guardar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- modal qr --}}
            <div class="modal fade" id="staticBackdropQR{{ $item->id_participante }}" data-backdrop="static"
                data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div style="background: rgb(181, 211, 255)"
                            class="px-4 py-3 w-100 d-flex justify-content-between">
                            <h5 class="modal-title" id="staticBackdropLabel">Vista previa del QR</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="p-3">
                            <div>
                                <div class="w-100 m-auto text-center">
                                    <img style="width: 200px;"
                                        src="{{ asset("QR_asistentes/$item->id_participante.png") }}" alt="Código QR">

                                </div>
                                <a href="{{ asset("QR_asistentes/$item->id_participante.png") }}"
                                    download="{{ $item->id_participante }}" class="btn btn-secondary text-center"><i
                                        class="fas fa-download"></i> Descargar QR</a>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Cerrar</button>
                                    <a href="{{ route('participante.crearQR', $item->id_participante) }}"
                                        class="btn btn-success"><i class="fas fa-qrcode"></i> Generar QR</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>



@endsection
