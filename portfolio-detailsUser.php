<?php
session_start();
if(isset($_GET["end"])){
    if($_GET["end"]){
        session_destroy();
        header("location:".$_SERVER["PHP_SELF"]);
    }
}
?>
<!DOCTYPE html>
<?php
    
if(!isset($_SESSION["ID"])){
    header("location:portfolioUser.php");
}
?>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Details user - Workshopp.er</title>
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
          <li><a href="contact.php">Contact</a></li>
              <li><a href="portfolioAut.php">Auteurs</a></li>
            <li class="active"><a href="portfolioUser.php">Gebruikers</a></li>

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
          <li>Users</li>
          <li>Details</li>
        </ol>
        <h2>User Details</h2>

      </div>
    </section><!-- End Breadcrumbs -->

    <!-- ======= Portfolio Details Section ======= -->

<?php
$mysqli= new MySQLi("localhost","root","","gip");
if(mysqli_connect_errno()){
    trigger_error("Fout bij verbinding: ".$mysqli->error);
}
else{
    $sql = "SELECT u.userID, u.userNm, u.userFoto, u.userStraat, g.PCode, g.Gemeente FROM tbluser u, tblgemeente g WHERE u.userID=".$_SESSION["ID"]." AND u.userPostcode = g.PostcodeID";
    if($stmt = $mysqli->prepare($sql)){
        if(!$stmt->execute()){
            echo"Het uitvoeren van de qry is mislukt: ".$stmt->error."in query";
        }
        else{
            $stmt->bind_result($userID, $userNm, $userFoto, $userStraat, $pcode, $gemeente);
            echo"
            <section id=\"portfolio-details\" class=\"portfolio-details\">
                <div class=\"container\">
                        <a class=\"icofont-arrow-left\" href=\"portfolioUser.php\">Terug</a>
                      <div class=\"row\">
                    <div class=\"col-lg-8\">
            ";
            while($stmt->fetch()){
                echo"
                  <img src=\"assets/img/uploads/".$userFoto."\" class=\"img-fluid\" alt=\"\">";
            }
            echo"
            <div class=\"col-lg-4 portfolio-info\">
                <h3>Informatie user</h3>
                <ul>
                    <li><strong>Naam</strong>: ".$userNm."</li>
                    <li><strong>Adres</strong>:</li>
                    <li>".$userStraat."<br>".$pcode." ".$gemeente."</li>
                    <li><strong>Interesses</strong>:</li>";
            $stmt->close(); 
        }
    }
    else{
        echo"Er zit een fout in de qry: ".$mysqli->error;
    }
}
$mysqli = new mysqli("localhost","root","","gip");
$sql = "SELECT i.interesseNm FROM tblinteresse i, tblinteressesuser iu WHERE iu.userID = ".$_SESSION["ID"]." AND iu.interesseID = i.interesseID";
if($stmt = $mysqli->prepare($sql)){
  if(!$stmt->execute()){
    echo"Het uitvoeren van qry interesses is mislukt: ".$stmt->error."in qry<br>";
  }
  else{
    $stmt->bind_result($int);
    while($stmt->fetch()){
        echo"<li>".$int."</li>";
    }
  }
  $stmt->close();
}
else{
  echo"Er zit in fout in qry interesses: ".$mysqli->error;"<br>";
}
    echo"            </ul>
                    <p>
                        
                    </p>
                </div>
                            </div>
                        </div>
                    </div>
                </section>
                ";
    
    ?>

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