<?php

require "../vendor/autoload.php";

use eftec\bladeone\BladeOne;
use App\BD;
use App\Producto;

$views = __DIR__ . '/../views';
$cache = __DIR__ . '/../cache';
$blade = new BladeOne($views, $cache, BladeOne::MODE_DEBUG);

try {
    $bd = BD::getConexion();
} catch (PDOException $error) {
    die("Error en la conexión con la BD");
}

if (isset($_POST['borrar-producto'])) {
    $productId = trim(filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING));
    $producto = Producto::recuperaProductoPorId($bd, $productId);
    $producto->elimina($bd);
    $productoEliminado = true;
    $productos = Producto::recuperaProductos($bd);
    echo $blade->run('productos', compact('productos', 'productoEliminado'));
} else if (isset($_POST['form-actualizar-producto'])) {
    $productId = trim(filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING));
    $producto = Producto::recuperaProductoPorId($bd, $productId);
    echo $blade->run('form-actualizar-producto', compact('producto'));
} else if (isset($_POST['actualizar-producto'])) {
    $productId = trim(filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING));
    $nombre = trim(filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING));
    $descripcion = trim(filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_STRING));
    $pvp = trim(filter_input(INPUT_POST, 'pvp', FILTER_SANITIZE_STRING));
    $producto = Producto::recuperaProductoPorId($bd, $productId);
    $producto->setNombre($nombre);
    $producto->setDescripcion($descripcion);
    $producto->setPvp($pvp);
    $producto->persiste($bd);
    $productoActualizado = true;
    $productos = Producto::recuperaProductos($bd);
    echo $blade->run('productos', compact('productos', 'productoActualizado'));
} else if (isset($_POST['form-crear-producto'])) {
    echo $blade->run('form-crear-producto');
} else if (isset($_POST['crear-producto'])) {
    $nombre = trim(filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING));
    $nombreCorto = trim(filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING));
    $descripcion = trim(filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_STRING));
    $pvp = trim(filter_input(INPUT_POST, 'pvp', FILTER_SANITIZE_STRING));
    $familia = trim(filter_input(INPUT_POST, 'familia', FILTER_SANITIZE_STRING));
    $producto = new Producto($nombre, $nombreCorto, $descripcion, $pvp, $familia);
    $producto->persiste($bd);
    $productoCreado = true;
    $productos = Producto::recuperaProductos($bd);
    echo $blade->run('productos', compact('productos', 'productoCreado'));
} else {
    $productos = Producto::recuperaProductos($bd);
    echo $blade->run('productos', compact('productos'));
}

