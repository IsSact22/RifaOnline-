<!DOCTYPE html>
<!-- formulario.php -->
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link  href="assets/styles.css" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="img/logo_ca.png">
    <title>Rifa CAPRES-TSJ</title>
    
</head>
<body>
    <div class="container">
    <img src="img/Banner2015_2.jpg  " alt="Banner" class="full-width-img">
    <h2 class="custom-title">RIFA DIA DE LAS MADRES</h2>
        <br>
            <p class="custom-P">CAPRESTSJ, te invita participar en el primer sorteo, donde ganarás fabulosos premios:</p>
            <p class="custom-P">
               <ul class="custom-P " >
               • 1er Premio: Un televisor 50” + XXX+XXX <br>
               • 2do Premio: XXX+XXX+XXX <br>
               • 3er Premio: XXX + XXX + XXX
               </ul>
            </p>
            <p  class="custom-P "><b>EL SORTEO SE REALIZARÁ EL DÍA 10 DE MAYO DE 2024, POR LA LOTERIA </b></p>
            <p><b>Condiciones</b> </p>
            <p>
                <ul>
                    • Costo por acción Bs.80 <br>
                    • Los boletos no cobrados, NO PARTICIPAN. <br>
                    •Acepta que el monto total de los números adquiridos, sera debitado de su cuenta bancaria del Banco de Venezuela la segunda quincena de abril (30/04/2024). 
                </ul>
            </p>

            <p><b>Al participar en la rifa, aceptas cumplir con todas estas reglas y condiciones. ¡Buena suerte!</b></p>
        </p>
    <div class="formulario">
                    <p></p>
                        <?php
                            include 'conexion.php';
                            ?>
            
            <form action="procesar.php" method="post">
            
            <div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="nombre" class="custom-label">Nombre:</label>
            <input type="text" id="nombre" name="nombre" class="custom-input" required placeholder="Ingrese su nombre">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="apellido" class="custom-label">Apellido:</label>
            <input type="text" id="apellido" name="apellido" class="custom-input" required placeholder="Ingrese su apellido">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="cedula" class="custom-label">Cédula:</label>
            <input type="text" id="cedula" name="cedula" class="custom-input" pattern="[0-8]+" title="Ingrese solo números" required placeholder="Ingresar 8 dígitos">
                <ul class="CLU">
                    <li class="CLI">
                        <div class="conts">
                            <h3>Nota:</h3>
                            <p>Debera completar esta casilla con 8 digitos, si su cedula es de 7 digitos 
                            por favor incluir un cero (0) al principio de la cedula </p>

                        </div>
                    </li>
                </ul>
        </div>
    </div>
