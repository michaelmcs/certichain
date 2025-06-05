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


    <h4 class="text-center text-secondary">LISTA DE CURSOS REGISTRADOS</h4>

    <div class="form-group row col-12 px-4">
        <div class="col-12 col-sm-9">
            <input type="text" id="nombre" class="form-control p-3"
                placeholder="Ingrese el Nombre o Descripción del curso">
        </div>
        <button id="buscar" class="btn btn-success col-12 col-sm-3 mt-2 mt-sm-0" type="submit"><i class="fas fa-search"></i> Buscar</button>
    </div>
    <div class="card-block table-responsive">
        <table id="" class="display table table-striped" cellspacing="0" width="100%">
            <thead class="table-primary">
                <tr>
                    <th class="sorting_desc">ID</th>
                    <th>NOMBRE</th>
                    <th>HORAS</th>
                    <th>DESCRIPCION</th>
                    <th>INICIO</th>
                    <th>TERMINO</th>
                    <th>ESTADO</th>
                    <th></th>
                </tr>
            </thead>

            <tbody id="tbody">

            </tbody>
        </table>
    </div>


    @if (session('CORRECTO'))
        <div class="alert alert-success"><i class="fas fa-check"></i> {{ session('CORRECTO') }}</div>
    @endif

    @if (session('INCORRECTO'))
        <div class="alert alert-error"><i class="fas fa-times"></i> {{ session('INCORRECTO') }}</div>
    @endif

    @if (session('DUPLICADO'))
        <div class="alert alert-error"><i class="fas fa-exclamation-triangle"></i> {{ session('DUPLICADO') }}</div>
    @endif
    <div class="pb-1 pt-2">
        <a href="{{ route('curso.create') }}" class="btn btn-rounded btn-primary"><i class="fas fa-plus"></i>&nbsp;
            Registrar</a>

        {{-- <a href="{{ route('exportCurso.index') }}" class="btn btn-rounded btn-success"><i
                class="fas fa-file-excel"></i>&nbsp;
            Descargar Excel</a> --}}
    </div>

    @error('nombre')
        <div class="alert alert-error"><i class="fas fa-times"></i> {{ $message }}</div>
    @enderror
    @error('estado')
        <div class="alert alert-error"><i class="fas fa-times"></i> {{ $message }}</div>
    @enderror
    @error('hora')
        <div class="alert alert-error"><i class="fas fa-times"></i> {{ $message }}</div>
    @enderror
    @error('descripcion')
        <div class="alert alert-error"><i class="fas fa-times"></i> {{ $message }}</div>
    @enderror
    @error('inicio')
        <div class="alert alert-error"><i class="fas fa-times"></i> {{ $message }}</div>
    @enderror
    @error('termino')
        <div class="alert alert-error"><i class="fas fa-times"></i> {{ $message }}</div>
    @enderror


    <section class="card">
        <div class="card-block">
            <table id="example2" class="display table table-striped" cellspacing="0" width="100%">
                <thead class="table-primary">
                    <tr>
                        <th class="sorting_desc">ID</th>
                        <th>NOMBRE</th>
                        <th>HORAS</th>
                        <th>DESCRIPCION</th>
                        <th>INICIO</th>
                        <th>TERMINO</th>
                        <th>ESTADO</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($datos as $item)
                        <tr>
                            <td>{{ $item->id_curso }}</td>
                            <td style="width: 300px">{{ $item->nombre }}</td>
                            <td>{{ $item->horas }}</td>
                            <td>{{ $item->descripcion }}</td>
                            <td>{{ $item->inicio }}</td>
                            <td>{{ $item->termino }}</td>
                            <td>
                                @if ($item->estado == 'proximamente')
                                    <span class="badge badge-warning bg-warning">{{ $item->estado }}</span>
                                @else
                                    @if ($item->estado == 'encurso')
                                        <span class="badge badge-success bg-success">{{ $item->estado }}</span>
                                    @else
                                        @if ($item->estado == 'finalizado')
                                            <span class="badge badge-danger bg-danger">{{ $item->estado }}</span>
                                        @endif
                                    @endif
                                @endif
                            </td>
                            <td>

                                <a style="top: 0" href="" class="btn btn-sm btn-warning m-1" data-toggle="modal"
                                    data-target="#staticBackdrop{{ $item->id_curso }}"><i class="fas fa-edit"></i></a>

                                <form onsubmit="return confirmarEliminar()"
                                    action="{{ route('curso.destroy', $item->id_curso) }}" method="POST"
                                    class="d-inline formulario-eliminar">
                                    @csrf
                                    @method('delete')
                                    {{-- <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash-alt"></i>
                                    </button> --}}
                                </form>

                                <a href="#" class="btn btn-sm btn-danger eliminar" data-id="{{ $item->id_curso }}">
                                    <i class="fas fa-trash-alt"></i>
                                </a>

                            </td>

                            {{-- modal de modificar --}}
                            <!-- Modal -->
                            <div class="modal fade" id="staticBackdrop{{ $item->id_curso }}" data-backdrop="static"
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
                                                <form action="{{ route('curso.update', $item->id_curso) }}"
                                                    enctype="multipart/form-data" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="row">
                                                        <div class="fl-flex-label mb-4 col-12">
                                                            <input required type="text" name="nombre"
                                                                class="input input__text" id="nombre"
                                                                placeholder="Nombre del curso"
                                                                value="{{ old('nombre', $item->nombre) }}">
                                                        </div>

                                                        <div class="fl-flex-label mb-4 col-12">
                                                            <input required type="text" name="hora"
                                                                class="input input__text" id="hora"
                                                                placeholder="Horas del curso"
                                                                value="{{ old('hora', $item->horas) }}">
                                                            @error('hora')
                                                                <small class="error error__text">{{ $message }}</small>
                                                            @enderror
                                                        </div>

                                                        <div class="fl-flex-label mb-4 col-12">
                                                            <textarea required class="input input__text" name="descripcion" cols="30" rows="3"
                                                                placeholder="Descripcion del curso">{{ old('descripcion', $item->descripcion) }}</textarea>
                                                        </div>

                                                        <div class="fl-flex-label mb-4 col-12">
                                                            <label class="label2">Fecha de inicio del curso</label>
                                                            <input required type="date" name="inicio"
                                                                class="input input__text"
                                                                value="{{ old('inicio', $item->inicio) }}">
                                                            @error('inicio')
                                                                <small class="error error__text">{{ $message }}</small>
                                                            @enderror
                                                        </div>

                                                        <div class="fl-flex-label mb-4 col-12">
                                                            <label class="label2">Fecha de finalización del curso</label>
                                                            <input required type="date" name="termino"
                                                                class="input input__text"
                                                                value="{{ old('termino', $item->termino) }}">
                                                            @error('termino')
                                                                <small class="error error__text">{{ $message }}</small>
                                                            @enderror
                                                        </div>

                                                        <div class="fl-flex-label mb-4 col-12 col-lg-12">
                                                            <label>Seleccionar estado del curso *</label>
                                                            <select required name="estado" class="input input__select">
                                                                <option
                                                                    {{ $item->estado == 'proximamente' ? 'selected' : '' }}
                                                                    value="proximamente">Próximamente</option>
                                                                <option {{ $item->estado == 'encurso' ? 'selected' : '' }}
                                                                    value="encurso">En curso</option>
                                                                <option
                                                                    {{ $item->estado == 'finalizado' ? 'selected' : '' }}
                                                                    value="finalizado">Finalizado</option>
                                                            </select>

                                                        </div>
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
            var ruta = "{{ url('buscar/curso') }}/" + valor + "";
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
                                <td>${item.id_curso}</td>
                                <td>${item.nombre}</td>
                                <td>${item.horas}</td>
                                <td>${item.descripcion}</td>
                                <td>${item.inicio}</td>
                                <td>${item.termino}</td>
                                <td>
                                    ${item.estado === "proximamente" ? '<span class="badge badge-warning bg-warning">Proximamente</span>' : (item.estado === "encurso" ? '<span class="badge badge-success bg-success">En curso</span>' : '<span class="badge badge-danger bg-danger">Finalizado</span>')}
                                </td>
                                
                                <td>
                                    <a style="top: 0" href="curso/ver/curso/${item.id_curso}" class="btn btn-sm btn-primary m-1"><i class="fas fa-eye"></i></a>
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
