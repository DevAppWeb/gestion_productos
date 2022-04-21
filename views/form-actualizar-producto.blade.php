<?php
require_once 'conexion.php';
if (!(isset($_POST['pet-modificar']) || isset($_POST['modificar']))) {
    header('Location:listado.php');
}

if (isset($_POST['modificar'])) {
//recogemos los datos del formlario, trimamos las cadenas
    $nombre = ucwords(trim(filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING)));
    $nombreCorto = strtoupper(trim(filter_input(INPUT_POST, 'nombre_corto', FILTER_SANITIZE_STRING)));
    $pvp = filter_input(INPUT_POST, 'pvp', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $descripcion = trim(filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_STRING));
    $familia = filter_input(INPUT_POST, 'familia', FILTER_SANITIZE_STRING);
    $id = filter_input(INPUT_POST, 'id');
    $error = empty($pvp) || empty($nombreCorto) || empty($nombre);
    if (!$error) {
        $modifica = "update productos set nombre=:nombre, nombre_corto=:nombre_corto, pvp=:pvp, descripcion=:descripcion, familia=:familia where id=:id";
        $stmtModificaProducto = $conProyecto->prepare($modifica);
        try {
            $productoModificado = $stmtModificaProducto->execute([
                ':nombre' => $nombre,
                ':nombre_corto' => $nombreCorto,
                ':pvp' => $pvp,
                ':familia' => $familia,
                ':descripcion' => $descripcion,
                ':id' => $id
            ]);
        } catch (PDOException $ex) {
            if ($ex->getcode() == 23000) {
                $errorDuplicadoNombreCorto = true;
            } else {
                die("Ocurrio un error al insertar el producto, mensaje de error: " . $ex->getMessage());
            }
        }
        $stmtModificaProducto = null;
    }
}
if (!(isset($productoModificado))) {
    $id = $_POST['id'];
    $consultaFamilias = "select cod, nombre from familias order by nombre";
    $stmtObtenerFamilias = $conProyecto->prepare($consultaFamilias);
    $consultaDatosProducto = "select * from productos where id=:id";
    $stmtObtenerDatosProducto = $conProyecto->prepare($consultaDatosProducto);
    try {
        $stmtObtenerFamilias->execute();
        $stmtObtenerDatosProducto->execute([':id' => $id]);
    } catch (PDOException $ex) {
        die("Error al recuperar el producto" . $ex->getMessage());
    }
    $producto = $stmtObtenerDatosProducto->fetch(PDO::FETCH_OBJ); //no hace falta while, esta consulta devuelve una fila.
    $familias = array();
    while ($familia = $stmtObtenerFamilias->fetch(PDO::FETCH_OBJ)) {
        $familias[] = $familia;
    }
}
$conProyecto = null;
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <!-- css para usar Bootstrap -->
        <link rel="stylesheet"
              href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
              integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <title>Update</title>
    </head>
    <body style="background: #4dd0e1">
        <h3 class="text-center mt-2 font-weight-bold">Modificar Producto</h3>
        <div class="container mt-3">
            <?php if (isset($productoModificado) && $productoModificado): ?>
                <p class='text-info font-weight-bold'>Producto modificado con éxito</p>
                <a href="listado.php" class="btn btn-info">Volver</a>
            <?php else: ?>
                <form method="POST" action="<?= "{$_SERVER['PHP_SELF']}" ?>">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <input type="hidden" name="id" value="<?= $id ?>" >
                            <label for="nombre">Nombre</label>
                            <input type="text" class="<?= "form-control " . ((isset($error) && (empty($nombre))) ? "is-invalid" : "") ?>" 
                                   id="nombre" placeholder="Nombre" name="nombre" value="<?= (isset($producto)) ? $producto->nombre : $nombre ?>" >
                            <div class="col-sm-10 invalid-feedback">
                                <p>Introduce nombre</p>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="nombre_corto">Nombre Corto</label>
                            <input type="text" class="<?= "form-control " . ($errorDuplicadoNombreCorto || (isset($error) && (empty($nombreCorto))) ? "is-invalid" : "") ?>"
                                   id="nombre_corto" value = "<?= (isset($producto)) ? $producto->nombre_corto : $nombre_corto ?>" name="nombre_corto" >
                            <div class="col-sm-10 invalid-feedback">
                                <p><?= (isset($errorDuplicadoNombreCorto) && $errorDuplicadoNombreCorto) ? "Nombre corto duplicado" : "Introduce nombre corto" ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="pvp">Precio (€)</label>
                            <input type="number" class="<?= "form-control " . ((isset($error) && (empty($pvp))) ? "is-invalid" : "") ?>" 
                                   id="pvp" value='<?= $producto->pvp ?>' name="pvp" min="0" step="0.01" >
                            <div class="col-sm-10 invalid-feedback">
                                <p>Introduce PVP</p>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="familia">Familia</label>
                            <select class="form-control" name="familia">
                                <?php foreach ($familias as $familia): ?>
                                <option value='<?= $familia->cod ?>' <?= ($familia->cod == $producto->familia) ? "selected" : "" ?>><?= $familia->nombre ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-9">
                            <label for="d">Descripción</label>
                            <textarea class="form-control" name="descripcion" id="d" rows="12">
                                <?= $producto->descripcion ?>
                            </textarea>
                        </div>
                    </div>
                    <input type="submit" class="btn btn-primary mr-3" name="modificar" value="Modificar">
                    <input type="submit" class="btn btn-info" formaction="listado.php" value="Volver" >
                </form>
            <?php endif ?>
        </div>
    </body>
</html>