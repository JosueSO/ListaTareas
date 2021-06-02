<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css" integrity="sha384-SZXxX4whJ79/gErwcOYf+zWLeJdY/qpuqC4cAa9rOGUstPomtqpuNWT9wdPEn2fk" crossorigin="anonymous">
    <title>Listado</title>
</head>
<body>
    <?php include('../Layout/header.php') ?>

    <div class="w-100 w-md-50 mx-auto py-2 mb-5">
        <h2 class="text-center">Agregar Usuario</h2>

        <form method="POST" enctype="multipart/form-data" action="/lista_tareas/Controllers/agregarUsuariosController.php">
            <div class="form-group row">
                <div class="col-12 col-md-4 pt-0">
                    <label for="name">Nombre</label>
                </div>
                <div class="col-12 col-md-8 pt-0">
                    <input type="text" class="form-control" name="name" id="name">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-12 col-md-4 pt-0">
                    <label for="password">Contraseña</label>
                </div>
                <div class="col-12 col-md-8 pt-0">
                    <input type="password" class="form-control" name="password" id="password">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-12 col-md-4 pt-0">
                    <label for="photo">Foto</label>
                </div>
                <div class="col-12 col-md-8 pt-0">
                    <input type="file" class="form-control" name="photo" id="photo" accept=".jpeg, .jpg">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-12 col-md-8 offset-0 offset-md-4 text-center text-md-left pt-0">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </form>
    </div>

    <?php include('../Layout/footer.php') ?>
</body>
</html>