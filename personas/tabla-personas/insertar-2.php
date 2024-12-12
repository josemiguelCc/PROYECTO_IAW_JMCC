<?php
/**
 * @author    Bartolomé Sintes Marco - bartolome.sintes+mclibre@gmail.com
 * @license   https://www.gnu.org/licenses/agpl-3.0.txt AGPL 3 or later
 * @link      https://www.mclibre.org
 */

require_once "../comunes/biblioteca.php";

session_name("sesiondb");
session_start();

if (!isset($_SESSION["conectado"])) {
    header("Location:../index.php");
    exit;
}

$pdo = conectaDb();

cabecera("Users - Add 2");

$nombre    = recoge("nombre");
$apellidos = recoge("apellidos");
$telefono  = recoge("telefono");
$correo    = recoge("correo");
$localidad = recoge("localidad");

// Check that a record is not being created empty
$registroNoVacioOk = false;

if ($nombre == "" || $apellidos == "" || $telefono == "" || $correo == "" || $localidad == "") {
    print "    <p class=\"aviso\">At least one field must be filled out. The record has not been saved.</p>\n";
    print "\n";
} else {
    $registroNoVacioOk = true;
}

// Check that the record does not duplicate an existing one
$registroDistintoOk = false;

if ($registroNoVacioOk) {
    $consulta = "SELECT COUNT(*) FROM personas
                 WHERE nombre = :nombre
                 AND apellidos = :apellidos
                 AND telefono = :telefono
                 AND correo = :correo
                 AND localidad = :localidad";

    $resultado = $pdo->prepare($consulta);
    if (!$resultado) {
        print "    <p class=\"aviso\">Error preparing the query. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } elseif (!$resultado->execute([":nombre" => $nombre, ":apellidos" => $apellidos, ":telefono" => $telefono, ":correo" => $correo, ":localidad" => $localidad])) {
        print "    <p class=\"aviso\">Error executing the query. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } elseif ($resultado->fetchColumn() > 0) {
        print "    <p class=\"aviso\">The record already exists.</p>\n";
    } else {
        $registroDistintoOk = true;
    }
}

// If all checks are successful ...
if ($registroNoVacioOk && $registroDistintoOk) {
    // Insert the record into the table
    $consulta = "INSERT INTO personas
                 (nombre, apellidos, telefono, correo, localidad)
                 VALUES (:nombre, :apellidos, :telefono, :correo, :localidad)";

    $resultado = $pdo->prepare($consulta);
    if (!$resultado) {
        print "    <p class=\"aviso\">Error preparing the query. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } elseif (!$resultado->execute([":nombre" => $nombre, ":apellidos" => $apellidos, ":telefono" => $telefono, ":correo" => $correo, ":localidad" => $localidad])) {
        print "    <p class=\"aviso\">Error executing the query. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } else {
        print "    <p>Record created successfully</p>\n";
    }
}

pie();
?>
