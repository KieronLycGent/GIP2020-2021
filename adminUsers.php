<?php
session_start();
if(isset($_GET["end"])){
    if($_GET["end"]){
        session_destroy();
        header("location:".$_SERVER["PHP_SELF"]);
    }
}
if(!$_SESSION["admin"]){
    header("location:index.php");
  }
if(isset($_GET["option"])&&$_GET["option"] == "edit"){
  $_SESSION["ID"] = $_GET["item"];
    header("location:wijzigenUser.php");
}
?>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Contact - Workshopp.er</title>
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
<style type="txt/css">
table{
  border: #000 solid 1px;
}
</style>
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
          <?php
          if(isset($_SESSION["login"])){
              if(!($_SESSION["login"])){
                  echo"<a href=\"inloggen.php\">Inloggen</a>";
                  echo"<a href=\"registreer.php\">Registreren</a>";
              }
              else{
                  echo"<a href=\"".$_SERVER["PHP_SELF"]."?end=true\">Uitloggen</a>";
                  if($_SESSION["loginType"] == "user"){
                      
                      echo"<a href=\"wijzigenUser.php\">Profiel</a>";
                  }
                  else{
                      echo"<a href=\"wijzigenAut.php\">Profiel</a>";
                  }
              }
          }
          else{
              echo"<a href=\"inloggen.php\">Inloggen</a>";
              echo"<a href=\"registreer.php\">Registreren</a>";
          }
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
          <li class="active"><a href="contact.php">Contact</a></li>
              <li><a href="portfolioAut.php">Auteurs</a></li>
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
          <li><a href="admin.php">Admin</a></li>
          <li>Gebruikers</li>
        </ol>
        <h2>Gebruikers</h2>
      </div>
    </section><!-- End Breadcrumbs -->
    <!-- ======= Content Section ======= -->
    <section id="content" class="content">
          <div class="container">
            <div class="row">
                <table id="tblusers" class="admin">
                    <tr>
                        <th width = 20px>ID</th>
                        <th width = 150px>Naam</th>
                        <th width = 200px>Straat</th>
                        <th width = 100px>Postcode</th>
                        <th width = 120px>Gemeente</th>
                        <th width = 200px>Email</th>
                        <th width = 20px>Gedeactiveerd?</th>
                        <th></th>
                        <th></th>
                    </tr>
                    <?php
$mysqli = new mysqli("localhost","root","","gip");
if(mysqli_connect_errno()){
    trigger_error("Fout bij verbinding: ".$mysqli->error);
}
else{
    $sql = "SELECT u.userID, u.userNm, u.userStraat, g.PCode, g.Gemeente, u.userEmail, u.deactivated
    FROM tbluser u, tblgemeente g 
    WHERE g.PostcodeId = u.userPostcode";
    if($stmt = $mysqli->prepare($sql)){
        if(!$stmt->execute()){
            echo"Het uitvoeren van qry getUsers is mislukt: ".$stmt->error."in query";
        }
        else{
            $stmt->bind_result($uID, $uNm, $uStraat, $uPC, $uGem, $uEmail, $uDeact);
            while($stmt->fetch()){
                echo"
                <tr>
                    <td>".$uID."</td>
                    <td>".$uNm."</td>
                    <td>".$uStraat."</td>
                    <td>".$uPC."</td>
                    <td>".$uGem."</td>
                    <td>".$uEmail."</td>";
                    switch($uDeact){
                        case 0:
                            echo"<td>Nee</td>";
                            break;
                        default:
                            echo"<td>Ja</td>";
                            break;
                    }
                echo"
                    <td><a href=\"adminUsers.php?item=".$uID."&option=edit\"><i class=\"icofont-pencil-alt-5\"></i></a>
                    <td><a href=\"adminUsers.php?item=".$uID."&option=deact\"><i class=\"icofont-not-allowed\"></i></a>
                </tr>";
            }
        }
    }
}
                    ?>
                </table>
            </div>
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