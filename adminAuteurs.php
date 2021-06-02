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
   header("location:wijzigenAut.php");
}
?>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Admin: Auteurs - Workshopp.er</title>
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
              if($_SESSION["admin"]!=0){
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
          <li>Auteurs</li>
        </ol>
        <h2>Auteurs</h2>
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
                        <th width = 200px>Email</th>
                        <th width = 200px>Rekeningnummer</th>
                        <th width = 20px>Gedeactiveerd?</th>
                        <th></th>
                        <th></th>
                    </tr>
                    <?php
$mysqli= new MySQLi("fdb18.awardspace.net","3833910_gip","Paswoord100","3833910_gip");
if(mysqli_connect_errno()){
    trigger_error("Fout bij verbinding: ".$mysqli->error);
}
else{
    $sql = "SELECT a.auteurID,a.auteurNm,a.auteurEmail,a.rekNr,a.deactivated
    FROM tblauteur a";
    if($stmt = $mysqli->prepare($sql)){
        if(!$stmt->execute()){
            echo"Het uitvoeren van qry getAuts is mislukt: ".$stmt->error."in query";
        }
        else{
            $stmt->bind_result($aID,$aNm,$aEmail,$aRekNr,$aDeact);
            while($stmt->fetch()){
                echo"
                <tr>
                    <td>".$aID."</td>
                    <td>".$aNm."</td>
                    <td>".$aEmail."</td>
                    <td>".$aRekNr."</td>";
                    switch($aDeact){
                        case 0:
                            echo"<td>Nee</td>";
                            break;
                        default:
                            echo"<td>Ja</td>";
                            break;
                    }
                echo"
                    <td><a href=\"adminAuteurs.php?item=".$aID."&option=edit\"><i class=\"icofont-pencil-alt-5\"></i></a>
                    <td><a href=\"adminAuteurs.php?item=".$aID."&option=deact\"><i class=\"icofont-not-allowed\"></i></a>
                </tr>";
            }
        }
        $stmt->close();
    }
    else{
      echo"Er zit een fout in qry getAuts: ".$mysqli->error;
    }
}
                    ?>
                </table>
            </div>
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