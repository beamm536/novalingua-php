<nav class="navbar">
  <div class="nav-inner">
    <a class="brand" href="index.php">
      <img src="logo.png" alt="logo nova lingua" class="logo-img">
      <div class="brand-text">
        <div class="brand-main">NOVALINGUA</div>
        <div class="brand-sub">SCHOOL</div>
      </div>
    </a>

    <div class="nav-links" id="nav-links">
      <a href="index.php">INICIO</a>
      <a href="#">CURSOS</a>
      <a href="#">PROFESORES</a>
      <a href="#">SOBRE NOSOTROS</a>
    </div>

    <div class="nav-actions">
      <a class="btn-login" href="login.php">Iniciar Sesión</a>
      <a class="btn-signup" href="#">Registrarse</a>
      
      <button class="menu-toggle" id="menu-toggle" aria-label="Abrir menú">
        <span class="hamburger"></span>
        <span class="hamburger"></span>
        <span class="hamburger"></span>
      </button>
    </div>
  </div>

  <div class="mobile-menu" id="mobile-menu" aria-hidden="true">
    <a href="index.php">INICIO</a>
    <a href="#">CURSOS</a>
    <a href="#">PROFESORES</a>
    <a href="#">SOBRE NOSOTROS</a>
    <hr class="mobile-divider" />
    <a class="btn-login-mobile" href="login.php">Iniciar Sesión</a>
    <a class="btn-signup mobile" href="#">Registrarse</a>
  </div>
</nav>

<script>
  document.addEventListener("DOMContentLoaded", () => {
    const btn = document.getElementById("menu-toggle");
    const mobile = document.getElementById("mobile-menu");
    if (!btn || !mobile) return;
    
    btn.addEventListener("click", () => {
      const open = mobile.getAttribute("aria-hidden") === "false";
      mobile.setAttribute("aria-hidden", String(!open));
      mobile.classList.toggle("open");
    });
  });
</script>