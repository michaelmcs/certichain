$(document).on("click", "a.eliminar", function (event) {
    event.preventDefault(); // Evita que se siga el enlace
    var form = $(this).prev(".formulario-eliminar"); // Obtiene el formulario anterior al botón
    var url = form.attr("action"); // Obtiene la URL de la ruta de eliminación
    var csrf_token = $('meta[name="csrf-token"]').attr("content"); // Obtiene el token CSRF

    Swal.fire({
        title: "¿Está seguro?",
        text: "¡ No podrá recuperar este registro !",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#2CB073",
        cancelButtonColor: "#d33",
        confirmButtonText: "<i class='fas fa-check'></i> Si, Eliminar",
        cancelButtonText: "<i class='fas fa-times'></i> No, Salir",
        reverseButtons: true,
        padding: "20px",
        backdrop: true,
        position: "top",
        allowOutsideClick: true,
        allowEscapeKey: true,
        allowEnterKey: false,
    }).then((confirm) => {
        if (confirm.isConfirmed) {
            // Agrega el token CSRF al formulario
            form.append(
                '<input type="hidden" name="_token" value="' + csrf_token + '">'
            );
            form.submit(); // Envía el formulario
        }
    });
});
