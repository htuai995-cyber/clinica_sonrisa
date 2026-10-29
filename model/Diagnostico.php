<?php
require_once __DIR__ . '/BaseModel.php';

class Diagnostico extends BaseModel
{
    protected string $tabla = 'diagnosticos';
    protected array $columnas = ['cita_id', 'diagnostico', 'observaciones', 'fecha'];
}
