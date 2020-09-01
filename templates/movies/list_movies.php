    <?php

        

        if($_GET["list"] > 0) {
            // si un poster est cliqué alors :

            // je sauvagarde d'abord l'id du film dans la variable $movieID =>      
            $movieID = $_GET['list'];

            // je code la requete sql qui selectionne toutes les infos du film dont l'id correspond à $movieID
            $sqlMovieClicked ="SELECT * FROM `movies` JOIN phase ON movies.id_phase = phase.id  WHERE movies.id = $movieID ";
            $sqlMovieClicked = $db->query($sqlMovieClicked);

            // je sauvegarde le résultat de la requête dans  $MovieClickedData :
            $MovieClickedData= $sqlMovieClicked->fetch(PDO::FETCH_ASSOC);
            var_dump($MovieClickedData);

            // je mets chaque valeur du tableau dans une variable
            $movieName = $MovieClickedData["name"];
            $movieDirector = $MovieClickedData["director"];
            $movieDate = $MovieClickedData["release_date"];
            $moviePhase = $MovieClickedData["id_phase"];
            $movieImage = $MovieClickedData["image"];

            // je code la requete sql qui selectionne les infos des acteurs qui ont un role dans le film cliqué:
            $sqlActorsFromThisMovie ="SELECT actors.first_name, actors.last_name, actors.dob, actors.image, actors_movies.role FROM `actors_movies` JOIN  actors ON actors.id = actors_movies.id_actors JOIN movies ON actors_movies.id_movie = movies.id   WHERE movies.id = $movieID ";
            $sqlActorsFromThisMovie= $db->query($sqlActorsFromThisMovie);
            // je sauvegarde le résultat de la requête dans le tableauActors :
            $ActorsFromThisMovie = $sqlActorsFromThisMovie->fetchAll(PDO::FETCH_ASSOC);
 
        
            $sql_all_Actors_and_Role = "SELECT actors.last_name, actors.first_name, actors_movies.role FROM actors_movies JOIN actors ON actors_movies.id_actors = actors.id JOIN movies ON actors_movies.id_movie = movies.id";
            $sql_all_Actors_and_Role= $db->query($sql_all_Actors_and_Role);

            $AllActorsAndRole = $sql_all_Actors_and_Role->fetchAll(PDO::FETCH_ASSOC);
        

            echo '
            
            <div id="blackboard" class="blackboard flexCenter">
                <div class="dataBoard">
                
                    <div class="movieOrActorPoster">
                        <div> 
                            <img src="./uploads/'.$movieImage.'">
                        </div>
                    </div>

                    <div class="flexCenter flexColumn spaceAround flexStart">
                        <div class="movieOrActorDatas">
                            <p>
                                TITRE: '.$movieName.'
                            </p>
                            <p>
                                DIRECTEUR: '.$movieDirector.'
                            </p>
                            <p>
                                SORTIE: ' .$movieDate.'
                            </p>
                            <p>
                                PHASE: '.$moviePhase.'
                            </p>                       
                        </div>
                    

                        <div class="movieDatas flexCenter flexColumn flexStart">
                        
                            <p>ACTEURS PRINCIPAUX</p>

                            <div class="flexCenter flexStart flexWrap">';

                                foreach($ActorsFromThisMovie as $row){
                                    
                                    echo '
                                    <div class="flexCenter flexStart">
                                        <div class="imgLink small">
                                            <img src="./uploads/'.$row["image"].'">
                                        </div>
                                        <div>
                                            <br>
                                            <p> NOM: '.$row["last_name"].'</p>
                                            <p> PRENOM: '.$row["first_name"].'</p>
                                            <p> DATE DE NAISSANCE : '.$row["dob"].'</p>
                                            <p> ROLE: '.$row["role"].'</p><br>
                                        </div>
                                    </div>';
                                }; echo'

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
                                <a href="index.php?list">RETOUR</a>
                            </div>
                                  
                        </div>
                    </div>   
                </div>
            </div>

            <div id="blackboardEdit" class="blackboard flexCenter translateX-100">
                    
                <div class="formBoard flexCenter spaceAround">

                    <div class="moviePoster">
                        <div> 
                            <img src="./uploads/'.$movieImage.'">
                        </div>
                    </div>
                    <div class="movieDatas">
                        <form class="formFlex" action="./index.php" method="post" enctype="multipart/form-data">
                        
                        <div>
                            <p>
                                MODIFIER LES INFOS DU NAVET
                            </p>
                        </div>

                        <div class="newMovieDatasField">
                            <div>
                                <input name="movieID" type="hidden" value="'.$movieID.'">
                            </div>

                            <div class="formDatasInputFields">          
                                <label for="name">TITRE</label>                
                                <input type="text" class="form-control" name="name" id="name" value="'.$movieName.'">
                            </div>

                            <div class="formDatasInputFields">
                                <label for="director">DIRECTEUR</label>
                                <input type="text" class="form-control" id="director" name="director" value="'.$movieDirector.'">
                            </div>
                                
                            <div class="formDatasInputFields">
                                <label for="release_date">DATE DE SORTIE:</label>
                                <input type="date" id="release_date" name="release_date" value="'.$movieDate.'" min="1900-01-01" max="2020-07-01">
                            </div>

                            <div class="radioField">

                                <div class="selectRadioField">       
                                    <input type="radio" id="phaseI" name="id_phase"  value="1" ';
                                    if($MovieClickedData["id_phase"] == "1"){ echo 'checked ';}; echo'
                                     >    
                                    <label for="phaseI">phase 1</label>
                                </div>

                                <div class="selectRadioField">
                                    <input type="radio" id="phaseII" name="id_phase"  value="2" '; 
                                    if($MovieClickedData["id_phase"] == "2"){ echo 'checked ';}; echo'
                                    >
                                    <label  for="phaseII"> phase 2</label>
                                </div>

                                <div class="selectRadioField">
                                    <input type="radio" id="phaseIII" name="id_phase"  value="3" ';
                                    if($MovieClickedData["id_phase"] == "3"){ echo 'checked ';}; echo'
                                    >
                                    <label  for="phaseIII"> phase 3</label>
                                </div>

                            </div>
                        
                    
                            <div class="formDatasInputFields">
                                <label for="uploadedImg"> POSTER(jpg/png)</label>
                                <input type="file" name="uploadedImg" id="uploadedImg">
                            </div>
                            
                        </div>

                    
                
                        <div class="addActorsDatafield">
                            <div>
                                <p>MODIFIER LES ACTEURS</P>
                            </div>

                            <div class="checkBoxField">';

                    
                                //var_dump($AllActorsAndRole);
                            
                                $sql_all_Actors_and_Role = "SELECT actors.last_name, actors.first_name, actors.id FROM actors";
                                $sql_all_Actors_and_Role= $db->query($sql_all_Actors_and_Role);
                                $AllActorsAndRole = $sql_all_Actors_and_Role->fetchAll(PDO::FETCH_ASSOC);

                                $sqlActorsOnThisMovie = "SELECT actors.id FROM actors_movies JOIN actors ON actors.id = actors_movies.id_actors WHERE actors_movies.id_movie = $movieID";
                                $sqlActorsOnThisMovie= $db->query($sqlActorsOnThisMovie);
                                $actorsOnThisMovie = $sqlActorsOnThisMovie->fetchAll(PDO::FETCH_ASSOC);

                                if(!is_null($actorsOnThisMovie) ){
                                    foreach($actorsOnThisMovie as $key){
                                    $idActorOnthisMovie[] = $key["id"];
                                    };
                                };

                            
                                foreach($AllActorsAndRole as $row2){
                                    if(isset($idActorOnthisMovie)){
                                        if(in_array($row2["id"], $idActorOnthisMovie)){
                                            echo' 
                                            <div>
                                                <input type="checkbox" id="selectActors[]" checked name="selectActors[]" value="'.$row2["id"].'" >
                                                <label for="selectActors[]">'.$row2["last_name"].'</label>
                                            </div>'; 
                                        }
                                        else{
                                            echo'
                                            <div>
                                                <input type="checkbox" id="selectActors[]" name="selectActors[]" value="'.$row2["id"].'" >
                                                <label for="selectActors[]">'.$row2["last_name"].'</label>
                                            </div>';
                                        }
                                    }
                                    else{
                                    echo'
                                        <div>
                                            <input type="checkbox" id="selectActors[]" name="selectActors[]" value="'.$row2["id"].'" >
                                            <label for="selectActors[]">'.$row2["last_name"].'</label>
                                        </div>';
                                    };
                                };                  
                        
                                echo'
                            </div>
                            
                            <div>
                                <p> OU AJOUTER LES TROIS PRINCIPAUX ACTEURS</p>
                            </div>


                            <div class="newActorsDatasfield">

                                <div class="formDatasInputFields">          
                                    <label for="last_name[]">NOM</label>                
                                    <input type="text" class="imputField" name="last_name[]" id="last_name[]" placeholder="nom de l\'acteur">
                                </div>

                                <div class="formDatasInputFields">          
                                    <label for="first_name[]">PRENOM</label>                
                                    <input type="text" class="imputField" name="first_name[]" id="first_name[]" placeholder=" prénom de l\'acteur">
                                </div>

                                <div class="formDatasInputFields">
                                    <label for="dob[]">NE(E) LE</label>
                                    <input type="date" class="imputField" id="dob[]" name="dob[]" placeholder="date de naissance">
                                </div>

                                <div class="formDatasInputFields">
                                    <label for="role[]" >ROLE</label>                
                                    <input type="text" class="imputField" name="role[]" id="role[]" placeholder="role">
                                </div>

                                <div class="formDatasInputFields">
                                    <label for="uploadedImg">  PHOTO(jpg/png)</label>
                                    <input type="file" name="actorUploadedImg[]" id="actorUploadedImg">
                                </div>

                            </div>

                            <div class="newActorsDatasfield">

                                <div class="formDatasInputFields">          
                                    <label for="last_name[]">NOM</label>                
                                    <input type="text" class="imputField" name="last_name[]" id="last_name[]" placeholder="nom de l\'acteur">
                                </div>

                                <div class="formDatasInputFields">          
                                    <label for="first_name[]">PRENOM</label>                
                                    <input type="text" class="imputField" name="first_name[]" id="first_name[]" placeholder=" prénom de l\'acteur">
                                </div>

                                <div class="formDatasInputFields">
                                    <label for="dob[]"> NE(E) LE </label>
                                    <input type="date" class="imputField" id="dob[]" name="dob[]" placeholder="date de naissance">
                                </div>

                                <div class="formDatasInputFields">
                                    <label for="ROLE[]">ROLE</label>                
                                    <input type="text" class="imputField" name="role[]" id="role[]" placeholder="role">
                                </div>

                                <div class="formDatasInputFields">
                                    <label for="uploadedImg">  PHOTO(jpg/png)</label>
                                    <input type="file" name="actorUploadedImg[]" id="actorUploadedImg">
                                </div>

                            </div>

                            <div class="newActorsDatasfield">

                                <div class="formDatasInputFields">          
                                    <label for="last_name[]">NOM</label>                
                                    <input type="text" class="imputField" name="last_name[]" id="last_name[]" placeholder="nom de l\'acteur">
                                </div>

                                <div class="formDatasInputFields">          
                                    <label for="first_name[]">PRENOM</label>                
                                    <input type="text" class="imputField" name="first_name[]" id="first_name[]" placeholder=" prénom de l\'acteur">
                                </div>

                                <div class="formDatasInputFields">
                                    <label for="dob[]">NE(E) LE</label>
                                    <input type="date" class="imputField" id="dob[]" name="dob[]" placeholder="date de naissance">
                                </div>

                                <div class="formDatasInputFields">
                                    <label for="ROLE[]">ROLE</label>                
                                    <input type="text" class="imputField" name="role[]" id="role[]" placeholder="role">
                                </div>

                                <div class="formDatasInputFields">
                                    <label for="uploadedImg">  PHOTO(jpg/png)</label>
                                    <input type="file" name="actorUploadedImg[]" id="actorUploadedImg">
                                </div>

                            </div>
            
                            <div class="buttonsBlock alignCenter">
                                <input class=""  name = "isSubmit-edit" type="submit" value="ENREGISTRER LES DONNEES" > 
                                <div id="backButton" class="button">            
                                    <a href="index.php?list">ANNULER</a>
                                </div>  
                            </div>
                        </div>
                    </form>                  
                </div>

            </div>
            
            </div>
            
            ';

        }
        else{}
    ?>

