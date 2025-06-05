@extends('layouts/inicio')
@section('contenido')
    @if (session('mensaje'))
        <div class="alert alert-danger">{{ session('mensaje') }}</div>
    @endif
    @if (session('aviso'))
        <div class="alert alert-danger">{{ session('aviso') }}</div>
    @endif
    <div class="col-12 p-3">
        <div class="row">
            <div class="col-12 col-sm-4 col-lg-3 mb-2">
                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <p class="nav-link text-white m-0 text-center bg-secondary"><b>CURSOS</b></p>
                    {{-- <a class="nav-link active" id="v-pills-inicio-tab" data-bs-toggle="pill" href="#v-pills-inicio"
                        role="tab" aria-controls="v-pills-inicio" aria-selected="true">Inicio</a> --}}
                    {{-- <li class="nav-item">
                        <a class="nav-link text-white px-3 {{ Request::is('busqueda*') ? 'activo' : '' }}"
                            aria-current="page" href="{{ route('busqueda.index') }}"><i class="fa-solid fa-search"></i>
                            Certificados</a>
                    </li> --}}
                    <a class="nav-link" id="v-pills-home-tab" data-bs-toggle="pill" href="#v-pills-home" role="tab"
                        aria-controls="v-pills-home" aria-selected="true"><i class="far fa-check-circle"></i> Próximamente</a>
                    <a class="nav-link" id="v-pills-profile-tab" data-bs-toggle="pill" href="#v-pills-profile"
                        role="tab" aria-controls="v-pills-profile" aria-selected="false"><i class="fas fa-check-circle"></i> En curso</a>
                    <a class="nav-link" id="v-pills-messages-tab" data-bs-toggle="pill" href="#v-pills-messages"
                        role="tab" aria-controls="v-pills-messages" aria-selected="false"><i class="fas fa-check-square"></i> Finalizado</a>
                </div>
            </div>
            <div class="col-12 col-sm-8 col-lg-9" style="height: 100vh;overflow: scroll">


                <div class="tab-content" id="v-pills-tabContent">
                    <div class="tab-pane fade show active" id="v-pills-inicio" role="tabpanel"
                        aria-labelledby="v-pills-inicio-tab">
                        <div class="row col-12">
                            @foreach ($datos as $item)
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="text-center">BIENVENIDOS AL SISTEMA DE CERTIFICACIÓN DIGITAL</h3>
                                            <h6 class="text-center">Consulta de Certificados
                                            </h6>
                                        </div>
                                        <!-- <div class="text-center p-3">
                                            <img class="img-fluid" width="200" src="{{asset("foto/empresa/$item->foto")}}"
                                                alt="">
                                        </div> -->
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>


                <div class="tab-content" id="v-pills-tabContent">
                    <div class="tab-pane fade show" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                        <div class="row col-12">
                            @foreach ($cursoProx as $item)
                                <div class="col-12 col-md-4 mb-4">
                                    <div class="card">
                                        <div class="card-header text-white" style="background: rgb(231, 134, 0)">
                                            <h6 class="card-title">{{ $item->nombre }}</h6>
                                        </div>
                                        <div class="card-body">
                                            <p class="card-text" style="color:rgb(71, 71, 71)">{{ $item->descripcion }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>



                <div class="tab-content" id="v-pills-tabContent">
                    <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                        <div class="row col-12">
                            @foreach ($cursoEncu as $item2)
                                <div class="col-12 col-md-4 mb-4">
                                    <div class="card">
                                        <div class="card-header bg-success text-white">
                                            <h6 class="card-title">{{ $item2->nombre }}</h6>
                                        </div>
                                        <div class="card-body">
                                            <p class="card-text" style="color:rgb(71, 71, 71)">{{ $item2->descripcion }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>



                <div class="tab-content" id="v-pills-tabContent">
                    <div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">
                        <div class="row col-12">
                            @foreach ($cursoFina as $item3)
                                <div class="col-12 col-md-4 mb-4">
                                    <div class="card">
                                        <div class="card-header bg-danger text-white">
                                            <h6 class="card-title">{{ $item3->nombre }}</h6>
                                        </div>
                                        <div class="card-body">
                                            <p class="card-text" style="color:rgb(71, 71, 71)">{{ $item3->descripcion }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>





            </div>
        </div>




    </div>
@endsection
