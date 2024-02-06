<?php

session_start();
if(!isset($_SESSION['usuario'])){
  header("Location:/login.html");
  exit(0);
}

?>

<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8" />
    <link rel="stylesheet" type="text/css" href="../css/style.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>DEPARTAMENTO DE INFORMATICA SAFA</title>
    <link
      href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;700&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="../css/datatables.min.css" />
    <link rel="stylesheet" href="../css/bootstrap-clockpicker.css" />
    <link rel="stylesheet" href="../css/fullcalendar.min.css" />
    <style>
      .fc-event{
        background-color: transparent;
        color: black;
        border-color: transparent;
      }
      .fc-event:hover{
        background-color:skyblue;
      }
    </style>
    <script src="../js/datatables.min.js"></script>
    <script src="../js/moment-with-locales.js"></script>
    <script src="../js/jquery-3.7.1.min.js"></script>
    <script src="../js/popper.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
    <script src="../js/bootstrap-clockpicker.js"></script>
    <script src="../js/es.global.js"></script>
  </head>
  <body class="cuerpo">
    <div id="contenido">
      <header>
        <hgroup>
          <img src="../img/safa2.png" alt="" width="80" height="80" align="left" />
          <h1>Colegio Sagrada Familia Tandil - Nivel Secundario 2</h1>
        </hgroup>
        <nav>
          <ul>
            <li><a href="../index.html">Inicio</a></li>
            <li><a href="../galeria.html">Galeria</a></li>
            <li><a href="../recursero.html">Recursero</a></li>
            <li><a href="../login.html">Departamento</a></li>
          </ul>
        </nav>
      </header>
      <section>
        <div id="textoprincipal3">
          <h4>
            En este calendario puedes reservar el material de trabajo o revisar si está disponible
          </h4>
        </div>
      </section>
    </div>
    <br />
    <div class="container">
        <!-- Panel de control -->
        <div class="container-fluid">
          <section class="content-header d-flex justify-content-between align-items-center">
            <h1>Calendario</h1>
            <h4>Hola @<strong><?php echo $_SESSION['usuario']; ?></strong> </h4>
            <h4><a href="logout.php" class="btn btn-outline-danger">Cerrar Sesion</a></h4>
          </section>
          <div class="row">
            <div class="col-10">
              <!-- FullCalendar -->
              <div id="calendar"></div>
            </div>
            <div class="col-2">
              <div class="" id="external-events" style="margin-bottom: 1em; height: 350px; width:260px; border: 1px solid #000; overflow: auto; padding: 1em">
                <h4 class="text-center">Eventos predefinidos</h4>
                <div id="listaeventospredefinidos">
                  <?php
                    require("conexion.php");
                    $conexion = regresarConexion();
                    $datos = mysqli_query($conexion, "SELECT id,titulo,horainicio,horafin,colortexto,colorfondo FROM eventospredefinidos");
                    $ep = mysqli_fetch_all($datos, MYSQLI_ASSOC);
                    foreach($ep as $fila){
                        echo "<div class='fc-event' data-titulo='$fila[titulo]' data-horafin='$fila[horafin]' data-horainicio='$fila[horainicio]' data-colorfondo='$fila[colorfondo]' data-colortexto='$fila[colortexto]'
                        style='border-color:$fila[colorfondo];color:$fila[colortexto];background-color:$fila[colorfondo];margin:10px'>
                        $fila[titulo] [" .substr($fila['horainicio'],0,5) . " a " .substr($fila['horafin'],0,5) . "]</div>";
                    }
                  ?>
                </div>
              </div>
              <hr>
              <div class="" style="text-align: center">
                <button type="button" id="BotonEventosPredefinidos" class="btn btn-success">Administrar eventos</button>
              </div>
            </div>
          </div>
        </div>


        <!-- formulario de eventos -->
        <div class="modal fade" id="FormularioEventos" tabindex="-1" role="dialog">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <input type="hidden" id="Id">
                <div class="form-row">
                  <div class="form-group col-md-12">
                    <label for="">Titulo del Evento:</label>
                    <input type="text" id="Titulo" class="form-control" placeholder="">
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-6">
                    <label for="">Fecha de inicio:</label>
                    <div class="input-group" data-autoclose="true">
                      <input type="date" class="form-control" value="" id="FechaInicio">
                    </div>
                  </div>
                  <div class="form-group col-md-6" id="TituloHoraInicio">
                    <label for="">Hora de inicio:</label>
                    <div class="input-group clockpicker" data-autoclose="true">
                      <input type="text" class="form-control" value="" id="HoraInicio" autocomplete="off">
                    </div>
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-6">
                    <label for="">Fecha de finalizacion:</label>
                    <div class="input-group" data-autoclose="true">
                      <input type="date" class="form-control" value="" id="FechaFin">
                    </div>
                  </div>
                  <div class="form-group col-md-6" id="TituloHoraFin">
                    <label for="">Hora de finalizacion:</label>
                    <div class="input-group clockpicker" data-autoclose="true">
                      <input type="text" class="form-control" value="" id="HoraFin" autocomplete="off">
                    </div>
                  </div>
                </div>
                <div class="form-row">
                  <label for="">Descripcion:</label>
                  <textarea id="Descripcion" class="form-control" rows="3"></textarea>
                </div>
                <div class="form-row">
                  <label for="">Color de fondo:</label>
                  <input type="color" value="#3788D8" id="ColorFondo" class="form-control" style="height: 36px;">
                </div>
                <div class="form-row">
                  <label for="">Color de Texto:</label>
                  <input type="color" value="#FFFFFF" id="ColorTexto" class="form-control" style="height: 36px;">
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" id="BotonAgregar" class="btn btn-success">Agregar</button>
                <button type="button" id="BotonModificar" class="btn btn-warning">Modificar</button>
                <button type="button" id="BotonBorrar" class="btn btn-danger">Borrar</button>
                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancelar</button>
              </div>
            </div>
          </div>
        </div>      
    </div>
    <footer id="contacto">
      <br />

      <p>Cosmovisión centrada en Cristo</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
    <script src="../js/calendario.js"></script>
  </body>
</html>
