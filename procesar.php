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

// Verificar si se han seleccionado más de 2 números
if (count($numeros) > 2) {
    // Redirigir con mensaje de error
    header("Location: formulario.php?error=exceed_limit");
    exit();
}


// Verificar si se han seleccionado al menos un número
if (empty($numeros)) {
    // Redirigir con mensaje de error
    header("Location: formulario.php?error=no_numbers_selected");
    exit();
}
// Convertir array de números a cadena
$numeros_seleccionados = implode(',', $numeros);


// Obtener el nombre completo del participante
$nombre_completo = $nombre . ' ' . $apellido;

//  Verificar si el nombre ya está registrado
// $sql_check_nombre = "SELECT * FROM participantes WHERE cedula = ?";
//  $stmt_check_nombre = $conn->prepare($sql_check_nombre);
//  $stmt_check_nombre->bind_param("s", $cedula);
//  $stmt_check_nombre->execute();
//  $result_check_nombre = $stmt_check_nombre->get_result();


 
    // Preparar la consulta de inserción
    $sql_insert = "INSERT INTO participantes (nombre, apellido, cedula, numero) VALUES (?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("ssss", $nombre, $apellido, $cedula, $numeros_seleccionados);



// Después de la inserción en la base de datos
if ($stmt_insert->execute()) {
    // Consultar el nombre completo y los números seleccionados del participante
    $sql_select_info = "SELECT CONCAT(nombre, ' ', apellido, ' ', cedula) AS nombre_completo, numero FROM participantes WHERE cedula = ?";
    $stmt_select_info = $conn->prepare($sql_select_info);
    $stmt_select_info->bind_param("s", $cedula);
    $stmt_select_info->execute();
    $result_select_info = $stmt_select_info->get_result();
    $row_select_info = $result_select_info->fetch_assoc();
    $nombre_completo = $row_select_info['nombre_completo'];
    $numeros_seleccionados = $row_select_info['numero'];

    // Marcar los números seleccionados como ocupados en la tabla 'numeros'
    $numeros_seleccionados_array = explode(',', $numeros_seleccionados);
    $sql_update_numeros = "UPDATE numeros SET ocupado = 1 WHERE numero = ?";
    $stmt_update_numeros = $conn->prepare($sql_update_numeros);
    foreach ($numeros_seleccionados_array as $numero) {
        $stmt_update_numeros->bind_param("i", $numero);
        $stmt_update_numeros->execute();
    }
    $stmt_update_numeros->close();

    // Redirigir con mensaje de éxito y el nombre completo del participante
    header("Location: formulario.php?success=true&nombre_completo=" . urlencode($nombre_completo) . "&numeros_seleccionados=" . urlencode($numeros_seleccionados));
    exit();
} else {
    // En caso de error
    header("Location: formulario.php?error=insert_error");
    exit();
}

$conn->close();
?>
