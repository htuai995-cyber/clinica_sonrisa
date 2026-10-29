<?php
require_once __DIR__ . '/BaseModel.php';

class Especialidad extends BaseModel
{
    protected string $tabla = 'especialidades';
    protected array $columnas = ['nombre'];
}
