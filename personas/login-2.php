<?php

require_once "comunes/biblioteca.php";

session_name("sesiondb");
session_start();

$usuario = recoge("usuario");
$password = recoge("password");

// Validaciones y autenticación del usuario (simplificada para este ejemplo)
if (($usuario == "root" && $password == "root") || ($usuario == "josemiguel" && $password == "josemiguel")) {
    $_SESSION["conectado"] = true;

    // Conectar a la base de datos
    $pdo = conectaDb();

    // Guardar el acceso en la base de datos
    $horaAcceso = date('Y-m-d H:i:s');
    $ipUsuario = $_SERVER['REMOTE_ADDR'];

    $query = "INSERT INTO accesos (usuario, hora_acceso, ip) VALUES (:usuario, :hora_acceso, :ip)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ":usuario" => $usuario,
        ":hora_acceso" => $horaAcceso,
        ":ip" => $ipUsuario
    ]);

    // Redirigir al usuario a la página de personas
    header("Location: tabla-personas/personas.php");
    exit;
} else {
    header("Location: login-1.php?aviso=Error: Incorrect username and/or password.");
    exit;
}

?>
