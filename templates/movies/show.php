<!-- show -->
<?php

$movieID = $_GET['id'];

$sql ="SELECT * FROM `movies` JOIN phase ON movies.id_phase = phase.id  WHERE movies.id = $movieID ";
$sql= $db->query($sql);
$tableau2=$sql->fetch(PDO::FETCH_ASSOC);
$movieName = $tableau2["name"];
?>

    <h2>Les infos du navet</h2>
    <div class="container-fluid">
        <div class="row">
            <div class="col-6"> 
                <?php echo '<img src="./uploads/'.$tableau2["image"].'">'; ?>
            </div>
            <div class=" col-6 clearfix">
                <p>
                   Nom du film : <?php echo $tableau2["name"];    ?>
                </p>
                <p>
                    Nom du directeur : <?php echo $tableau2["director"];    ?>
                </p>
                <p>
                    Date de sortie : <?php echo $tableau2["release_date"];    ?>
                </p>
                <p>
                   Phase :  <?php echo $tableau2["phase"];   ?>
                </p>
                 
                
                <a href ="index.php?edit=<?php echo $movieID ?>" >modifier</a>;


                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">Supprimer</button>
                    
                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">SUPRIMER LE NAVET DE LA BD?</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                    CERTAINS ? 
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <a class="nav-link" href="index.php?del=<?php echo $movieID; ?> ">suprimer</a>
                            </div>
                        </div>
                    </div>
                </div>
                    

            </div>
        </div>
    </div>





