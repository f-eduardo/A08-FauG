<section>
<h2>La liste des films</h2>
<div class="container-fluid d-flex justify-content-around flex-wrap">

<?php

$sql = $db->prepare("SELECT movies.image, movies.id FROM movies");
$sql->execute();
$tableau=$sql->FetchAll(PDO::FETCH_ASSOC);

foreach ($tableau as $row){ // fetchall renvoie toujours un tableau contenant tous tes résultats, et chaque résultat est à son tour un tableau. Le premier foreach est pour faire une boucle de chacun de ces tableau, j'ai juste besoin de référencer la ligne ($row) pour le foreach imbriqué, je n'ai pas besoin de valeur à ce moment là, pour ça que j'ai pas de "=> $val"

      echo '<a href ="index.php?id='.$row['id'].'"> <img src="./uploads/'.$row["image"].'"</a>';
    }  
  

?>
</div>
</section>