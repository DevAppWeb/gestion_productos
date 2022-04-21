@extends('app')
@section('encabezado', "Crear Producto")
@section('contenido')
<form name="crear" method="POST" action="{{ $_SERVER['PHP_SELF'] }}">
    <div class="row my-4">
        <div class="form-group col-md-6">
            <label for="nombre">Nombre</label>
            <input type="text" class="form-control" id="nombre" placeholder="Nombre" name="nombre" value="{{ ($nombre ?? '') }}" required>
        </div>
    </div>
    <div class="form-group col-md-6">
        <label for="nombre_corto">Nombre Corto</label>
        <input type="text" class="form-control" id="nombre_corto" placeholder="Nombre Corto" name="nombre_corto" value="{{ ($nombreCorto ?? '') }}" required>
    </div>
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="pvp">Precio (€)</label>
            <input type="number" class="form-control" id="pvp" placeholder="Precio (€)" name="pvp" min="0" step="0.01" value="{{ ($pvp ?? '') }}" required>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-9">
            <label for="descripcion">Descripción</label>
            <textarea class="form-control" name="descripcion" id="d" rows="4"> {{ ($descripcion ?? '') }}</textarea>
        </div>
    </div>
    <div class="form-group col-md-6">
        <label for="familia">Familia</label>
        <select id="familia" class="form-control" name="familia">
            <?php foreach ($familias as $familia): ?>
                <option value='<?= $familia->cod ?>'><?= $familia->nombre ?></option>
            <?php endforeach ?>
        </select>
    </div>
    <input type="submit" class="btn btn-primary mr-3" name="crear-producto" value="Crear">
    <input type="reset" value="Limpiar" class="btn btn-success mr-3" onclick="this.querySelectorAll('input[type=text]').forEach(function (input, i) {
                input.value = '';
            })">
    <a href="index.php" class="btn btn-info">Volver</a>
</form>
@endsection


