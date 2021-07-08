<?php

require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
use App\config\Database;


header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: DELETE , OPTIONS');

if($_SERVER['REQUEST_METHOD'] == 'DELETE' || $_SERVER['REQUEST_METHOD'] == "OPTIONS") {
     
    $database = new Database();
    $state = $database->delete($id); 
    if($state === false) {
        http_response_code(400);
        echo json_encode(["message" => "La requete a rencontre un problème ,  aucune ligne affectee." ]);       
    }else {       
        http_response_code(200);
        echo json_encode(["message" => "La suppression c'est correctement deroulee" ]);       
    }
}else {
     // Mauvaise méthode, on gère l'erreur
    http_response_code(405);
    echo json_encode(["message" => "La methode n'est pas un DELETE et n'est donc pas autorisee sur cette url."]);
}