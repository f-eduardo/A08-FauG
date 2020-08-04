<h2>La liste des films</h2>
<?php

$sql = $db->prepare("SELECT movies.name FROM movies");
$sql->execute();
$tableau=$sql->FetchAll(PDO::FETCH_ASSOC);

foreach ($tableau as $row){ // fetchall renvoie toujours un tableau contenant tous tes résultats, et chaque résultat est à son tour un tableau. Le premier foreach est pour faire une boucle de chacun de ces tableau, j'ai juste besoin de référencer la ligne ($row) pour le foreach imbriqué, je n'ai pas besoin de valeur à ce moment là, pour ça que j'ai pas de "=> $val"

    foreach ($row as $key => $val){ // Et ici étant à l'intérieur du foreach, je me trouve dans un des résultats de la requête, qui se présente sous forme d'un tableau, et là pour le coup j'ai besoin du nom de colonne MYSQL et de la valeur
      echo "<p>".$val."</p>";
    }  
  
}
?>