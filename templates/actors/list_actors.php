<?php

    // si un poster est cliqué :

    if($_GET["list_actors"] > 0) {
        
        // je sauvagarde l'id du fimm dans la variable $movieID =>
        
        $actorID = $_GET['list_actors'];

        // je code la requete sql qui selectionne toutes les infos depuis la table actors, en jointure avec la table phase
        // la colonne qui contient dans movies.id l'id de ma variable $mooviesID :

        $sqlActorClicked ="SELECT * FROM `actors` JOIN actors_movies ON actors.id = actors_movies.id_actors  WHERE actors.id = $actorID ";
        $sqlActorClicked= $db->query($sqlActorClicked);

        // je sauvegarde le résultat de la requête dans un tableau:

        $ActorClicked= $sqlActorClicked->fetch(PDO::FETCH_ASSOC);

        $actorsFirstName = $ActorClicked["first_name"];
        $actorsLastName = $ActorClicked["last_name"];
        $actorsDob = $ActorClicked["dob"];
        $actorsImage = $ActorClicked["image"];

        // je code la requête pour obtenir tous les films dans lesquels l'acteur joue
        $sqlMoviesPlayed ="SELECT movies.name, movies.image, actors_movies.role FROM `actors_movies` JOIN  movies ON movies.id = actors_movies.id_movie JOIN actors ON actors_movies.id_actors = actors.id   WHERE actors.id = $actorID ";
        $sqlMoviesPlayed= $db->query($sqlMoviesPlayed);
        $tableauMoviesPlayed = $sqlMoviesPlayed->fetchAll(PDO::FETCH_ASSOC);

        
        
        echo '        
            <div id="blackboard" class="blackboard flexCenter">
                <div class="dataBoard">
                
                    <div class="movieOrActorPoster">
                        <div> 
                            <img src="./uploads/'.$ActorClicked["image"].'">
                        </div>
                    </div>

                    <div class="flexCenter flexColumn spaceAround flexStart">
                        
                        <div class="movieOrActorDatas">
                            <p>
                                PRENOM :'.$actorsFirstName.'
                            </p>
                            <p>
                                NOM :'.$actorsLastName.'
                            </p>
                            <p>
                                NE(E) LE :' .$actorsDob.'
                            </p>
                        </div>
                    

                        <div class="movieDatas flexCenter flexColumn flexStart">
                                
                            <p>NAVETOGRAPHIE :</p>
                                
                            <div class="flexCenter flexStart flexWrap">';

                                foreach($tableauMoviesPlayed as $row){
                                    
                                    echo '
                                    <div class="flexCenter flexStart">

                                        <div class="imgLink small">
                                            <img src="./uploads/'.$row["image"].'">
                                        </div>

                                        <div>
                                            <br>
                                            <p> TITRE DU FILM: '.$row["name"].'</p>
                                            <p> ROLE: '.$row["role"].'</p><br>
                                        </div>

                                    </div>';
                                }; echo '

                            </div>
                        </div>

                    <div class="buttonsBlock flexCenter">
                            
                        <div id="editButton" class="button">            
                            MODIFIER
                        </div>

                        <div id="deletButton" class="button">            
                            SUPPRIMER
                        </div>

                        <div id="backButton" class="button">            
                            <a href="index.php?list_actors">RETOUR</a>
                        </div>
                                        
                    </div>
                </div>                        
            </div>
            </div>

            <div id="blackboardEdit" class="blackboard flexCenter translateX-100">

                <div class="formBoard flexCenter spaceAround">

                    <div class="moviePoster">
                        <div> 
                            <img src="./uploads/'.$actorsImage.'">
                        </div>
                    </div>

                    <div class="movieDatas">
                        <form class="formFlex" action="./index.php" method="post" enctype="multipart/form-data">

                            <div>
                                <p>
                                    MODIFIER LES INFOS De l\'ACTEUR
                                </p>
                            </div>

                            <div class="newMovieDatasField">
                                <div>
                                    <input name="actorID" type="hidden" value="'.$actorID.'">
                                </div>

                                <div class="formInputFields">          
                                    <label for="last_name">NOM DE L\'ACTEUR</label>                
                                    <input type="text" class="imputField" name="last_name" id="last_name" value="'.$actorsLastName.'">
                                </div>

                                <div class="formInputFields">          
                                    <label for="first_name">PRENOM DE L\'ACTEUR</label>                
                                    <input type="text" class="imputField" name="first_name" id="first_name" value="'.$actorsFirstName.'">
                                </div>
                  
                                <div class="formInputFields">
                                    <label for="dob">DATE DE NAISSANCE</label>
                                    <input type="text" class="imputField" id="dob" name="dob" VALUE="'.$actorsDob.'">
                                </div>

                                <div class="formDatasInputFields">
                                    <label for="uploadedImg"> POSTER(jpg/png)</label>
                                    <input type="file" name="uploadedImg" id="uploadedImg">
                                </div>
                            </div>

                            <div class="addActorsDatafield">
                                <div>
                                    <p>MODIFIER LES FILMS</P>
                                </div>

                                <div class="checkBoxField">';

                                    $sqlAllMovies = "SELECT movies.name, movies.id FROM movies";
                                    $sqlAllMovies= $db->query($sqlAllMovies);
                                    $allMovies = $sqlAllMovies->fetchAll(PDO::FETCH_ASSOC);

                                    $sqlAllmoviesFromThisActor = "SELECT movies.id FROM actors_movies JOIN movies ON movies.id = actors_movies.id_movie WHERE actors_movies.id_actors = $actorID";
                                    $sqlAllmoviesFromThisActor= $db->query($sqlAllmoviesFromThisActor);
                                    $moviesFromThisActor = $sqlAllmoviesFromThisActor->fetchAll(PDO::FETCH_ASSOC);

                                    if(!is_null($moviesFromThisActor) ){
                                        foreach($moviesFromThisActor as $key){
                                        $idmoviesFromThisActor[] = $key["id"];
                                        };
                                    };

                        
                                    foreach($allMovies as $row2){
                                        if(isset($idmoviesFromThisActor)){
                                            if(in_array($row2["id"], $idmoviesFromThisActor)){
                                                echo' 
                                                <div>
                                                    <input type="checkbox" id="selectMovies[]" checked name="selectMovies[]" value="'.$row2["id"].'" >
                                                    <label for="selectMovies[]">'.$row2["name"].'</label>
                                                </div>'; 
                                            }
                                            else{
                                                echo'
                                                <div>
                                                    <input type="checkbox" id="selectMovies[]" name="selectMovies[]" value="'.$row2["id"].'" >
                                                    <label for="selectMovies[]">'.$row2["name"].'</label>
                                                </div>';
                                            }
                                        }
                                        else{
                                        echo'
                                            <div>
                                                <input type="checkbox" id="selectMovies[]" name="selectMovies[]" value="'.$row2["id"].'" >
                                                <label for="selectMovies[]">'.$row2["name"].'</label>
                                            </div>';
                                        };
                                    };echo'
                                </div>

                            </div>

                            <div class="buttonsBlock alignCenter">

                                <div>
                                    <input class=""  name = "isSubmit-editActors" type="submit" value="MODIFIER L\'acteur" >   
                                </div>

                                <div id="backButton" class="button">            
                                    <a href="index.php?list_actors">ANNULER</a>
                                </div>

                            </div>
                        </form>
                  
                    </div>

                </div>

            </div>
        ';

    }else{}
