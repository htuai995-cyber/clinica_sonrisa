<?php
require_once __DIR__ . '/../controller/AuthController.php';

$error = '';
$auth = new AuthController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        validarCsrf();
        $accion = $_POST['accion'] ?? '';

        if ($accion === 'registro') {
            $auth->registrar(
                trim($_POST['nombre'] ?? ''),
                trim($_POST['email'] ?? ''),
                $_POST['password'] ?? ''
            );
            $error = 'Usuario creado. Ahora puedes iniciar sesión.';
        } elseif ($accion === 'login') {
            if ($auth->ingresar(trim($_POST['email'] ?? ''), $_POST['password'] ?? '')) {
                redirigir('home.php');
            }
            $error = 'Credenciales incorrectas.';
        }
    } catch (Throwable $ex) {
        $error = $ex->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clínica Odontológica | Acceso</title>
    <link rel="stylesheet" href="../assetes/css/vet.css?v=<?= filemtime(__DIR__ . '/../assetes/css/vet.css') ?>">
</head>
<body class="auth">
    <main class="auth-layout">
        <section class="auth-visual" aria-label="Atención odontológica profesional">
            <a class="auth-brand" href="login.php">
                <span class="brand-mark" aria-hidden="true">+</span>
                <span>Clínica Odontológica<small>Gestión de atención dental</small></span>
            </a>
            <div class="auth-visual-copy">
                <span class="eyebrow">CUIDAMOS CADA SONRISA</span>
                <h1>Atención cercana.<br>Sonrisas saludables.</h1>
                <p>Un espacio sencillo para organizar cada detalle de tu clínica.</p>
            </div>
            <span class="auth-visual-caption">Tu equipo, tus pacientes y su bienestar en un solo lugar.</span>
        </section>

        <section class="auth-box">
            <div class="mobile-brand">
                <span class="brand-mark" aria-hidden="true">+</span>
                <span>Clínica Odontológica<small>Gestión de atención dental</small></span>
            </div>
            <span class="eyebrow">PORTAL DE LA CLÍNICA</span>
            <h2>Bienvenido</h2>
            <p class="auth-intro">Ingresa a tu espacio de trabajo para continuar.</p>

            <?php if ($error !== ''): ?>
                <p class="auth-message" role="status"><?= e($error) ?></p>
            <?php endif; ?>

            <form method="post">
                <input type="hidden" name="csrf" value="<?= e(csrf()) ?>">

                <label for="nombre">Nombre <span>(solo para registrarse)</span></label>
                <input id="nombre" name="nombre" placeholder="Tu nombre completo" autocomplete="name">

                <label for="email">Correo electrónico</label>
                <input id="email" name="email" type="email" placeholder="nombre@clinica.com" autocomplete="email" required>

                <label for="password">Contraseña</label>
                <input id="password" name="password" type="password" placeholder="Escribe tu contraseña" autocomplete="current-password" required>

                <div class="actions">
                    <button type="submit" name="accion" value="login">Ingresar</button>
                    <button type="submit" name="accion" value="registro" class="secondary">Crear cuenta</button>
                </div>
            </form>
            <p class="auth-note">Para crear una cuenta, completa tu nombre y usa una contraseña de al menos 10 caracteres.</p>
        </section>
    </main>
</body>
</html>
