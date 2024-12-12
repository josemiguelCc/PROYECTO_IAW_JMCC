<?php
/**
 * @author    Bartolomé Sintes Marco - bartolome.sintes+mclibre@gmail.com
 * @license   https://www.gnu.org/licenses/agpl-3.0.txt AGPL 3 or later
 * @link      https://www.mclibre.org
 */

require_once "comunes/biblioteca.php";

session_name("sesiondb");
session_start();

if (isset($_SESSION["conectado"])) {
    header("Location:tabla-personas/personas.php");
    exit;
}

cabecera("Login 1");

$aviso = recoge("aviso");

if ($aviso != "") {
    print "    <p class=\"aviso\">$aviso</p>\n";
    print "\n";
}
?>
<div class="login-container">
    <form action="login-2.php" method="post" class="login-form">
        <h2>Login</h2>
        <p>Enter your username and password:</p>

        <table>
            <tr>
                <td>User:</td>
                <td><input type="text" name="usuario" maxlength="20" autofocus require/></td>
            </tr>
            <tr>
                <td>Password:</td>
                <td><input type="password" name="password" maxlength="20"/></td>
            </tr>
        </table>

        <div class="form-buttons">
            <input type="submit" value="Enter">
            <input type="reset" value="Delete">
        </div>
    </form>
</div>
<?php
pie();
?>
