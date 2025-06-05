@extends('layouts/formBusqueda')

<style>
    main {
        display: flex;
        justify-content: center !important;
        flex-wrap: wrap;
    }

    .container {
        margin: 0 !important;
    }

    iframe {
        width: 90vw;
        height: 70vh;
        min-width: 300px;
        border: 1px solid #ddd;
    }

    @media screen and (max-width:680px) {
        iframe {
            width: 100%;
        }
    }
</style>

@section('resultado')
    @if (session('CORRECTO'))
        {!! session('CORRECTO') !!}
    @endif

    @if (empty($certPart))
        @if (strtolower($participo_como) !== 'otro')
            {{-- Certificado generado dinámicamente con TCPDF --}}
            <iframe src="{{ route('busqueda.pdf', [$id_certificado, $id_participante]) }}" frameborder="0"></iframe>
        @else
            <div class="alert alert-danger">
                Aún no se ha subido tu certificado. Ud. no está registrado como Asistente ni Ponente. Por favor, consulte con el administrador.
            </div>
        @endif
    @else
        {{-- Certificado en PDF subido manualmente --}}
        <iframe src="{{ asset("certificados/$certPart") }}" frameborder="0"></iframe>
    @endif
@endsection
