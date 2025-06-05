@extends('layouts.app')

@section('content')
    <!--.side-menu-->

    <h2 class="text-center text-secondary pb-2">PANEL DE CONTROL</h2>

    <div class="container-fluid text-center">
        <div class="row">

            <!--.col-->
            <div class="col-12">
                <div class="row">
                    <div class="col-12 col-sm-6 col-lg-4">
                        <article class="statistic-box bg-primary">
                            <div>
                                @foreach ($curso as $item)
                                    <div class="number text-light">{{ $item->total }}</div>
                                @endforeach
                                <div class="caption">
                                    <div>CURSOS</div>
                                </div>
                            </div>
                        </article>
                    </div>
                    <!--.col-->
                    <div class="col-12 col-sm-6 col-lg-4">
                        <article class="statistic-box purple">
                            <div>
                                @foreach ($participante as $item)
                                    <div class="number text-light">{{ $item->total }}</div>
                                @endforeach
                                <div class="caption">
                                    <div>PARTICIPANTES</div>
                                </div>
                            </div>
                        </article>
                    </div>
                    <!--.col-->
                    <div class="col-12 col-sm-6 col-lg-4">
                        <article class="statistic-box green">
                            <div>
                                @foreach ($usuario as $item)
                                    <div class="number text-light">{{ $item->total }}</div>
                                @endforeach
                                <div class="caption">
                                    <div>USUARIOS</div>
                                </div>
                            </div>
                        </article>
                    </div>
                </div>
                <!--.row-->
            </div>
            <!--.col-->

            <!--.col-->
            <div class="container" style="width: 100%;">
                <canvas id="grafica" height="90"></canvas>
            </div>
            <!--.row-->

        </div>
    </div>

    <!--.container-fluid-->
    <!--.page-content-->
    </body>
@endsection
