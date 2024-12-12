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

cabecera("Users - Add 1");

    // Display the form
?>
<form action="insertar-2.php" method="post">
  <p>Enter the new record's details:</p>

  <table>
    <tr>
      <td>Name:</td>
      <td><input type="text" name="nombre" maxlength="20" pattern="^[A-Z][a-z]+( [A-Z][a-z]+)*$" title="Name must start with uppercase and can be composed by one or more names separated by spaces." autofocus></td>
    </tr>
    <tr>
      <td>Surname:</td>
      <td><input type="text" name="apellidos" maxlength="40" pattern="^[A-Z][a-z]+ [A-Z][a-z]+$" title="First and second surname must start with uppercase and followed by lowercase, separated by a space."></td>
    </tr>
    <tr>
      <td>Telephone:</td>
      <td><input type="text" name="telefono" maxlength="20" pattern="^\d{9}$" title="Telephone must be exactly 9 digits."></td>
    </tr>
    <tr>
      <td>Email:</td>
      <td><input type="text" name="correo"  maxlength="50" pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" title="Please enter a valid email address."></td>
    </tr>
    <tr>
      <td>City:</td>
      <td><input type="text" name="localidad" maxlength="40"></td>
    </tr>
  </table>
  <p>
    <input type="submit" value="Add">
    <input type="reset" value="Reset Form">
  </p>
</form>
<?php

pie();
?>
