<?php
include 'conexion.php';

// Obtener los datos de la base de datos
$sql = "SELECT * FROM participantes";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h1>Participantes Registrados</h1>";
    echo "<table border='1'>";
    echo "<tr><th>Nombre</th><th>Apellido</th><th>Cédula</th><th>Números seleccionados</th></tr>";
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["nombre"] . "</td>";
        echo "<td>" . $row["apellido"] . "</td>";
        echo "<td>" . $row["cedula"] . "</td>";
        
        // Separar la cadena de números en números individuales
        $numeros_seleccionados = explode(',', $row["numero"]);
        echo "<td>";
        foreach ($numeros_seleccionados as $numero) {
            echo $numero . " ";
        }
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "0 resultados";
}
$conn->close();
?>
