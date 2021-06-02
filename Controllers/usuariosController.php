<?php 

require_once("../Models/db.php");
require_once("../Models/Usuario.php");
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $json_string = file_get_contents('php://input');
    $json_obj = json_decode($json_string);

    //var_dump($json_obj);
    
    if ($json_obj->nombre_usuario == null || $json_obj->nombre_usuario == "") {
        
        $response = new Response();
        $response->setHttpStatusCode(400);
        $response->addMessage("El nombre de usuario no puede ser null o estar vacío");
        $response->send();

        exit();
    }

    if ($json_obj->contasena == null || $json_obj->contasena == "") {
        
        $response = new Response();
        $response->setHttpStatusCode(400);
        $response->addMessage("La contraseña no puede ser null o estar vacía");
        $response->send();

        exit();
    }

    $nombre_usuario = $json_obj->nombre_usuario;
    $contasena = $json_obj->contasena . "";
    
    try {
        $query = $connection->prepare('SELECT * FROM usuarios WHERE nombre_usuario = :nombre_usuario AND contrasena = :contrasena');
        $query->bindParam(':nombre_usuario', $nombre_usuario, PDO::PARAM_STR);
        $query->bindParam(':contrasena', $contasena, PDO::PARAM_STR);
        $query->execute();
    
        $rowCount = $query->rowCount();

        if ($rowCount == 0) {
            $response = new Response();
            $response->setHttpStatusCode(500);
            $response->addMessage("El nombre de usuario o la contraseña son incorrectos");
            $response->send();

            exit();
        }

        $usuario;

        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $usuario_aux = new Usuario($row["id"], $row["nombre_usuario"], $row["foto"]);

            $usuario[] = $usuario_aux->returnArray();
        }

        session_start();

        $_SESSION["id"] = $usuario_aux->getId();
        $_SESSION["nombre_usuario"] = $usuario_aux->getNombreUsuario();
        $_SESSION["foto"] = $usuario_aux->getFoto();

        $response = new Response();
        $response->setHttpStatusCode(200);
        $response->setData($usuario);
        $response->send();

        exit();
    }
    catch(PDOException $e) {
        error_log("Query error - " . $e, 0);

        $response = new Response();
        $response->setHttpStatusCode(500);
        $response->addMessage("Error al iniciar sesión");
        $response->send();

        exit();
    }
}

?>