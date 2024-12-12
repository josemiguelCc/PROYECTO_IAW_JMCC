<?php
require_once "../comunes/biblioteca.php";

session_name("sesiondb");
session_start();

if (!isset($_SESSION["conectado"])) {
    header("Location:../index.php");
    exit;
}

$pdo = conectaDb();
cabecera("Decrypt data");
// Verificar si se confirmó el descifrado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmar']) && $_POST['confirmar'] === 'si') {

    // Función para generar el mapa de descifrado basado en el mapa de cifrado
    function crearMapaDescifrado($mapaCifrado) {
        $mapaDescifrado = [];
        foreach ($mapaCifrado as $clave => $valor) {
            $mapaDescifrado[$valor] = $clave;
        }
        return $mapaDescifrado;
    }

    // Función de cifrado por sustitución (original)
    function crearMapaSustitucion() {
        $sustitucion = [
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
        return $sustitucion;
    }

    // Función para descifrar texto usando el mapa de descifrado
    function descifrarPorSustitucion($texto, $mapaDescifrado) {
        $textoDescifrado = '';
        for ($i = 0; $i < strlen($texto); $i++) {
            $caracter = $texto[$i];
            if (isset($mapaDescifrado[$caracter])) {
                $textoDescifrado .= $mapaDescifrado[$caracter];
            } else {
                $textoDescifrado .= $caracter;
            }
        }
        return $textoDescifrado;
    }

    // Crear el mapa de cifrado y generar el mapa de descifrado
    $mapaCifrado = crearMapaSustitucion();
    $mapaDescifrado = crearMapaDescifrado($mapaCifrado);

    // Obtener todos los registros de la tabla "personas"
    $query = "SELECT * FROM personas";
    $stmt = $pdo->query($query);

    // Recorrer cada fila de la base de datos
    while ($persona = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Descifrar cada campo
        $nombreDescifrado = descifrarPorSustitucion($persona['nombre'], $mapaDescifrado);
        $apellidosDescifrado = descifrarPorSustitucion($persona['apellidos'], $mapaDescifrado);
        $telefonoDescifrado = descifrarPorSustitucion($persona['telefono'], $mapaDescifrado);
        $correoDescifrado = descifrarPorSustitucion($persona['correo'], $mapaDescifrado);
        $localidadDescifrada = descifrarPorSustitucion($persona['localidad'], $mapaDescifrado);

        // Actualizar la base de datos con los datos descifrados
        $update_sql = "UPDATE personas SET 
                        nombre = ?, 
                        apellidos = ?, 
                        telefono = ?, 
                        correo = ?, 
                        localidad = ? 
                       WHERE id = ?";

        $stmtUpdate = $pdo->prepare($update_sql);
        $stmtUpdate->execute([$nombreDescifrado, $apellidosDescifrado, $telefonoDescifrado, $correoDescifrado, $localidadDescifrada, $persona['id']]);

    }

    header("Location: listar.php");
    exit;

} else {
    // Si no se confirma el descifrado, redirigir a la página de inicio o mostrar un mensaje
    echo "The decryption was canceled or no valid option was selected.";
}
pie()
?>
