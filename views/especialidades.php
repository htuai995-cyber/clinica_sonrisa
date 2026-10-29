<?php
require_once __DIR__ . '/../controller/EspecialidadController.php';
exigirSesion();
$controller = new EspecialidadController();
$error = $controller->procesar();
$edit = isset($_GET['editar']) ? $controller->modelo->obtener((int) $_GET['editar']) : false;
$rows = $controller->modelo->listar();
$campos = $controller->campos;
$titulo = $controller->titulo;
require __DIR__ . '/especialidades/index.php';
