<?php
$mysqli= new MySQLi("localhost","root","","gipcorr");
if(mysqli_connect_errno()){
    trigger_error("Fout bij verbinding: ".$mysqli->error);
}
else{
    $sql = "select * from tblAuteur";
    if($stmt = $mysqli->prepare($sql)){
        if(!$stmt->execute()){
            echo"Het uitvoeren van de qry is mislukt: ".$stmt->error."in query";
        }
        else{
            $stmt->bind_result($auteurID, $auteurNm, $auteurBesch, $auteurFoto);
            while($stmt->fetch()){
                echo"
                <div class=\"row portfolio-container\">

          <div class=\"col-lg-4 col-md-6 portfolio-item\">
            <div class=\"portfolio-wrap\">
              <img src=\"assets/img/".$auteurFoto."\" class=\"img-fluid\" alt=\"\">
              <div class=\"portfolio-info\">
                
                <div class=\"portfolio-links\">
                  <a href=\"assets/img/portfolio/portfolio-1.jpg\" data-gall=\"portfolioGallery\" class=\"venobox\" title=\"App 1\"><i class=\"bx bx-plus\"></i></a>
                  <a href=\"portfolio-details.html\" title=\"More Details\"><i class=\"bx bx-link\"></i></a>
                </div>
              </div>
            </div>
          </div>";
            }
        }
        $stmt->close();
    }
    else{
        echo"Er zit een fout in de qry: ".$mysqli->error;
    }
}