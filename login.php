<?php
// 1. Iniciamos la sesión para que el servidor recuerde quién se ha logueado
session_start();

// 2. Traemos la conexión sencilla que hicimos arriba
require_once "config/conexion.php";

$mensaje_error = "";

// 3. ¿El usuario ha pulsado el botón de enviar el formulario?
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["input_email"];
    $password = $_POST["input_password"];

    // 4. Buscamos en la tabla 'alumno_info' si existe ese email y contraseña
    // Nota: Como estamos haciéndolo nivel principiante, buscamos el texto plano directamente.
    $consulta = $pdo->prepare("SELECT * FROM alumno_info WHERE email = :correo AND contrasenia = :clave");
    $consulta->execute([
        "correo" => $email,
        "clave"  => $password
    ]);

    $alumno = $consulta->fetch(PDO::FETCH_ASSOC);

    // 5. Si la base de datos encontró una coincidencia...
    if ($alumno) {
        // Guardamos el ID del alumno en la memoria del servidor
        $_SESSION["id_alumno"] = $alumno["id_alumno"]; // Asegúrate de que se llame así en tu BD
        
        // Lo mandamos directos al panel de control
        header("Location: dashboard.php");
        exit();
    } else {
        // Si no coincide, preparamos un aviso para la pantalla
        $mensaje_error = "El correo o la contraseña no son correctos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión — Novalingua</title>
    <style>
        /* Un estilo rápido y limpio para que no se vea feo */
        body { font-family: sans-serif; background: #f0f2f5; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-box { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); width: 300px; }
        h2 { margin-top: 0; color: #1a365d; text-align: center; }
        .input-group { margin-bottom: 15px; }
        .input-group label { display: block; margin-bottom: 5px; font-size: 14px; }
        .input-group input { width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        .btn { width: 100%; padding: 10px; background: #1a365d; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; }
        .btn:hover { background: #2b6cb0; }
        .error { color: red; font-size: 14px; text-align: center; margin-bottom: 15px; }
    </style>
</head>
<body>

<div class="login-box">
    <h2>NOVALINGUA</h2>

    <?php if (!empty($mensaje_error)): ?>
        <div class="error"><?php echo $mensaje_error; ?></div>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <div class="input-group">
            <label>Correo Electrónico</label>
            <input type="email" name="input_email" required placeholder="tu@correo.com">
        </div>

        <div class="input-group">
            <label>Contraseña</label>
            <input type="password" name="input_password" required placeholder="******">
        </div>

        <button type="submit" class="btn">Entrar a la escuela</button>
    </form>
</div>

</body>
</html>