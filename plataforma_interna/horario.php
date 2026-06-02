<?php
session_start();
require_once "../config/conexion.php";

if (!isset($_SESSION['id_alumno'])) {
    header("Location: login.php");
    exit();
}

$id_alumno = $_SESSION['id_alumno'];


$query = "SELECT c.descripcion, c.dias_semana, c.franja_horaria 
          FROM alumno_curso ac
          JOIN curso c ON ac.id_curso = c.id_curso
          WHERE ac.id_alumno = :id AND ac.estado = 'Activo'";

$stmt = $pdo->prepare($query);
$stmt->execute(['id' => $id_alumno]);
$lista_cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Horario — Novalingua</title>
    <link rel="stylesheet" href="../scss/scss_interno/main.css">
    <style>
        
        .tabla-horario { width: 100%; border-collapse: collapse; margin-top: 20px; background: white; }
        .tabla-horario th, .tabla-horario td { border: 1px solid #ddd; padding: 12px; text-align: center; }
        .tabla-horario th { background-color: #1a365d; color: white; }
        .clase-activa { background-color: #ebf8ff; color: #2b6cb0; font-weight: bold; border: 2px solid #2b6cb0; }
    </style>
</head>
<body>

<div class="dashboard-container">
  
  <?php include "../include/menulateral.php"; ?>

  <main class="main-content">
    <header class="dashboard-header">
      <h2>📅 Mi Calendario Académico</h2>
      <p>Aquí puedes consultar los horarios de todas tus materias activas.</p>
    </header>

    <?php if (empty($lista_cursos)): ?>
        <div class="dashboard-card">
            <p>Actualmente no estás matriculado en ningún curso activo.</p>
        </div>
    <?php else: ?>
        
        <?php foreach ($lista_cursos as $curso): ?>
            
            <div class="dashboard-card" style="margin-bottom: 30px;">
              <h3 style="color: #2b6cb0; font-size: 1.1rem;">📚 <?php echo $curso['descripcion']; ?></h3>
              <p>🗓️ <strong>Días de clase:</strong> <?php echo $curso['dias_semana']; ?></p>
              <p>⏰ <strong>Horario:</strong> <?php echo $curso['franja_horaria']; ?></p>
              
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
                          <td><?php echo $curso['franja_horaria']; ?></td>
                          <td class="<?php echo (strpos($curso['dias_semana'], 'Lunes') !== false) ? 'clase-activa' : ''; ?>">
                              <?php echo (strpos($curso['dias_semana'], 'Lunes') !== false) ? '📖 Clase' : 'Libre'; ?>
                          </td>
                          <td class="<?php echo (strpos($curso['dias_semana'], 'Martes') !== false) ? 'clase-activa' : ''; ?>">
                              <?php echo (strpos($curso['dias_semana'], 'Martes') !== false) ? '📖 Clase' : 'Libre'; ?>
                          </td>
                          <td class="<?php echo (strpos($curso['dias_semana'], 'Miércoles') !== false) ? 'clase-activa' : ''; ?>">
                              <?php echo (strpos($curso['dias_semana'], 'Miércoles') !== false) ? '📖 Clase' : 'Libre'; ?>
                          </td>
                          <td class="<?php echo (strpos($curso['dias_semana'], 'Jueves') !== false) ? 'clase-activa' : ''; ?>">
                              <?php echo (strpos($curso['dias_semana'], 'Jueves') !== false) ? '📖 Clase' : 'Libre'; ?>
                          </td>
                          <td class="<?php echo (strpos($curso['dias_semana'], 'Viernes') !== false) ? 'clase-activa' : ''; ?>">
                              <?php echo (strpos($curso['dias_semana'], 'Viernes') !== false) ? '📖 Clase' : 'Libre'; ?>
                          </td>
                      </tr>
                  </tbody>
              </table>
            </div>

        <?php endforeach; ?>
        
    <?php endif; ?>
  </main>
</div>

</body>
</html>