<?php
require_once "../comunes/biblioteca.php";

session_name("sesiondb");
session_start();

if (!isset($_SESSION["conectado"])) {
    header("Location:../index.php");
    exit;
}

cabecera("Decrypt data");

echo "<form method='POST' action='descifrar-2.php'>
        <label>¿Estás seguro de que deseas descifrar todos los datos?</label><br>
        <input type='radio' name='confirmar' value='si'> Sí
        <input type='radio' name='confirmar' value='no'> No<br>
        <input type='submit' value='Confirmar'>
      </form>";

pie();
?>