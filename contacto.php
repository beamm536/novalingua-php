<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto — Novalingua</title>
    <link rel="stylesheet" href="./scss/scss_publico/main_publico.css">
</head>
<body>

  <?php include "includes/navbar_publico.php"; ?>

  <main class="novalingua-container">
    <section class="contacto-wrapper">
      
      <div class="contacto-form-box">
        <h2>Escríbenos</h2>
        <p>¿Tienes dudas sobre los niveles o las inscripciones? Déjanos un mensaje.</p>
        
        <form action="#" method="POST">
          <div class="form-group">
            <label>Nombre Completo</label>
            <input type="text" name="nombre" required placeholder="Ej. Ana Silva">
          </div>
          <div class="form-group">
            <label>Correo Electrónico</label>
            <input type="email" name="email" required placeholder="tu@correo.com">
          </div>
          <div class="form-group">
            <label>Mensaje</label>
            <textarea name="mensaje" rows="5" required placeholder="¿En qué te podemos ayudar?"></textarea>
          </div>
          <button type="submit" class="btn-primary">Enviar Mensaje</button>
        </form>
      </div>

      <div class="contacto-info-box">
        <h2>Nuestra Sede Central</h2>
        <p>📍 Av. da Boavista 1234, 4100-111 Oporto, Portugal</p>
        <p>📞 +351 220 000 000</p>
        <p>⏰ Lunes a Viernes: 09:00 - 21:00 | Sábados: 09:00 - 14:00</p>
        
        <div class="mapa-container">
          <iframe 
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d12011.037346129598!2d-8.64024345!3d41.1579438!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd2465adc353a2b7%3A0x6fb2846fc454c794!2sAv.%20da%20Boavista%2C%20Porto%2C%20Portugal!5e0!3m2!1ses!2ses!4v1715970000000!5m2!1ses!2ses" 
            width="100%" 
            height="250" 
            style="border:0; border-radius: 12px;" 
            allowfullscreen="" 
            loading="lazy">
          </iframe>
        </div>
      </div>

    </section>
  </main>

  <?php include "include/footer_publico.php"; ?>

</body>
</html>