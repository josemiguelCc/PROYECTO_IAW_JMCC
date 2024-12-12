<?php
/**
 * @author    Bartolomé Sintes Marco - bartolome.sintes+mclibre@gmail.com
 * @license   https://www.gnu.org/licenses/agpl-3.0.txt AGPL 3 or later
 * @link      https://www.mclibre.org
 */

// Cargar la Biblioteca específica de la base de datos utilizada

function recoge($key, $type = "", $default = null, $allowed = null)
{
    if (!is_string($key) && !is_int($key) || $key == "") {
        trigger_error("Function recoge(): Argument #1 (\$key) must be a non-empty string or an integer", E_USER_ERROR);
    } elseif ($type !== "" && $type !== []) {
        trigger_error("Function recoge(): Argument #2 (\$type) is optional, but if provided, it must be an empty array or an empty string", E_USER_ERROR);
    } elseif (isset($default) && !is_string($default)) {
        trigger_error("Function recoge(): Argument #3 (\$default) is optional, but if provided, it must be a string", E_USER_ERROR);
    } elseif (isset($allowed) && !is_array($allowed)) {
        trigger_error("Function recoge(): Argument #4 (\$allowed) is optional, but if provided, it must be an array of strings", E_USER_ERROR);
    } elseif (is_array($allowed) && array_filter($allowed, function ($value) { return !is_string($value); })) {
        trigger_error("Function recoge(): Argument #4 (\$allowed) is optional, but if provided, it must be an array of strings", E_USER_ERROR);
    } elseif (!isset($default) && isset($allowed) && !in_array("", $allowed)) {
        trigger_error("Function recoge(): If argument #3 (\$default) is not set and argument #4 (\$allowed) is set, the empty string must be included in the \$allowed array", E_USER_ERROR);
    } elseif (isset($default, $allowed) && !in_array($default, $allowed)) {
        trigger_error("Function recoge(): If arguments #3 (\$default) and #4 (\$allowed) are set, the \$default string must be included in the \$allowed array", E_USER_ERROR);
    }

    if ($type == "") {
        if (!isset($_REQUEST[$key]) || (is_array($_REQUEST[$key]) != is_array($type))) {
            $tmp = "";
        } else {
            $tmp = trim(htmlspecialchars($_REQUEST[$key]));
        }
        if ($tmp == "" && !isset($allowed) || isset($allowed) && !in_array($tmp, $allowed)) {
            $tmp = $default ?? "";
        }
    } else {
        if (!isset($_REQUEST[$key]) || (is_array($_REQUEST[$key]) != is_array($type))) {
            $tmp = [];
        } else {
            $tmp = $_REQUEST[$key];
            array_walk_recursive($tmp, function (&$value) use ($default, $allowed) {
                $value = trim(htmlspecialchars($value));
                if ($value == "" && !isset($allowed) || isset($allowed) && !in_array($value, $allowed)) {
                    $value = $default ?? "";
                }
            });
        }
    }
    return $tmp;
}

/* 
Esta función pinta la parte superior de las páginas web
SI LA SESIÓN ESTÁ INICIADA: Saca el menú de las funciones que se pueden hacer en la base de datos + DESCONECTARSE
SI LA SESIÓN NO ESTÁ INICIADA: Saca exclusivamente el menu CONECTARSE 
*/
function cabecera($texto)
{
    print "<!DOCTYPE html>\n";
    print "<html lang=\"es\">\n";
    print "<head>\n";
    print "  <meta charset=\"utf-8\">\n";
    print "  <title>\n";
    print "    $texto. Bases de datos (3) 2. Bases de datos (3).\n";
    print "    Ejercicios. PHP. Bartolomé Sintes Marco. www.mclibre.org\n";
    print "  </title>\n";
    print "  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\n";
    print "  <link rel=\"stylesheet\" href=\"style.css\" title=\"Color\">\n";
    print "</head>\n";
    print "\n";
    print "<body>\n";
    print "  <header>\n";
    print "    <h1>$texto</h1>\n";
    print "\n";
    print "    <nav>\n";
    print "      <ul>\n";
    if (!isset($_SESSION["conectado"])) {
        print "        <li><a href=\"login-1.php\">Login</a></li>\n";
    } else {
        print "        <li><a href=\"insertar-1.php\">Add record</a></li>\n";
        print "        <li><a href=\"listar.php\">List</a></li>\n";
        print "        <li><a href=\"borrar-1.php\">Delete</a></li>\n";
        print "        <li><a href=\"buscar-1.php\">Search</a></li>\n";
        print "        <li><a href=\"modificar-1.php\">Edit</a></li>\n";
        print "        <li><a href=\"borrar-todo-1.php\">Delete all</a></li>\n";
        print "        <li><a href=\"../logout.php\">Logout</a></li>\n";
        print "        <li><a href=\"cifrado-1.php\">Encrypt</a></li>\n";
        print "        <li><a href=\"descifrar-1.php\">Decrypt</a></li>\n";
        print "        <li><a href=\"registros.php\">Records</a></li>\n";
    }
    print "      </ul>\n";
    print "    </nav>\n";
    print "  </header>\n";
    print "\n";
    print "  <main>\n";
}

function pie()
{
    print "  </main>\n";
    print "\n";
    print "  <footer>\n";
    print "    <p class=\"ultmod\">\n";
    print "      Última modificación de esta página:\n";
    print "      <time datetime=\"2024-02-20\">20 de febrero de 2024</time>\n";
    print "    </p>\n";
    print "\n";
    print "    <p class=\"licencia\">\n";
    print "      Esta actividad ha sido desaroola por José Miguel Camino © \n";
    print "    </p>\n";
    print "  </footer>\n";
    print "</body>\n";
    print "</html>\n";
}

// Funciones BASES DE DATOS
function conectaDb()
{
    try {
        $tmp = new PDO("mysql:host=localhost;dbname=db_iaw_jmcc;charset=utf8mb4", "josemiguel", "123");
        return $tmp;
    } catch (PDOException $e) {
        print "    <p class=\"aviso\">Error: Cannot connect to the database. {$e->getMessage()}</p>\n";
    }
}

// MYSQL: Borrado y creación de base de datos y tablas

function borraTodo()
{
    // Conectar a la base de datos
    $pdo = conectaDb();

    // Base de datos y tablas a crear
    $baseDatos = "db_iaw_jmcc";
    $tablas = [
        "personas" => "CREATE TABLE personas (
                       id INTEGER UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                       nombre VARCHAR(255),
                       apellidos VARCHAR(255),
                       telefono VARCHAR(255),
                       correo VARCHAR(255),
                       localidad VARCHAR(255)
                       )",
        "accesos" => "CREATE TABLE accesos (
                      id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                      usuario VARCHAR(255) NOT NULL,
                      hora_acceso DATETIME NOT NULL,
                      ip VARCHAR(45) NOT NULL
                      )"
    ];

    // Borrar la base de datos si existe
    $pdo->exec("DROP DATABASE IF EXISTS $baseDatos");

    // Crear la base de datos
    $pdo->exec("CREATE DATABASE $baseDatos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

    // Seleccionar la base de datos
    $pdo->exec("USE $baseDatos");

    // Crear las tablas en la base de datos
    foreach ($tablas as $tableName => $createQuery) {
        $pdo->exec($createQuery);
        print "    <p>Tabla $tableName Successfully created in the database $baseDatos.</p>\n";
    }
}



function encripta($cadena)
{
    return hash("sha256", $cadena);
}
?>
