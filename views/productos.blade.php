@extends('app')
@section('titulo', "Productos")
@section('encabezado', "Listado de Productos")
@section('contenido')
@if(isset($productoCreado))
<div class="alert alert-success h-100 mt-3">
    <p>Nuevo producto creado con éxito</p>
</div>
@endif
@if(isset($productoActualizado))
<div class="alert alert-success h-100 mt-3">
    <p>Prodducto actualizado con éxito</p>
</div>
@endif
<form action='index.php' method='POST' style='display:inline'>
    <button type='submit' class='btn btn-info mr2' name="form-crear-producto">Nuevo Producto</button>
</form>
<table class="table table-striped">
    <thead>
        <tr class="text-center">
            <th scope="col">Código</th>
            <th scope="col">Nombre</th>
            <th scope="col">Precio</th>
        </tr>
    </thead>
    <tbody>
        @foreach($productos as $producto)
        <tr class="text-center">
            <td scope="row">{{$producto->getId()}}</td>
            <td>{{$producto->getNombre()}}</td>
            @if($producto->getPvp()>100)
            <td class='text-danger'>{{$producto->getPvp()}}</td>
            @else
            <td class='text-success'>{{$producto->getPvp()}}</td>
            @endif
            <td>
                <form action='index.php' method='POST' style='display:inline'>
                    <input type='hidden' name='id' value='<?= $producto->getId() ?>'>
                    <button type='submit' class='btn btn-warning mr2' name="form-actualizar-producto">Actualizar</button>
                </form>
                <form action='index.php' method='POST' style='display:inline'>
                    <input type='hidden' name='id' value='<?= $producto->getId() ?>'> <!-- mandamos el código del producto a borrar -->
                    <button type='submit' onclick="return confirm('¿Borrar Producto?')" class='btn btn-danger' name="borrar-producto">Borrar</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection