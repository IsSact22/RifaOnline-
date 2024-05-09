<!-- consulta.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link  href="assets/styles.css" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Consulta de Participantes</title>
</head>
<body>
    

<div class="container">
    <img src="img/Banner2015_2.jpg  " alt="Banner" class="full-width-img">
    <hr>
    <h2 class="custom-title">Consulta de participantes</h2><br>
    <div id="consulta-container">
        <!-- Formulario de consulta de participantes -->
        <form id="consulta-form" action="consultar.php" method="GET">
            <label for="cedula">Ingrese su número de cédula:</label>
            <input type="text" id="cedula" name="cedula" required>
            <button type="submit">Buscar</button>

           
        </form>
        <br>
        <div id="resultado-consulta"></div>
                <?php
                include 'conexion.php';
                ?>
</div>
    

<!-- Modal de confirmación -->
<div class="modal fade" id="confirmacionModal" tabindex="-1" aria-labelledby="confirmacionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmacionModalLabel">Confirmación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="confirmacionMensaje"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Aceptar</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal de confirmación -->
<div class="modal fade" id="revisionModal" tabindex="-1" aria-labelledby="revisionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="revisionModalLabel">Revision</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="confirmacionMensaje"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Aceptar</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal de error -->
<div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="errorModalLabel">Error</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="errorMensaje"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Aceptar</button>
            </div>
        </div>
    </div>
</div>





<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<!-- <script>
    function mostrarConfirmacion(mensaje) {
        $('#confirmacionMensaje').text(mensaje);
        $('#confirmacionModal').modal('show');
    }

    // Ejemplo de uso:
    $(document).ready(function() {
        // Llamada a la función mostrarConfirmacion con un mensaje de ejemplo
        mostrarConfirmacion('La consulta ha sido confirmada y almacenada correctamente.');
    });
</script> -->


    <script>
        // Manejar el envío del formulario de consulta con AJAX
        document.getElementById('consulta-form').addEventListener('submit', function(event) {
            event.preventDefault(); // Evitar la recarga de la página
            var form = event.target;
            var formData = new FormData(form);

            // Realizar la consulta con AJAX
            var xhr = new XMLHttpRequest();
            xhr.open('GET', form.action + '?' + new URLSearchParams(formData).toString());
            xhr.onload = function() {
                if (xhr.status === 200) {
                    document.getElementById('resultado-consulta').innerHTML = xhr.responseText;
                } else {
                    console.error('Error al realizar la consulta:', xhr.statusText);
                }
            };
            xhr.send();
        });
    </script>
</body>
</html>
