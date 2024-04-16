<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link  href="assets/styles.css" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <title>Rifa</title>
    
</head>
<body>
    <div class="container">
    <img src="img/Banner2015_2.jpg  " alt="Banner" class="full-width-img">
        <h2 class="custom-title">Sorteo de Números</h2>
        <br>
            <!-- <ul>
                <li><strong>Llenar Todos los Datos Requeridos:</strong> Es obligatorio completar todos los campos del formulario de registro con la información solicitada. Esto incluye nombre, apellido y cédula.</li>
                <li><strong>Selección de Número por Registro:</strong> Cada participante debe seleccionar al menos un número disponible de la tabla de números. Un registro no será válido si no se elige al menos un número.</li>
                <li><strong>Un Número por Participante:</strong> Cada participante puede seleccionar un máximo de un número por registro. Se desactivará la opción de seleccionar más de un número.</li>
                <li><strong>Cumplimiento de Normas:</strong> Todos los participantes deben cumplir con las normas y regulaciones establecidas para la rifa. El incumplimiento de estas normas puede resultar en la descalificación del participante.</li>
                <li><strong>Participación Voluntaria:</strong> La participación en la rifa es voluntaria y no está sujeta a ningún tipo de obligación.</li>
                <li><strong>Resultados Finales:</strong> Los resultados finales de la rifa se determinarán de acuerdo con el método especificado por los organizadores. Estos resultados serán definitivos y no estarán sujetos a revisión.</li>
                <li><strong>Premios:</strong> Los premios ofrecidos en la rifa estarán sujetos a disponibilidad y podrán variar según la cantidad de participantes y otras condiciones.</li>
            </ul> -->
            <p class="custom-P">Bienvenidos al gran sorteo CAPRESTSJ</p>
            <p class="custom-P">Al participar en la rifa, aceptas cumplir con todas estas reglas y condiciones. ¡Buena suerte!</p>
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
                            <label for="nombre" class="custom-label" >Nombre:</label>
                            <input type="text" id="nombre" name="nombre" class="custom-input" required placeholder="ingresa su nombre"> 
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="apellido" class="custom-label" >Apellido:</label>
                            <input type="text" id="apellido" name="apellido" class="custom-input" required placeholder="ingresa su apellido">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="cedula" class="custom-label" >Cédula:</label>
                            <input type="text" id="cedula" name="cedula" class="custom-input" pattern="[0-8]+" title="Ingrese solo números" required placeholder="00000000">
                        </div>
                    </div>
                </div>
            
                <p><a href="mostrar_participantes.php">Ver participantes registrados</a></p>
                <hr>
                <?php
                // Obtener el número de página actual
                $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
                // Número de números por página
                $numbersPerPage = 70;
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
                        foreach ($numeros_pagina as $numero) {
                            $sql_check = "SELECT * FROM participantes WHERE numero = '$numero'";
                            $result_check = $conn->query($sql_check);
                            if ($result_check->num_rows > 0) {
                                // Si el número está ocupado, marcarlo como ocupado en la tabla
                                echo "<td class='ocupado'>$numero</td>";
                            } else {
                                // Verificar si el número está seleccionado por el participante actual
                                $checked = in_array($numero, $_POST['numeros'] ?? array());
                                // Marcar el checkbox como seleccionado y deshabilitado si es necesario
                                $checked_attr = $checked ? "checked disabled" : "";
                                
                                echo "<td><input type='checkbox' name='numeros[]' value='$numero' $checked_attr>$numero</td>";
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
                        <?php if ($currentPage < $totalPages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $currentPage + 1; ?>" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        <?php endif; ?>
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
    <!-- Bootstrap Bundle JS -->
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
  

   
</body>
</html>