?>
<div id="blackboardActors" class="blackboard flexCenter translateX-100">
    <div class="formBoard flexCenter ">
        <form class="form flexCenter flexColumn" action="./index.php" method="post" enctype="multipart/form-data">

            <div>
                <p>
                    AJOUTER LES INFOS D'UN ACTEUR
                </p>
            </div>

            <div class="newMovieDatasField">


                <div class="formDatasInputFields">          
                    <label for="last_name">NOM </label>                
                    <input type="text" class="imputField" name="last_name" id="last_name" placeholder="'nom de l'acteur'">
                </div>

                <div class="formDatasInputFields">          
                    <label for="first_name">PRENOM </label>                
                    <input type="text" class="imputField" name="first_name" id="first_name" placeholder=" prénom de l'acteur">
                </div>


                <div class="formDatasInputFields">
                        <label for="dob">NE(E) LE</label>
                        <input type="date" class="imputField" id="dob" name="dob" value="2018-07-22" min="1900-01-01" max="2020-07-01" placeholder="'date de naissance">
                    </div>

                <div class="formDatasInputFields">
                    <label for="uploadedImg"> PHOTO(jpg/png)</label>
                    <input type="file" name="uploadedImg" id="uploadedImg">
                </div>
            </div>
            <div class="addActorsDatafield">
                <div>
                    <p>ASSOCIER DES NAVETS A L'ACTEUR</P>
                </div>
                <div class="checkBoxField">
                    <?php 
                        //var_dump($AllActorsAndRole);
                        
                        $sqlAllMovies = "SELECT movies.name, movies.id FROM movies";
                        $sqlAllMovies= $db->query($sqlAllMovies);
                        $allMovies = $sqlAllMovies->fetchAll(PDO::FETCH_ASSOC);
                        
                        foreach($allMovies as $row2){
                            echo'
                            <div>
                                <input type="checkbox" id="selectMovies[]" name="selectMovies[]" value="'.$row2["id"].'" >
                                <label for="selectMovies[]">'.$row2["name"].'</label>
                            </div>';
                        };                  
                    ?>
                </div>
            </div>
            
            <div class="buttonsBlock alignCenter">

                <div>
                    <input class=""  name = "isSubmit-Actors" type="submit" value="AJOUTER L'ACTEUR" >   
                </div>
                <div id="backButton" class="button">            
                    ANNULER
                </div>

            </div>
        </form>                  
    </div>
</div>


<div id="blackboardDelete" class="blackboard delete " >
    <div id="confirmBox" class="confirmBox ">

        <div class="confirmBoxMessage">
            <p>
                Supprimer pour de bon l'acteur?
            </p>
        </div>

        <div class="buttonsBlock alignCenter">
                                    

            <div id="delet" class="button">            
                <p>
                <a class="nav-link" href="index.php?delActor=<?php echo $actorID; ?> ">Oui!</a>
                </p>
            </div>

            <div id="backButtonDelete" class="button">            
                <p>
                    Non...
                </p>
            </div>
                                                            
        </div> 
    </div>
</div>





<div class=" flexCenter flexColumn spaceAround">
    <?php require("./templates/actorsHeader.html") ?>;
    <div class=" board flexCenter">
        <?php

        $sql = $db->prepare("SELECT actors.image, actors.id FROM actors");
        $sql->execute();
        $tableau=$sql->FetchAll(PDO::FETCH_ASSOC);

        foreach ($tableau as $row){ // fetchall renvoie toujours un tableau contenant tous tes résultats, et chaque résultat est à son tour un tableau. Le premier foreach est pour faire une boucle de chacun de ces tableau, j'ai juste besoin de référencer la ligne ($row) pour le foreach imbriqué, je n'ai pas besoin de valeur à ce moment là, pour ça que j'ai pas de "=> $val"

            echo '
            <a class="imgLink" href ="index.php?list_actors='.$row['id'].'"> <img src="./uploads/'.$row["image"].'"></a>';
            }  
        

        ?>
    </div>
</div>
    
    
