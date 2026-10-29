<?php
require_once __DIR__ . '/BaseModel.php';

class Paciente extends BaseModel
{
    protected string $tabla = 'pacientes';
    protected array $columnas = ['nombres', 'apellidos', 'dni', 'telefono', 'email', 'direccion', 'fecha_nacimiento'];
}
