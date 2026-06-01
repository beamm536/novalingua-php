<?php
session_start();
require_once "config/conexion.php"; 

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $contrasenia = trim($_POST['contrasenia']); // Asegúrate de que coincida con el name de tu input

    if (!empty($email) && !empty($contrasenia)) {
        try {
            // --- PASO 1: Buscar en la tabla de PROFESORES ---
            $sql_prof = "SELECT id_profesor, nombre, contrasenia FROM profesor WHERE email = :email LIMIT 1";
            // NOTA: Si en tu tabla 'profesor' no existiera la columna 'contrasenia', 
            // recuerda que en el dump actual está compartiendo estructura de accesos.
            $stmt_prof = $pdo->prepare($sql_prof);
            $stmt_prof->execute(['email' => $email]);
            $profesor = $stmt_prof->fetch(PDO::FETCH_ASSOC);

            if ($profesor && $profesor['contrasenia'] == $contrasenia) { // Cambiar por password_verify si usas hash
                // Es un profesor válido
                $_SESSION['id_profesor'] = $profesor['id_profesor'];
                $_SESSION['nombre'] = $profesor['nombre'];
                $_SESSION['rol'] = 'profesor';

                header("Location: plataforma_interna/plataforma_profesor/dashboard_prof.php");
                exit();
            }

            // --- PASO 2: Si no es profesor, buscar en la tabla de ALUMNOS ---
            $sql_alum = "SELECT id_alumno, nombre, contrasenia FROM alumno_info WHERE email = :email LIMIT 1";
            $stmt_alum = $pdo->prepare($sql_alum);
            $stmt_alum->execute(['email' => $email]);
            $alumno = $stmt_alum->fetch(PDO::FETCH_ASSOC);

            if ($alumno && $alumno['contrasenia'] == $contrasenia) {
                // Es un alumno válido
                $_SESSION['id_alumno'] = $alumno['id_alumno'];
                $_SESSION['nombre'] = $alumno['nombre'];
                $_SESSION['rol'] = 'alumno';

                // Rediriges a la carpeta o panel del alumno (ejemplo: dashboard_alumno.php)
                header("Location: plataforma_interna/dashboard.php");
                exit();
            }

            // Si llegó aquí, las credenciales no coinciden en ninguna de las dos tablas
            $error = "❌ El correo electrónico o la contraseña son incorrectos.";

        } catch (PDOException $e) {
            $error = "❌ Error en el sistema: " . $e->getMessage();
        }
    } else {
        $error = "❌ Por favor, rellena todos los campos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión — Novalingua</title>
    <link rel="stylesheet" href="css/main.css"> </head>
<body style="background: #f7fafc; display: flex; align-items: center; justify-content: center; height: 100vh; font-family: sans-serif;">

    <div style="background: #fff; padding: 40px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); width: 100%; max-width: 400px;">
        <h2 style="color: #1a365d; margin-bottom: 10px; text-align: center;">Novalingua</h2>
        <p style="color: #718096; text-align: center; margin-bottom: 20px;">Plataforma Interna</p>

        <?php if(!empty($error)): ?>
            <div style="background: #fed7d7; color: #742a2a; padding: 10px; border-radius: 4px; margin-bottom: 15px; font-size: 0.9rem; font-weight: bold;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST" style="display: flex; flex-direction: column; gap: 15px;">
            <div style="display: flex; flex-direction: column; gap: 5px;">
                <label style="font-weight: 600; color: #4a5568;">Correo Electrónico</label>
                <input type="email" name="email" required placeholder="ejemplo@novalingua.com" style="padding: 10px; border: 1px solid #e2e8f0; border-radius: 4px;">
            </div>

            <div style="display: flex; flex-direction: column; gap: 5px;">
                <label style="font-weight: 600; color: #4a5568;">Contraseña</label>
                <input type="password" name="contrasenia" required placeholder="********" style="padding: 10px; border: 1px solid #e2e8f0; border-radius: 4px;">
            </div>

            <button type="submit" style="background: #1a365d; color: white; padding: 12px; border: none; border-radius: 4px; font-weight: bold; cursor: pointer; margin-top: 10px;">
                Ingresar a la Plataforma
            </button>
        </form>
    </div>

</body>
</html>