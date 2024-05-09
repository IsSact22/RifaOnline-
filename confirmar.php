<?php
// Conexión a la base de datos
include 'conexion.php';

if (isset($_POST['confirmacion'])) {
    // Obtener la cédula del formulario de confirmación
    $cedula = $_POST['cedula'];

    // Verificar si la cédula ya ha sido confirmada antes
    $sql_verificar = "SELECT confirmado FROM consulta WHERE cedula = ?";
    $stmt_verificar = $conn->prepare($sql_verificar);
    $stmt_verificar->bind_param("s", $cedula);
    $stmt_verificar->execute();
    $stmt_verificar->store_result();

    if ($stmt_verificar->num_rows > 0) {
        // La cédula ya ha sido confirmada previamente
        echo "<p>Usted ya ha sido confirmado anteriormente. No es necesario realizar otra confirmación.</p>";
    } else {
        // La cédula no ha sido confirmada previamente, proceder con la confirmación
        $confirmado = $_POST['confirmacion'] == 'on' ? 'verificado' : 'no confirmado';
        $sql_insertar = "INSERT INTO consulta (cedula, confirmado) VALUES (?, ?)";
        $stmt_insertar = $conn->prepare($sql_insertar);
        $stmt_insertar->bind_param("ss", $cedula, $confirmado);
        $stmt_insertar->execute();

        if ($stmt_insertar->affected_rows > 0) {
            echo "<p>La consulta ha sido confirmada y almacenada correctamente.</p>";
        } else {
            echo "<p>Error al almacenar la confirmación.</p>";
        }
    }
} else {
    echo "<p>Por favor, vuelva a la página de consulta para seleccionar una cédula.</p>";
}

// Cerrar la conexión
$conn->close();
?>