<div id="blackboardDelete" class="blackboard delete " >
    <div id="confirmBox" class="confirmBox ">

        <div class="confirmBoxMessage">
            <p>
                Supprimer pour de bon le navet?
            </p>
        </div>

        <div class="buttonsBlock alignCenter">
                                    

            <div id="delet" class="button">            
                <p>
                <a class="nav-link" href="index.php?del=<?php echo $movieID; ?> ">Oui!</a>
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

<div id="blackboardMovies" class="blackboard flexCenter translateX-100">

    <div class="formBoard flexCenter">

    <form class="form flexCenter flexColumn" action="./index.php" method="post" enctype="multipart/form-data">
            <div>
                <p>
                    AJOUTER LES INFOS D'UN NAVET
                </p>
            </div>

            <div class="newMovieDatasField">

                <div class="formDatasInputFields">          
                    <label for="name">TITRE</label>                
                    <input type="text" class="form-control" name="name" id="name" placeholder="nom du film">
                </div>

                <div class="formDatasInputFields">
                    <label for="director">DIRECTEUR</label>
                    <input type="text" class="form-control" id="director" name="director" placeholder="nom du réalisateur">
                </div>
                            
                <div class="formDatasInputFields">
                    <label for="release_date">DATE DE SORTIE:</label>
                    <input type="date" id="release_date" name="release_date" value="2018-07-22" min="1900-01-01" max="2020-07-01">
                </div>

                <div class="radioField">
                    <div class="selectRadioField">       
                        <input type="radio" id="phaseI" name="id_phase"  value="1" checked>     
                        <label for="phaseI">phase 1</label>
                    </div>

                    <div class="selectRadioField">
                        <input type="radio" id="phaseII" name="id_phase"  value="2">
                        <label  for="phaseII"> phase 2</label>
                    </div>

                    <div class="selectRadioField">
                        <input type="radio" id="phaseIII" name="id_phase"  value="3">
                        <label  for="phaseIII"> phase 3</label>
                    </div>
                </div>
                
                <div class="formDatasInputFields">
                    <label for="uploadedImg"> POSTER(jpg/png)</label>
                    <input type="file" name="uploadedImg" id="uploadedImg">
                </div>

            </div>

                   
            
            <div class="addActorsDatafield">
                <div>
                    <p>ASSOCIER AU NAVET DES ACTEURS DE LA BASE DE DONNEES</P>
                </div>
                <div class="checkBoxField">
                    <?php 
                        //var_dump($AllActorsAndRole);
                        
                        $sql_all_Actors_and_Role = "SELECT actors.last_name, actors.first_name, actors.id FROM actors";
                        $sql_all_Actors_and_Role= $db->query($sql_all_Actors_and_Role);
                
                        $AllActorsAndRole = $sql_all_Actors_and_Role->fetchAll(PDO::FETCH_ASSOC);
                        
                        foreach($AllActorsAndRole as $row2){
                            echo'
                            <div>
                                <input type="checkbox" id="selectActors[]" name="selectActors[]" value="'.$row2["id"].'" >
                                <label for="selectActors[]">'.$row2["last_name"].'</label>
                            </div>';
                        };                  
                    ?>
                </div>
                <div>
                    <p> OU ASSOCIER DES NOUVEAUX ACTEURS AU NAVET</p>
                </div>


                <div class="newActorsDatasfield">

                    <div class="formDatasInputFields">          
                        <label for="last_name[]">NOM</label>                
                        <input type="text" class="imputField" name="last_name[]" id="last_name[]" placeholder="'nom de l'acteur'">
                    </div>

                    <div class="formDatasInputFields">          
                        <label for="first_name[]">PRENOM</label>                
                        <input type="text" class="imputField" name="first_name[]" id="first_name[]" placeholder=" prénom de l'acteur">
                    </div>

                    <div class="formDatasInputFields">
                        <label for="dob[]">NE(E) LE</label>
                        <input type="date" class="imputField" id="dob[]" name="dob[]" placeholder="'date de naissance">
                    </div>

                    <div class="formDatasInputFields">
                        <label for='role[]' >ROLE</label>                
                        <input type="text" class="imputField" name="role[]" id="role[]" placeholder="role">
                    </div>

                    <div class="formDatasInputFields">
                        <label for="uploadedImg">  PHOTO(jpg/png)</label>
                        <input type="file" name="actorUploadedImg[]" id="actorUploadedImg">
                    </div>

                </div>

                <div class="newActorsDatasfield">

                    <div class="formDatasInputFields">          
                        <label for="last_name[]">NOM</label>                
                        <input type="text" class="imputField" name="last_name[]" id="last_name[]" placeholder="'nom de l'acteur'">
                    </div>

                    <div class="formDatasInputFields">          
                        <label for="first_name[]">PRENOM</label>                
                        <input type="text" class="imputField" name="first_name[]" id="first_name[]" placeholder=" prénom de l'acteur">
                    </div>

                    <div class="formDatasInputFields">
                        <label for="dob[]"> NE(E) LE </label>
                        <input type="date" class="imputField" id="dob[]" name="dob[]" placeholder="'date de naissance">
                    </div>

                    <div class="formDatasInputFields">
                        <label for="ROLE[]">ROLE</label>                
                        <input type="text" class="imputField" name="role[]" id="role[]" placeholder="role">
                    </div>

                    <div class="formDatasInputFields">
                        <label for="uploadedImg">  PHOTO(jpg/png)</label>
                        <input type="file" name="actorUploadedImg[]" id="actorUploadedImg">
                    </div>

                </div>

                <div class="newActorsDatasfield">

                    <div class="formDatasInputFields">          
                        <label for="last_name[]">NOM</label>                
                        <input type="text" class="imputField" name="last_name[]" id="last_name[]" placeholder="'nom de l'acteur'">
                    </div>

                    <div class="formDatasInputFields">          
                        <label for="first_name[]">PRENOM</label>                
                        <input type="text" class="imputField" name="first_name[]" id="first_name[]" placeholder=" prénom de l'acteur">
                    </div>

                    <div class="formDatasInputFields">
                        <label for="dob[]">NE(E) LE</label>
                        <input type="date" class="imputField" id="dob[]" name="dob[]" placeholder="'date de naissance">
                    </div>

                    <div class="formDatasInputFields">
                        <label for="ROLE[]">ROLE</label>                
                        <input type="text" class="imputField" name="role[]" id="role[]" placeholder="role">
                    </div>

                    <div class="formDatasInputFields">
                        <label for="uploadedImg">  PHOTO(jpg/png)</label>
                        <input type="file" name="actorUploadedImg[]" id="actorUploadedImg">
                    </div>

                </div>


            </div>
                       
            <div class="buttonsBlock alignCenter">
                <input class=""  name = "isSubmit-addmore" type="submit" value="ENREGISTRER LES DONNEES" > 
                <div id="backButton" class="button">            
                    ANNULER
                </div>  
            </div>
        </form>
        
    </div>

</div>


<div class="flexCenter flexColumn spaceAround">
    <?php require("./templates/filmHeader.html") ?>;

    <div class="board flexCenter spaceAround">
        <?php

            $sql = $db->prepare("SELECT movies.image, movies.id FROM movies");
            $sql->execute();
            $tableau=$sql->FetchAll(PDO::FETCH_ASSOC);

            foreach ($tableau as $row){ // fetchall renvoie toujours un tableau contenant tous tes résultats, et chaque résultat est à son tour un tableau. Le premier foreach est pour faire une boucle de chacun de ces tableau, j'ai juste besoin de référencer la ligne ($row) pour le foreach imbriqué, je n'ai pas besoin de valeur à ce moment là, pour ça que j'ai pas de "=> $val"

                echo 
        '<a class="imgLink" href ="index.php?list='.$row['id'].'"> <img src="./uploads/'.$row["image"].'"</a>';
            };
        

        ?>
    </div>
</div>
    
    
