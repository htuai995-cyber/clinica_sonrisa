<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../model/Especialidad.php';

class EspecialidadController
{
    public Especialidad $modelo;

    public array $campos = [
        'nombre' => 'text',
    ];

    public string $titulo = 'Especialidades';

    public function __construct()
    {
        $this->modelo = new Especialidad(db());
    }

    public function procesar(): string
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return '';
        }

        try {
            validarCsrf();
            $id = filter_var(
                $_POST['id'] ?? 0,
                FILTER_VALIDATE_INT,
                ['options' => ['min_range' => 1]]
            ) ?: 0;

            if (($_POST['accion'] ?? '') === 'eliminar') {
                $this->modelo->eliminar($id);
            } else {
                $datos = validarDatos($this->campos, $_POST);
                $this->modelo->guardar($datos, $id);
            }

            notificar('Operación realizada correctamente');
            redirigir('especialidades.php');
        } catch (PDOException $ex) {
            error_log($ex->getMessage());
            return 'No se pudo completar la operación. Verifique referencias, duplicados y datos.';
        } catch (Throwable $ex) {
            return $ex->getMessage();
        }
    }
}
