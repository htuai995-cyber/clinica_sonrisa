<div class="table-scroll">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $fila): ?>
                <tr>
                    <td><?= e($fila['id']) ?></td>
                    <td><?= e($fila['nombre']) ?></td>
                    <td>
                        <a href="especialidades.php?editar=<?= e($fila['id']) ?>">Editar</a>
                        <form method="post" onsubmit="return confirm('¿Eliminar registro?')">
                            <input type="hidden" name="csrf" value="<?= e(csrf()) ?>">
                            <input type="hidden" name="id" value="<?= e($fila['id']) ?>">
                            <button type="submit" class="danger" name="accion" value="eliminar">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
