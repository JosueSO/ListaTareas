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
    $nombre = $_POST["name"];
    $contrasena = $_POST["password"];
    $foto = "";

    if (sizeof($_FILES) > 0)
    {
        $tmp_name = $_FILES["photo"]["tmp_name"];
        $foto = file_get_contents($tmp_name);        
    }

    $query = $connection->prepare('INSERT INTO usuarios VALUES(NULL, :nombre, :contrasena, :foto)');
    $query->bindParam(':nombre', $nombre, PDO::PARAM_STR);
    $query->bindParam(':contrasena', $contrasena, PDO::PARAM_STR);
    $query->bindParam(':foto', $foto, PDO::PARAM_STR);
    $query->execute();
    
    $rowCount = $query->rowCount();

    if ($rowCount == 0) {
        header("Location: http://localhost/lista_tareas/Views/Usuarios/?error=No se pudo insertar el usuario");

        exit();
    }

    header("Location: http://localhost/lista_tareas/Views/Usuarios/");
    exit();
}