<?php
require_once "../comunes/biblioteca.php";

session_name("sesiondb");
session_start();

if (!isset($_SESSION["conectado"])) {
    header("Location:../index.php");
    exit;
}

cabecera("Users - HOME");

echo '<main>';

$hay_personas = false; 

if ($hay_personas) {
    // Mostrar listado de personas
    echo '<p>List of users</p>';
} else {
    // Mostrar mensaje si no hay personas
    echo '<p>Choose one of the functions above</p>';
}
echo '</main>';

pie();
?>
