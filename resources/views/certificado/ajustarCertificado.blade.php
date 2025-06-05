@extends('layouts/app')
@section('titulo', 'Configurar Modelo Certificado')

@section('content')
@if (session('CORRECTO'))
    <div class="alert alert-success"><i class="fas fa-check"></i> {{ session('CORRECTO') }}</div>
@endif
@if (session('INCORRECTO'))
    <div class="alert alert-danger"><i class="fas fa-times"></i> {{ session('INCORRECTO') }}</div>
@endif

<style>
    .modo-mover { cursor: move !important; }
    .editor-wrapper { display: flex; height: 82vh; }
    #editor-container {
        position: relative; width: 297mm; height: 210mm;
        border: 1px solid #ddd; background-color: #f9f9f9;
        transform: scale(0.8); transform-origin: top left;
    }
    #editor-background {
        position: absolute; width: 100%; height: 100%;
        object-fit: contain; pointer-events: none;
    }
    .campo-editable {
        position: absolute;
        border: 2px dashed rgba(255, 0, 0, 0.7);
        background-color: rgba(255, 255, 255, 0.5);
        display: flex; align-items: center; justify-content: center;
        font-weight: bold; cursor: move; resize: both;
        overflow: visible; box-sizing: border-box;
        white-space: nowrap; text-align: center;
        padding: 0 !important;
        margin: 0 !important;
    }
    .campo-editable.activo { outline: 2px solid blue; }

    .panel-control {
        width: 300px; padding: 20px;
        background-color: #f5f5f5; border-left: 1px solid #ddd;
        display: flex; flex-direction: column;
    }
    .campos-container { display: flex; flex-direction: column; gap: 10px; }
    .campo-control { border: 1px solid #ddd; border-radius: 4px; padding: 10px; background-color: white; }
    .campo-titulo { font-weight: bold; margin-bottom: 10px; text-align: center; }
    .input-row { display: flex; justify-content: space-between; margin-bottom: 8px; }
    .input-group-text { font-size: 12px; width: 30px; justify-content: center; }
</style>

@php
    use Illuminate\Support\Facades\DB;
    $item = $datos[0];
    $elementos = DB::table('certificado_elemento')->where('id_certificado', $item->id_certificado)->get();
@endphp

<div class="editor-wrapper">
    <div id="editor-container">
        <img id="editor-background" src="{{ asset('modelo_certificados/' . $item->modelo) }}" alt="Modelo Certificado">

        @foreach ($elementos as $el)
            @php
                $texto = $el->contenido ?? strtoupper($el->nombre);
                $tamano = $el->tamano_fuente ?? 12;
                $ancho = $el->forzar_ancho ?? ($el->tipo === 'imagen' ? $el->ancho : mb_strlen($texto) * $tamano * 0.6 / 3.78);
                $alto = $el->tipo === 'imagen' ? $el->alto : round($tamano * 0.35, 1);
            @endphp

            <div class="campo-editable"
                 id="campo-{{ $el->nombre }}"
                 data-campo="{{ $el->nombre }}"
                 data-tipo="{{ $el->tipo }}"
                 data-x="{{ $el->x }}"
                 data-y="{{ $el->y }}"
                 data-w="{{ $ancho }}"
                 data-h="{{ $alto }}"
                 data-f="{{ $tamano }}"
                 data-fuente="{{ $el->fuente }}"
                 data-forzar="{{ $el->forzar_ancho }}"
                 style="left:{{ $el->x }}mm; top:{{ $el->y }}mm; width:{{ $ancho }}mm; height:{{ $alto }}mm; font-size:{{ $tamano }}pt; font-family:{{ $el->fuente }};">
                 {{ $texto }}
            </div>
        @endforeach
    </div>

    <div class="panel-control">
        <input type="hidden" id="certificado_id" value="{{ $item->id_certificado }}">
        <div class="campos-container">
            @foreach ($elementos as $el)
                <div class="campo-control" id="control-{{ $el->nombre }}">
                    <div class="campo-titulo">{{ strtoupper($el->nombre) }}</div>
                    <div class="input-row">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">X:</span>
                            <input type="number" step="0.1" class="form-control campo-input" data-campo="{{ $el->nombre }}" data-prop="x" value="{{ $el->x }}">
                        </div>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">Y:</span>
                            <input type="number" step="0.1" class="form-control campo-input" data-campo="{{ $el->nombre }}" data-prop="y" value="{{ $el->y }}">
                        </div>
                    </div>
                    @if ($el->tipo === 'imagen')
                        <div class="input-row">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text">An:</span>
                                <input type="number" step="0.1" class="form-control campo-input" data-campo="{{ $el->nombre }}" data-prop="w" value="{{ $el->ancho }}">
                            </div>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text">Al:</span>
                                <input type="number" step="0.1" class="form-control campo-input" data-campo="{{ $el->nombre }}" data-prop="h" value="{{ $el->alto }}">
                            </div>
                        </div>
                    @else
                        <div class="input-group input-group-sm mt-2">
                            <span class="input-group-text">Tamaño:</span>
                            <input type="number" step="0.1" class="form-control campo-input" data-campo="{{ $el->nombre }}" data-prop="f" value="{{ $tamano }}">
                        </div>
                        <div class="input-group input-group-sm mt-2">
                            <span class="input-group-text">Fuente:</span>
                            <select class="form-control campo-input" data-campo="{{ $el->nombre }}" data-prop="fuente">
                                @php
                                    $fuentes = ['Arial', 'Times New Roman', 'Georgia', 'Comic Sans MS', 'Verdana', 'Aegean', 'Symphony'];
                                @endphp
                                @foreach ($fuentes as $fuente)
                                    <option value="{{ $fuente }}" {{ $el->fuente === $fuente ? 'selected' : '' }}>{{ $fuente }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="input-group input-group-sm mt-2">
                            <span class="input-group-text">Ancho fijo:</span>
                            <input type="number" step="0.1" class="form-control campo-input" data-campo="{{ $el->nombre }}" data-prop="forzar" value="{{ $el->forzar_ancho }}">
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/interactjs/dist/interact.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const scaleFactor = 0.8;
    const certificadoId = document.getElementById('certificado_id').value;

    function guardarPosicion(element) {
        const data = {
            id: certificadoId,
            campo: element.dataset.campo,
            tipo: element.dataset.tipo,
            x: parseFloat(element.dataset.x),
            y: parseFloat(element.dataset.y),
            w: parseFloat(element.dataset.w),
            h: parseFloat(element.dataset.h),
            f: parseFloat(element.dataset.f),
            fuente: element.dataset.fuente,
            forzar: parseFloat(element.dataset.forzar) || null
        };

        fetch("{{ route('certificado.guardarPosicion') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(data)
        });
    }

    function medirTexto(texto, fuente, tamanoPt) {
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        ctx.font = `${tamanoPt}pt ${fuente}`;
        const widthPx = ctx.measureText(texto).width;
        return widthPx / 3.78;
    }

    function ajustarAnchoTexto(campo) {
        const forzado = campo.dataset.forzar;
        if (forzado) {
            campo.style.width = `${forzado}mm`;
            campo.dataset.w = forzado;
        } else {
            const texto = campo.innerText.trim();
            const fuente = campo.dataset.fuente;
            const tamano = parseFloat(campo.dataset.f);
            const nuevoAncho = medirTexto(texto, fuente, tamano);
            campo.style.width = `${nuevoAncho.toFixed(1)}mm`;
            campo.dataset.w = nuevoAncho.toFixed(1);
        }
        guardarPosicion(campo);
        actualizarInputs(campo);
    }

    function actualizarInputs(campo) {
        const props = ['x', 'y', 'w', 'h', 'f', 'fuente', 'forzar'];
        props.forEach(prop => {
            const input = document.querySelector(`.campo-input[data-campo="${campo.dataset.campo}"][data-prop="${prop}"]`);
            if (input) input.value = campo.dataset[prop];
        });
    }

    document.querySelectorAll('.campo-editable').forEach(campo => {
        if (campo.dataset.tipo === 'texto') ajustarAnchoTexto(campo);

        interact(campo).draggable({
            modifiers: [interact.modifiers.restrictRect({ restriction: '#editor-container', endOnly: true })],
            listeners: {
                move(event) {
                    const x = (parseFloat(campo.dataset.x) || 0) + (event.dx / scaleFactor / 3.78);
                    const y = (parseFloat(campo.dataset.y) || 0) + (event.dy / scaleFactor / 3.78);
                    campo.style.left = `${x}mm`;
                    campo.style.top = `${y}mm`;
                    campo.dataset.x = x.toFixed(1);
                    campo.dataset.y = y.toFixed(1);
                    guardarPosicion(campo);
                    actualizarInputs(campo);
                }
            }
        });

        if (campo.dataset.tipo === 'imagen') {
            interact(campo).resizable({
                edges: { left: true, right: true, bottom: true, top: true },
                listeners: {
                    move(event) {
                        const w = (event.rect.width / scaleFactor / 3.78).toFixed(1);
                        const h = (event.rect.height / scaleFactor / 3.78).toFixed(1);
                        campo.style.width = `${w}mm`;
                        campo.style.height = `${h}mm`;
                        campo.dataset.w = w;
                        campo.dataset.h = h;
                        guardarPosicion(campo);
                        actualizarInputs(campo);
                    }
                }
            });
        }
    });

    document.querySelectorAll('.campo-input').forEach(input => {
        input.addEventListener('change', function () {
            const campo = document.getElementById(`campo-${this.dataset.campo}`);
            const prop = this.dataset.prop;
            campo.dataset[prop] = this.value;

            if (prop === 'x') campo.style.left = `${this.value}mm`;
            if (prop === 'y') campo.style.top = `${this.value}mm`;
            if (prop === 'w') campo.style.width = `${this.value}mm`;
            if (prop === 'h') campo.style.height = `${this.value}mm`;
            if (prop === 'f') {
                campo.style.fontSize = `${this.value}pt`;
                campo.style.height = `${(parseFloat(this.value) * 0.35).toFixed(1)}mm`;
                campo.dataset.h = (parseFloat(this.value) * 0.35).toFixed(1);
            }
            if (prop === 'fuente') campo.style.fontFamily = this.value;

            ajustarAnchoTexto(campo);
        });
    });
});
</script>
@endsection
