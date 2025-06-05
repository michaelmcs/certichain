@extends('layouts/app')
@section('titulo', 'lista de participantes')

@section('content')

    {{-- notificaciones --}}

    <script>
        function eliminar() {
            let res = confirm("¿estás seguro de eliminar?")
            return res
        }

        function confirmarEliminarTodo() {
            let res = confirm("Por tu seguridad te preguntamos nuevamente: ¿estás seguro de eliminar todo el registro?")
            return res
        }
    </script>

    <h4 class="text-center text-secondary">LISTA DE PARTICIPANTES</h4>
    <div class="form-group row col-12 px-4">
        <div class="col-12 col-sm-9">
            <input type="text" id="nombre" class="form-control p-3"
                placeholder="Ingrese el DNI o Nombre del participante">
        </div>
        <button id="buscar" class="btn btn-success col-12 col-sm-3 mt-2 mt-sm-0" type="submit"><i
                class="fas fa-search"></i> Buscar</button>
    </div>
    <div class="card-block table-responsive">
        <table id="" class="display table table-striped" cellspacing="0" width="100%">
            <thead class="table-primary">
                <tr>
                    <th class="sorting_desc">ID</th>
                    <th>DNI</th>
                    <th>PARTICIPANTE</th>
                    <th>CURSO</th>
                    <th>CORREO</th>
                    <th>PARTICIPÓ COMO</th>
                    <th>CODIGO</th>
                    <th>CERTIFICADO</th>
                    <th></th>
                </tr>
            </thead>

            <tbody id="tbody">

            </tbody>
        </table>
    </div>


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

    <div class="pb-1 pt-2">
        <a href="{{ route('participante.create') }}" class="btn btn-rounded btn-primary"><i class="fas fa-plus"></i>&nbsp;
            Registrar</a>

        <a href="{{ route('exportParticipante.index') }}" class="btn btn-rounded btn-success"><i
                class="fas fa-file-excel"></i>&nbsp;
            <i class="fas fa-download"></i> Descargar Excel</a>

        <form onsubmit="return confirmarEliminarTodo()" action="{{ route('participante.eliminarTodo') }}"
            class="d-inline formulario-eliminar">
        </form>

        <a href="#" class="btn btn-rounded btn-danger eliminar">
            <i class="fas fa-trash-alt"></i> Eliminar Todo
        </a>
    </div>


    <section class="card">
        <div class="card-block">
            <table id="example2" class="display table table-striped" cellspacing="0" width="100%">
                <thead class="table-primary">
                    <tr>
                        <th class="sorting_desc">ID</th>
                        <th>DNI</th>
                        <th>PARTICIPANTE</th>
                        <th>CURSO</th>
                        <th>CORREO</th>
                        <th>PARTICIPÓ COMO</th>
                        <th>CODIGO</th>
                        <th>CERTIFICADO</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($datos as $item)
                        <tr>
                            <td>{{ $item->id_participante }}</td>
                            <td>{{ $item->dni }}</td>
                            <td>{{ $item->nombre }} {{ $item->apellido }}</td>
                            <td>{{ $item->curso }}</td>
                            <td>{{ $item->correo }}</td>
                            <td>{{ $item->participo_como }}</td>
                            <td>{{ $item->codigo }}</td>
                            <td>
                                @if ($item->participo_como == 'otro')
                                    @if ($item->certificado == '')
                                        <a class="text-danger" style="top: 0" href="" data-toggle="modal"
                                            data-target="#cambiar{{ $item->id_participante }}">Sin certificado</a>
                                    @else
                                        <a style="top: 0" href="" data-toggle="modal"
                                            data-target="#cambiar{{ $item->id_participante }}">Ver certificado</a>
                                    @endif
                                @endif
                            </td>
                            <td>

                                @if ($item->participo_como == 'otro')
                                    <a style="top: 0" href="" class="btn btn-sm btn-primary m-1" data-toggle="modal"
                                        data-target="#staticBackdropQR{{ $item->id_participante }}"><i
                                            class="fas fa-qrcode"></i></a>
                                @endif

                                <a style="top: 0" href="" class="btn btn-sm btn-warning m-1" data-toggle="modal"
                                    data-target="#staticBackdrop{{ $item->id_participante }}"><i
                                        class="fas fa-edit"></i></a>

                                <form action="{{ route('participante.destroy', $item->id_participante) }}" method="POST"
                                    class="d-inline formulario-eliminar">
                                    @csrf
                                    @method('delete')
                                    {{-- <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash-alt"></i>
                                    </button> --}}
                                </form>

                                <a href="#" class="btn btn-sm btn-danger eliminar"
                                    data-id="{{ $item->id_participante }}">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>

                            {{-- modal de modificar --}}
                            <!-- Modal -->
                            <div class="modal fade" id="staticBackdrop{{ $item->id_participante }}" data-backdrop="static"
                                data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div style="background: rgb(181, 211, 255)"
                                            class="px-4 py-3 w-100 d-flex justify-content-between">
                                            <h5 class="modal-title" id="staticBackdropLabel">Modificar Participante</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="p-3">
                                            <div>
                                                <form action="{{ route('participante.update', $item->id_participante) }}"
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
                                                        <input type="number" name="dni" class="input input__text"
                                                            id="dni" placeholder="DNI del participante *"
                                                            value="{{ $item->dni }}">

                                                    </div>

                                                    <div class="fl-flex-label mb-4 col-12 col-lg-12">
                                                        <input type="text" name="nombre" class="input input__text"
                                                            id="nombre" placeholder="Nombres del participante *"
                                                            value="{{ $item->nombre }}">

                                                    </div>

                                                    <div class="fl-flex-label mb-4 col-12 col-lg-12">
                                                        <input type="text" name="apellido" class="input input__text"
                                                            id="apellido" placeholder="Apellidos del participante *"
                                                            value="{{ $item->apellido }}">

                                                    </div>

                                                    <div class="fl-flex-label mb-4 col-12 col-lg-12">
                                                        <input type="email" name="correo" class="input input__text"
                                                            id="correo" placeholder="Correo del participante"
                                                            value="{{ $item->correo }}">
                                                    </div>

                                                    <div class="fl-flex-label mb-4 col-12 col-lg-12">
                                                        <label>Participó como</label>
                                                        <select name="participa" class="input input__text">
                                                            <option
                                                                {{ $item->participo_como == 'asistente' ? 'selected' : '' }}
                                                                value="asistente">Asistente</option>
                                                            <option
                                                                {{ $item->participo_como == 'ponente' ? 'selected' : '' }}
                                                                value="ponente">Ponente</option>
                                                            <option {{ $item->participo_como == 'otro' ? 'selected' : '' }}
                                                                value="otro">Otro</option>
                                                        </select>
                                                    </div>

                                                    <div class="fl-flex-label mb-4 col-12 col-lg-12">
                                                        <input type="text" name="codigo" class="input input__text"
                                                            id="codigo" placeholder="Código del participante"
                                                            value="{{ $item->codigo }}">

                                                    </div>


                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal"><i class="fas fa-times"></i>
                                                            Cerrar</button>
                                                        <button type="submit" class="btn btn-primary"><i
                                                                class="fas fa-save"></i> Modificar</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal -->
                            <div class="modal fade" id="cambiar{{ $item->id_participante }}" data-backdrop="static"
                                data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div style="background: rgb(181, 211, 255)"
                                            class="px-4 py-3 w-100 d-flex justify-content-between">
                                            <h5 class="modal-title" id="staticBackdropLabel">Mi Certificado</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
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
                                                        <iframe src="{{ asset("certificados/$item->certificado") }}"
                                                            frameborder="0" width="100%"></iframe>
                                                        <div class="text-center mb-2">
                                                            <a href='{{ asset('certificados/' . $item->certificado) }}'
                                                                class="btn btn-success px-4"
                                                                download="certificado{{ $item->dni }}"><i
                                                                    class="fas fa-file-pdf"></i> Descargar certificado</a>
                                                        </div>
                                                    @endif


                                                    <div class="fl-flex-label mb-4 col-12 col-lg-12">
                                                        <label>Selecionar un certificado</label>
                                                        <input required type="file" name="certificado"
                                                            class="input input__text" value="{{ $item->codigo }}">

                                                    </div>


                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal"><i class="fas fa-times"></i>
                                                            Cerrar</button>

                                                        @if ($item->certificado != '')
                                                            <a onclick="return eliminar()"
                                                                href="{{ route('participante.eliCer', $item->id_participante) }}"
                                                                class="btn btn-danger">Eliminar este
                                                                certificado</a>
                                                        @endif

                                                        <button type="submit" class="btn btn-primary"><i
                                                                class="fas fa-save"></i> Guardar</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- modal qr --}}
                            <div class="modal fade" id="staticBackdropQR{{ $item->id_participante }}"
                                data-backdrop="static" data-keyboard="false" tabindex="-1"
                                aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div style="background: rgb(181, 211, 255)"
                                            class="px-4 py-3 w-100 d-flex justify-content-between">
                                            <h5 class="modal-title" id="staticBackdropLabel">Vista previa del QR</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="p-3">
                                            <div>
                                                <div class="w-100 m-auto text-center">
                                                    <img style="width: 200px;"
                                                        src="{{ asset("QR_asistentes/$item->id_participante.png") }}"
                                                        alt="Código QR">

                                                </div>
                                                <a href="{{ asset("QR_asistentes/$item->id_participante.png") }}"
                                                    download="{{ $item->id_participante }}"
                                                    class="btn btn-secondary text-center"><i class="fas fa-download"></i>
                                                    Descargar QR</a>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal"><i class="fas fa-times"></i> Cerrar</button>
                                                    <a href="{{ route('participante.crearQR', $item->id_participante) }}"
                                                        class="btn btn-success"><i class="fas fa-qrcode"></i> Generar
                                                        QR</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="text-right">
                {{ $datos->links('pagination::bootstrap-4') }}
                Mostrando {{ $datos->firstItem() }} - {{ $datos->lastItem() }} de {{ $datos->total() }}
                resultados
            </div>
        </div>
    </section>

    <script>
        let buscar = document.getElementById("buscar");
        let valor = document.getElementById("nombre");
        valor.addEventListener("blur", buscarEstudiante)
        valor.addEventListener("keyup", buscarEstudiante)
        buscar.addEventListener("click", buscarEstudiante)


        function buscarEstudiante() {
            let valor = document.getElementById("nombre").value;
            // if (valor == "") {
            //     alert("Ingrese datos para buscar")
            // }
            var ruta = "{{ url('buscar/participante') }}/" + valor + "";
            $.ajax({
                url: ruta,
                type: "get",
                success: function(data) {
                    let tr = document.createElement("tr");
                    let tbody = document.querySelector("tbody");
                    let fragmento = document.createDocumentFragment();
                    let td = "";
                    data.dato.forEach(function(item, index) {
                        td +=
                            `<tr>
                                <td>${item.id_participante}</td>
                                <td>${item.dni}</td>
                                <td>${item.nombre} ${item.apellido}</td>
                                <td>${item.nomCurso}</td>
                                <td>${item.correo}</td>
                                <td>
                                    ${item.participo_como == 'otro' ? '<span class="badge badge-success bg-success">Otro</span>' : item.participo_como}
                                </td>
                                <td>${item.codigo}</td>
                                <td>
                                    ${item.certificado == null || item.certificado === '' ? '' : '<span class="badge badge-success bg-success">Con certificado</span>'}
                                </td>
                                
                                <td>
                                    <a style="top: 0" href="participante/ver/participante/${item.id_participante}" class="btn btn-sm btn-primary m-1"><i class="fas fa-eye"></i></a>
                                </td>


                            </tr>`;
                        tbody.innerHTML = td
                    })

                },
                error: function(data) {
                    let tbody = document.getElementById("tbody");
                    tbody.innerHTML = ""
                }
            })
        }
    </script>
@endsection
