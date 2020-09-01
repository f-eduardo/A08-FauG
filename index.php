<?php include_once ('./settings/db.php'); ?>

<!DOCTYPE html>
<html lang="en">

    <?php include_once ('./templates/head.html'); ?>

    <body>        
        <?php include_once ('./templates/header.html'); ?>

        <section class="flexCenter flexColumn">          
            <?php
                if(isset($_GET["list"])){

                    //si on click sur le bouton "liste des navets" on affiche les navets.
                    require("./templates/movies/list_movies.php");

                }elseif(isset($_GET["list_actors"])){

                    //si on click sur le bouton "liste des actors on affiche les acteurs.
                    require("./templates/actors/list_actors.php");

                }elseif(isset($_GET["new"])){

                    //si on click sur le bouton ajouter un film on affiche le formulaire.
                    require("./templates/movies/_form_new.php");

                }elseif(isset($_POST["isSubmit-addmore"])){

                    //si on click sur le bouton envoyer du formulaire d'ajout de navet alors:
                    
                    // J'affiche à l'écran les bouttons pour retourner sur la liste des navets et sur celle des acteurs
                    echo '
                    <div class="flexCenter flexColumn">
    
                        <div class="buttonsBlock">
                                            
                            <button type="button" class="button">            
                                <a href="index.php?list"> VOIR LES NAVETS</a>  
                            </button>
                
                            <button class="button">            
                            <a href="index.php?list_actors"> VOIR LES ACTEURS</a>  
                            </button>
                                        
                        </div>  
                    </div>';

                    
                    
                    // j'enregistre les infos concernant le navet dans le tableau $movieData
                    $movieData = [
                        'name'=> $_POST['name'], 
                        'director'=> $_POST['director'], 
                        'id_phase'=> ($_POST['id_phase']),
                        'release_date' =>$_POST['release_date'],
                        "img" =>$_FILES ['uploadedImg'],                              
                    ];

                    // J'affiche à l'écran ce message :
                    echo '<div class="alertText flexCenter"><p> Les informations du film " '.$movieData["name"].' " ont été enregistrées avec succés ! <p></div>';


                    //je prépare la requête $sqlAddMovieData
                    $sqlAddMovieData = $db->prepare(
                        "INSERT INTO 
                        `movies`
                        ( `name`, `release_date`, `director`, `id_phase`) 
                        VALUES 
                        (:name, :release_date, :director, :id_phase)"
                    );

                    // j'execute la requete en attribuant aux keys les valeurs de $sqlAddMovieData
                    $sqlAddMovieData->execute([
                        ":name" => $movieData["name"], 
                        ":release_date" => $movieData["release_date"], 
                        ":director" =>$movieData["director"], 
                        ":id_phase"=>$movieData["id_phase"], 
                        //":image"=> $movieData["img"]["name"]
                    ]);

                    // je récupère l'id du film fraichement créée en la sauvegardant dans $newMovieID
                    $newMovieID= $db->lastInsertId();

                    if(!empty($_FILES['uploadedImg']["name"])){
                        // je crée les variables nécésaire pour sauvegarder l'image du film 
                        $newMovieImgValidExt = ["image/jpg", "image/png","image/jpeg"];
                        $newMovieImgFileExt = $_FILES["uploadedImg"]["type"];
                        $newMovieImgTempFile = $_FILES["uploadedImg"]["tmp_name"];
                        $newMovieImgfile = 'uploads/'.$_FILES["uploadedImg"]["name"];
                        $newMovieImgName = $_FILES["uploadedImg"]["name"];
                        $newMovieImgSize = $_FILES["uploadedImg"]["size"];

                        if(!in_array($newMovieImgFileExt, $newMovieImgValidExt)){
                            
                            // si l'extention de l'image associée au navet est invalide, alors j'affiche ce message:
                            echo '<div class="alertText flexCenter red"><p> L\'image '.$newMovieImgName.' n\'a été enregistrée car son format n\'est pas valide!<p></div>';

                        }elseif($newMovieImgSize > 1000000){
                            
                            // si l'image est dépasse les 1MO, alors j'affiche ce message:
                            echo '<div ><p> L\'image '.$newMovieImgName.' n\'a pas été enregistrée car elle trop lourde!<p></div>';

                        }else{
                        
                                // sinon, alors
                        
                            // 1-je code la requête sql pour enregistrer le nom de l'image associée au navet dans la BD
                            $sqlAddMovieImg = $db->prepare(
                                "UPDATE
                                movies
                                SET
                                image= :image    
                                WHERE
                                movies.id = :movie_id"
                            );

                            $sqlAddMovieImg ->execute(
                                [
                                    ":image"=> $movieData["img"]["name"],
                                    ":movie_id" => $newMovieID
                                ]
                            );

                            // 2 -je déplace l'image depuis le dossier temp vers le dossier uploads:
                            move_uploaded_file($newMovieImgTempFile, $newMovieImgfile);

                            //3 -j'affiche à l'écran le message suivant :
                            echo '<div class="alertText flexCenter"><p> L\'image '.$newMovieImgName.' a été correctement enregistrée<p></div>';

                        }
                    };

                    // j'enregistre les infos des différents acteurs  dans le tableau:
                        $newActorsData=[
                            'last_name'=>$_POST['last_name'],
                            'first_name'=>$_POST['first_name'],
                            'dob'=>$_POST['dob'],
                        ];
    
                        // je code un forEach por les requetes d'insertions de chaque tableau de données du tableau $newActorsData
                        
                        $i = 0;
                        $results=[];
                        foreach ($newActorsData as $row){
                            
                            $sqlnewActorsData = $db->prepare(
                                "INSERT INTO 
                                `actors`
                                ( `last_name`, `first_name`, `dob`) 
                                VALUES 
                                (:last_name, :first_name, :dob)"
                            );
    
                            $sqlnewActorsData->execute([
                                ":last_name" => $newActorsData["last_name"]["$i"], 
                                ":first_name" => $newActorsData["first_name"]["$i"], 
                                ":dob"=>$newActorsData["dob"]["$i"], 
                                // ":image"=> $newsActorsImg["name"]["$i"]
                            ]);
                            $results[] = $db->lastInsertId();
                            $i++;
                        };
                        
                        //var_dump($results); essayer de comprendre pourquoi il génère des doublons?
    
                        $newActorsRole=[
                            'role'=> $_POST['role']
                        ];
    
                        // ayant récupéré l'id du nouveau film, et les ID des nouveaux acteurs et leur role, je peux remplir la table actors_movies en faisant un forEach
                        
                        $x = 0;
                        foreach($results as $row){
    
                            $sqlNewActorsOfThisMovie= $db->prepare
                            ("INSERT INTO 
                                actors_movies
                                (id_actors, id_movie, role)
                                VALUES
                                (:id_actors, :id_movie, :role)"                        
                            );
    
                            $sqlNewActorsOfThisMovie->execute(
                            [ 
                            ":id_actors" => $results["$x"],
                            ":id_movie" => $newMovieID,
                            ":role" => $newActorsRole["role"]["$x"],                     
                            ]
                            );
                        $x++;
                        };
    
                        if (isset($_POST["selectActors"])) {
                            // si l'utilisateur a coché des acteurs déjà présent dans la base de données
                            // si le tableau selectactors n'est pas nul
                            if(!is_null($_POST["selectActors"])){
                                //alors j'enregistres les données du tableau selecctactors dans $ActorsSelected 
                                $actorsSelected = $_POST["selectActors"];
                                
                                // je code le forEach pour faire la requete d'insertion dans actors_movies pour chaque acteur coché                        
                                
                                foreach ($actorsSelected as $row1){
                                    $sqlactorsSelected= $db->prepare
                                    ("INSERT INTO 
                                        actors_movies
                                        (id_actors, id_movie)
                                        VALUES
                                        (:id_actors, :id_movie)"                        
                                    );
                           
                                    $sqlactorsSelected->execute(
                                        [
                                            "id_actors" => $row1,
                                            "id_movie" => $newMovieID
                                        ]
                                    );
                                }   
                            }
                        };

                    // j'enregistre les images des nouveaux acteurs dans un tableau
                    $newsActorsImg = $_FILES['actorUploadedImg'];


                    $y = 0;
                    foreach ($newsActorsImg['name'] as $row){

                        $newActorImgName = $newsActorsImg['name'] [$y];
                        $newActorImgvalidExt = ["image/jpg", "image/png","image/jpeg"];
                        $newActorImgfileExt = $newsActorsImg["type"][$y];
                        $newActorImgtempFile = $newsActorsImg["tmp_name"][$y];
                        $newActorImgSize= $newsActorsImg["size"][$y];
                        $newActorImgfile = 'uploads/'.$newsActorsImg["name"][$y];
 
                        if(!empty($newsActorsImg['name'][$y])){

                            if($newActorImgSize > 1000000){
                                // si l'image du film est plus grande qu'1mo
                                // j'affiche une alerte
                                echo '<div class="alertText flexCenter red"><p> l\image '.$newActorImgName.' n\'a pas été enregistré car elle trop lourde!<p></div>';
    
                            }elseif(!in_array($newActorImgfileExt, $newActorImgvalidExt)){

                                echo '<div class="alertText flexCenter red"><p> l\'image '.$newActorImgName.' n\'a pas été enregistré car son extention n\'est pas valide!<p></div>';
            
                            }else{

                                $sqlAddActorImg = $db->prepare(
                                    "UPDATE 
                                    actors
                                    SET
                                    image= :image    
                                    WHERE
                                    actors.id = :actor_id"
                                );
    
                                $sqlAddActorImg ->execute(
                                    [
                                        ":image" => $newActorImgName,
                                        ":actor_id" => $results["$y"]
                                    ]
                                );

                                move_uploaded_file($newActorImgtempFile, $newActorImgfile);
                                echo '<div class="alertText flexCenter"><p> l\'image '.$newActorImgName.' de l\'acteur " '.$newActorsData["last_name"][$y].' " a été correctement enregistrée<p></div>';
                            }
                            $y++;
                        };
                    };
                    
                    // echo "<pre>";
                    //print_r($_FILES['actorUploadedImg']);
                    //echo "</pre>";
                    

                   

                     
                }elseif(isset($_POST["isSubmit-Actors"])){

                        //si on click sur le bouton envoyer du formulaire d'ajout de navet alors:
                        
                        // J'affiche à l'écran les bouttons pour retourner sur la liste des navets et sur celle des acteurs
                        echo '
                        <div class="flexCenter flexColumn">
        
                            <div class="buttonsBlock">
                                                
                                <button type="button" class="button">            
                                    <a href="index.php?list"> VOIR LES NAVETS</a>  
                                </button>
                    
                                <button class="button">            
                                    VOIR LES ACTEURS
                                    </button>
                                            
                            </div>  
                        </div>';
    
                        
                        
                        // j'enregistre les infos concernant le navet dans le tableau $movieData
                        $actorData = [
                            'last_name'=> $_POST['last_name'], 
                            'first_name'=> $_POST['first_name'], 
                            'dob' =>$_POST['dob'],
                            "img" =>$_FILES ['uploadedImg'],                              
                        ];
    
                        // J'affiche à l'écran ce message :
                        echo '<div class="alertText flexCenter"><p> Les informations de l\'acteur " '.$actorData["last_name"].' " ont été enregistrées avec succés ! <p></div>';
    
    
                        //je prépare la requête $sqlAddMovieData
                        $sqlAddActorData = $db->prepare(
                            "INSERT INTO 
                            `actors`
                            ( `last_name`, `first_name`,`dob`) 
                            VALUES 
                            ( :last_name, :first_name, :dob)"
                        );
    
                        // j'execute la requete en attribuant aux keys les valeurs de $sqlAddMovieData
                        $sqlAddActorData->execute([
                            ":last_name" => $actorData["last_name"], 
                            ":first_name" => $actorData["first_name"], 
                            ":dob" =>$actorData["dob"] 
                            //":image"=> $movieData["img"]["name"]
                        ]);
    
                        // je récupère l'id du film fraichement créée en la sauvegardant dans $newMovieID
                        $newActorID= $db->lastInsertId();
    
                        if(!empty($_FILES['uploadedImg']["name"])){
                            // je crée les variables nécésaire pour sauvegarder l'image du film 
                            $newActorImgValidExt = ["image/jpg", "image/png","image/jpeg"];
                            $newActorImgFileExt = $_FILES["uploadedImg"]["type"];
                            $newActorImgTempFile = $_FILES["uploadedImg"]["tmp_name"];
                            $newActorImgfile = 'uploads/'.$_FILES["uploadedImg"]["name"];
                            $newActorImgName = $_FILES["uploadedImg"]["name"];
                            $newActorImgSize = $_FILES["uploadedImg"]["size"];
    
                            if(!in_array($newActorImgFileExt, $newActorImgValidExt)){
                                
                                // si l'extention de l'image associée au navet est invalide, alors j'affiche ce message:
                                echo '<div class="alertText flexCenter red"><p> L\'image '.$newActorImgName.' n\'a été enregistrée car son format n\'est pas valide!<p></div>';
    
                            }elseif($newActorImgSize > 1000000){
                                
                                // si l'image est dépasse les 1MO, alors j'affiche ce message:
                                echo '<div ><p> L\'image '.$newActorImgName.' n\'a pas été enregistrée car elle trop lourde!<p></div>';
    
                            }else{
                            
                                    // sinon, alors
                            
                                // 1-je code la requête sql pour enregistrer le nom de l'image associée au navet dans la BD
                                $sqlAddActorImg = $db->prepare(
                                    "UPDATE
                                    actors
                                    SET
                                    image= :image    
                                    WHERE
                                    actors.id = :actor_id"
                                );
    
                                $sqlAddActorImg ->execute(
                                    [
                                        ":image"=> $actorData["img"]["name"],
                                        ":actor_id" => $newActorID
                                    ]
                                );
    
                                // 2 -je déplace l'image depuis le dossier temp vers le dossier uploads:
                                move_uploaded_file($newActorImgTempFile, $newActorImgfile);
    
                                //3 -j'affiche à l'écran le message suivant :
                                echo '<div class="alertText flexCenter"><p> L\'image '.$newActorImgName.' a été correctement enregistrée<p></div>';
    
                            }
                        };
            
                            if (isset($_POST["selectMovies"])) {
                                // si l'utilisateur a coché des films déjà présent dans la base de données
                                
                                // si le tableau selectactors n'est pas nul
                                if(!is_null($_POST["selectMovies"])){
                                    //alors j'enregistres les données du tableau selecctactors dans $ActorsSelected 
                                    $MoviesSelected = $_POST["selectMovies"];
                                    
                                    // je code le forEach pour faire la requete d'insertion dans actors_movies pour chaque acteur coché                        
                                    
                                    foreach ($MoviesSelected as $row1){
                                        $sqlMoviesSelected= $db->prepare
                                        ("INSERT INTO 
                                            actors_movies
                                            (id_actors, id_movie)
                                            VALUES
                                            (:id_actors, :id_movie)"                        
                                        );
                               
                                        $sqlMoviesSelected->execute(
                                            [
                                                "id_actors" => $newActorID,
                                                "id_movie" => $row1
                                            ]
                                        );
                                    }   
                                }
                            };
                                    

                }elseif(isset($_GET['del'])){
                    
                    $deleteID = $_GET['del'];

                    $sqlImg ="SELECT movies.image FROM `movies` WHERE movies.id = $deleteID";
                    $sqlImg= $db->query($sqlImg);
                    $image = $sqlImg-> fetch(PDO::FETCH_ASSOC);

                    $file = 'uploads/'.$image["image"];
                    @unlink( $file );

                        
                    $sql = $db->prepare("DELETE FROM `movies` WHERE `movies`.`id` = $deleteID ");
                    $sql->execute();

                    echo '
                    <div class="flexCenter flexColumn">
    
                        <div class="buttonsBlock">
                                            
                            <button type="button" class="button">            
                                <a href="index.php?list"> VOIR LES NAVETS</a>  
                            </button>
                
                            <button class="button">            
                                <a href="index.php?list_actors">VOIR LES ACTEURS</a> 
                            </button>
                                        
                        </div>  
                    </div>';

                    echo '<h2> le film a été supprimé </h2>';

                }elseif(isset($_GET['delActor'])){
                    
                    $deleteID = $_GET['delActor'];

                    $sqlActorImg ="SELECT actors.image FROM `actors` WHERE actors.id = $deleteID";
                    $sqlActorImg= $db->query($sqlActorImg);
                    $actorImg = $sqlActorImg-> fetch(PDO::FETCH_ASSOC);

                    $file = 'uploads/'.$actorImg["image"];
                    @unlink( $actorImg );

                        
                    $sql = $db->prepare("DELETE FROM `actors` WHERE `actors`.`id` = $deleteID ");
                    $sql->execute();


                    echo '<h2> l\'acteur a été supprimé </h2>';
                
                }elseif(isset($_GET['edit'])){

                    require("./templates/movies/_form_edit.php");

                

                }elseif(isset($_POST['isSubmit-edit'])){
                    // Si le formulaire d'édition d'un film est envoyé alors:
                        


                    // j'enregistre les données du formulaire dans le tableau $movieUpdatedDatas:

                    $movieUpdatedDatas = [
                        'name'=> $_POST['name'], 
                        'director'=> $_POST['director'], 
                        'id_phase'=> ($_POST['id_phase']),
                        'release_date' =>$_POST['release_date'],
                        "img" =>$_FILES ['uploadedImg'],
                        "movieID" =>$_POST['movieID'],
                    ];
                        
                    // j'enregistre l'id du film dans la variable $editedMovieID
                    $editedMovieID = $movieUpdatedDatas["movieID"];

                    
                    
                    // j'enregistre le nom du film dans une variable  
                    $editedMovieName= $movieUpdatedDatas["name"];

                    echo '
                    <div class="flexCenter flexColumn">
 
                        <div class="buttonsBlock">
                                            
                            <button type="button" class="button">            
                                <a href="index.php?list"> VOIR LES NAVETS</a>  
                            </button>
                
                            <button class="button">            
                                VOIR LES ACTEURS
                             </button>
                                      
                        </div>  
                    </div>';

                    echo '<div class="alertText flexCenter"><p> Les informations du film " '.$editedMovieName.' " ont été actualisées avec succés ! <p></div>';


                    // je prépare ma requete pour update ce film
                    $sqlUpdateThisMovie = $db->prepare(
                        'UPDATE 
                            movies
                        SET
                            name = :name, release_date = :date, director = :director, id_phase = :phase,/* image= :image*/ modified_at = CURRENT_TIMESTAMP
                        WHERE
                        movies.id = :movie_id'
                    );
                        
                    // j'éxecute la requete
                    $sqlUpdateThisMovie->execute(

                        [
                            "name" => $movieUpdatedDatas["name"],
                            "date" => $movieUpdatedDatas["release_date"],
                            "director" => $movieUpdatedDatas["director"],
                            "phase" => $movieUpdatedDatas["id_phase"],
                            /*"image" => $movieUpdatedDatas["img"]["name"],*/
                            "movie_id" => $editedMovieID,
                        ]
                    );

                    $editedMovieImg = $movieUpdatedDatas["img"];
                    //var_dump($editedMovieImg);

                        
                    if(!is_null($_FILES['uploadedImg']["name"])){
                        // si, et seulement si une nouvelle img a été associée au film, alors :

                        // je crée les variables nécésaire pour sauvegarder l'image du film 
                        $editedMovieImgValidExt = ["image/jpg", "image/png","image/jpeg"];
                        $editedMovieImgFileExt = $_FILES["uploadedImg"]["type"];
                        $editedMovieImgTempFile = $_FILES["uploadedImg"]["tmp_name"];
                        $editedMovieImgfile = 'uploads/'.$_FILES["uploadedImg"]["name"];
                        $editedMovieImgName = $_FILES["uploadedImg"]["name"];
                        $editedMovieImgSize = $_FILES["uploadedImg"]["size"];
                        $editedMovieImgError = $_FILES["uploadedImg"]["error"];

                            
                        if( $editedMovieImgError == 4){

                            // si aucune nouvelle image est associée au film, j'affiche le message suivant :
                            echo "<div class='alertText flexCenter'><p> L'image du film '".$editedMovieName."' n'a pas été modifiée<p></div>";

                        }elseif(!in_array($editedMovieImgFileExt, $editedMovieImgValidExt)){

                            // si l'image n'a pas une extention valide alors j'affiche un message d'alerte
                            echo '<div class="alertText flexCenter red"><p> L\'image '.$editedMovieImgName.' n\'a pas été enregistré car son extention n\'est pas valide!<p></div>';
                    
                        }elseif($editedMovieImgSize > 1000000){

                            // si l'image pèse plus d'un 1MO j'affiche un message d'alerte
                            echo '<div class="alertText flexCenter red"><p> L\image '.$editedMovieImgName.' n\'a pas été enregistré car elle trop lourde!<p></div>';

                        }else{

                            // sinon, 
                             // alors j'enregistre le nom de la nouvelle image du film dans la base de donnée
                            $sqlEditedMovieImg = $db->prepare(
                                "UPDATE 
                                movies
                                SET
                                image= :image
                                    
                                WHERE
                                movies.id = :movie_id"
                            );

                            $sqlEditedMovieImg ->execute(
                                [
                                    ":image" => $movieUpdatedDatas["img"]["name"],
                                    ":movie_id" => $editedMovieID
                                ]
                            );

                            //ensuite j'efface la précédente image associée au film du dossier uploads
                            // début du code pour effacer l'ancien poster :

                            $sqlImgEdit ="SELECT movies.image FROM `movies` WHERE movies.id = $editedMovieID";
                            $sqlImgEdit= $db->query($sqlImgEdit);
                            $imageEdit = $sqlImgEdit-> fetch(PDO::FETCH_ASSOC);
                            $fileEdit = 'uploads/'.$imageEdit["image"];
                            @unlink( $fileEdit );
                                                                
                            // fin du code pour effacer l'ancien poster.

                            // je déplace l'image depuis son dossier temp vers le dossier uploads.
                            move_uploaded_file($editedMovieImgTempFile, $editedMovieImgfile);
                            echo '<div class="alertText flexCenter"><p> l\image '.$editedMovieImgName.' a été correctement enregistrée<p></div>';

                        };                       
                    };

                    // j'éfface la relation entre les acteurs et ce film dans la tabla actors_movies afin de pouvoir l'actualiser par la suite avec les nouvelles données
                        
                    $sqlUpdatePreDelete = $db->prepare("DELETE FROM actors_movies WHERE actors_movies.id_movie = $editedMovieID");                   
                    $sqlUpdatePreDelete->execute();

                    
                    if (isset($_POST["selectActors"])) {
                        if(!is_null($_POST["selectActors"])){
                            $tableActorsSelect = $_POST["selectActors"];
                        
                            //var_dump($tableActorsSelect);
                    
                            $z = 0;
                            foreach ($tableActorsSelect as $row1){
                                $sqlSelectActorMovies= $db->prepare
                                ("INSERT INTO 
                                    actors_movies
                                    (id_actors, id_movie)
                                    VALUES
                                    (:id_actors, :id_movie)"                        
                                );

                        
                                $sqlSelectActorMovies->execute(
                                    [
                                        "id_actors" => $row1,
                                        "id_movie" => $editedMovieID
                                    ]
                                );
                            }   
                        }
                    }


                    // j'enregistre dans un tableau les infos des acteurs ajoutés 
                    $editedActorsOnThisMovie=[
                        'last_name'=>$_POST['last_name'],
                        'first_name'=>$_POST['first_name'],
                        'dob'=>$_POST['dob'],
                    ];


                    $a = 0;
                    $results2 = [];
                    // j'insère dans la base de données tous les nouveaux acteurs 
                    foreach ($editedActorsOnThisMovie as $row){

                        $sqlEditedActorsOnThisMovie = $db->prepare("INSERT INTO 
                                                    `actors`
                                                    ( `last_name`, `first_name`, `dob` /*image*/) 
                                                    VALUES 
                                                    (:last_name, :first_name, :dob /*:image*/)"
                        );
    
                        $sqlEditedActorsOnThisMovie->execute(
                            [":last_name" => $editedActorsOnThisMovie["last_name"]["$a"], 
                                ":first_name" => $editedActorsOnThisMovie["first_name"]["$a"], 
                                ":dob"=>$editedActorsOnThisMovie["dob"]["$a"]
                            ]
                        );
                        $results2[] = $db->lastInsertId();
                        $a++;
                    };
                    //var_dump($results2);

                    // j'enregistre le role de chaque acteur ajoutée à la base de donnée
                    $newActorsRole=[
                        'role'=> $_POST['role']
                    ];

                    // je mets en relation ces nouveaux acteurs avec le film modifié:
                    $w = 0;
                    foreach($results2 as $row){
        
                        $sqlNewActorsOfThisMovie= $db->prepare
                        ("INSERT INTO 
                                actors_movies
                                (id_actors, id_movie, role)
                                VALUES
                                (:id_actors, :id_movie, :role)"                        
                        );
        
                        $sqlNewActorsOfThisMovie->execute(
                            [ 
                            ":id_actors" => $results2["$w"],
                            ":id_movie" => $editedMovieID,
                            ":role" => $newActorsRole["role"]["$w"],                     
                            ]
                        );
                        $w++;
                    };

                    


                    // j'enregistre les images des nouveaux acteurs dans un tableau
                    $editedActorsImg = $_FILES['actorUploadedImg'];
                    //var_dump($editedActorsImg);

                    $y = 0;
                    foreach ($editedActorsImg['name'] as $row){

                        $editedActorImgName = $editedActorsImg['name'] [$y];
                        $editedActorImgvalidExt = ["image/jpg", "image/png","image/jpeg"];
                        $editedActorImgfileExt = $editedActorsImg["type"][$y];
                        $editedActorImgtempFile = $editedActorsImg["tmp_name"][$y];
                        $editedActorImgSize= $editedActorsImg["size"][$y];
                        $editedActorImgfile = 'uploads/'.$editedActorsImg["name"][$y];
                        $editedActorImgError = $editedActorsImg["error"][$y];
 
                        if($editedActorImgError == 4){

                            // si aucune image est associée a l'acteur, j'affiche le message suivant :
                            //echo "<div class='alertText flexCenter'><p> Aucune image n'\a été téléchargée pour l'acteur '" .$editedActorsOnThisMovie["last_name"]["$y"]. "'<p></div>";

                        }elseif($editedActorImgSize > 1000000){

                            // si l'image de l'acteur pèse plus d'1mo, alors j'affiche ce message :
                            echo '<div class="alertText flexCenter red"><p> L\'image de l\acteur " '.$editedActorsOnThisMovie["last_name"]["$y"].' " n\'a pas été enregistré car elle trop lourde!<p></div>';
 
                        }elseif(!in_array($editedActorImgfileExt, $editedActorImgvalidExt)){

                            // si l'extension de limage de l'acteur est invalide:
                            echo '<div class="alertText flexCenter red" ><p> l\image '.$editedActorImgName.' n\'a pas été enregistré car son extention n\'est pas valide!<p></div>';
                            
                        }else{
                            //sinon, si l'image est valide, je l'enregistre dans la base de donnée a la ligne de l'acteur correspondant

                            $sqlAddActorImg = $db->prepare(
                                "UPDATE 
                                actors
                                SET
                                image= :image    
                                WHERE
                                actors.id = :actor_id"
                            );

                            $sqlAddActorImg ->execute(
                                [
                                    ":image" => $editedActorImgName,
                                    ":actor_id" => $results2["$y"]
                                ]
                            );
                            // sans oublier d'enregistre l'image valide dans le dossier upoads
                            move_uploaded_file($editedActorImgtempFile, $editedActorImgfile);
                            echo '<div class="alertText flexCenter"><p> L\'image  de l\'acteur "'.$editedActorsOnThisMovie["last_name"]["$y"].' " a été correctement enregistrée<p></div>';
                        }
                        $y++;
                    }
                }elseif(isset($_POST['isSubmit-editActors'])){
                    // Si le formulaire d'édition d'un film est envoyé alors:
                        


                    // j'enregistre les données du formulaire dans le tableau $movieUpdatedDatas:

                    $actorUpdatedDatas = [
                        'last_name'=> $_POST['last_name'], 
                        'first_name'=> $_POST['first_name'], 
                        'dob'=> ($_POST['dob']),
                        "img" =>$_FILES ['uploadedImg'],
                        "actorID" =>$_POST['actorID'],
                    ];
                    var_dump($actorUpdatedDatas);
                        
                    // j'enregistre l'id de l'acteur dans la variable $editedActorID
                    $editedActorID = $actorUpdatedDatas["actorID"];

                    
                    
                    // j'enregistre le nom de l'acteur dans une variable  
                    $editedActeurName= $actorUpdatedDatas["last_name"];

                    echo '
                    <div class="flexCenter flexColumn">
 
                        <div class="buttonsBlock">
                                            
                            <button type="button" class="button">            
                                <a href="index.php?list"> VOIR LES NAVETS</a>  
                            </button>
                
                            <button class="button">            
                                VOIR LES ACTEURS
                             </button>
                                      
                        </div>  
                    </div>';

                    echo '<div class="alertText flexCenter"><p> Les informations de l/acteur " '.$editedActeurName.' " ont été actualisées avec succés ! <p></div>';


                    // je prépare ma requete pour update ce film
                    $sqlUpdateThisActor = $db->prepare(
                        'UPDATE 
                            actors
                        SET
                            last_name = :last_name, dob = :dob, first_name = :first_name,/* image= :image*/ modified_at = CURRENT_TIMESTAMP
                        WHERE
                        actors.id = :actor_id'
                    );
                        
                    // j'éxecute la requete
                    $sqlUpdateThisActor->execute(

                        [
                            "last_name" => $actorUpdatedDatas["last_name"],
                            "dob" => $actorUpdatedDatas["dob"],
                            "first_name" => $actorUpdatedDatas["first_name"],
                            ":actor_id" => $editedActorID,
                        ]
                    );

                    $editedActorImg = $actorUpdatedDatas["img"];
                    //var_dump($editedMovieImg);

                        
                    if(!is_null($_FILES['uploadedImg']["name"])){
                        // si, et seulement si une nouvelle img a été associée au film, alors :

                        // je crée les variables nécésaire pour sauvegarder l'image du film 
                        $editedActorImgValidExt = ["image/jpg", "image/png","image/jpeg"];
                        $editedActorImgFileExt = $_FILES["uploadedImg"]["type"];
                        $editedActorImgTempFile = $_FILES["uploadedImg"]["tmp_name"];
                        $editedActorImgfile = 'uploads/'.$_FILES["uploadedImg"]["name"];
                        $editedActorImgName = $_FILES["uploadedImg"]["name"];
                        $editedActorImgSize = $_FILES["uploadedImg"]["size"];
                        $editedActorImgError = $_FILES["uploadedImg"]["error"];

                            
                        if( $editedActorImgError == 4){

                            // si aucune nouvelle image est associée au film, j'affiche le message suivant :
                            echo "<div class='alertText flexCenter'><p> L'image du film '".$editedActeurName."' n'a pas été modifiée<p></div>";

                        }elseif(!in_array($editedActorImgFileExt, $editedActorImgValidExt)){

                            // si l'image n'a pas une extention valide alors j'affiche un message d'alerte
                            echo '<div class="alertText flexCenter red"><p> L\'image '.$editedActeurName.' n\'a pas été enregistré car son extention n\'est pas valide!<p></div>';
                    
                        }elseif($editedActorImgSize > 1000000){

                            // si l'image pèse plus d'un 1MO j'affiche un message d'alerte
                            echo '<div class="alertText flexCenter red"><p> L\image '.$editedActeurName.' n\'a pas été enregistré car elle trop lourde!<p></div>';

                        }else{

                            // sinon, 
                             // alors j'enregistre le nom de la nouvelle image du film dans la base de donnée
                            $sqlEditedActorImg = $db->prepare(
                                "UPDATE 
                                actors
                                SET
                                image= :image
                                    
                                WHERE
                                actors.id = :actor_id"
                            );

                            $sqlEditedActorImg ->execute(
                                [
                                    ":image" => $actorUpdatedDatas["img"]["name"],
                                    ":actor_id" => $editedActorID
                                ]
                            );

                            //ensuite j'efface la précédente image associée au film du dossier uploads
                            // début du code pour effacer l'ancien poster :

                            $sqlImgEdit ="SELECT actors.image FROM `actors` WHERE actors.id = $editedActorID";
                            $sqlImgEdit= $db->query($sqlImgEdit);
                            $imageEdit = $sqlImgEdit-> fetch(PDO::FETCH_ASSOC);
                            $fileEdit = 'uploads/'.$imageEdit["image"];
                            @unlink( $fileEdit );
                                                                
                            // fin du code pour effacer l'ancien poster.

                            // je déplace l'image depuis son dossier temp vers le dossier uploads.
                            move_uploaded_file($editedActorImgTempFile, $editedActorImgfile);
                            echo '<div class="alertText flexCenter"><p> l\image '.$editedActorImgName.' a été correctement enregistrée<p></div>';

                        };                       
                    };

                    // j'éfface la relation entre les acteurs et ce film dans la tabla actors_movies afin de pouvoir l'actualiser par la suite avec les nouvelles données
                        
                    $sqlUpdatePreDelete = $db->prepare("DELETE FROM actors_movies WHERE actors_movies.id_actors = $editedActorID");                   
                    $sqlUpdatePreDelete->execute();

                    
                    if (isset($_POST["selectMovies"])) {
                        if(!is_null($_POST["selectMovies"])){
                            $tableMoviesSelect = $_POST["selectMovies"];
                        
                            //var_dump($tableMoviesSelect);
                    
                            $z = 0;
                            foreach ($tableMoviesSelect as $row1){
                                $sqlSelectActorMovies= $db->prepare
                                ("INSERT INTO 
                                    actors_movies
                                    (id_actors, id_movie)
                                    VALUES
                                    (:id_actors, :id_movie)"                        
                                );

                        
                                $sqlSelectActorMovies->execute(
                                    [
                                        "id_actors" => $editedActorID,
                                        "id_movie" => $row1
                                    ]
                                );
                            }   
                        }
                    }


        
                
                
                }else{
                        require("./templates/home_welcome_message.html");
                }
                    
            ?>
                
               
        </section>

        <?php include ('./templates/footer.html'); ?>

        <?php 
            if(isset($_GET["list_actors"])){
                if($_GET["list_actors"] > 0){
                    echo '<script src="./js/script.js"></script>';
                }else{
                    echo'<script src="./js/actors.js"></script>';
                }
            }elseif(isset($_GET["list"])){
                if($_GET["list"] > 0){
                    echo '<script src="./js/script.js"></script>';
                }else{
                    echo'<script src="./js/movies.js"></script>';
                }
            }else{
            };
      
        ?>
        
        

    </body>

</html>
