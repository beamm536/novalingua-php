<?php
session_start();
require_once "config/conexion.php";

if (!isset($_SESSION['id_alumno'])) {
    header("Location: login.php");
    exit();
}

$id_alumno = $_SESSION['id_alumno'];

// Consultamos los datos de horario del curso de este alumno
$query = "SELECT c.descripcion, c.dias_semana, c.franja_horaria 
          FROM alumno_curso ac
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
    <title>Mi Horario — Novalingua</title>
    <link rel="stylesheet" href="scss/dashboard.css">
    <style>
        /* Unos estilos rápidos para la tabla de horarios */
        .tabla-horario { width: 100%; border-collapse: collapse; margin-top: 20px; background: white; }
        .tabla-horario th, .tabla-horario td { border: 1px solid #ddd; padding: 12px; text-align: center; }
        .tabla-horario th { background-color: #1a365d; color: white; }
        .clase-activa { background-color: #ebf8ff; color: #2b6cb0; font-weight: bold; border: 2px solid #2b6cb0; }
    </style>
</head>
<body>

<div class="dashboard-container">
  
    <?php include "include/menulateral.php"; ?>

  <main class="main-content">
    <header class="dashboard-header">
      <h2>📅 Mi Calendario Académico</h2>
      <p>Estás matriculado en: <strong><?php echo $datos['descripcion']; ?></strong></p>
    </header>

    <div class="dashboard-card">
      <h3>TU TURNO ASIGNADO</h3>
      <p>🗓️ <strong>Días de clase:</strong> <?php echo $datos['dias_semana']; ?></p>
      <p>⏰ <strong>Horario:</strong> <?php echo $datos['franja_horaria']; ?></p>
      
      <table class="tabla-horario">
          <thead>
              <tr>
                  <th>Hora</th>
                  <th>Lunes</th>
                  <th>Martes</th>
                  <th>Miércoles</th>
                  <th>Jueves</th>
                  <th>Viernes</th>
              </tr>
          </thead>
          <tbody>
              <tr>
                  <td>17:00 - 18:30</td>
                  <td>Libre</td>
                  <td>Libre</td>
                  <td>Libre</td>
                  <td>Libre</td>
                  <td>Libre</td>
              </tr>
              <tr>
                  <td>18:00 - 19:30</td>
                  <td class="clase-activa">🇬🇧 Clase de Idioma<br><?php echo $datos['franja_horaria']; ?></td>
                  <td>Libre</td>
                  <td class="clase-activa">🇬🇧 Clase de Idioma<br><?php echo $datos['franja_horaria']; ?></td>
                  <td>Libre</td>
                  <td>Libre</td>
              </tr>
          </tbody>
      </table>
    </div>
  </main>
</div>

</body>
</html>