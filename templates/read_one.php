<?php

require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
use App\config\Database;


header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");

if($_SERVER['REQUEST_METHOD'] == 'GET') {
    // La bonne méthode est utilisée
    $database = new Database();
    $result = $database->get_one($id);
    if($result === false) {
        http_response_code(400);
        echo json_encode(["message" => "Aucun utilisateur ne corespond à cette id"]);
    }else {
        echo json_encode($result);
    }
}else {
     // Mauvaise méthode, on gère l'erreur
    http_response_code(405);
    echo json_encode(["message" => "La méthode n'est pas un GET et n'est donc pas autoriséé sur cette url"]);
}