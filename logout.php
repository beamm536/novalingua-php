<?php
// 1. Inicializamos la sesión para poder destruirla
session_start();

// 2. Desactivamos todas las variables de sesión de golpe
$_SESSION = array();

// 3. Destruimos la cookie de sesión en el navegador por seguridad
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 4. Destruimos la sesión en el servidor
session_destroy();

// 5. Redirección limpia al index de la raíz del proyecto
header("Location: /novalingua/index.php");
exit();
?>