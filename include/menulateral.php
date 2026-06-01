<?php
//que detecte la pagina actual en la que nos encontramos
$pagina_actual = basename($_SERVER['PHP_SELF']);
?>
<aside class="sidebar">
  <div class="logo-container">
    <h1 class="logo">NOVALINGUA <span>ESTUDIANTES</span></h1>
  </div>
  
  <nav class="sidebar-menu">
    <a href="dashboard.php" class="menu-item <?= $pagina_actual == 'dashboard.php' ? 'active' : '' ?>">
       Mis cursos
    </a>
    
    <a href="horario.php" class="menu-item <?= $pagina_actual == 'horario.php' ? 'active' : '' ?>">
       Horario
    </a>
    
    <a href="configuracion.php" class="menu-item <?= $pagina_actual == 'configuracion.php' ? 'active' : '' ?>">
     Configuración
    </a>
    
    <a href="incidencias.php" class="menu-item <?= $pagina_actual == 'incidencias.php' ? 'active' : '' ?>">
     Reportar Incidencia
    </a>

    <a href="logout.php" class="menu-item logout-btn">
       Cerrar sesión
    </a>
  </nav>
</aside>