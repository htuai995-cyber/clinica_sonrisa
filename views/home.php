<?php
require_once __DIR__ . '/../controller/BaseController.php';
exigirSesion();
require __DIR__ . '/layout.php';



$modulosInicio = [
    'pacientes' => [
        'imagen' => 'paciente.jpg',
        'descripcion' => 'Información y expedientes de tus pacientes.',
    ],
    'especialidades' => [
        'imagen' => 'especialidad.jpg',
        'descripcion' => 'Organiza las áreas de atención odontológica.',
    ],
    'odontologos' => [
        'imagen' => 'odontologos.jpg',
        'descripcion' => 'Administra el equipo profesional de la clínica.',
    ],
    'servicios' => [
        'imagen' => 'servicios.jpg',
        'descripcion' => 'Consulta tratamientos, precios y duración.',
    ],
    'citas' => [
        'imagen' => 'citas.jpg',
        'descripcion' => 'Programa y da seguimiento a cada consulta.',
    ],
    'diagnosticos' => [
        'imagen' => 'diagnostico.jpg',
        'descripcion' => 'Registra la evaluación clínica del paciente.',
    ],
    'tratamientos' => [
        'imagen' => 'tratamiento.jpg',
        'descripcion' => 'Consulta planes e indicaciones de tratamiento.',
    ],
    'pagos' => [
        'imagen' => 'pagos.jpg',
        'descripcion' => 'Lleva el control de pagos y referencias.',
    ],
];

layoutStart('Panel de control', true);
?>
<section class="welcome-banner">
    <div class="welcome-copy">
        <span class="eyebrow">GESTIÓN CLÍNICA</span>
        <h1>Una sonrisa saludable empieza con una buena atención.</h1>
        <p>Hola, <?= e($_SESSION['nombre'] ?? 'bienvenido') ?>. Desde aquí puedes organizar la atención y el trabajo diario de tu clínica.</p>
        <a class="welcome-action" href="citas.php">＋ <span>Programar una cita</span></a>
        <div class="welcome-note"><span class="status-dot"></span> Tu espacio de trabajo está listo</div>
    </div>
    <div class="welcome-photo" role="img" aria-label="Consultorio odontológico moderno"></div>
</section>

<div class="section-heading">
    <div>
        <span class="eyebrow">ACCESOS DIRECTOS</span>
        <h2>¿Qué necesitas gestionar?</h2>
    </div>
    <p>Elige una sección para comenzar.</p>
</div>

<div class="cards">
    <?php foreach (modulos() as $id => $modulo): ?>
        <a class="card" href="<?= e($modulo['url']) ?>">
            <img
                loading="lazy"
                alt="<?= e($modulo['nombre']) ?>"
                src="../imagenes/<?= e($modulosInicio[$id]['imagen']) ?>"
            >
            <div class="card-content">
                <span class="card-label">CLÍNICA</span>
                <h2><?= e($modulo['nombre']) ?></h2>
                <p><?= e($modulosInicio[$id]['descripcion']) ?></p>
                <span class="card-link">Abrir sección <span aria-hidden="true">→</span></span>
            </div>
        </a>
    <?php endforeach; ?>
</div>
<?php layoutEnd(); ?>
