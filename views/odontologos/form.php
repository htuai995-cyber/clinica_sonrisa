<form method="post" class="form-grid">
    <input type="hidden" name="csrf" value="<?= e(csrf()) ?>">
    <input type="hidden" name="id" value="<?= e($edit['id'] ?? 0) ?>">

    <?php foreach ($campos as $campo => $tipo): ?>
        <label>
            <?= e(ucwords(str_replace('_', ' ', $campo))) ?>
            <?php if (in_array($tipo, ['pacientes', 'odontologos', 'especialidades', 'servicios', 'citas', 'diagnosticos'], true)): ?>
                <?php $opciones = opcionesReferencia($tipo); ?>
                <select name="<?= e($campo) ?>" required>
                    <option value="">Seleccione...</option>
                    <?php foreach ($opciones as $opcion): ?>
                        <option value="<?= e($opcion['id']) ?>" <?= ($edit[$campo] ?? '') == $opcion['id'] ? 'selected' : '' ?>>
                            <?= e($opcion['etiqueta']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            <?php else: ?>
                <input
                    name="<?= e($campo) ?>"
                    type="<?= e($tipo) ?>"
                    value="<?= e($edit[$campo] ?? '') ?>"
                    <?= in_array($campo, ['nombres', 'apellidos', 'dni'], true) ? 'required' : '' ?>
                >
            <?php endif; ?>
        </label>
    <?php endforeach; ?>

    <div class="actions">
        <button type="submit" name="accion" value="guardar">Guardar</button>
        <a class="btn secondary" href="odontologos.php">Cancelar</a>
        <button type="reset" class="secondary">Limpiar</button>
    </div>
</form>
