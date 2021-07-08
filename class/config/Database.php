<?php

namespace App\config;
use \PDO;
use App\model\Produits;
use Exception;
use PDOException;

class Database {

    
    const CONFIG = 'mysql:dbname=api_rest;host=127.0.0.1:3308';
    const ID_DB = 'root';
    const PASS_DB = '';
    const ERRMODE = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
    const TABLE = 'produits';

    private $pdo;

   public function __construct ()
   {
       if($this->pdo === null) {
           try {  
               $this->pdo = new PDO($this::CONFIG , $this::ID_DB , $this::PASS_DB , $this::ERRMODE);
           } catch (PDOException $e) {
               echo "Erreur de connexion : " . $e->getMessage();
           }
       }
   }

   public function get_all ()
   {
      $query_string = "SELECT * FROM " . $this::TABLE . " ORDER BY created_at DESC";
      $query_r = $this->pdo->query($query_string);
      $results = $query_r->fetchAll(PDO::FETCH_CLASS , Produits::class);
      if($results === false) {
           return false;
      }
      return $results;
   }

   public function get_one (int $id)
   {
      $query_string = "SELECT * FROM " . $this::TABLE .  " WHERE id = :id";
      $query_r = $this->pdo->prepare($query_string);
      $query_r->execute(['id' => $id]);
      $query_r->setFetchMode(PDO::FETCH_CLASS , Produits::class);
      $result =  $query_r->fetch();
      if($result === false) {
           return false;
      }
      return $result;
   }

   public function create (string $nom , string $description , string $categorie_id , string $prix) : bool
   {
       $query_string = "INSERT INTO " . $this::TABLE . " SET nom = :nom , description = :description , categorie_id = :categorie_id , created_at = now() , updated_at = now() , prix = :prix";
       $query = $this->pdo->prepare($query_string);
        //on vérifie si pas de probleme , si pb on renvoie directement false
       if($query !== false) {
           $state =$query->execute(['nom' => $nom , 'description' => $description , 'categorie_id' => $categorie_id, "prix" => $prix]);
       }else {
           return false;
       }
        //on regarde si la requête a été faite correctement 
       if($state === false && !$query->rowCount()) {
           return false;
       }else {
           return true;
       }
   }

   public function delete (string $id) :bool 
   {
       $query_string = "DELETE FROM " . $this::TABLE . " WHERE id = :id";
       $query = $this->pdo->prepare($query_string);
       $state = $query->execute(['id' => $id]);
       if( !$state ) {
           return false;
       }     
           return true;                   
   }

   public function update (string $nom , string $description ,string $categorie_id ,string $prix ,string $id) 
   {
       $query_string = "UPDATE " . $this::TABLE . " SET nom = :nom , description = :description , categorie_id = :categorie_id ,updated_at = now(), prix = :prix WHERE id = :id";
       $query = $this->pdo->prepare($query_string);
       // On sécurise les données
       $nom = htmlspecialchars(strip_tags($nom));
       $description = htmlspecialchars(strip_tags($description));
       $categorie_id = htmlspecialchars(strip_tags($categorie_id));
       $prix = htmlspecialchars(strip_tags($prix));
       $id = htmlspecialchars(strip_tags($id));
       // on peut ensuite attacher les variables
       $query->bindParam(':nom' , $nom);
       $query->bindParam(':description' , $description);
       $query->bindParam(':categorie_id' , $categorie_id);
       $query->bindParam(':prix' , $prix);
       $query->bindParam(':id' , $id);
       // on exécute avec en même temps la vérif pour savoir si tout est ok
        if(!$query->execute()){
            throw new Exception("Un probleme est survenue avec update");
        }
       if($query->rowCount() <= 0) {
           return false;
       }
       return true;
   }

   public function verif_apikey (string $key): bool
   {
        $query_string = "SELECT * FROM " . "api_key";
        $query = $this->pdo->query($query_string);
        $result = $query->fetch(PDO::FETCH_OBJ);
        if(!$result) {
            throw new Exception("Un problème est survenue lors de la récupération de la clé d'api");
        }
        $keyOrigine =  $result->apikey;
        if($key === $keyOrigine) {
            return true;
        }
        return false;
   }
}