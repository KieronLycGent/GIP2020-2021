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
          <?php
            //echo"<a href=\"inloggen.php\">Inloggen</a>";
          ?>
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
                    <?php
                    /* 
                        if((isset($_POST["verzenden"]))&&(isset($_POST["naam"]))&&($_POST["naam"]!="")&&isset($_POST["besch"])&&$_POST["besch"]!=""&&isset($_POST["email"])&&$_POST["email"]!=""&&isset($_POST["pw"])&&isset($_POST["pwCheck"])&&$_POST["pwCheck"]==$_POST["pw"]){
                            $mysqli= new MySQLi("localhost","root","","gip");
                            if(mysqli_connect_errno()){
                                trigger_error('Fout bij verbinding: '.$mysqli->error); 
                            }
                            else{
                                $sql = "INSERT INTO tblAuteur (auteurNm,auteurEmail,auteurPasw,auteurBesch,auteurFoto) VALUES (?,?,?,?,?)"; 
                                if($stmt = $mysqli->prepare($sql)) {     
                                    $stmt->bind_param('sssss',$naam,$email,$pw,$besch,$foto);
                                    $naam = $mysqli->real_escape_string($_POST["naam"]) ;
                                    $email = $mysqli->real_escape_string($_POST["email"]);
                                    $pw = $mysqli->real_escape_string($_POST["pw"]) ;
                                    $besch = $mysqli->real_escape_string($_POST["besch"]);
                                    $foto = $mysqli->real_escape_string("ws.png");
                                    if(!$stmt->execute()){
                                        echo 'het uitvoeren van de query is mislukt:';
                                    }
                                    else{  
                                        echo 'Account aangemaakt';
                                    }
                                    $stmt->close();
                                }
                                else{
                                    echo 'Er zit een fout in de query'; 
                                }
                            }
                        }*/
                ?> 
                <form id="form1" name="form1" method="post" action="aanmakenWS.php">
                    <h2>Workshop aanmaken</h2>
                    <p>Titel: <br>&nbsp;
                        <input type="text" name="titel" id="titel" placeholder="Titel" required value="<?php
                                                                                           if(isset($_POST["titel"])){
                                                                                               echo($_POST["titel"]);
                                                                                           }?>">
                    </p>
                    <p>Beschrijving: <br>&nbsp;
                    <textarea id="besch" name="besch" rows="4" cols="50">
                       <?php
                        if(isset($_POST["besch"])){
                          echo($_POST["besch"]);
                        }
                        ?>
                    </textarea>
                        
                    </p>
                    <p>Aantal personen: <br>&nbsp;
                      <input type="number" id="pers" name="pers" min="1" required>
                    </p>
                    <p>Min. leeftijd: <br>&nbsp;
                      <input type="number" id="ageMin" name="ageMin" min="0" required>
                    </p>
                    <p>Max. leeftijd: (openlaten indien geen) <br>&nbsp;
                      <input type="number" id="ageMax" name="ageMax" min="0">
                    </p>
                    <p>Type: &nbsp;
                    <?php
                    $mysqli= new MySQLi("localhost","root","","gip");
                    if(mysqli_connect_errno()){
                        trigger_error("Fout bij verbinding: ".$mysqli->error);
                    }
                    else{
                        $sql = "select actTypeID, actType from tblTypes";
                        if($stmt = $mysqli->prepare($sql)){
                            if(!$stmt->execute()){
                                echo"Het uitvoeren van de qry is mislukt: ".$stmt->error."in query";
                            }
                            else{                          
                                echo"&nbsp; <select name=\"type\" id=\"type\"><option value=\"-\">-Selecteer type-</option>";
                                $stmt->bind_result($ID, $type);
                                while($stmt->fetch()){
                                    echo"<option value=\"$ID\">".$type."</option>";
                                }
                            }
                            echo"</select>";
                            if(isset($_POST["type"])&&$_POST["type"]!="-"){
                              $mysqli = new MySQLi("localhost","root","","gip");
                              if(mysqli_connect_errno()){
                                trigger_error("Fout bij verbinding: ".$mysqli->error);
                              }
                              else{
                                $sql = "SELECT actType FROM tblTypes WHERE actTypeID = ".$_POST["type"];
                                if($stmt = $mysqli->prepare($sql)){
                                  if(!$stmt->execute()){
                                    echo"Het uitvoeren van qry 'POST' is mislukt: ".$stmt->error." in qry";
                                  }
                                  else{
                                    $stmt->bind_result($postID);
                                    $stmt->fetch();
                                    echo "<br>Vorige keuze was: ".$postID;
                                  }
                                  $stmt->close();
                                }
                              }
                            }
                            else{
                              echo"<br>";
                            }
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