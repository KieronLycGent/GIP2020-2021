<?php
session_start();
if(isset($_GET["end"])){
    if($_GET["end"]){
        session_destroy();
        header("location:".$_SERVER["PHP_SELF"]);
    }
}    
if(!isset($_SESSION["ID"])){
    header("location:portfolioWS.php");
}
if(isset($_POST["join"])){
  // getActID
  $mysqli= new mysqli("localhost","root","","gip");
  //$mysqli= new MySQLi("fdb18.awardspace.net","3833910_gip","Paswoord100","3833910_gip");
  if(mysqli_connect_errno()){
    trigger_error("Fout bij verbinding: ".$mysqli->error);
  }
  else{
    $sql="SELECT ac.actID, ac.actPrijs FROM tblactiviteit ac WHERE ac.actID = ".$_SESSION["ID"];
    if($stmt = $mysqli->prepare($sql)){
      if(!$stmt->execute()){
        echo("Het uitvoeren van qry getActIDPrijs is mislukt: ".$stmt->error."<br>");
      }
      else{
        $stmt->bind_result($actID,$actPrijs);
        $stmt->fetch();
      }
      $stmt->close();
    }
    else{
      echo("Er zit een fout in qry getActIDPrijs: ".$mysqli->error);
    }
  }
  // joinAct
  $mysqli= new mysqli("localhost","root","","gip");
  //$mysqli= new MySQLi("fdb18.awardspace.net","3833910_gip","Paswoord100","3833910_gip");
  if(mysqli_connect_errno()){
    trigger_error("Fout bij verbinding: ".$mysqli->error);
  }
  else{
    if($actPrijs==0){
      $sql="INSERT INTO tblusersperact(actID, userID, inschrDatum, betaald) VALUES (?,?,NOW(),1)";
    }
    else{
      $sql="INSERT INTO tblusersperact(actID, userID, inschrDatum, betaald) VALUES (?,?,NOW(),0)";
    }
    if($stmt = $mysqli->prepare($sql)){
      $stmt->bind_param('ii',$actID,$inschrID);
      $inschrID = $_SESSION["loginID"];
      if(!$stmt->execute()){
        echo"Het uitvoeren van qry joinAct is mislukt: ".$stmt->error."<br>";
      }
      $stmt->close();
    }
    else{
      echo"Er zit een fout in qry joinAct: ".$mysqli->error."<br>";
    }
  }
}
$mysqli= new mysqli("localhost","root","","gip");
//$mysqli= new MySQLi("fdb18.awardspace.net","3833910_gip","Paswoord100","3833910_gip");
if(mysqli_connect_errno()){
  trigger_error("Fout bij verbinding: ".$mysqli->error);
}
else{
  $sql = "SELECT ac.actID, au.auteurNm, au.auteurID, ac.actFoto, ac.actNm, ty.actType, ac.persAantal, ac.persLeeftijdMax, ac.persLeeftijdMin, ac.actBesch, dt.actDatum, TIME_FORMAT(dt.actTijd, '%k:%i'), ac.benNodig, ac.benOpsomming, ac.actPrijs 
  FROM tblactiviteit ac, tblauteur au, tbltypes ty, tbldatumtijd dt WHERE ac.actAuteursID = au.auteurID AND ac.actTypeID = ty.actTypeID AND ac.tijdID = dt.tijdID AND ac.actID = ".$_SESSION["ID"];
  if($stmt = $mysqli->prepare($sql)){
    if(!$stmt->execute()){
      echo("Het uitvoeren van de qry is mislukt: ".$stmt->error."<br>");
    }
    else{
      $stmt->bind_result($actID,$autNm,$autID,$actFoto,$actNm,$actType,$persAantal,$persAgeMax,$persAgeMin,$actBesch,$actDatum,$actTijd,$benNodig,$benOpsomming,$actPrijs);
      $stmt->fetch();
    }
    $stmt->close();
  }
  else{
  echo("Er zit een fout in qry getActInfo: ".$mysqli->error);
  }
}
?>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Details auteur - Workshopp.er</title>
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
  <!-- ======= Top Bar ======= -->
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
        <?php
          if(isset($_SESSION["login"])){
              if(!($_SESSION["login"])){
                  echo"<li><a href=\"inloggen.php\"><i class=\"icofont-sign-in\">Inloggen</i></a></li>";
                  echo"<li><a href=\"registreer.php\">Registreren</a>";
              }
              else{
                  echo"<li><a href=\"".$_SERVER["PHP_SELF"]."?end=true\"><i class=\"icofont-sign-out\"></i>Uitloggen</a></li>";
                  if($_SESSION["admin"]){
                    echo"<li><a href=\"admin.php\">Admin</a></li>";
                  }
                  else{
                    if($_SESSION["loginType"] == "user"){
                      
                      echo"<li><a href=\"wijzigenUser.php\">Profiel</a></li>";
                  }
                  else{
                      echo"<li><a href=\"wijzigenAut.php\">Profiel</a></li>";
                  }
                  }
              }
          }
          else{
              echo"<li><a href=\"inloggen.php\"><i class=\"icofont-sign-in\"></i>Inloggen</a></li>";
              echo"<li><a href=\"registreer.php\">Registreren</a></li>";
          }
          ?>
          <li class="active"><a href="index.php">Home</a></li>
            <li><a href="about.php">Over</a></li>
          <li><a href="contact.php">Contact</a></li>
          <?php
            if(isset($_SESSION["admin"])){
              if($_SESSION["admin"]){
                echo"
                <li><a href=\"portfolioAut.php\">Auteurs</a></li>
                <li><a href=\"portfolioUser.php\">Gebruikers</a></li>
                ";
              }
            }
            if(isset($_SESSION["login"])){
              if(($_SESSION["login"])){
                
                if($_SESSION["loginType"] == "aut"){
                    echo"<li><a href=\"aanmakenWS.php\">Workshop aanmaken</a></li>
                         <li><a href=\"portfolioWSEigen.php\">Mijn workshops</a></li>";
                }
                else if($_SESSION["loginType"] == "user"){
                    echo"<li class=\"active\"><a href=\"portfolioWS.php\">Workshops</a></li>";
                }
              }
            }
            else{
              echo"<li class=\"active\"><a href=\"portfolioWS.php\">Workshops</a></li>";
            }                
            ?>

        </ul>
      </nav><!-- .nav-menu -->

    </div>
  </header><!-- End Header -->
