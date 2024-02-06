document.addEventListener('DOMContentLoaded', function () {

    $('.clockpicker').clockpicker();

    let tabla1 = $('#tabla1').DataTable({
        "ajax": {
            url: '/Departamento-de-Informatica/php/datospredefinidos.php?accion=listar',
            dataSrc: ""
        },
        "columns": [
            {
                "data": "id"
            },
            {
                "data": "titulo"
            },
            {
                "data": "colortexto"
            },
            {
                "data": "colorfondo"
            },
            {
                "data": "horainicio"
            },
            {
                "data": "horafin"
            },
            {
                "data": null,
                "orderable": false
            }
        ],
        columnDefs: [{
            targets: -1,
            className: 'dt-body-center',
            "defaultContent": "<button class='btn btn-sm btn-danger botonborrar'> Borrar </button>",
            data: null
        },
        {
            targets: 1,
            className: 'dt-body-center',

        },
        {
            targets: 2,
            className: 'dt-body-center',
        }],
        "language": {
            "url": "datatables/spanish.json"
        },
        'rowCallback': function (row, data, index) {
            $(row).find('td:eq(1)').css('color', data.colortexto);
            $(row).find('td:eq(1)').css('background-color', data.colorfondo);
        },
        "lengthMenu": [
            [10, 25, 50, -1],
            [10, 25, 50, "Todos"]
        ],
    });
    // Eventos de botones de la App

    $('#BotonAgregar').click(function () {
        $('#FormularioEventosPredefinidos').modal('show');
        limpiarFormulario();
    })

    $('#BotonSalir').click(function () {
        window.location = '/Departamento-de-Informatica/php/departamento.php';
    })

    //Agregar evento predefinido
    $('#BotonConfirmarAgregar').click(function () {
        let registro = recuperarDatosFormulario();
        agregarRegistro(registro);
    });

    //Boton Borrar
    $('#BotonBorrar').click(function () {
        let registro = recuperarDatosFormulario();
        borrarRegistro(registro);
    });

    //Funciones para comunicarse con el server via AJAX
    function agregarRegistro(registro) {
        $.ajax({
            type: 'POST',
            url: '/Departamento-de-Informatica/php/datospredefinidos.php?accion=agregar',
            data: registro,
            success: function (msg) {
                tabla1.ajax.reload();
                $('#FormularioEventosPredefinidos').modal('hide');
            },
            error: function (error) {
                console.error('que error muestra?', error);
                alert('hubo un error al agregar un evento' + error);
            }
        });
    }

    $('#tabla1 tbody').on('click', 'button.botonborrar', function () {
        let evento = tabla1.row($(this).parents('tr')).data();
        if (confirm("¿Seguro que quieres eliminar el evento " + evento.titulo + "?")) {
            let registro = tabla1.row($(this).parents('tr')).data();
            borrarRegistro(registro);
        }
    });

    function borrarRegistro(registro) {
        $.ajax({
            type: 'POST',
            url: '/Departamento-de-Informatica/php/datospredefinidos.php?accion=borrar',
            data: registro,
            success: function (msg) {
                tabla1.ajax.reload();
            },
            error: function (error) {
                alert("Hubo un error al borrar el evento: " + error);
            }
        });
    }

    // Funciones para el formulario de eventos
    function limpiarFormulario() {
        $('#Titulo').val('');
        $('#HoraInicio').val('');
        $('#HoraFin').val('');
        $('#ColorFondo').val('#3788D8');
        $('#ColorTexto').val('#ffffff');
    }

    function recuperarDatosFormulario() {
        let registro = {
            titulo: $('#Titulo').val(),
            horainicio: $('#HoraInicio').val(),
            horafin: $('#HoraFin').val(),
            colorfondo: $('#ColorFondo').val(),
            colortexto: $('#ColorTexto').val(),
        }
        return registro;
    }

});