<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../model/BaseModel.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function db(): PDO
{
    static $db = null;
    return $db ??= (new Database())->conectar();
}

function e($valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

function csrf(): string
{
    return $_SESSION['csrf'] ??= bin2hex(random_bytes(32));
}

function validarCsrf(): void
{
    if (!hash_equals(csrf(), (string) ($_POST['csrf'] ?? ''))) {
        throw new RuntimeException('Token de seguridad no válido');
    }
}

function exigirSesion(): void
{
    if (empty($_SESSION['uid'])) {
        header('Location: login.php');
        exit;
    }
}

function redirigir(string $ruta): void
{
    header('Location: ' . $ruta);
    exit;
}

function notificar(string $mensaje): void
{
    $_SESSION['flash'] = $mensaje;
}

function validarDatos(array $campos, array $datos): array
{
    $salida = [];
    $referencias = ['pacientes', 'odontologos', 'especialidades', 'servicios', 'citas', 'diagnosticos'];
    $obligatorios = [
        'nombre', 'nombres', 'apellidos', 'dni', 'especialidad_id',
        'paciente_id', 'odontologo_id', 'servicio_id', 'fecha_hora',
        'cita_id', 'diagnostico', 'diagnostico_id', 'descripcion_tratamiento',
        'precio', 'monto', 'metodo',
    ];
    $opciones = [
        'estado_cita' => ['pendiente', 'confirmada', 'atendida', 'cancelada'],
        'estado_pago' => ['pendiente', 'pagado', 'anulado'],
        'metodo_pago' => ['efectivo', 'tarjeta_credito', 'tarjeta_debito', 'yape'],
    ];

    foreach ($campos as $campo => $tipo) {
        $valor = trim((string) ($datos[$campo] ?? ''));

        if (in_array($campo, $obligatorios, true) && $valor === '') {
            throw new RuntimeException('Este campo es obligatorio: ' . $campo);
        }

        if ($tipo === 'number' && $valor !== '' && (!is_numeric($valor) || (float) $valor < 0)) {
            throw new RuntimeException('Número inválido: ' . $campo);
        }

        if ($tipo === 'integer' && $valor !== '' && (
            !is_numeric($valor) || (float) $valor < 0 || floor((float) $valor) !== (float) $valor
        )) {
            throw new RuntimeException('Número entero inválido: ' . $campo);
        }

        if ($tipo === 'email' && $valor !== '' && !filter_var($valor, FILTER_VALIDATE_EMAIL)) {
            throw new RuntimeException('Correo inválido');
        }

        if (in_array($tipo, $referencias, true) && ($valor === '' || !ctype_digit($valor) || (int) $valor < 1)) {
            throw new RuntimeException('Seleccione una referencia válida: ' . $campo);
        }

        if ($tipo === 'datetime-local' && $valor !== '') {
            $fecha = DateTime::createFromFormat('!Y-m-d\TH:i', $valor);
            if (!$fecha || $fecha->format('Y-m-d\TH:i') !== $valor) {
                throw new RuntimeException('Fecha y hora inválidas');
            }
            $valor = $fecha->format('Y-m-d H:i:s');
        }

        if ($tipo === 'date' && $valor !== '') {
            $fecha = DateTime::createFromFormat('!Y-m-d', $valor);
            if (!$fecha || $fecha->format('Y-m-d') !== $valor) {
                throw new RuntimeException('Fecha inválida');
            }
        }

        if (isset($opciones[$tipo]) && !in_array($valor, $opciones[$tipo], true)) {
            throw new RuntimeException('Selección inválida');
        }

        $salida[$campo] = $valor === '' ? null : $valor;
    }

    return $salida;
}

function opcionesReferencia(string $tipo): array
{
    $consultas = [
        'pacientes' => "SELECT id, CONCAT(nombres, ' ', apellidos, ' · DNI ', dni) AS etiqueta
            FROM pacientes ORDER BY apellidos, nombres LIMIT 500",
        'odontologos' => "SELECT id, CONCAT(nombres, ' ', apellidos) AS etiqueta
            FROM odontologos ORDER BY apellidos, nombres LIMIT 500",
        'especialidades' => 'SELECT id, nombre AS etiqueta FROM especialidades ORDER BY nombre LIMIT 500',
        'servicios' => 'SELECT id, nombre AS etiqueta FROM servicios ORDER BY nombre LIMIT 500',
        'citas' => "SELECT c.id, CONCAT('Cita #', c.id, ' · ', DATE_FORMAT(c.fecha_hora, '%d/%m/%Y %H:%i'),
            ' · ', p.nombres, ' ', p.apellidos) AS etiqueta
            FROM citas c JOIN pacientes p ON p.id = c.paciente_id
            ORDER BY c.fecha_hora DESC LIMIT 500",
        'diagnosticos' => "SELECT id, CONCAT('Diagnóstico #', id, ' · ', LEFT(diagnostico, 80)) AS etiqueta
            FROM diagnosticos ORDER BY id DESC LIMIT 500",
    ];

    if (!isset($consultas[$tipo])) {
        throw new LogicException('Tipo de referencia no válido: ' . $tipo);
    }

    return db()->query($consultas[$tipo])->fetchAll();
}

function modulos(): array
{
    return [
        'pacientes' => ['nombre' => 'Pacientes', 'url' => 'pacientes.php'],
        'especialidades' => ['nombre' => 'Especialidades', 'url' => 'especialidades.php'],
        'odontologos' => ['nombre' => 'Odontólogos', 'url' => 'odontologos.php'],
        'servicios' => ['nombre' => 'Servicios', 'url' => 'servicios.php'],
        'citas' => ['nombre' => 'Citas', 'url' => 'citas.php'],
        'diagnosticos' => ['nombre' => 'Diagnósticos', 'url' => 'diagnosticos.php'],
        'tratamientos' => ['nombre' => 'Tratamientos', 'url' => 'tratamientos.php'],
        'pagos' => ['nombre' => 'Pagos', 'url' => 'pagos.php'],
    ];
}
