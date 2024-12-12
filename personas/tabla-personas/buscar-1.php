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

cabecera("Search - 1");

// Check if the database contains records
$recordsExistOk = false;

$query = "SELECT COUNT(*) FROM personas";

$result = $pdo->query($query);
if (!$result) {
    print "    <p class=\"aviso\">Query error. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
} elseif ($result->fetchColumn() == 0) {
    print "    <p class=\"aviso\">No records have been created yet.</p>\n";
} else {
    $recordsExistOk = true;
}

// If all checks were successful ...
if ($recordsExistOk) {
    // Display the form
?>

<form action="buscar-2.php" method="post">
<h2>Enter the name of the record to search</h2>
  <table>
    <tr>
      <td>Name:</td>
      <td><input type="text" name="nombre"  autofocus></td>
    </tr>
  </table>
  <p>
    <input type="submit" value="Search">
    <input type="reset" value="Reset form">
  </p>
</form>
<?php
}

pie();
?>
