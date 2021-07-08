<?php

use App\config\Database;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST , OPTIONS");


if($_SERVER["REQUEST_METHOD"] == 'POST' || $_SERVER['REQUEST_METHOD'] == "OPTIONS") {
    // on vérifie que l'on a bien les data
        $data = json_decode(file_get_contents("php://input"));
       
    if(!empty($data->nom) && !empty($data->description) && !empty($data->categorie_id) && !empty($data->prix)) {
        $database = new Database();
        $state = $database->create($data->nom ,$data->description ,$data->categorie_id ,$data->prix);
        if($state) {
            // Ici la création a fonctionné
            // On envoie un code 201
            http_response_code(201);
            echo json_encode(["message" => "L'ajout a ete effectue correctement"]);
        }else {
             // Ici la création n'a pas fonctionné
            // On envoie un code 503
            http_response_code(503);
            echo json_encode(["message" => "L'ajout n'a pas ete effectue, un probleme est survenue"]);
        }
    }
}else {
     // Mauvaise méthode, on gère l'erreur
    http_response_code(405);
    echo json_encode(["message" => "La methode n'est pas un Post et n'est donc pas autorisee sur cette url"]);
}