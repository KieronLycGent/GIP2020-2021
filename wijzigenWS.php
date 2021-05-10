<?php
session_start();
if(isset($_GET["end"])){
    if($_GET["end"]){
        session_destroy();
        header("location:".$_SERVER["PHP_SELF"]);
    }
}    
if(!isset($_SESSION["ID"])){
    header("location:portfolioAut.php");
}
if(isset($_POST["naam"])&&$_POST["naam"]!=""&&(isset($_POST["besch"]))&&$_POST["besch"]!=""&&(isset($_POST["foto"]))&&$_POST["foto"]!=""){
     
    $mysqli = new MySQLi("localhost","root","","gip");
        if(mysqli_connect_errno()){
            trigger_error("fout bij de verbinding: ".$mysqli->error);
        }
        $sql = "UPDATE tblauteur SET auteurNm = '".$_POST["naam"]."', auteurBesch = '".$_POST["besch"]."', auteurFoto = '".$_POST["foto"]."' WHERE auteurID = ".$_SESSION["ID"];;
        if($stmt = $mysqli->prepare($sql)){
            if(!$stmt->execute()){
                echo"het uitvoeren van de qry is mislukt";
            }
            $stmt->close();
        }
        else{
            echo"Er zit een fout in de qry";
        }
}
$mysqli= new mysqli("localhost","root","","gip");
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
  <header id="header">
    <div class="container d-flex">

      <div class="logo mr-auto">
        <h1 class="text-light"><a href="index.php"><span>Workshopp.er</span></a></h1>
        <!-- Uncomment below if you prefer to use an image logo -->
        <!-- <a href="index.php"><img src="assets/img/logo.png" alt="" class="img-fluid"></a>-->
      </div>

      <nav class="nav-menu d-none d-lg-block">
        <ul>
          <li class="active"><a href="index.php">Home</a></li>

            <li><a href="about.php">Over</a></li>
          <li><a href="contact.php">Contact</a></li>
              <li class="active"><a href="portfolioAut.php">Auteurs</a></li>
            <li><a href="portfolioUser.php">Gebruikers</a></li>

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
    <form action="<?php $_SERVER["PHP_SELF"]?>" method="post">
    <?php
echo"
<section id\"portfolio-details\" class=\"portfolio-details\">
  <div class=\"container\">
    <a class=\"icofont-arrow-left\" href=\"portfolioWS.php\">Terug</a>
    <a href=\"wijzigenWS.php\"><i class=\"icofont-pencil\">Aanpassen</i></a>
    <div class=\"row\">
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
              echo"<li>&nbsp;&nbsp;De bovenstaande benodigdheden zijn<strong>verplicht</strong> mee te nemen!</li>";
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
              echo($actPrijs." per persoon");
            }
            echo"
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>";
?>
    /*$mysqli= new MySQLi("localhost","root","","gip");
    if(mysqli_connect_errno()){
        trigger_error("Fout bij verbinding: ".$mysqli->error);
    }
    else{
      
      $sql = "SELECT ac.actID, au.auteurNm, au.auteurID, ac.actFoto, ac.actNm, ty.actType, ac.persAantal, ac.persLeeftijdMax, ac.persLeeftijdMin, ac.actBesch, dt.actDatum, TIME_FORMAT(dt.actTijd, '%k:%i'), ac.benNodig, ac.benOpsomming, ac.actPrijs 
      FROM tblactiviteit ac, tblauteur au, tbltypes ty, tbldatumtijd dt WHERE ac.actAuteursID = au.auteurID AND ac.actTypeID = ty.actTypeID AND ac.tijdID = dt.tijdID AND ac.actID = ".$_SESSION["ID"];        if($stmt = $mysqli->prepare($sql)){
            if(!$stmt->execute()){
                echo"Het uitvoeren van de qry is mislukt: ".$stmt->error."in query";
            }
            else{
              $stmt->bind_result($actID,$autNm,$autID,$actFoto,$actNm,$actType,$persAantal,$persAgeMax,$persAgeMin,$actBesch,$actDatum,$actTijd,$benNodig,$benOpsomming,$actPrijs);
              echo"
                <section id=\"portfolio-details\" class=\"portfolio-details\">
                    <div class=\"container\">
                        <div class=\"row\">
                        <div class=\"col-lg-8\">
                ";
                while($stmt->fetch()){
                    echo"
                     <img src=\"assets/img/uploads/".$auteurFoto."\" class=\"img-fluid\" alt=\"\">
                     <input type=\"text\" name=\"foto\" id=\"foto\" value=".$auteurFoto.">";
                }
                echo"
                <div class=\"col-lg-4 portfolio-info\">
                    <h3>Informatie Auteur</h3>
                    <ul>
                        <li><input type=\"text\" id=\"naam\" name=\"naam\" value=\"".$auteurNm."\"></li>
                        <li><textarea id=\"besch\" name=\"besch\">".$auteurBesch."</textarea></li>     
                    </ul>
                </div>
                <input type=\"submit\" value=\"Wijzigen\">
                </div>
                </div>
                </div>
                </section>
                ";
            }
            $stmt->close();
        }
        else{
            echo"Er zit een fout in de qry: ".$mysqli->error;
        }
    }*/

    ?>
        
    </form>
  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer">

    <div class="footer-newsletter">
      <div class="container">
        <div class="row">
          <div class="col-lg-6">
            <h4>Onze nieuwsbrief</h4>
            <p>Om updates te rapporteren hebben we een nieuwsbrief. Vul hier uw email in en druk op abonneer om te abonneren.</p>
          </div>
          <div class="col-lg-6">
            <form action="" method="post">
              <input type="email" name="email"><input type="submit" value="Abonneer">
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="footer-top">
      <div class="container">
        <div class="row">

          <div class="col-lg-3 col-md-6 footer-links">
            <h4>Useful Links</h4>
            <ul>
              <li><i class="bx bx-chevron-right"></i> <a href="index.php">Home</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="about.php">Over</a></li>
                <li><i class="bx bx-chevron-right"></i> <a href="contact.php">Contact</a></li>
            </ul>
          </div>

            <div class="col-lg-3 col-md-6 footer-contact">
            <h4>Contacteer ons</h4>
            <p>
              Damaststraat 56<br>
              Mariakereke, 9030 Gent<br>
              Belgi&euml;<br><br>
              <strong>Telefoon:</strong> +32 499 98 75 34<br>
              <strong>Email:</strong> kieron.parmentier@telenet.be<br>
                
            </p>
                <a href="#" class="twitter"><i class="bx bxl-twitter"></i></a>
                <a href="#" class="facebook"><i class="bx bxl-facebook"></i></a>
                <a href="#" class="instagram"><i class="bx bxl-instagram"></i></a>
                <a href="#" class="google-plus"><i class="bx bxl-skype"></i></a>
                <a href="#" class="linkedin"><i class="bx bxl-linkedin"></i></a>
        </div>
      </div>
    </div>
      </div>
    <div class="container">
      <div class="copyright">
        &copy; Copyright <strong><span>Eterna</span></strong>. All Rights Reserved
      </div>
      <div class="credits">
        <!-- All the links in the footer should remain intact. -->
        <!-- You can delete the links only if you purchased the pro version. -->
        <!-- Licensing information: https://bootstrapmade.com/license/ -->
        <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/eterna-free-multipurpose-bootstrap-template/ -->
        Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
      </div>
    </div>
  </footer><!-- End Footer -->
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