</div>





            
                <p><a href="mostrar_participantes.php">Ver participantes registrados</a></p>
                <hr>
                <input type="hidden" name="seleccionados" id="seleccionados" value="<?php echo implode(',', $_POST['numeros'] ?? []); ?>">

                    <div id="tabla-numeros">
                        <!-- Aquí se cargará dinámicamente la tabla de números -->
                        <?php
                        // Obtener el número de página actual
                        $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
                        // Número de números por página
                        $numbersPerPage = 100;
                        // Calcular el índice de inicio para esta página
                        $startIndex = ($currentPage - 1) * $numbersPerPage;
                        // Generar números disponibles con tres dígitos
                        $numeros_disponibles = range(0, 999);
                        // Formatear los números para que tengan tres dígitos
                        $numeros_disponibles = array_map(function($num) {
                            return str_pad($num, 3, '0', STR_PAD_LEFT);
                        }, $numeros_disponibles);
                        // Filtrar los números para mostrar solo los de esta página
                        $numeros_pagina = array_slice($numeros_disponibles, $startIndex, $numbersPerPage);
                        ?>
                        <table>
                                <tr>
                                <?php
                                        $counter = 0; // Inicializamos la variable $counter
                                        $seleccionados = explode(',', $_POST['seleccionados'] ?? ''); // Obtener los números seleccionados
                                        foreach ($numeros_pagina as $numero) {
                                            $sql_check = "SELECT * FROM participantes WHERE FIND_IN_SET('$numero', numero)";
                                            $result_check = $conn->query($sql_check);
                                            if ($result_check->num_rows > 0) {
                                                // Si el número está ocupado, marcarlo como ocupado en la tabla
                                                echo "<td class='ocupado'>$numero</td>";
                                            } else {
                                                // Verificar si el número está seleccionado por el participante actual
                                                $checked = in_array($numero, $seleccionados);
                                                // Marcar el checkbox como seleccionado si es necesario
                                                $checked_attr = $checked ? "checked" : "";
                                                echo "<td><input type='checkbox' class='checkbox-numero' name='numeros[]' value='$numero' $checked_attr>$numero</td>";
                                            }
                                            // Si el contador es divisible por 10, cierra la fila y comienza una nueva
                                            if (($counter + 1) % 10 == 0) {
                                                echo "</tr><tr>";
                                            }
                                            $counter++;
                                        }
                                ?>

                                </tr>
                        </table>
                        <!-- Controles de paginación -->
                        <?php
                            // Calcular la cantidad total de páginas
                            $totalPages = ceil(count($numeros_disponibles) / $numbersPerPage);
                        ?>
                    </div>


                    <nav aria-label="Page navigation">
                        <ul class="justify-content-center paginacion">
                            <?php if ($currentPage > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $currentPage - 1; ?>" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?php echo $i === $currentPage ? 'active' : ''; ?>"><a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                            <?php endfor; ?>
                            <!-- <?php if ($currentPage < $totalPages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $currentPage + 1; ?>" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            <?php endif; ?> -->
                        </ul>
                    </nav>

                <input type="submit" value="Enviar" class="custom-button">
                <hr>
                            
            </form>
            
        </div>
    </div>
    
    <!-- Modales -->
    <!-- Modal de registro exitoso -->
    <div class="modal fade" id="registroExitosoModal" tabindex="-1" aria-labelledby="registroExitosoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="registroExitosoModalLabel">Registro Exitoso</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <b> ¡Hola,  <?php echo isset($_GET['nombre_completo']) ? strtoupper($_GET['nombre_completo']) : 'PARTICIPANTE'; ?>! <br></b>
                    <b><?php echo "Números seleccionados: " . $_GET['numeros_seleccionados']; ?></b> <br>   
                        <p>Por favor imprimir el comprobante antes de cerrar esta pestaña</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Aceptar</button>
                    <input type="button" value="Imprimir" class="printbutton">
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de alerta de duplicado -->
    <div class="modal fade" id="duplicadoModal" tabindex="-1" aria-labelledby="duplicadoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="duplicadoModalLabel">Registro Duplicado</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¡Ya estás registrado!
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Aceptar</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal de alerta de selección de números -->
    <div class="modal fade" id="noNumbersModal" tabindex="-1" aria-labelledby="noNumbersModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="noNumbersModalLabel">Error de selección</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Por favor, selecciona al menos un número antes de enviar el formulario.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Aceptar</button>
                </div>
            </div>
        </div>
    </div>



    <!-- Bootstrap Bundle JS  -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<!-- Script para mostrar la alerta de registro exitoso o de duplicado -->
<script>
    window.addEventListener('DOMContentLoaded', (event) => {
        const urlParams = new URLSearchParams(window.location.search);
        const successParam = urlParams.get('success');
        const errorParam = urlParams.get('error');

        if (successParam === 'true') {
            $('#registroExitosoModal').modal('show');
            // Limpiar el parámetro de éxito de la URL
            urlParams.delete('success');
            history.replaceState(null, null, window.location.pathname + '?' + urlParams.toString());
        } else if (errorParam === 'duplicate') {
            $('#duplicadoModal').modal('show');
            // Limpiar el parámetro de error de la URL
            urlParams.delete('error');
            history.replaceState(null, null, window.location.pathname + '?' + urlParams.toString());
        }
    });
</script>


<script>
    // Función para mostrar el modal de alerta de selección de números
    function mostrarModalNoNumbers() {
        $('#noNumbersModal').modal('show');
        // Limpiar el parámetro de error de la URL
        const urlParams = new URLSearchParams(window.location.search);
        urlParams.delete('error');
        history.replaceState(null, null, window.location.pathname + '?' + urlParams.toString());
    }

    // Script para mostrar el modal si no se han seleccionado números
    window.addEventListener('DOMContentLoaded', (event) => {
        const errorParam = new URLSearchParams(window.location.search).get('error');

        if (errorParam === 'no_numbers_selected') {
            mostrarModalNoNumbers();
        }
    });
</script>
<!-- Boton de imprimir  -->
<script>
        document.querySelectorAll('.printbutton').forEach(function(element) {
            element.addEventListener('click', function() {
                print();
            });
        });
    </script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


<script>

$(document).ready(function() {
    // Leer las selecciones almacenadas en la cookie
    var cookieValue = document.cookie.replace(/(?:(?:^|.*;\s*)seleccionados\s*=\s*([^;]*).*$)|^.*$/, "$1");
    var seleccionados = cookieValue ? cookieValue.split(',') : [];

    // Marcar las casillas de verificación según las selecciones almacenadas
    seleccionados.forEach(function(numero) {
        $('input[value="' + numero + '"]').prop('checked', true);
    });
});

</script>
<!-- paginacion y refres de scroll -->
<script>
// Objeto para almacenar las selecciones de casillas por número de página
var seleccionesPorPagina = {};

$(document).ready(function() {
    // Manejar el clic en los enlaces de paginación
    $(document).on('click', '.page-link', function(e) {
        e.preventDefault();
        var page = $(this).attr('href');

        // Obtener los números seleccionados en la página actual
        var currentPageSelected = getCurrentPageSelection();

        // Realizar una solicitud GET al servidor para obtener el contenido de la siguiente página
        $.get(page, function(data) {
            var tablaNumeros = $(data).find('#tabla-numeros').html();

            // Actualizar el contenido de la tabla en la página actual
            $('#tabla-numeros').html(tablaNumeros);

            // Volver a marcar los números seleccionados después de cargar la nueva página
            currentPageSelected.forEach(function(numero) {
                $('input[value="' + numero + '"]').prop('checked', true);
            });
        });
    });

    // Manejar el cambio de estado de los checkboxes de números (evento delegado)
    $(document).on('change', 'input[name="numeros[]"]:checkbox', function() {
        // Obtener el número de página actual
        var currentPage = getCurrentPage();

        // Obtener el número seleccionado
        var numero = $(this).val();
        var isChecked = $(this).prop('checked');

        // Actualizar el estado del checkbox en seleccionesPorPagina
        if (!seleccionesPorPagina[currentPage]) {
            seleccionesPorPagina[currentPage] = [];
        }
        if (isChecked) {
            seleccionesPorPagina[currentPage].push(numero);
        } else {
            var index = seleccionesPorPagina[currentPage].indexOf(numero);
            if (index !== -1) {
                seleccionesPorPagina[currentPage].splice(index, 1);
            }
        }
    });

    // Guardar el estado de las casillas seleccionadas antes de enviar el formulario
    $('form').submit(function() {
        // Obtener todas las selecciones de casillas
        var todasLasSelecciones = [];
        Object.values(seleccionesPorPagina).forEach(function(selecciones) {
            todasLasSelecciones = todasLasSelecciones.concat(selecciones);
        });

        // Establecer el valor del campo oculto con todas las selecciones
        $('#seleccionados').val(todasLasSelecciones.join(','));
    });

    // Función para obtener el número de página actual
    function getCurrentPage() {
        return parseInt($('.page-item.active .page-link').text());
    }

    // Función para obtener las selecciones de números en la página actual
    function getCurrentPageSelection() {
        var currentPage = getCurrentPage();
        return seleccionesPorPagina[currentPage] || [];
    }
});

</script>



   
</body>
</html>
