<?php include_once ('./settings/db.php'); ?>
<?php
if(!empty($_POST["isSubmit-addmore"])){

    $table = [
        'name'=> $_POST['name'], 
        'director'=> $_POST['director'], 
        'id_phase'=> ($_POST['id_phase']),
        'release_date' =>$_POST['release_date'],
        "img" =>$_FILES ['uploadedImg']    
     ];
    
    
}


 ?>

<!DOCTYPE html>
<html lang="en">

    <?php include_once ('./templates/head.html'); ?>

    <body>

        <?php include_once ('./templates/header.html'); ?>

        <?php include_once './templates/nav.html'; ?>

        <section>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-3"> 
                        <?php include_once ('./templates/nav.html'); ?>            
                    </div>
                    <div class=" col-9 clearfix">
                        <?php

                            if(isset($_GET["list"])){
                                require("./templates/movies/list.php");
                            }
                            elseif(isset($_GET["new"])){

                                require("./templates/movies/_form_new.php");
                            }

                            elseif(isset($_POST["isSubmit-addmore"])){

                                $validExt = ["image/jpg", "image/png","image/jpeg"];
                                $fileExt = $_FILES["uploadedImg"]["type"];
                                $tempFile = $_FILES["uploadedImg"]["tmp_name"];
                                $file = 'uploads/'.$_FILES["uploadedImg"]["name"];
                                
                                var_dump($table);

                                $sql = $db->prepare("INSERT INTO `movies`( `name`, `release_date`, `director`, `id_phase`, `image`) VALUES (:name, :release_date, :director, :id_phase, :image)");
                                $sql->execute(
                                    [":name" => $table["name"], ":release_date" => $table["release_date"], ":director" =>$table["director"], ":id_phase"=>$table["id_phase"], ":image"=> $table["img"]["name"]]
                                );
                                echo $db->lastInsertId();

                                move_uploaded_file($tempFile, $file);
                                echo '
                                <div class="alert alert-success" role="alert"><p class="text-center"> image enregistrée</p></div>
                                <div class="alert alert-success" role="alert"> <p class="text-center"> les données ont été saugardées</p></div>';
                                            
      
                            }

                        
                        // * Conditions with:
                        //*      - if/else
                        //*          or
                        //*      - switch/case
                        

                        
                        //if ...
                        //include_once './templates/movies/_form_new.php';
                        
                        //elseif ...
                        //     include_once './templates/movies/_form_edit.php';
                        
                        //elseif ...
                            
                            else{
                            echo ' <h2> Bienvenu Vieux Geek </2>';
                            //- button for add new movie
                            }
                            
                        ?>
                    </div>
            </div>
        </section>

        <?php include_once './templates/footer.html'; ?>

    </body>

</html>
