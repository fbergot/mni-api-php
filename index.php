<?php

require_once 'vendor/autoload.php';

$router = new AltoRouter();

// Les routes et méthodes
$router->map('GET' , '/' , '/read_all');
$router->map('GET' , '/produit/[i:id]' , '/read_one');
$router->map('POST|OPTIONS' , '/produit' , '/create');
$router->map('DELETE|OPTIONS' , '/produit/[i:id]' , '/delete');
$router->map('PUT|OPTIONS' , '/produit/update/[i:id]' , '/update');

//on récupère le tableau avec tous les résultats
$match = $router->match();

if($match !== false) {
    if(isset($match['params']['id'])) {
        $id = $match['params']['id'];
    }
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    require 'templates' . $match['target'] . '.php';
}else {
        require 'templates/404.php';
}