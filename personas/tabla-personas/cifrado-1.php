<?php
require_once "../comunes/biblioteca.php";

session_name("sesiondb");
session_start();

if (!isset($_SESSION["conectado"])) {
    header("Location:../index.php");
    exit;
}

cabecera("Cifrar datos");

echo "<form method='POST' action='cifrado-2.php'>
        <label>Are you sure you want to encrypt the user database? </label><br>
        <input type='radio' name='confirmar' value='si'> Yes
        <input type='radio' name='confirmar' value='no'> No<br>
        <input type='submit' value='Confirmar'>
      </form>";

pie();
?>
