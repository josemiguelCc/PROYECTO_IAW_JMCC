<?php
require_once "../comunes/biblioteca.php";

session_name("sesiondb");
session_start();

if (!isset($_SESSION["conectado"])) {
    header("Location:../index.php");
    exit;
}

$pdo = conectaDb();
cabecera("encrypt");
// Verificar si se confirmó el cifrado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmar']) && $_POST['confirmar'] === 'si') {

    // Función de cifrado por sustitución
    function crearMapaSustitucion() {
        return [
            'a' => 'b', 'b' => 'c', 'c' => 'd', 'd' => 'e', 'e' => 'f',
            'f' => 'g', 'g' => 'h', 'h' => 'i', 'i' => 'j', 'j' => 'k',
            'k' => 'l', 'l' => 'm', 'm' => 'n', 'n' => 'o', 'o' => 'p',
            'p' => 'q', 'q' => 'r', 'r' => 's', 's' => 't', 't' => 'u',
            'u' => 'v', 'v' => 'w', 'w' => 'x', 'x' => 'y', 'y' => 'z',
            'z' => 'a',
            'A' => 'B', 'B' => 'C', 'C' => 'D', 'D' => 'E', 'E' => 'F',
            'F' => 'G', 'G' => 'H', 'H' => 'I', 'I' => 'J', 'J' => 'K',
            'K' => 'L', 'L' => 'M', 'M' => 'N', 'N' => 'O', 'O' => 'P',
            'P' => 'Q', 'Q' => 'R', 'R' => 'S', 'S' => 'T', 'T' => 'U',
            'U' => 'V', 'V' => 'W', 'W' => 'X', 'X' => 'Y', 'Y' => 'Z',
            'Z' => 'A',
            '0' => '1', '1' => '2', '2' => '3', '3' => '4', '4' => '5',
            '5' => '6', '6' => '7', '7' => '8', '8' => '9', '9' => '0'
        ];
    }

    // Función para cifrar texto usando el mapa de cifrado
    function cifrarPorSustitucion($texto, $mapaCifrado) {
        $textoCifrado = '';
        for ($i = 0; $i < strlen($texto); $i++) {
            $caracter = $texto[$i];
            if (isset($mapaCifrado[$caracter])) {
                $textoCifrado .= $mapaCifrado[$caracter];
            } else {
                $textoCifrado .= $caracter;
            }
        }
        return $textoCifrado;
    }

    // Crear el mapa de cifrado
    $mapaCifrado = crearMapaSustitucion();

    // Obtener todos los registros de la tabla "personas"
    $query = "SELECT * FROM personas";
    $stmt = $pdo->query($query);

    // Recorrer cada fila de la base de datos
    while ($persona = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Cifrar cada campo
        $nombreCifrado = cifrarPorSustitucion($persona['nombre'], $mapaCifrado);
        $apellidosCifrado = cifrarPorSustitucion($persona['apellidos'], $mapaCifrado);
        $telefonoCifrado = cifrarPorSustitucion($persona['telefono'], $mapaCifrado);
        $correoCifrado = cifrarPorSustitucion($persona['correo'], $mapaCifrado);
        $localidadCifrada = cifrarPorSustitucion($persona['localidad'], $mapaCifrado);

        // Actualizar la base de datos con los datos cifrados
        $update_sql = "UPDATE personas SET 
                        nombre = ?, 
                        apellidos = ?, 
                        telefono = ?, 
                        correo = ?, 
                        localidad = ? 
                       WHERE id = ?";

        $stmtUpdate = $pdo->prepare($update_sql);
        $stmtUpdate->execute([$nombreCifrado, $apellidosCifrado, $telefonoCifrado, $correoCifrado, $localidadCifrada, $persona['id']]);
    }


    // Redirigir automáticamente a listar.php
    header("Location: listar.php");
    exit;

} else {
    // Si no se confirma el cifrado, redirigir a la página de inicio o mostrar un mensaje
    echo "El cifrado fue cancelado o no se seleccionó una opción válida.";
}
pie();
?>
