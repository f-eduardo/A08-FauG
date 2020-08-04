<?php

$host = "localhost:3308";
$dbname = "a08"; 
$login = "root";
$mdp = "";


// connexion à la bdd
try{
    $db = new PDO('mysql:host='.$host.';dbname='.$dbname.';charset=UTF8',$login,$mdp);
}

catch(Exception $e){
    die('erreur:'.$e->getMessage());

}
