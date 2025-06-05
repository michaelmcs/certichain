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

    table {
        width: 600px;
        max-width: 600px;
    }

    @media screen and (max-width:680px) {
        table {
            width: 100%;
            max-width: none;
        }
    }
</style>

@section('resultado')
    @if (session('MENSAJE'))
        {!! session('MENSAJE') !!}
    @endif

    <table class="table">
        <thead class="table-dark">
            <tr>
                <th>N°</th>
                <th>DETALLES</th>
                <th>Descargar</th>
                <th>Acción</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($datosCert as $key => $item5)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>
                        <h6>{{ $item5->curso }}</h6>
                        <span class="badge text-bg-warning p-2">Código: {{ $item5->codigo }}</span>
                        <span class="badge text-bg-success p-2">{{ $item5->participo_como }}</span>
                    </td>
                    <td>
                        <a href="{{ route('busqueda.ver', ['participante' => $item5->id_participante, 'codigo' => $item5->cod_verificacion]) }}"
                           class="btn btn-danger">
                            <i class="fa-solid fa-file-pdf"></i>
                        </a>
                    </td>
                    <td>
                        <button class="btn btn-info ver-pdf"
                                data-certificado-id="{{ $item5->id_participante }}"
                                data-bs-toggle="modal"
                                data-bs-target="#staticBackdrop{{ $item5->id_participante }}">
                            Enviar código
                        </button>
                    </td>
                </tr>

                <!-- Modal -->
                <div class="modal fade" id="staticBackdrop{{ $item5->id_participante }}" data-bs-backdrop="static"
                     data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content p-3">
                            <div class="modal-header">
                                <h4 class="modal-title fs-5" id="staticBackdropLabel">Ver mi Certificado</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="alert" data-id="enviando-correo">Enviando correo...</div>
                                <p><b>Sr. {{ $item5->nombre }} {{ $item5->apellido }}</b><br>
                                    Se ha enviado un correo a: <span class="text-primary">{{ $item5->correo }}</span>
                                    indicando el <b>CÓDIGO</b> a emplear
                                </p>
                            </div>
                            <form id="form-{{ $item5->id_participante }}" class="formulario">
                                <input type="hidden" name="participante" value="{{ $item5->id_participante }}">
                                <input type="hidden" name="curso" value="{{ $item5->id_curso }}">

                                <div class="p-2 form-group">
                                    <label><b>Ingrese el código aquí</b></label>
                                    <input type="text" class="form-control border-1" name="txtcodigo">
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                    <button type="submit" class="btn btn-primary continuar">Continuar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </tbody>
    </table>

    <!-- JS para enviar correo -->
    <script>
        document.querySelectorAll('.ver-pdf').forEach(btn => {
            btn.addEventListener('click', () => {
                const certificadoId = btn.getAttribute('data-certificado-id');
                const modal = document.querySelector(`#staticBackdrop${certificadoId}`);
                const alertBox = modal.querySelector('[data-id]');

                alertBox.textContent = "Enviando correo...";
                alertBox.classList.remove("alert-success", "alert-danger");

                const ruta = "{{ url('enviarCorreo') }}/" + certificadoId;

                fetch(ruta)
                    .then(res => res.json())
                    .then(data => {
                        alertBox.textContent = data.mensaje;
                        alertBox.classList.add(data.mensaje === "Correo enviado correctamente" ? "alert-success" : "alert-danger");
                    })
                    .catch(() => {
                        alertBox.textContent = "Error al enviar el correo";
                        alertBox.classList.add("alert-danger");
                    });
            });
        });
    </script>

    <!-- JS para validar el código -->
    <script>
        $(document).ready(function () {
            $('.formulario').on('submit', function (e) {
                e.preventDefault();
                const formData = $(this).serializeArray();
                let participante, curso, txtcodigo;

                formData.forEach(item => {
                    if (item.name === 'participante') participante = item.value;
                    if (item.name === 'curso') curso = item.value;
                    if (item.name === 'txtcodigo') txtcodigo = item.value;
                });

                if (!txtcodigo) {
                    alert("Ingrese el código, por favor");
                    return;
                }

                const url = "{{ route('correo.enviarCodigo', ['participante' => ':participante', 'curso' => ':curso', 'codigo' => ':codigo']) }}"
                    .replace(':participante', encodeURIComponent(participante))
                    .replace(':curso', encodeURIComponent(curso))
                    .replace(':codigo', encodeURIComponent(txtcodigo));

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function (data) {
                        if (data.mensaje === "success") {
                            const redirect = "{{ route('busqueda.ver', [':participante', ':codigo']) }}"
                                .replace(':participante', data.id_participante)
                                .replace(':codigo', data.codigo);
                            window.location.href = redirect;
                        } else {
                            alert("El código ingresado no es correcto");
                        }
                    },
                    error: function () {
                        alert("Error al validar el código");
                    }
                });
            });
        });
    </script>
@endsection
