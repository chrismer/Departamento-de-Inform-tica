document.addEventListener('DOMContentLoaded', function () {

    //Funciones de botones
    $('#NuevoUsuario').click(function () {
        borrarFormulario();
        $('#FormNuevoUsuario').modal('show');
    });

    $('#BotonAgregarUsuario').click(function () {
        let nuevoUsuario = retornarNuevoUsuario();
        if (validarEntradaDatos(nuevoUsuario)) {
            agregarNuevoUsuario(nuevoUsuario);
        }
    });

    $('#BotonLogin').click(function () {
        let usuario = retornarUsuario();
        loginUsuario(usuario);
    });

    //Funciones de Formulario
    function borrarFormulario() {
        $('#NombreNuevo').val('');
        $('#Clave1').val('');
        $('#Clave2').val('');
    }

    function retornarNuevoUsuario() {
        let nuevoUsuario = {
            nombrenuevo: $('#NombreNuevo').val(),
            clave1: $('#Clave1').val(),
            clave2: $('#Clave2').val()
        }
        return nuevoUsuario;
    }

    function retornarUsuario() {
        let usuario = {
            usuario: $('#Usuario').val(),
            password: $('#Password').val()
        }
        return usuario;
    }

    function validarEntradaDatos(nuevousuario) {
        if (nuevousuario.nombrenuevo == '') {
            alert('debes ingresar un nombre de usuario')
            return false;
        }

        if (nuevousuario.clave1 == '') {
            alert('debes ingresar una contraseña')
            return false;
        }

        if (nuevousuario.clave1 != nuevousuario.clave2) {
            alert('las claves ingresadas no coinciden');
            return false;
        }
        return true;
    }

    //Funciones AJAX

    function loginUsuario(usuario) {
        $.ajax({
            type: 'POST',
            url: 'php/login.php',
            data: usuario,
            success: function (respuesta) {
                if (respuesta == 'Correcto') {
                    window.location = 'php/departamento.php';
                } else {
                    alert('Nombre o Contraseña incorrectas');
                }
            },
            error: function () {
                alert('hubo un error');
            }
        });
    }

    function agregarNuevoUsuario(nuevousuario) {
        $.ajax({
            type: 'POST',
            url: 'php/datosusuarios.php?accion=existe',
            data: nuevousuario,
            success: function (info) {
                if (info.resultado == 'norepetido') {
                    $.ajax({
                        type: 'POST',
                        url: 'php/datosusuarios.php?accion=agregar',
                        data: nuevousuario,
                        success: function () {
                            alert('se agrego el nuevo usuario')
                            $('#FormNuevoUsuario').modal('hide');
                        },
                        error: function (err) {
                            console.log('error de q?', err);
                            alert('ocurrio un error');
                        }
                    });
                } else {
                    alert('usuario existente');
                }
            },
            error: function () {
                alert('ocurrio un error');
            }
        });
    };
});