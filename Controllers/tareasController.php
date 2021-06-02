<?php 

require_once("../Models/db.php");
require_once("../Models/Tarea.php");
require_once("../Models/Response.php");

try {
    $connection = DB::getConnection();
}
catch(PDOException $e) {
    error_log("Connection error - " . $e, 0);

    $response = new Response();
    $response->setHttpStatusCode(500);
    $response->addMessage("Error de conexión a la BD");
    $response->send();

    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (array_key_exists("id", $_GET)) {
        //Entra si se manda un ID en la ruta
        $id = $_GET["id"];

        if ($id == '' || !is_numeric($id)) {
            $response = new Response();
            $response->setHttpStatusCode(400);
            $response->addMessage("El id debe contener un valor y ser numérico");
            $response->send();

            exit();
        }

        try {
            $query = $connection->prepare('SELECT * FROM tareas WHERE id = :id');
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            $query->execute();
    
            $rowCount = $query->rowCount();

            if ($rowCount == 0) {
                $response = new Response();
                $response->setHttpStatusCode(404);
                $response->addMessage("Tarea no encontrada");
                $response->send();

                exit();
            }
    
            while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $tarea = new Tarea($row["id"], $row["nombre"], $row["descripcion"], $row["fecha"], $row["completada"], $row["categoria_id"], 0);
    
                $arreglo_tareas[] = $tarea->returnArray();
            }

            $response = new Response();
            $response->setHttpStatusCode(200);
            $response->setData($arreglo_tareas[0]);
            $response->send();

            exit();
        }
        catch(PDOException $e) {
            error_log("Query error - " . $e, 0);
            
            $response = new Response();
            $response->setHttpStatusCode(500);
            $response->addMessage("Error al consultar la tarea");
            $response->send();

            exit();
        }
    }
    else {
        //Consulta todos los registros
        try {
            $query = $connection->prepare('SELECT * FROM tareas');
            $query->execute();
    
            $arreglo_tareas = array();
    
            while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $tarea = new Tarea($row["id"], $row["nombre"], $row["descripcion"], $row["fecha"], $row["completada"], $row["categoria_id"], 0);
    
                $arreglo_tareas[] = $tarea->returnArray();
            }
    
            $response = new Response();
            $response->setHttpStatusCode(200);
            $response->setData($arreglo_tareas);
            $response->send();

            exit();
        }
        catch(PDOException $e) {
            error_log("Connection error - " . $e, 0);
            
            $response = new Response();
            $response->setHttpStatusCode(500);
            $response->addMessage("Error al consultar las tareas");
            $response->send();

            exit();
        }
    }
}
else if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $json_string = file_get_contents('php://input');
    $json_obj = json_decode($json_string);
    
    if ($json_obj->nombre == null || $json_obj->nombre == "") {
        
        $response = new Response();
        $response->setHttpStatusCode(400);
        $response->addMessage("El nombre no puede ser null o estar vacío");
        $response->send();

        exit();
    }

    if ($json_obj->categoria_id == null || !is_numeric($json_obj->categoria_id)) {
        
        $response = new Response();
        $response->setHttpStatusCode(400);
        $response->addMessage("La categoría no puede ser null y debe ser numérico");
        $response->send();

        exit();
    }

    if ($json_obj->descripcion == null || $json_obj->descripcion == "") {
        
        $response = new Response();
        $response->setHttpStatusCode(400);
        $response->addMessage("La descripción no puede ser null o estar vacía");
        $response->send();

        exit();
    }

    $nombre = $json_obj->nombre;
    $categoria_id = $json_obj->categoria_id;
    $descripcion = $json_obj->descripcion;
    $fecha = $json_obj->fecha;
    $completada = 0;
    
    try {
        $query = $connection->prepare('INSERT INTO tareas VALUES(NULL, :nombre, :descripcion, :fecha, :completada, :categoria_id)');
        $query->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $query->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
        $query->bindParam(':fecha', $fecha, PDO::PARAM_STR);
        $query->bindParam(':completada', $completada, PDO::PARAM_INT);
        $query->bindParam(':categoria_id', $categoria_id, PDO::PARAM_INT);
        $query->execute();
    
        $rowCount = $query->rowCount();

        if ($rowCount == 0) {
            $response = new Response();
            $response->setHttpStatusCode(500);
            $response->addMessage("Error al crear la tarea");
            $response->send();

            exit();
        }

        $response = new Response();
        $response->setHttpStatusCode(201);
        $response->addMessage("Tarea creada con éxito");
        $response->send();

        exit();
    }
    catch(PDOException $e) {
        error_log("Query error - " . $e, 0);

        $response = new Response();
        $response->setHttpStatusCode(500);
        $response->addMessage("Error al crear la tarea");
        $response->send();

        exit();
    }
}
else if ($_SERVER["REQUEST_METHOD"] == "PUT") {
    if (!array_key_exists("id", $_GET)) {
        $response = new Response();
        $response->setHttpStatusCode(400);
        $response->addMessage("Id no especificado en la ruta");
        $response->send();

        exit();
    }

    $id = $_GET["id"];

    if ($id == '' || !is_numeric($id)) {
        $response = new Response();
        $response->setHttpStatusCode(400);
        $response->addMessage("El id debe contener un valor y ser numérico");
        $response->send();

        exit();
    }

    $json_string = file_get_contents('php://input');
    $json_obj = json_decode($json_string);
    
    if ($json_obj->nombre == null || $json_obj->nombre == "") {
        
        $response = new Response();
        $response->setHttpStatusCode(400);
        $response->addMessage("El nombre no puede ser null o estar vacío");
        $response->send();

        exit();
    }

    if ($json_obj->categoria_id == null || !is_numeric($json_obj->categoria_id)) {
        
        $response = new Response();
        $response->setHttpStatusCode(400);
        $response->addMessage("La categoría no puede ser null y debe ser numérico");
        $response->send();

        exit();
    }

    if ($json_obj->descripcion == null || $json_obj->descripcion == "") {
        
        $response = new Response();
        $response->setHttpStatusCode(400);
        $response->addMessage("La descripción no puede ser null o estar vacía");
        $response->send();

        exit();
    }

    $nombre = $json_obj->nombre;
    $categoria_id = $json_obj->categoria_id;
    $descripcion = $json_obj->descripcion;
    $fecha = $json_obj->fecha;
    
    try {
        $query = $connection->prepare('UPDATE tareas SET nombre = :nombre, descripcion = :descripcion, fecha = :fecha, categoria_id = :categoria_id WHERE id = :id');
        $query->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $query->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
        $query->bindParam(':fecha', $fecha, PDO::PARAM_STR);
        $query->bindParam(':categoria_id', $categoria_id, PDO::PARAM_INT);
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
    
        $rowCount = $query->rowCount();

        if ($rowCount == 0) {
            $response = new Response();
            $response->setHttpStatusCode(500);
            $response->addMessage("Error al actualizar la tarea");
            $response->send();

            exit();
        }

        $response = new Response();
        $response->setHttpStatusCode(200);
        $response->addMessage("Tarea actualizada con éxito");
        $response->send();

        exit();
    }
    catch(PDOException $e) {
        error_log("Query error - " . $e, 0);

        $response = new Response();
        $response->setHttpStatusCode(500);
        $response->addMessage("Error al actualizar la tarea");
        $response->send();

        exit();
    }
}

?>