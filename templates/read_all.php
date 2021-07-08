<?php

require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
use App\config\Database;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");

if($_SERVER['REQUEST_METHOD'] == 'GET'){
    if(!empty($_GET['apikey'])) {       
        try {
            $database = new Database();
            if($database->verif_apikey($_GET['apikey'])) {
                http_response_code(200);
                $results = $database->get_all();
                echo json_encode($results);
            } else {
                http_response_code(401);
                echo json_encode(['alert' => 'La cle d\'api fournie n\'est pas correcte']);
            }            
        } catch (Exception $e) {
                http_response_code(500);
                echo json_encode(['error' => 'Une erreur est survenue' , 'message' => $e->message]);
        }
    }else {
            http_response_code(401);
            echo json_encode(['alert' => 'Une cle d\'api est requise en parametre de la requete']);
    }
    
}else{
    // Mauvaise méthode, on gère l'erreur
    http_response_code(405);
    echo json_encode(["message" => "La methode n'est pas un GET et n'est donc pas autorisee sur cette url"]);
}