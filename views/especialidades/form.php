<form method="post" class="form-grid">
    <input type="hidden" name="csrf" value="<?= e(csrf()) ?>">
    <input type="hidden" name="id" value="<?= e($edit['id'] ?? 0) ?>">

    <label>
        Nombre
        <input name="nombre" type="text" value="<?= e($edit['nombre'] ?? '') ?>" required>
    </label>

    <div class="actions">
        <button type="submit" name="accion" value="guardar">Guardar</button>
        <a class="btn secondary" href="especialidades.php">Cancelar</a>
        <button type="reset" class="secondary">Limpiar</button>
    </div>
</form>
