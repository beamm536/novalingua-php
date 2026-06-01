<?php
// 1. Declaramos la lista de idiomas igual que hacías en Astro
$listaIdiomas = [
  [ "idioma" => "Inglés",    "niveles" => "A1 - C1", "modalidad" => "Online / Presencial", "flag" => "🇬🇧" ],
  [ "idioma" => "Francés",   "niveles" => "A1 - B2", "modalidad" => "Online / Presencial", "flag" => "🇫🇷" ],
  [ "idioma" => "Alemán",    "niveles" => "A1 - B1", "modalidad" => "Online",              "flag" => "🇩🇪" ],
  [ "idioma" => "Portugués", "niveles" => "A1 - C1", "modalidad" => "Online / Presencial", "flag" => "🇵🇹" ],
  [ "idioma" => "Español",   "niveles" => "A1 - C1", "modalidad" => "Online",              "flag" => "🇪🇸" ]
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuestros Cursos — Novalingua</title>
    <link rel="stylesheet" href="./scss/scss_publico/main_publico.css">
</head>
<body>

  <?php include "include/navbar_publico.php"; ?>

  <main class="novalingua-container">
    
    <section class="section-idiomas-disponibles">
      <div class="section-header text-center">
        <h2>Nuestros cursos</h2>
        <p>Elige el idioma que quieres aprender y consulta niveles, horarios y modalidad.</p>
      </div>

      <div class="grid-idiomas">
        <?php foreach ($listaIdiomas as $item): ?>
          <div class="course-card-mini">
            <div class="card-mini-flag"><?php echo $item['flag']; ?></div>
            <h3><?php echo $item['idioma']; ?></h3>
            <p class="levels">🎓 <?php echo $item['niveles']; ?></p>
            <p class="modality">💻 <?php echo $item['modalidad']; ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    </section>

    <section class="platform-banner">
      <div class="platform-container">
        <div class="platform-content">
          <h2>Tu aprendizaje en una sola plataforma</h2>
          <p>Accede a tus clases, materiales, horarios y progreso desde cualquier lugar.</p>
          <a href="login.php" class="btn-platform">Acceder a la plataforma</a>
        </div>
      </div>
    </section>

    <hr class="section-divider" />

    <section class="course-section">
      <div class="section-header">
        <h2>Cursos Presenciales e Intensivos</h2>
        <p>Clases dinámicas e inmersivas en nuestras sedes físicas para acelerar tu aprendizaje.</p>
      </div>

      <div class="table-container">
        <table class="courses-table">
          <thead>
            <tr>
              <th>Curso</th>
              <th>Duración</th>
              <th>Sedes Disponibles</th>
              <th>Formato</th>
              <th>Acción</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><strong>Intensivo Global</strong></td>
              <td>4 semanas (40h)</td>
              <td>Oporto, Braga, Coímbra</td>
              <td><span class="badge badge-presencial">Presencial</span></td>
              <td><a href="#contacto" class="btn-table">Saber más</a></td>
            </tr>
            <tr>
              <td><strong>Inmersión de Fin de Semana</strong></td>
              <td>2 fines de semana</td>
              <td>Oporto</td>
              <td><span class="badge badge-presencial">Presencial</span></td>
              <td><a href="#contacto" class="btn-table">Saber más</a></td>
            </tr>
            <tr>
              <td><strong>Preparación de Exámenes Oficiales</strong></td>
              <td>6 semanas (30h)</td>
              <td>Braga, Coímbra</td>
              <td><span class="badge badge-hybrid">Híbrido</span></td>
              <td><a href="#contacto" class="btn-table">Saber más</a></td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>

    <section class="course-section bg-light">
      <div class="section-header">
        <h2>Opciones Online Flexibles</h2>
        <p>Aprende sin fronteras con la misma calidad y rigor de nuestra metodología presencial.</p>
      </div>

      <div class="cards-grid">
        <div class="course-card">
          <div class="card-body">
            <span class="card-tag">En Directo</span>
            <h3>Novalingua Live</h3>
            <p>Clases en vivo por videoconferencia con profesores nativos en grupos reducidos para garantizar la máxima participación.</p>
          </div>
          <div class="card-footer">
            <a href="#contacto" class="btn-primary">Empieza tu viaje</a>
          </div>
        </div>

        <div class="course-card">
          <div class="card-body">
            <span class="card-tag">100% Flexible</span>
            <h3>A tu propio ritmo</h3>
            <p>Acceso 24/7 a nuestra plataforma interactiva multimedia con tutorías personalizadas semanales con tu mentor.</p>
          </div>
          <div class="card-footer">
            <a href="#contacto" class="btn-primary">Empieza tu viaje</a>
          </div>
        </div>

        <div class="course-card">
          <div class="card-body">
            <span class="card-tag">Personalizado</span>
            <h3>One-to-One Premium</h3>
            <p>Sesiones individuales enfocadas al 100% en tus objetivos de carrera profesional, entrevistas o metas académicas.</p>
          </div>
          <div class="card-footer">
            <a href="#contacto" class="btn-primary">Empieza tu viaje</a>
          </div>
        </div>
      </div>
    </section>
  </main>

  <?php include "include/footer_publico.php"; ?>

</body>
</html>