<main id="main">
    <!-- ======= Breadcrumbs ======= -->
    <section id="breadcrumbs" class="breadcrumbs">
      <div class="container">

        <ol>
          <li><a href="index.php">Home</a></li>
          <li>Auteurs</li>
          <li>Details</li>
        </ol>
        <h2>Auteur Details</h2>

      </div>
    </section><!-- End Breadcrumbs -->

    <!-- ======= Portfolio Details Section ======= -->

    <?php
    echo"
    <section id\"portfolio-details\" class=\"portfolio-details\">
      <div class=\"container\">
        <a class=\"icofont-arrow-left\" href=\"portfolioWS.php\">Terug</a>";
      if($_SESSION["loginID"]==$autID&&$_SESSION["loginType"]=="aut"){
        echo"<br><a href=\"wijzigenWS.php\"><i class=\"icofont-pencil\">Aanpassen</i></a>";
      }
      else if($_SESSION["admin"]){
        echo"<br><a href=\"wijzigenWS.php\"><i class=\"icofont-pencil\">Aanpassen als admin</i></a>";
      }
       echo" <div class=\"row\">
          <div class=\"col-lg-8\">
            <img src=\"assets/img/uploads/".$actFoto."\" class=\"img-fluid\" alt=\"\">
            <div class=\"col-lg-4 portfolio-info\">
              <h3>Informatie activiteit</h3>
              <ul>
                <li><strong>Titel</strong>:</li>
                <li>&nbsp;".$actNm."</li>
                <li><strong>Beschrijving</strong>:</li>
                <li>&nbsp;".$actBesch."</li>
                <li><strong>Auteur</strong>:</li>
                <li>&nbsp;".$autNm."</li>
                <li><strong>Activiteitstype</strong>:</li>
                <li>&nbsp;".$actType."</li>
                <li><strong>Leeftijd</strong>:</li>
                <li>&nbsp;".$persAgeMin."-".$persAgeMax."</li>
                <li><strong>Datum en tijdstip</strong>:</li>
                <li>&nbsp;".$actDatum." om ".$actTijd."</li>
                <li><strong>Benodigdheden</strong>:</li>
                <li>&nbsp;";
                if($benOpsomming == ""){
                  echo"Er zijn geen specifieke benodigdheden.";
                  $blancoOpsomming = true;
                }
                echo"</li>";
                if($benNodig != 0){
                  echo"<li>&nbsp;De bovenstaande benodigdheden zijn <strong>verplicht</strong> mee te nemen!</li>";
                }
                else{
                  if(!$blancoOpsomming){
                    echo"<li>&nbsp;&nbsp;De bovenstaande benodigdheden zijn niet verplicht mee te nemen.</li>";
                  }
                }
                echo"
                <li><strong>Prijs</strong>:</li>
                <li>&nbsp;
                ";
                if($actPrijs == 0){
                  echo"Deze activiteit is gratis";
                }
                else{
                  echo($actPrijs."&euro; per persoon");
                }
                echo"
                </li>
              </ul>
            </div>";
            if(isset($_SESSION["loginType"])&&$_SESSION["loginType"] == "user"){
              if($actPrijs == 0){
                echo"<form name =\"frmJoin\" id=\"frmJoin\" method=\"post\" action=\"".$_SERVER["PHP_SELF"]."\">
                <input type=\"submit\" name=\"join\" id=\"join\" value=\"inschrijven\">
                </form>";
              }
              else{
                echo"<form name =\"frmJoin\" id=\"frmJoin\" method=\"post\" action=\"".$_SERVER["PHP_SELF"]."\">
                <input type=\"submit\" name=\"join\" id=\"join\" value=\"Betalen\">
                </form>";
              }
            }
        echo"</div>
        </div>
      </div>
    </section>";
    ?>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
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