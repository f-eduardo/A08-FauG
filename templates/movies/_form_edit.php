<?php

$movieID = $_GET['edit'];

$sql ="SELECT * FROM `movies` JOIN phase ON movies.id_phase = phase.id  WHERE movies.id = $movieID ";
$sql= $db->query($sql);
$tableauEdit=$sql->fetch(PDO::FETCH_ASSOC);
$movieName = $tableauEdit["name"];
$movieDirector = $tableauEdit["director"];
$movieDate = $tableauEdit["release_date"];
$moviePhase = $tableauEdit["phase"];

?>


<form action="./index.php" method="post" enctype="multipart/form-data">
  <div class="form-row">
    <div class="card col-md-7 mx-auto my-1">
      <div class="form-group">

        <label for="name">name</label>

        <input type="text" class="form-control" name="name" id="name" placeholder="<?php echo $movieName ?>">
      </div>

      <div class="form-group">
        <label for="director">director</label>

        <input type="text" class="form-control" id="director" name="director" placeholder="<?php echo $movieDirector ?>">
      </div>
      <div class="custom-control custom-radio">       
        <input type="radio" id="phaseI" name="id_phase" class="custom-control-input" value="1">

        <label class="custom-control-label" for="phaseI">phase 1</label>
      </div>

      <div class="custom-control custom-radio">
        <input type="radio" id="phaseII" name="id_phase" class="custom-control-input" value="2">
        <label class="custom-control-label" for="phaseII"> phase 2</label>
      </div>

      <div class="custom-control custom-radio">
        <input type="radio" id="phaseIII" name="id_phase" class="custom-control-input" value="3">
        <label class="custom-control-label" for="phaseIII"> phase 3</label>
      </div>
    </div>

      <div>
        <label for="release_date">date de sortie:</label>
        <input type="date" id="release_date" name="release_date"
        value="<?php echo $movieDate ?>"
        min="1900-01-01" max="2020-07-01">
      </div>


    </div>

    <div class="card col-md-11 mx-auto my-1">
      <div class="form-group">
        <label for="uploadedImg">Joindre une image (jpg ou png)</label>
        <input type="file" class="form-control-file" name="uploadedImg" id="uploadedImg">
      </div>
    </div>

  </div>
  
  <div>
    < name="isedit" class="btn btn-primary float-right" type="submit" value="actualiser la fiche" >   
  </div>
</form>

