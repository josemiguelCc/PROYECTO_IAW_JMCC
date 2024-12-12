<?php
require_once "../comunes/biblioteca.php";

session_name("sesiondb");
session_start();

if (!isset($_SESSION["conectado"])) {
    header("Location: login-1.php");
    exit;
}

// Conectar a la base de datos
$pdo = conectaDb();

// Obtener todos los registros de acceso
$query = "SELECT * FROM accesos ORDER BY hora_acceso DESC";
$stmt = $pdo->query($query);

// Mostrar los registros en una tabla HTML
cabecera("access logs");

echo "<table border='1'>
        <tr>
            <th>ID</th>
            <th>Usuario</th>
            <th>Hora de acceso</th>
            <th>IP</th>
        </tr>";

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "<tr>
            <td>{$row['id']}</td>
            <td>{$row['usuario']}</td>
            <td>{$row['hora_acceso']}</td>
            <td>{$row['ip']}</td>
          </tr>";
}

echo "</table>";

pie();
?>
