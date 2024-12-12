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

cabecera("USER - Modify 2");

$id = recoge("id");

// Check the received data
$idOk = false;

if ($id == "") {
    print "    <p class=\"aviso\">No record has been selected.</p>\n";
} else {
    $idOk = true;
}

// Check that the record with the received id exists in the database
$registroEncontradoOk = false;

if ($idOk) {
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

// If all checks have been successful...
if ($idOk && $registroEncontradoOk) {
    // Retrieve the record with the received id to include its values in the form
    $consulta = "SELECT * FROM personas
                 WHERE id = :id";

    $resultado = $pdo->prepare($consulta);
    if (!$resultado) {
        print "    <p class=\"aviso\">Error preparing the query. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } elseif (!$resultado->execute([":id" => $id])) {
        print "    <p class=\"aviso\">Error executing the query. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } else {
        $registro = $resultado->fetch();

    print "<form action=\"modificar-3.php\" method=\"get\">";
    print "      <p>Modify the fields you want:</p>";

    print "      <table>";
    print "        <tr>";
    print "          <td>Name:</td>";
    print "          <td><input type=\"text\" name=\"nombre\" value=\"$registro[nombre]\" maxlength=\"20\" pattern=\"^[A-Z][a-z]+( [A-Z][a-z]+)*$\" title=\"Name must start with uppercase and can be composed by one or more names separated by spaces.\"></td>";
    print "        </tr>";
    print "        <tr>";
    print "          <td>Surname:</td>";
    print "          <td><input type=\"text\" name=\"apellidos\" value=\"$registro[apellidos]\" maxlength=\"40\" pattern=\"[A-Z][a-z]+ [A-Z][a-z]+$\" title=\"First and second surname must start with uppercase and followed by lowercase, separated by a space.\"></td>";
    print "        </tr>";
    print "        <tr>";
    print "          <td>Phone:</td>";
    print "          <td><input type=\"text\" name=\"telefono\" value=\"$registro[telefono]\" maxlength=\"9\" pattern=\"^\\d{9}$\" title=\"Telephone must be exactly 9 digits.\"></td>";
    print "        </tr>";
    print "        <tr>";
    print "          <td>Email:</td>";
    print "          <td><input type=\"text\" name=\"correo\" value=\"$registro[correo]\" maxlength=\"50\" pattern=\"^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$\" title=\"Please enter a valid email address.\"></td>";
    print "        </tr>";
    print "        <tr>";
    print "          <td>City:</td>";
    print "          <td><input type=\"text\" name=\"localidad\" value=\"$registro[localidad]\" maxlength=\"40\"></td>";
    print "        </tr>";
    print "      </table>";
    print "";
    print "      <p>";
    print "        <input type=\"hidden\" name=\"id\" value=\"$id\">";
    print "        <input type=\"submit\" value=\"Update\">";
    print "        <input type=\"reset\" value=\"Reset form\">";
    print "      </p>";
    print "    </form>";
  }
}

pie();
?>
