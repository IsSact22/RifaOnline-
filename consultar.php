<?php
// Conexión a la base de datos
include 'conexion.php';

// Obtener el número de cédula enviado por el formulario
$cedula = $_GET['cedula'];

// Consulta a la base de datos
$sql = "SELECT * FROM participantes WHERE cedula = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $cedula);
$stmt->execute();
$result = $stmt->get_result();

// Mostrar los resultados
if ($result->num_rows > 0) {
    echo "<h2>Resultados de la consulta:</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Nombre</th><th>Apellido</th><th>Cédula</th><th>Números</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['nombre'] . "</td>";
        echo "<td>" . $row['apellido'] . "</td>";
        echo "<td>" . $row['cedula'] . "</td>";
        echo "<td>" . $row['numero'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";

    // Agregar el formulario de confirmación
    echo "<p>Por favor, si está conforme con los resultados, marque la casilla de confirmación:</p>";
    echo "<form id='confirmacion-form' action='confirmar.php' method='POST'>";
    echo "<input type='hidden' name='cedula' value='$cedula'>"; // Pasar la cédula al formulario de confirmación
    echo "<input type='checkbox' id='confirmacion' name='confirmacion'>";
    echo "<label for='confirmacion'>Confirmar</label>";
    echo "<button type='submit' class='buttone'>Enviar</button>";
    echo "</form>";
} else {
    echo "<p>No se encontraron resultados para la cédula proporcionada.</p>";
}

// Cerrar la conexión
$conn->close();
?>
