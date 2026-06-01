<?php
session_start();
require_once "../config/conexion.php";

// Si el alumno no ha iniciado sesión, al login directo
if (!isset($_SESSION['id_alumno'])) {
    header("Location: login.php");
    exit();
}

$id_alumno = $_SESSION['id_alumno'];

// Consulta súper directa con JOIN para traer el curso activo del alumno
$query = "SELECT ai.nombre, ai.apellidos, c.descripcion, c.nivel, c.modalidad, ac.progreso 
          FROM alumno_curso ac
          JOIN alumno_info ai ON ac.id_alumno = ai.id_alumno
          JOIN curso c ON ac.id_curso = c.id_curso
          WHERE ac.id_alumno = :id AND ac.estado = 'Activo' LIMIT 1";

$stmt = $pdo->prepare($query);
$stmt->execute(['id' => $id_alumno]);
$datos = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Cursos — Novalingua</title>
    <link rel="stylesheet" href="../scss/scss_interno/main.css">
</head>
<body>

<div class="dashboard-container">
  
  <?php include "../include/menulateral.php"; ?>

  <main class="main-content">
    <header class="dashboard-header">
      <h2>Bienvenida, <?php echo $datos['nombre'] . " " . $datos['apellidos']; ?></h2>
    </header>

    <div class="grid-layout">
      <div class="dashboard-card">
        <h3>CURSO ACTUAL</h3>
        <h4><?php echo $datos['descripcion']; ?></h4>
        <p><?php echo $datos['nivel']; ?> — <?php echo $datos['modalidad']; ?></p>
        
        <div class="progress-container">
          <div class="progress-bar-wrapper">
            <div class="progress-bar" style="width: <?php echo $datos['progreso']; ?>%"></div>
          </div>
          <span><?php echo $datos['progreso']; ?>%</span>
        </div>
      </div>

      <div class="dashboard-card">
        <h3>📝 PRÓXIMAS ENTREGAS</h3>
        <ul style="list-array: none; padding-left: 0; line-height: 2;">
            <li>❌ <strong>Grammar Unit 3</strong> - Retrasado</li>
            <li>⏳ <strong>Vocabulary Quiz</strong> - Pendiente (Vence mañana)</li>
            <li>✅ <strong>Writing Essay 1</strong> - Calificado (Nota: 8.5)</li>
        </ul>
      </div>
    </div>
  </main>
</div>

</body>
</html>