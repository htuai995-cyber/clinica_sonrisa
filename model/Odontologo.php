<?php
require_once __DIR__ . '/BaseModel.php';

class Odontologo extends BaseModel
{
    protected string $tabla = 'odontologos';
    protected array $columnas = ['nombres', 'apellidos', 'dni', 'especialidad_id', 'telefono', 'email'];
}
