<?php
$errors = [];
$success = isset($_GET['success']) && $_GET['success'] === '1';

$nombre = "";
$apellido = "";
$correo = "";
$telefono = "";
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST["nombre"] ?? "");
    $apellido = trim($_POST["apellido"] ?? "");
    $correo = trim($_POST["correo"] ?? "");
    $telefono = trim($_POST["telefono"] ?? "");
    $mensaje = trim($_POST["mensaje"] ?? "");
    $js_validated = trim($_POST["js_validated"] ?? "0");

    if (strlen($nombre) < 2) {
        $errors["nombre"] = "El nombre debe tener al menos 2 caracteres.";
    } elseif (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $nombre)) {
        $errors["nombre"] = "El nombre solo puede contener letras.";
    }

    if (strlen($apellido) < 2) {
        $errors["apellido"] = "El apellido debe tener al menos 2 caracteres.";
    } elseif (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $apellido)) {
        $errors["apellido"] = "El apellido solo puede contener letras.";
    }

    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $errors["correo"] = "Introduce un correo electrónico válido.";
    }

    if (!empty($telefono) && !preg_match("/^[6789]\d{8}$/", $telefono)) {
        $errors["telefono"] = "El teléfono debe ser un número válido de 9 dígitos.";
    }

    if (strlen($mensaje) < 10) {
        $errors["mensaje"] = "El mensaje debe tener al menos 10 caracteres.";
    }

    if (empty($errors)) {
        if ($js_validated === "1") {
            $nombre = $apellido = $correo = $telefono = $mensaje = "";
        } else {
            header("Location: contacto.php?success=1");
            exit();
        }
    }
}
?>
<!doctype html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="author" content="Antonio Gutiérrez Simón" />
    <meta name="description" content="Contacto Toni Dev" />
    <title>ToniDev - Contacto</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;700&display=swap"
      rel="stylesheet"
    />
    <link
      rel="shortcut icon"
      href="media/img/SoloLogo_Toni_dev.png"
      type="image/x-icon"
    />
    <link rel="stylesheet" href="styles/styles.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/contacto.js" defer></script>
    <script src="js/main.js" defer></script>
  </head>
  <body>
    <header>
      <div class="contenedor-header">
        <a href="index.html" class="logo"
          ><img src="media/img/logo_Toni_dev.png" alt="Logo ToniDev"
        /></a>
        <label for="menu-toggle" class="icono-menu">☰</label>
        <input type="checkbox" id="menu-toggle" class="menu-oculto" />
        <nav class="navegacion">
          <ul>
            <li><a href="index.html">Home</a></li>
            <li><a href="index.html#sobre-mi">Sobre mí</a></li>
            <li><a href="index.html#proyectos">Proyectos</a></li>
            <li><a href="contacto.php">Contacto</a></li>
          </ul>
        </nav>
      </div>
    </header>
    <main>
      <section class="contacto-seccion">
        <div class="proyectos-titulo">
          <h2>/CONTACTO/</h2>
        </div>
        <form action="#" method="POST" class="formulario-contacto">
          <input type="hidden" name="js_validated" id="js_validated" value="0" />
          <div class="grid-form">
            <p>
              <label for="nombre">Nombre</label>
              <input type="text" id="nombre" name="nombre" 
                     class="<?php echo isset($errors['nombre']) ? 'error' : ''; ?>"
                     value="<?php echo htmlspecialchars($nombre); ?>" required />
              <?php if (isset($errors['nombre'])): ?>
                <span class="error-text"><?php echo $errors['nombre']; ?></span>
              <?php endif; ?>
            </p>
            <p>
              <label for="apellido">Apellido</label>
              <input type="text" id="apellido" name="apellido" 
                     class="<?php echo isset($errors['apellido']) ? 'error' : ''; ?>"
                     value="<?php echo htmlspecialchars($apellido); ?>" required />
              <?php if (isset($errors['apellido'])): ?>
                <span class="error-text"><?php echo $errors['apellido']; ?></span>
              <?php endif; ?>
            </p>
            <p>
              <label for="correo">Correo</label>
              <input type="email" id="correo" name="correo" 
                     class="<?php echo isset($errors['correo']) ? 'error' : ''; ?>"
                     value="<?php echo htmlspecialchars($correo); ?>" required />
              <?php if (isset($errors['correo'])): ?>
                <span class="error-text"><?php echo $errors['correo']; ?></span>
              <?php endif; ?>
            </p>
            <p>
              <label for="telefono">Teléfono</label>
              <input type="tel" id="telefono" name="telefono" 
                     class="<?php echo isset($errors['telefono']) ? 'error' : ''; ?>"
                     value="<?php echo htmlspecialchars($telefono); ?>" />
              <?php if (isset($errors['telefono'])): ?>
                <span class="error-text"><?php echo $errors['telefono']; ?></span>
              <?php endif; ?>
            </p>
          </div>
          <p class="mensaje">
            <label for="mensaje">Mensaje</label>
            <textarea id="mensaje" name="mensaje" rows="5" 
                      class="<?php echo isset($errors['mensaje']) ? 'error' : ''; ?>"
                      required><?php echo htmlspecialchars($mensaje); ?></textarea>
            <?php if (isset($errors['mensaje'])): ?>
              <span class="error-text"><?php echo $errors['mensaje']; ?></span>
            <?php endif; ?>
          </p>
          <button type="submit" class="boton-enviar">Enviar</button>
        </form>
      </section>
    </main>
    <footer>
      <div class="footer-img">
        <a href="index.html">
          <img src="media/img/logo_Toni_dev.png" alt="Logo ToniDev" />
        </a>
      </div>
      <div class="footer-div">
        <nav class="nav-footer">
          <ul>
            <li><a href="index.html">Home</a></li>
            <li><a href="#sobre-mi">Sobre mí</a></li>
            <li><a href="#proyectos">Proyectos</a></li>
            <li><a href="contacto.php">Contacto</a></li>
          </ul>
        </nav>
        <div class="redes">
          <ul>
            <li><img src="media/img/instagram.svg" alt="Icono instagram" /></li>
            <li><img src="media/img/twitter.svg" alt="Icono twitter" /></li>
            <li>
              <a href="https://github.com/tonigstm96"
                ><img src="media/img/github.svg" alt="Icono github"
              /></a>
            </li>
          </ul>
        </div>
        <h5>2026 © Antonio Gutiérrez Simón</h5>
      </div>
    </footer>
  </body>
</html>
