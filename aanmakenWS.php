<?php
session_start();
if(isset($_SESSION["login"])&&$_SESSION["login"])
{
  if($_SESSION["loginType"]!="aut"){
    header("location:index.php");
  }
}
else{
  header("location:index.php");
}
//code voor na de form is verzonden
if(isset($_POST["verzenden"])){
 // print_r($_POST);
  // echo"<br>";
  if(isset($_POST["ageMax"])){
    if($_POST["ageMax"]!=""){
      $ageMax = $_POST["ageMax"];
    }
    else{
      $ageMax = 100;
    }
  }
  else{
    $ageMax = 100;
  }
  if(isset($_POST["titel"])&&$_POST["titel"]!=""&&isset($_POST["besch"])&&$_POST["besch"]!=""&&isset($_POST["pers"])&&$_POST["pers"]>0&&isset($_POST["ageMin"])&&$_POST["ageMin"]>=0&&$_POST["ageMin"]<=$ageMax&&isset($_POST["date"])&&isset($_POST["time"])&&isset($_POST["type"])&&$_POST["type"]!="-"){
    //query dateTime
    $mysqli = new MySQLi("localhost","root","","gip");
    if(mysqli_connect_errno()){
      trigger_error("Fout bij verbinding: ".$mysqli->error);
    }
    else{
      $sql = "INSERT INTO tbldatumtijd (actDatum, actTijd) VALUES (?,?)";
      if($stmt = $mysqli->prepare($sql)){
        $stmt->bind_param('ss',$actDatum,$actTijd);
        $actDatum = $_POST["date"];
        $actTijd = $_POST["time"];
        if(!$stmt->execute()){
          echo("het uitvoeren van qry dateTime is mislukt: ".$stmt->error."<br>");
        }
        else{
          echo"dateTime ingevoegd <br>";
        }
        $stmt->close();
      }
      else{
        echo("Er zit een fout in qry dateTime: ".$mysqli->error."<br>");
      }
    }
    //query fetchDateTimeID
    $mysqli = new mysqli("localhost","root","","gip");
    if(mysqli_connect_errno()){
      trigger_error("Fout bij verbinding: ".$mysqli->error);
    }
    else{
      $sql = "SELECT COUNT(TijdID) FROM tbldatumtijd";
      if($stmt = $mysqli->prepare($sql)){
        if(!$stmt->execute()){
          echo("Het uitvoeren van qry fetchDateTime is mislukt: ".$stmt->error."<br>");
        }
        else{
          $stmt->bind_result($tijdID);
          $stmt->fetch();
        }
        $stmt->close();
      }
      else{
        echo("Er zit een fout in qry fetchDateTime: ".$mysqli->error."<br>");
      }
    }
    //query actInfo
    $mysqli = new MySQLi("localhost","root","","gip");
    if(mysqli_connect_errno()){
      trigger_error("Fout bij verbinding: ".$mysqli->error);
    }
    else{
      $sql = "INSERT INTO tblactiviteit (actAuteursID, actFoto, actNm, actTypeID, persAantal, persLeeftijdMax, persLeeftijdMin, actBesch, tijdID, benNodig, benOpsomming, actPrijs) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)"; //Dit zouden er 12 moeten zijn
      if($stmt = $mysqli->prepare($sql)){
        $stmt->bind_param('issiiiisiisi',$actAutId,$actFoto,$actNm,$actTypeID,$persAantal,$persLeeftijdMax,$persLeeftijdMin,$actBesch,$tijdID,$benNodig,$benOpsomming,$actPrijs);
        $actAutId = $_SESSION["loginID"];
        $actFoto = $mysqli->real_escape_string("ws.png");
        $actNm = $mysqli->real_escape_string($_POST["titel"]);
        $actTypeID = $_POST["type"];
        $persAantal = $_POST["pers"];
        $persLeeftijdMax = $ageMax;
        $persLeeftijdMin = $_POST["ageMin"];
        $actBesch = $mysqli->real_escape_string($_POST["besch"]);
        if(isset($_POST["benNodig"])){
          if($_POST["benNodig"]){
            $benNodig = $_POST["benNodig"];
          }
          else{
            $benNodig = 0;
          }
        }
        else{
          $benNodig = 0;
        }
        if(isset($_POST["benOpsomming"])){
          $benOpsomming = $mysqli->real_escape_string($_POST["benOpsomming"]);
        }
        else{
          $benOpsomming = "";
        }
        $actPrijs = $_POST["prijs"];
        if(!$stmt->execute()){
          echo("Het uitvoeren van qry actInfo is mislukt: ".$stmt->error."<br>");
        }
        else{
          echo"WS ingevoegd";
          header("location:portfolioWSEigen.php");
        }
        $stmt->close();
      }
      else{
        echo("Er zit een fout in qry actInfo: ".$mysqli->error."<br>");
      }
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Workshop aanmaken - Workshopp.er</title>
  <meta content="" name="descriptison">
  <meta content="" name="keywords">
  <!-- Favicons -->
  <link href="assets/img/ws.png" rel="icon">
  <link href="assets/img/ws.png" rel="apple-touch-icon">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/icofont/icofont.min.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/animate.css/animate.min.css" rel="stylesheet">
  <link href="assets/vendor/owl.carousel/assets/owl.carousel.min.css" rel="stylesheet">
  <link href="assets/vendor/venobox/venobox.css" rel="stylesheet">
  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">
  <!-- =======================================================
  * Template Name: Eterna - v2.1.0
  * Template URL: https://bootstrapmade.com/eterna-free-multipurpose-bootstrap-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>
<body>
  <section id="topbar" class="d-none d-lg-block">
    <div class="container d-flex">
      <div class="social-links">
      </div>
    </div>
  </section>
  <!-- ======= Header ======= -->
 <header id="header">
    <div class="container d-flex">
      <div class="logo mr-auto">
        <h1 class="text-light"><a href="index.php"><span>Workshopp.er</span></a></h1>
        <!-- Uncomment below if you prefer to use an image logo -->
        <!-- <a href="index.php"><img src="assets/img/logo.png" alt="" class="img-fluid"></a>-->
      </div>
      <nav class="nav-menu d-none d-lg-block">
        <ul>
          <li><a href="index.php">Home</a></li>
            <li><a href="about.php">Over</a></li>
          <li><a href="contact.php">Contact</a></li>
              <li class="active"><a href="portfolioAut.php">Auteurs</a></li>
            <li><a href="portfolioUser.php">Gebruikers</a></li>
        </ul>
      </nav><!-- .nav-menu -->
    </div>
  </header><!-- End Header -->
<main id="main">
    <!-- ======= Portfolio Details Section ======= -->
        <section>
            <div class="container">
                    
                <form id="form1" name="form1" method="post" action="aanmakenWS.php">
                    <h2>Workshop aanmaken</h2>
                    <p>Titel: <br>&nbsp;
                        <input type="text" name="titel" id="titel" placeholder="Titel" required value="<?php
                                                                                           if(isset($_POST["titel"])){
                                                                                               echo($_POST["titel"]);
                                                                                           }?>">
                    </p>
                    <p>Beschrijving: <br>&nbsp;
                        <textarea id="besch" name="besch" rows="4" cols="50"><?php
                        if(isset($_POST["besch"])){
                          echo(trim($_POST["besch"]));
                        }
                        ?></textarea>
                        
                    </p>
                    <p>Aantal personen: <br>&nbsp;
                      <input type="number" id="pers" name="pers" min="1" required value="<?php
                                                                              if(isset($_POST["pers"])){
                                                                                echo($_POST["pers"]);
                                                                              }
                                                                              ?>">
                    </p>
                    <p>Min. leeftijd: <br>&nbsp;
                      <input type="number" id="ageMin" name="ageMin" min="0" max="100" required value="<?php
                                                                                if(isset($_POST["ageMin"])){
                                                                                  echo($_POST["ageMin"]);
                                                                                }
                                                                                ?>">
                    </p>
                    <p>Max. leeftijd: (openlaten indien geen) <br>&nbsp;
                      <input type="number" id="ageMax" name="ageMax" min="0" max="100" value="<?php
                                                                            if(isset($_POST["ageMax"])){
                                                                              echo($_POST["ageMax"]);
                                                                            }
                                                                            ?>">
                    </p>
                    <p>Datum<br>&nbsp;<!--geeft de datum terug als YYYY-MM-DD -->
                      <input type="date" id="date" name="date" required value="<?php //MOET NAAR tblDatumTijd!!!
                                                                          if(isset($_POST["date"])){
                                                                            echo($_POST["date"]);
                                                                          }
                                                                          ?>">
                    </p>
                    <p>Tijdstip<br>&nbsp;<!--geeft het tijdstip terug als HH:MM -->
                      <input type="time" id="time" name="time" required value="<?php //MOET NAAR tblDatumTijd!!!
                                                                        if(isset($_POST["time"])){
                                                                          echo($_POST["time"]);
                                                                        }
                                                                        ?>">
                    </p>
                    <p>Moet er zelf iets meegebracht worden?
                      <input type="checkbox" id="benNodig" name="benNodig" value="1" <?php  if(isset($_POST["benNodig"])){
                                                                                    if($_POST["benNodig"]){
                                                                                      echo"checked";
                                                                                    }
                                                                                  } ?>>
                    </p>
                    <p>Benodigdheden: <br> &nbsp;
                    <textarea id="benOpsomming" name="benOpsomming" rows="4" cols="50"><?php
                        if(isset($_POST["benOpsomming"])){
                          echo(trim($_POST["benOpsomming"]));
                        }
                        ?></textarea>
                    </p>
                    <p>Type: <br>&nbsp;
                    <?php if(isset($_POST["verzenden"])&&$_POST["type"]=="-"){
                      echo"<a id=\"error\">Gelieve een optie te selecteren.</a><br>";
                    }
                    //activiteitstype als selectie geven
                    $mysqli= new MySQLi("localhost","root","","gip");
                    if(mysqli_connect_errno()){
                        trigger_error("Fout bij verbinding: ".$mysqli->error);
                    }
                    else{
                      $sql = "select actTypeID, actType from tbltypes";
                      if($stmt = $mysqli->prepare($sql)){
                        if(!$stmt->execute()){
                            echo"Het uitvoeren van de qry is mislukt: ".$stmt->error."in query";
                        }
                        else{                          
                          echo"&nbsp; <select name=\"type\" id=\"type\"><option value=\"-\">-Selecteer type-</option>";
                          $stmt->bind_result($ID, $type);
                            while($stmt->fetch()){
                            if(isset($_POST["type"])){
                              if($ID == $_POST["type"]){
                                echo"<option value =\"$ID\" selected>".$type."</option>";
                              }
                              else{
                                echo"<option value=\"$ID\">".$type."</option>";
                              }
                            }
                            else{
                              echo"<option value=\"$ID\">".$type."</option>";
                            }
                          }
                        }
                        echo"</select>";
                        echo"<br>
                        &nbsp;
                        <br>
                        ";
                        @$stmt->close();
                      }
                      else{
                        echo"Er zit een fout in de qry: ".$mysqli->error;
                      }
                    }
                    ?>
                    </p>
                    <p>Toegangsprijs in EUR (deze mag &euro;0 zijn)<br> &nbsp;
                      &euro;<input type="number" id="prijs" name="prijs" min="0" step=".01" value="<?php
                                                                                                  if(isset($_POST["prijs"])){
                                                                                                    echo($_POST["prijs"]);
                                                                                                  }
                                                                                                  else{
                                                                                                    echo"0.00";
                                                                                                  }
                                                                                                  ?>" required>
                    </p>
                    <p>
                        &nbsp;
                    </p>
                    <p>
                        <input type="submit" name="verzenden" id="verzenden" value="Aanmaken">
                    </p>
                    <p>
                        &nbsp;
                    </p>
                </form>
            </div>
        </section>
  </main><!-- End #main -->
  <a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/jquery/jquery.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/jquery.easing/jquery.easing.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/jquery-sticky/jquery.sticky.js"></script>
  <script src="assets/vendor/owl.carousel/owl.carousel.min.js"></script>
  <script src="assets/vendor/waypoints/jquery.waypoints.min.js"></script>
  <script src="assets/vendor/counterup/counterup.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/venobox/venobox.min.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>