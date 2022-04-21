@extends('app')
@section('encabezado', "Actualizar Producto")
@section('contenido')
<form name="Actualizar" method="POST" action="{{ $_SERVER['PHP_SELF'] }}">
    <input type="hidden" name="id" value="{{ $producto->getId() }}">
    <div class="row my-4">
        <div class="form-group col-md-6">
            <label for="nombre">Nombre</label>
            <input type="text" class="form-control" id="nombre" placeholder="Nombre" name="nombre" value="{{ ($producto->getNombre() ?? '') }}" required>
        </div>
    </div>
    <div class="form-group col-md-6">
        <label for="nombre_corto">Nombre Corto</label>
        <input type="text" class="form-control" id="nombre_corto" placeholder="Nombre Corto" name="nombre_corto" value="{{ ($producto->getNombreCorto() ?? '') }}" required>
    </div>
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="pvp">Precio (€)</label>
            <input type="number" class="form-control" id="pvp" placeholder="Precio (€)" name="pvp" min="0" step="0.01" value="{{ ($producto->getPvp() ?? '') }}" required>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-9">
            <label for="descripcion">Descripción</label>
            <textarea class="form-control" name="descripcion" id="d" rows="4"> {{ ($producto->getDescripcion() ?? '') }}</textarea>
        </div>
    </div>
    <div class="form-group col-md-6">
        <label for="familia">Familia</label>
        <select id="familia" class="form-control" name="familia">
            <option value='CAMARA'>CAMARA</option>
            <option value='MP3'>MP3</option>
            <option value='EBOOK'>EBOOK</option>
            <option value='IMPRES'>IMPRES</option>
            <option value='MEMFLA'>MEMFLA</option>  
        </select>
    </div>
    <input type="submit" class="btn btn-primary mr-3" name="actualizar-producto" value="Actualizar">
    <input type="reset" value="Limpiar" class="btn btn-success mr-3" onclick="this.querySelectorAll('input[type=text]').forEach(function (input, i) {
                input.value = '';
            })">
    <a href="index.php" class="btn btn-info">Volver</a>
</form>
@endsection