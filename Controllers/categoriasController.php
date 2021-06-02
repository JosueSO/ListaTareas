<?php 

require_once("../Models/db.php");
require_once("../Models/Categoria.php");
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
            exit();
        }

        try {
            $query = $connection->prepare('SELECT * FROM tareas WHERE id = :id');
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            $query->execute();
    
            $rowCount = $query->rowCount();

            if ($rowCount == 0) {
                exit();
            }
    
            while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $tarea = new Tarea($row["id"], $row["nombre"]);
    
                $arreglo_tareas[] = $tarea->returnArray();
            }
    
            echo json_encode($arreglo_tareas[0]);
            exit();
        }
        catch(PDOException $e) {
            error_log("Connection error - " . $e, 0);
            
            exit();
        }
    }
    else {
        //Consulta todos los registros
        try {
            $query = $connection->prepare('SELECT * FROM categorias');
            $query->execute();
    
            $arreglo_categorias = array();
    
            while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $categoria = new Categoria($row["id"], $row["nombre"]);
    
                $arreglo_categorias[] = $categoria->returnArray();
            }
    
            $response = new Response();
            $response->setHttpStatusCode(200);
            $response->setData($arreglo_categorias);
            $response->send();

            exit();
        }
        catch(PDOException $e) {
            error_log("Query error - " . $e, 0);
            
            $response = new Response();
            $response->setHttpStatusCode(500);
            $response->addMessage("Error al consultar lista de categorías");
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

    $nombre = $json_obj->nombre;
    
    try {
        $query = $connection->prepare('INSERT INTO categorias VALUES(NULL, :nombre)');
        $query->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $query->execute();
    
        $rowCount = $query->rowCount();

        if ($rowCount == 0) {
            $response = new Response();
            $response->setHttpStatusCode(500);
            $response->addMessage("Error al crear la categoría");
            $response->send();

            exit();
        }

        $response = new Response();
        $response->setHttpStatusCode(201);
        $response->addMessage("Categoría creada con éxito");
        $response->send();

        exit();
    }
    catch(PDOException $e) {
        error_log("Query error - " . $e, 0);

        $response = new Response();
        $response->setHttpStatusCode(500);
        $response->addMessage("Error al crear la categoría");
        $response->send();

        exit();
    }
}

?>