@extends('layouts/app')
@section('titulo', 'lista de Certificado')

@section('content')

    {{-- notificaciones --}}

    <h4 class="text-center text-secondary">LISTA DE MODELO DE CERTIFICADOS</h4>

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
                    <th>CURSO</th>
                    <th>DESCRIPCION</th>
                    {{-- <th>MODELO</th> --}}
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



        @error('modelo')
            <div class="alert alert-error"><i class="fas fa-times"></i> {{ $message }}</div>
        @enderror
        @error('curso')
            <div class="alert alert-error"><i class="fas fa-times"></i> {{ $message }}</div>
        @enderror
        @error('txtid')
            <div class="alert alert-error"><i class="fas fa-times"></i> {{ $message }}</div>
        @enderror
    </div>


    <section class="card">
        <div class="card-block">
            <table id="example2" class="display table table-striped" cellspacing="0" width="100%">
                <thead class="table-primary">
                    <tr>
                        <th class="sorting_desc">ID</th>
                        <th>CURSO</th>
                        <th>MODELO</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($datos as $key => $item)
                        <tr>
                            <td>{{ $item->id_certificado }}</td>
                            <td style="width: 300px">{{ $item->nombre }}</td>
                            <td>
                                @if ($item->modelo != '')
                                    <a class="text-primary" data-toggle="modal"
                                        data-target="#ver{{ $item->id_certificado }}"><i class="fas fa-eye"></i>Ver
                                        modelo</a>
                                @endif
                            </td>

                            <td>

                                @if ($item->id_certificado != '')
                                    <a style="top: 0" href="" class="btn btn-sm btn-warning m-1" data-toggle="modal"
                                        data-target="#nuevo{{ $key }}"><i class="fas fa-edit"></i></a>

                                <a href="{{ route('certificado.ajustar', $item->id_certificado) }}" class="btn btn-sm btn-primary m-1"><i class="fas fa-wrench"></i></a>

                                <a href="{{ route('certificado.blockchain', $item->id_certificado) }}"
   class="btn btn-sm btn-dark m-1"
   title="Registrar en Blockchain">
   <i class="fas fa-link"></i>
</a>

                                @else
                                    <a style="top: 0" href="" class="btn btn-sm btn-success m-1" data-toggle="modal"
                                        data-target="#agregar{{ $key }}"><i class="fas fa-plus"></i></a>
                                @endif


                            </td>


                            @if ($item->id_certificado != '')
                                {{-- modal para modificar imagen de modelo de certificado --}}
                                <div class="modal fade" id="nuevo{{ $key }}" data-backdrop="static"
                                    data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div style="background: rgb(181, 211, 255)"
                                                class="px-4 py-3 w-100 d-flex justify-content-between">
                                                <h5 class="modal-title" id="staticBackdropLabel">Modelo de certificado</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="p-3">
                                                <div>
                                                    <div class="text-center">
                                                        @if ($item->modelo != '')
                                                            <img style="width: 180px"
                                                                src="{{ asset("modelo_certificados/$item->modelo") }}"
                                                                alt="">
                                                        @endif
                                                    </div>

                                                    <form action="{{ route('certificado.add', $item->id_certificado) }}"
                                                        method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="row">

                                                            <input type="hidden" value="{{ $item->modelo }}"
                                                                name="txtid">
                                                            <input type="hidden" name="curso"
                                                                value="{{ $item->id_curso }}">
                                                            <div class="fl-flex-label mb-4 col-12 col-lg-12">
                                                                <input required type="file" name="modelo"
                                                                    class="input input__text" value="{{ old('modelo') }}">
                                                            </div>

                                                        </div>

                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal"><i class="fas fa-times"></i> Cerrar</button>
                                                            <button type="submit"
                                                                class="btn btn-primary"><i class="fas fa-save"></i> Modificar</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="ver{{ $item->id_certificado }}"
                                    aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div style="background: rgb(181, 211, 255)"
                                                class="px-4 py-3 w-100 d-flex justify-content-between">
                                                <h5 class="modal-title" id="staticBackdropLabel">Modelo de certificado
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="p-3">
                                                <div>
                                                    <div class="text-center">
                                                        @if ($item->modelo != '')
                                                            <img class="img-fluid"
                                                                src="{{ asset("modelo_certificados/$item->modelo") }}"
                                                                alt="">
                                                        @endif
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal"><i class="fas fa-times"></i> Cerrar</button>
                                                        <form action="{{ route('certificado.eliminarModelo',$item->id_certificado) }}" method="get"
                                                            class="d-inline formulario-eliminar">
                                                        </form>
                                                        <a href="#" class="btn btn-danger eliminar"
                                                            data-id="{{ $item->id_certificado }}">
                                                            <i class="fas fa-trash-alt"></i> Eliminar este modelo
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="modal fade" id="agregar{{ $key }}" data-backdrop="static"
                                    data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div style="background: rgb(181, 211, 255)"
                                                class="px-4 py-3 w-100 d-flex justify-content-between">
                                                <h5 class="modal-title" id="staticBackdropLabel">Agregar Modelo de
                                                    certificado</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="p-3">
                                                <div>
                                                    <form action="{{ route('certificado.store') }}" method="POST"
                                                        enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="row">

                                                            <div class="fl-flex-label mb-4 col-12 col-lg-12">
                                                                <input required type="file" name="modelo"
                                                                    class="input input__text"
                                                                    value="{{ old('modelo') }}">

                                                            </div>

                                                            <div class="fl-flex-label mb-4 col-12 col-lg-12">
                                                                <select required class="input input__select" name="curso">
                                                                    <option value="{{ $item->id_curso }}">
                                                                        {{ $item->nombre }}</option>
                                                                </select>
                                                            </div>


                                                        </div>

                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal"><i class="fas fa-times"></i> Cerrar</button>
                                                            <button type="submit"
                                                                class="btn btn-primary"><i class="fas fa-save"></i> Registrar</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif




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
             if (valor == "") {
                 alert("Ingrese datos para buscar")
             }
            var ruta = "{{ url('buscar/certificado') }}/" + valor + "";
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
                                <td>${item.descripcion}</td>
                                <td>
                                    <a style="top: 0" href="certificado/ver/certificado/${item.id_curso}" class="btn btn-sm btn-primary m-1"><i class="fas fa-eye"></i> Ver</a>
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
