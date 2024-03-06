let calendar;
document.addEventListener("DOMContentLoaded", function () {
    //arrastrar eventos predefinidos
    new FullCalendar.Draggable(document.getElementById('listaeventospredefinidos'), {
        itemSelector: '.fc-event',
        eventData: function (eventEl) {
            return {
                title: eventEl.innerText.trim()
            }
        }
    });

    $('.clockpicker').clockpicker();
    let calendarEl = document.getElementById("calendar");
    calendar = new FullCalendar.Calendar(calendarEl, {
        headerToolbar: {
            left: 'prev next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        locale: 'es',
        droppable: true,
        editable: true,
        initialView: "dayGridMonth",
        timeZone: 'UTC',
        eventSources: [
            {
                url: '../php/datoseventos.php?accion=listar',
                events: [{
                    backgroundColor: 'yellow'
                }]
            }
        ],
        eventTimeFormat: {
            // Formato de hora para los eventos
            hour: "2-digit",
            minute: "2-digit",
            hour12: false,
        },
        dateClick: function (info) {
            limpiarFormulario();
            $('#BotonAgregar').show();
            $('#BotonModificar').hide();
            $('#BotonBorrar').hide();

            if (info.allDay) {
                $('#FechaInicio').val(info.dateStr);
                $('#FechaFin').val(info.dateStr);
            } else {
                let fechaHora = info.dateStr.split("T");
                $('#FechaInicio').val(fechaHora[0]);
                $('#FechaFin').val(fechaHora[0]);
                $('#HoraInicio').val(fechaHora[1].substring(0, 5));
            }

            $('#FormularioEventos').modal('show');
        },
        eventClick: function (info) {
            $('#BotonAgregar').hide();

            let usuarioCreadorEvento = info.event.extendedProps.usuario || 'Anónimo';
            let usuarioLogueado = $('#userName').text();
            
            $('#Id').val(info.event.id);
            $('#Created').text('@' + usuarioCreadorEvento);
            $('#Titulo').val(info.event.title);
            $('#Descripcion').val(info.event.extendedProps.descripcion);
            $('#FechaInicio').val(moment(info.event.start).format('YYYY-MM-DD'));
            $('#FechaFin').val(moment(info.event.end).format('YYYY-MM-DD'));
            $('#HoraInicio').val(moment.utc(info.event.start).format('HH:mm'));
            $('#HoraFin').val(moment.utc(info.event.end).format('HH:mm'));
            $('#ColorFondo').val(info.event.backgroundColor);
            $('#ColorTexto').val(info.event.textColor);

            if (usuarioCreadorEvento === usuarioLogueado) {
                $('#BotonModificar').show();
                $('#BotonBorrar').show();
            } else {
                $('#BotonModificar').hide();
                $('#BotonBorrar').hide();
            }

            $('#FormularioEventos').modal('show');
        },
        eventResize: function (info) {
            $('#Id').val(info.event.id);
            $('#Titulo').val(info.event.title);
            $('#Descripcion').val(info.event.extendedProps.descripcion);
            $('#FechaInicio').val(moment(info.event.start).format('YYYY-MM-DD'));
            $('#FechaFin').val(moment(info.event.end).format('YYYY-MM-DD'));
            $('#HoraInicio').val(moment.utc(info.event.start).format('HH:mm'));
            $('#HoraFin').val(moment.utc(info.event.end).format('HH:mm'));
            $('#ColorFondo').val(info.event.backgroundColor);
            $('#ColorTexto').val(info.event.textColor);
            let registro = recuperarDatosFormulario();
            modificarRegistro(registro);
        },
        eventDrop: function (info) {
            $('#Id').val(info.event.id);
            $('#Titulo').val(info.event.title);
            $('#Descripcion').val(info.event.extendedProps.descripcion);
            $('#FechaInicio').val(moment(info.event.start).format('YYYY-MM-DD'));
            $('#FechaFin').val(moment(info.event.end).format('YYYY-MM-DD'));
            $('#HoraInicio').val(moment.utc(info.event.start).format('HH:mm'));
            $('#HoraFin').val(moment.utc(info.event.end).format('HH:mm'));
            $('#ColorFondo').val(info.event.backgroundColor);
            $('#ColorTexto').val(info.event.textColor);
            let registro = recuperarDatosFormulario();
            modificarRegistro(registro);
        },
        drop: function (info) {
            limpiarFormulario();
            $('#ColorFondo').val(info.draggedEl.dataset.colorfondo);
            $('#ColorTexto').val(info.draggedEl.dataset.colortexto);
            $('#Titulo').val(info.draggedEl.dataset.titulo);
            let fechaHora = info.dateStr.split("T");
            $('#FechaInicio').val(fechaHora[0]);
            $('#FechaFin').val(fechaHora[0]);
            if (info.allDay) {
                $('#HoraInicio').val(info.draggedEl.dataset.horainicio);
                $('#HoraFin').val(info.draggedEl.dataset.horafin);
            } else {
                $('#HoraInicio').val(fechaHora[1].substring(0, 5));
                $('#HoraFin').val(moment(fechaHora[1].substring(0, 5)).add(1, 'hours'));
            }
            let registro = recuperarDatosFormulario();
            agregarEventoPredefinido(registro);
        }
    });
    calendar.render();
});

//   eventos de botones de la aplicacion
$('#BotonAgregar').click(function () {
    let registro = recuperarDatosFormulario();
    agregarRegistro(registro);
});

//Boton Modificar
$('#BotonModificar').click(function () {
    let registro = recuperarDatosFormulario();
    modificarRegistro(registro);
});

//Boton Borrar
$('#BotonBorrar').click(function () {
    let registro = recuperarDatosFormulario();
    borrarRegistro(registro);
});

//Boton evento predefinido
$('#BotonEventosPredefinidos').click(function () {
    window.location = "../eventospredefinidos.html";
});

// funciones para comunicarse con el servidor AJAX!
function agregarRegistro(registro) {
    $.ajax({
        type: 'POST',
        url: '../php/datoseventos.php?accion=agregar',
        data: registro,
        success: function (msg) {
            calendar.refetchEvents();
            $('#FormularioEventos').modal('hide');
        },
        error: function (error) {
            alert('Error al agregar evento: ' + error);
        }
    })
}

//Funcion Modificar
function modificarRegistro(registro) {
    $.ajax({
        type: 'POST',
        url: '../php/datoseventos.php?accion=modificar',
        data: registro,
        success: function (msg) {
            calendar.refetchEvents();
            $('#FormularioEventos').modal('hide');
        },
        error: function (error) {
            alert('Error al modificar el evento: ' + error);
        }
    })
}

//Funcion Borrar
function borrarRegistro(registro) {
    $.ajax({
        type: 'POST',
        url: '../php/datoseventos.php?accion=borrar',
        data: registro,
        success: function (msg) {
            calendar.refetchEvents();
            $('#FormularioEventos').modal('hide');
        },
        error: function (error) {
            alert('Error al borrar el evento: ' + error);
        }
    })
}

function agregarEventoPredefinido(registro) {
    $.ajax({
        type: 'POST',
        url: '../php/datoseventos.php?accion=agregar',
        data: registro,
        success: function (msg) {
            calendar.removeAllEvents();
            calendar.refetchEvents();
            $('#FormularioEventos').modal('hide');
        },
        error: function (error) {
            alert('Error al agregar el evento: ' + error);
        }
    })
}

// funciones que interactuan con el FormularioEventos
function limpiarFormulario() {
    $('#Id').val('');
    $('#Created').text('');
    $('#Titulo').val('');
    $('#FechaInicio').val('');
    $('#HoraInicio').val('');
    $('#FechaFin').val('');
    $('#HoraFin').val('');
    $('#Descripcion').val('');
    $('#ColorFondo').val('#3788D8');
    $('#ColorTexto').val('#ffffff');
}

function recuperarDatosFormulario() {
    let registro = {
        id: $('#Id').val(),
        usuario: $('#userCreated').val(),
        usuario: $('#userName').val(),
        titulo: $('#Titulo').val(),
        descripcion: $('#Descripcion').val(),
        inicio: $('#FechaInicio').val() + ' ' + $('#HoraInicio').val(),
        fin: $('#FechaFin').val() + ' ' + $('#HoraFin').val(),
        colorfondo: $('#ColorFondo').val(),
        colortexto: $('#ColorTexto').val(),
    }
    return registro;
}