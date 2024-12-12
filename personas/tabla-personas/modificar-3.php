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

cabecera("USER - Modify 3");

$nombre    = recoge("nombre");
$apellidos = recoge("apellidos");
$telefono  = recoge("telefono");
$correo    = recoge("correo");
$localidad = recoge("localidad");
$id        = recoge("id");

if ($id == "") {
    print "    <p class=\"aviso\">No record has been selected.</p>\n";
} else {
    $idOk = true;
}

// Check that no empty record is being created
$registroNoVacioOk = false;

    if ($nombre == "" || $apellidos == "" || $telefono == "" || $correo == "" || $localidad == "") {
        print "    <p class=\"aviso\">You must fill in at least one of the fields. The record was not saved.</p>\n";
        print "\n";
    } else {
        $registroNoVacioOk = true;
}

// Check that the record with the given id exists in the database
$registroEncontradoOk = false;

if ($idOk && $registroNoVacioOk) {
    $consulta = "SELECT COUNT(*) FROM personas
                 WHERE id = :id";

    $resultado = $pdo->prepare($consulta);
    if (!$resultado) {
        print "    <p class=\"aviso\">Error preparing the query. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } elseif (!$resultado->execute([":id" => $id])) {
        print "    <p class=\"aviso\">Error executing the query. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } elseif ($resultado->fetchColumn() == 0) {
        print "    <p class=\"aviso\">Record not found.</p>\n";
    } else {
        $registroEncontradoOk = true;
    }
}

// If all checks have passed...
if ($idOk && $registroNoVacioOk && $registroEncontradoOk) {
    // Update the record with the received data
    $consulta = "UPDATE personas
                 SET nombre = :nombre, apellidos = :apellidos, telefono = :telefono, correo = :correo, localidad = :localidad
                 WHERE id = :id";

    $resultado = $pdo->prepare($consulta);
    if (!$resultado) {
        print "    <p class=\"aviso\">Error preparing the query. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } elseif (!$resultado->execute([":nombre" => $nombre, ":apellidos" => $apellidos, ":telefono" => $telefono, ":correo" => $correo, ":localidad" => $localidad, ":id" => $id])) {
        print "    <p class=\"aviso\">Error executing the query. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } else {
        print "    <p>Record successfully modified.</p>\n";
    }
}

pie();

