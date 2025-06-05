@extends('layouts/inicio')
@section('contenido')
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        h1 {
            text-align: center;
            margin-top: 10px;
            border: none;
            text-shadow: none;
            font-size: 35px;
        }

        .container2 {
            width: 70%;
            margin: 0 auto;
            padding: 20px;
            background-color: #f0f0f0;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }

        .label {
            font-weight: bold;
        }

        .data {
            margin-left: 20px;
        }
    </style>

    @if ($modo == 'tiene')

        <div class="container2">
            <h1 class="titulo">CERTIFICADO OTORGADO A:</h1>
            @foreach ($datosPersona as $item)
                <div>
                    <p><b class="label">DNI:</b> <span class="data">{{ $item->dni }}</span></p>
                    <p><b class="label">Nombres:</b> <span class="data">{{ $item->nombre }}
                            {{ $item->apellido }}</span></p>
                </div>
            @endforeach


        </div>
        @foreach ($datosPersona as $item)
            <iframe src="{{ asset("certificados/$item->certificado") }}" frameborder="0"
                style="width: 100%; height: 100vh;min-width: 0;"></iframe>
        @endforeach
    @else
        @if ($modo == 'notiene')
            <div class="container2">
            <h1 class="titulo">VICERRECTORADO ACADEMICO UNA - PUNO</h1>
                <h1 class="titulo">CERTIFICADO OTORGADO A:</h1>
                @foreach ($datosPersona as $item)
                    <div>
                        <p><b class="label">DNI:<span class="data">{{$item->dni }}</span></p>
                        <p><b class="label">Nombres:</b><span class="data">{{$item->nombre }}
                                {{ $item->apellido }}</span></p>
                        <p><b class="label">Por haber participado como:</b> <span
                                class="data">{{ $item->participo_como }}</span>
                        </p>

                        <p><b class="label">Codigo de certificación:</b><span class="data">{{$item->codigo }}</span>
                        </p>

                        <p>
                                <b class="label">Horas Academicas:</b><span class="data">{{$item->horas }} Horas</span>
                          </p>
                    </div>
                @endforeach


            </div>
            @foreach ($datosPersona as $item)
                <iframe src="{{ route('busqueda.miCertificado', [$item->id_certificado, $item->id_participante]) }}"
                    frameborder="0" style="width: 100%; height: 94vh;min-width: 0;"></iframe>
            @endforeach
        @endif
    @endif


@endsection
