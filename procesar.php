<?php
// procesar.php
include 'conexion.php';

// Obtener datos del formulario
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$cedula = $_POST['cedula'];
$numeros = isset($_POST['numeros']) ? $_POST['numeros'] : [];


// Verificar si la cédula contiene solo números
if (!ctype_digit($cedula)) {
    // Redirigir con mensaje de error
    header("Location: formulario.php?error=invalid_cedula");
    exit();
}

// Verificar si se han seleccionado al menos un número
if (empty($numeros)) {
    // Redirigir con mensaje de error
    header("Location: formulario.php?error=no_numbers_selected");
    exit();
}

// Consultar si la cédula está registrada en la tabla dto-aso
$sql = "SELECT * FROM `dto-aso` WHERE cdaso = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $cedula);
$stmt->execute();
$result = $stmt->get_result();

// Verificar si la cédula está registrada
if ($result->num_rows > 0) {
    // Preparar la consulta de inserción
    $numeros_implode = implode(',', $numeros); // Almacenar el resultado de implode
    $fecha = date("Y-m-d H:i:s"); // Obtener la fecha actual
    $sql_insert = "INSERT INTO participantes (nombre, apellido, cedula, numero,fecha) VALUES (?, ?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
   $stmt_insert->bind_param("sssss", $nombre, $apellido, $cedula, $numeros_implode, $fecha); // Usar la variable $numeros_implode y $fecha

    // Iniciar una transacción
    $conn->begin_transaction();

    // Insertar los datos del participante
    if ($stmt_insert->execute()) {
        // Marcar los números seleccionados como ocupados en la tabla 'numeros'
        $sql_update_numeros = "UPDATE numeros SET ocupado = 1 WHERE numero IN (?)";
        $stmt_update_numeros = $conn->prepare($sql_update_numeros);
        $stmt_update_numeros->bind_param("s", $numeros_implode); // Usar la misma variable $numeros_implode
        $stmt_update_numeros->execute();
        $stmt_update_numeros->close();

        // Commit de la transacción
        $conn->commit();

        // Redirigir con mensaje de éxito y el nombre completo del participante
        header("Location: formulario.php?success=true&nombre_completo=" . urlencode($nombre . ' ' . $apellido . ' ' . $cedula) . "&numeros_seleccionados=" . urlencode($numeros_implode)); // Usar la misma variable $numeros_implode
        exit();
    } else {
        // Rollback de la transacción en caso de error
        $conn->rollback();
        // Redirigir con mensaje de error
        header("Location: formulario.php?error=insert_error");
        exit();
    }
} else {
    // Redirigir con mensaje de error
    header("Location: formulario.php?error=cedula_no_registrada");
    exit();
}

// Cerrar la conexión
$conn->close();
?>
