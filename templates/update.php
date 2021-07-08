<?php

require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
use App\config\Database;


header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT ,OPTIONS");

if($_SERVER['REQUEST_METHOD'] === 'PUT' || $_SERVER['REQUEST_METHOD'] === 'OPTIONS' ) {
    // La bonne méthode est utilisée
    if($_SERVER['REQUEST_METHOD'] === 'PUT') {
        $data = json_decode(file_get_contents("php://input"));
        if($data->nom !== "" && $data->description !== "" && $data->categorie_id !== "" && $data->prix !== "") {
            $database = new Database();
            try {
                $result = $database->update($data->nom , $data->description , $data->categorie_id , $data->prix , $id);
                if($result === false) {
                    http_response_code(400);
                    echo json_encode(["message" => 'l\'id fourni est peut etre incorrect, aucun changement dans la BD']);            
                }else {
                    http_response_code(200);
                    echo json_encode(["message" => 'La mise a jour a ete effectuee avec succes']);            
                }
            } catch (PDOException|Exception $e) {
                    http_response_code(500);
                    echo json_encode(["etat" => 'Un probleme est survenue' , "message exception" => $e->message]);
            }
        }
    }
}else {
     // Mauvaise méthode, on gère l'erreur
    http_response_code(405);
    echo json_encode(["message" => "La méthode n'est pas un PUT et n'est donc pas autoriséé sur cette url"]);
}