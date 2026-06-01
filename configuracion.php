<?php
session_start();
require_once "config/conexion.php";

if (!isset($_SESSION['id_alumno'])) {
    header("Location: login.php");
    exit();
}

$id_alumno = $_SESSION['id_alumno'];

// Consultamos todos los datos personales del alumno
$query = "SELECT * FROM alumno_info WHERE id_alumno = :id LIMIT 1";
$stmt = $pdo->prepare($query);
$stmt->execute(['id' => $id_alumno]);
$alumno = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Configuración — Novalingua</title>
    <link rel="stylesheet" href="scss/dashboard.css">
</head>
<body>

<div class="dashboard-container">
  
   <?php include "include/menulateral.php"; ?>

  <main class="main-content">
    <header class="dashboard-header">
      <h2>⚙️ Datos de Cuenta y Perfil</h2>
    </header>

    <div class="grid-layout">
        <div class="dashboard-card">
  <h3>DATOS PERSONALES</h3>
  <ul class="profile-info-list">
    <li><strong>ID Alumno:</strong> <span>#<?php echo $alumno['id_alumno']; ?></span></li>
    <li><strong>Nombre Completo:</strong> <span><?php echo $alumno['nombre'] . " " . $alumno['apellidos']; ?></span></li>
    <li><strong>Correo Electrónico:</strong> <span><?php echo $alumno['email']; ?></span></li>
    <li><strong>Teléfono de Contacto:</strong> <span><?php echo $alumno['telefono']; ?></span></li>
    <li><strong>Estado en el Centro:</strong> <span class="badge-success">✔ Activo</span></li>
  </ul>
</div>
        <div class="dashboard-card" style="border-left: 4px solid #2b6cb0;">
          <h3>📌 INFORMACIÓN DE LA SECRETARÍA</h3>
          <p>Recuerda que para solicitar cambios de horario o modalidad (Presencial/Online), debes enviar un correo con un mínimo de 5 días de antelación al inicio del mes.</p>
          <p>📧 <strong>soporte@novalingua.com</strong></p>
        </div>
    </div>
  </main>
</div>

</body>
</html>