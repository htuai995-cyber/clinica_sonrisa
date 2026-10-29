<?php
require_once __DIR__ . '/BaseModel.php';

class Tratamiento extends BaseModel
{
    protected string $tabla = 'tratamientos';
    protected array $columnas = ['diagnostico_id', 'descripcion_tratamiento', 'indicaciones', 'costo'];
}
