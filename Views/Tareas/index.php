<?php 
    session_start();

    if (!array_key_exists("nombre_usuario", $_SESSION))
    {
        header("Location: http://localhost/lista_tareas/");
        exit();
    }
?>

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

    <?php echo $_SESSION["nombre_usuario"]; ?>

    <?php echo "<img alt='Foto' src=\"data:image/jpeg;base64," . $_SESSION["foto"] . "\" >"; ?>

    <div class="container py-2 mb-5">
        <form class="row align-items-center">
            <div class="col-auto">
                <label for="category">Categoría</label>
            </div>
            <div class="col col-md-auto">
                <select name="category" id="category" class="form-control">
                    <option value="">-- TODAS --</option>
                </select>
            </div>
            <div class="col-12 col-md-auto p-0"></div>
            <div class="col-6 col-md-auto">
                <input type="checkbox" name="check" id="check">
                <label for="check">Ver todas</label>
            </div>
            <div class="col-6 text-right">
                <a href="/lista_tareas/Views/Tareas/nueva.php" class="btn btn-info">
                    Agregar <i class="fas fa-plus"></i>
                </a>
            </div>
        </form>

        <div id="lista_tareas" class="mt-2">
            <!-- <div class="row border-top">
                <div class="col-12 col-md-8">
                    <h3 class="m-0">Nombre de la tarea</h3>
                </div>
                <div class="col-12 col-md-4 text-right">
                    24/05/2021 18:00
                </div>
                <div class="col-12 text-justify">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Nihil alias ad id nam pariatur eos natus illo harum molestiae ex debitis deserunt maiores, corporis voluptates iusto perspiciatis, sequi mollitia ipsum.
                </div>
                <div class="col-12 text-center text-md-right">
                    <form>
                        <div class="form-group">
                            <input type="checkbox" name="" id="" onchange="completeTask(1)">
                            <label for="check">Completada</label>
                            <button type="button" class="btn btn-warning" onclick="goEdit(1)"><i class="fas fa-pencil-alt"></i></button>
                            <button type="button" class="btn btn-danger" onclick="deleteConfirm(1)"><i class="fas fa-trash-alt"></i></button>
                        </div>
                    </form>
                </div>
            </div> -->
        </div>
    </div>

    <?php include('../Layout/footer.php') ?>

    <script>
        const boxCategory = document.getElementById("category");
        const taskList = document.getElementById("lista_tareas");

        boxCategory.addEventListener("change", getTaskList);

        var checkAll = document.getElementById("check");

        checkAll.addEventListener("change", getTaskList);

        getCategoryList();
        getTaskList();

        function getTaskList() {
            var xhttp = new XMLHttpRequest();

            xhttp.open("GET", "/lista_tareas/Controllers/tareasController.php", false);

            xhttp.onreadystatechange = function() {
                if (this.readyState == 4) {
                    var response = JSON.parse(this.responseText);

                    if (this.status == 200) {
                        loadTaskList(response.data);
                    }
                    else if (this.status == 500) {
                        alert(response.messages[0]);
                    }
                }
            };

            xhttp.send();
        }

        function loadTaskList(task_list) {
            var html = "";

            for(var i = 0; i < task_list.length; i++) {
                html +=
                `<div class="row border-top">
                    <div class="col-12 col-md-8">
                        <h3 class="m-0">${task_list[i].nombre}</h3>
                    </div>
                    <div class="col-12 col-md-4 text-right">
                        ${task_list[i].fecha == null ? "" : task_list[i].fecha}
                    </div>
                    <div class="col-12 text-justify">
                        ${task_list[i].descripcion}
                    </div>
                    <div class="col-12 text-center text-md-right">
                        <form>
                            <div class="form-group">
                                <input type="checkbox" name="" id="" onchange="completeTask(${task_list[i].id})" ${task_list[i].completada == 1 ? "checked" : ""}>
                                <label for="check">Completada</label>
                                <button type="button" class="btn btn-warning" onclick="goEdit(${task_list[i].id})"><i class="fas fa-pencil-alt"></i></button>
                                <button type="button" class="btn btn-danger" onclick="deleteConfirm(${task_list[i].id})"><i class="fas fa-trash-alt"></i></button>
                            </div>
                        </form>
                    </div>
                </div>`;
            }

            taskList.innerHTML = html;
        }

        function getCategoryList() {
            //Obtener categorías

            var xhttp = new XMLHttpRequest();

            xhttp.open("GET", "/lista_tareas/Controllers/categoriasController.php", false);

            xhttp.onreadystatechange = function() {
                if (this.readyState == 4) {
                    var response = JSON.parse(this.responseText);

                    if (this.status == 200) {
                        loadCategoryList(response.data);
                    }
                    else if (this.status == 500) {
                        alert(response.messages[0]);
                    }
                }
            };

            xhttp.send();
        }

        function loadCategoryList(category_list) {
            var html = `<option value="">-- TODAS --</option>`;

            for(var i = 0; i < category_list.length; i++) {
                html += `<option value="${category_list[i].id}">${category_list[i].nombre}</option>`;
            }

            boxCategory.innerHTML = html;
        }

        function goEdit(id) {
            window.location.href = "/lista_tareas/Views/Tareas/edit.php?id=" + id;
        }

        function deleteConfirm(id) {
            //Eliminar tarea
        }

        function completeTask(id) {
            //Cambiar estatus de la tarea
        }
    </script>
    <script src="../js/app.js"></script>
</body>
</html>