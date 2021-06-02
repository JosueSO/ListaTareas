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
        <h2 class="text-center">Editar tarea</h2>

        <form>
            <div class="form-group row">
                <div class="col-12 col-md-4 pt-0">
                    <label for="category">Categoría</label>
                </div>
                <div class="col-12 col-md-8 pt-0">
                    <select name="category" id="category" class="form-control">
                        <option value="">-- TODAS --</option>
                    </select>
                </div>
            </div>
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
                    <label for="date">Fecha</label>
                </div>
                <div class="col-12 col-md-8 pt-0">
                    <input type="date" class="form-control" name="date" id="date">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-12 col-md-4 pt-0">
                    <label for="desciption">Descripción</label>
                </div>
                <div class="col-12 col-md-8 pt-0">
                    <textarea name="desciption" class="form-control" rows="3" id="desciption"></textarea>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-12 col-md-8 offset-0 offset-md-4 text-center text-md-left pt-0">
                    <button type="button" class="btn btn-primary" onclick="saveTask()">Guardar</button>
                    <button type="button" class="btn btn-danger" onclick="goList()">Cancelar</button>
                </div>
            </div>
        </form>
    </div>

    <?php include('../Layout/footer.php') ?>

    <script>
        const task_id = "" + <?php echo $_GET["id"] ?> + "";
        const boxCategory = document.getElementById("category");
        const inputName = document.getElementById("name");
        const inputDate = document.getElementById("date");
        const inputDescription = document.getElementById("desciption");

        getCategoryList();

        getTask();

        function getTask() {
            var xhttp = new XMLHttpRequest();

            xhttp.open("GET", "/lista_tareas/Controllers/tareasController.php?id=" + task_id, false);

            xhttp.onreadystatechange = function() {
                if (this.readyState == 4) {
                    var response = JSON.parse(this.responseText);

                    if (this.status == 200) {
                        loadTask(response.data);
                    }
                    else {
                        alert(response.messages[0]);
                    }
                }
            };

            xhttp.send();
        }

        function loadTask(task) {
            boxCategory.value = task.categoria_id;
            inputName.value = task.nombre;
            inputDate.value = task.fecha;
            inputDescription.value = task.descripcion;
        }

        function saveTask() {
            //Guardar 
            var categoria_id = boxCategory.value;
            var nombre = inputName.value;
            var fecha = inputDate.value == "" ? null : inputDate.value;
            var descripcion = inputDescription.value;

            var json = {
                categoria_id: categoria_id,
                nombre: nombre,
                fecha: fecha,
                descripcion: descripcion
            };

            var xhttp = new XMLHttpRequest();

            xhttp.open("PUT", "/lista_tareas/Controllers/tareasController.php?id=" + task_id, false);

            xhttp.onreadystatechange = function() {
                if (this.readyState == 4) {
                    var response = JSON.parse(this.responseText);

                    if (this.status == 200) {
                        goList();
                    }
                    else if (this.status == 500) {
                        alert(response.messages[0]);
                    }
                    else if (this.status == 400) {
                        alert(response.messages[0]);
                    }
                }
            };

            xhttp.setRequestHeader("Content-Type", "application/json");

            xhttp.send(JSON.stringify(json));
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

        function goList() {
            window.location.href = "/lista_tareas/Views/Tareas/";
        }
    </script>
</body>
</html>