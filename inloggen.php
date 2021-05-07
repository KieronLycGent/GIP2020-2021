<?php
session_start();
$klopt = false;
$ID = 0;
if((isset($_POST["verzenden"]))&&(isset($_POST["email"]))&&($_POST["email"]!="")&&isset($_POST["pw"])&&$_POST["pw"]!=""){
    $mysqli= new MySQLi("localhost","root","","gip");
    if(mysqli_connect_errno()){
        trigger_error('Fout bij verbinding: '.$mysqli->error); 
    }
    else{
        if($_POST["acc"]=="user"){
            $sql = "SELECT userEmail, userPasw, userID, deactivated, isAdmin FROM tbluser WHERE userEmail = '".$_POST["email"]."'"; 
        }
        else if($_POST["acc"]=="aut"){
            $sql = "SELECT auteurEmail, auteurPasw, auteurID, deactivated, isAdmin FROM tblauteur WHERE auteurEmail = '".$_POST["email"]."'";
        }
        if($stmt = $mysqli->prepare($sql)) {     
            if(!$stmt->execute()){
                echo 'het uitvoeren van de query is mislukt:'.$stmt->error."in qry";
            }
            else{  
                $stmt->bind_result($emailCheck,$pwCheck,$ID, $deactivated, $admin);
                while($stmt->fetch()){
                    if(password_verify($_POST["pw"], $pwCheck)){
                        $klopt = true;
                    }
                    if($deactivated == 1){
                        $klopt = false;
                    }
                }
            }
            $stmt->close();
            if($klopt){
                if($_POST["acc"]=="user"){
                    $sql = "SELECT userID FROM tbluser WHERE userEmail ='".$_POST["email"]."'";
                    $_SESSION["ID"] = $ID;                                        }   
                else if($_POST["acc"]=="aut"){
                    $sql = "SELECT auteurID FROM tblauteur WHERE auteurEmail = '".$_POST["email"]."'";
                }
                if($stmt = $mysqli->prepare($sql)) {     
                    if(!$stmt->execute()){
                        echo 'het uitvoeren van de query is mislukt:'.$stmt->error."in qry";
                    }
                    else{  
                        $stmt->bind_result($_SESSION["loginID"]);
                        $stmt->fetch();
                        $_SESSION["login"] = true;
                        if($_POST["acc"]=="user"){
                            $_SESSION["loginType"] = "user";
                        }   
                        else if($_POST["acc"]=="aut"){
                            $_SESSION["loginType"] = "aut";
                        }
                        if($admin != 0){
                            $_SESSION["admin"] = true;
                        }
                        else{
                            $_SESSION["admin"] = false;
                        }
                        header("location:index.php");
                        }
                    $stmt->close();  
                }
                else{
                    echo 'Er zit een fout in de query ' .$mysqli->error; 
                } 
            }
        }
        else{
            echo 'Er zit een fout in de query ' .$mysqli->error; 
        }
    }
}

?> 
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Inloggen - Workshopp.er</title>
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
        <!---<ul>
          <li><a href="index.php">Home</a></li>

            <li><a href="about.php">Over</a></li>
          <li><a href="contact.php">Contact</a></li>
              <li><a href="portfolioAut.php">Auteurs</a></li>
            <li><a href="portfolioUser.php">Gebruikers</a></li>

        </ul>-->
      </nav><!-- .nav-menu -->

    </div>
  </header><!-- End Header -->
<main id="main">

    <!-- ======= Portfolio Details Section ======= -->
        <section>
            <div class="container">
                <?php
                if(!$klopt){
                    echo"<h5 id=\"error\">Paswoord of email niet gekend.</h5>";
                }
                ?>
                <form id="form1" name="form1" method="post" action="inloggen.php">
                    <h2>Inloggen</h2>
                    <a href="registreer.php">Hebt u nog geen account? Maak er dan hier een aan.</a>
                    <p>
                        <input type="radio" name="acc" id="user" value="user" <?php
                               if(isset($_POST["acc"])){
                                   if($_POST["acc"]=="user"){
                                       echo"checked";
                                   } 
                               }
                               else{
                                   echo"checked";
                               }
                               ?>>
                        <label for="user">Gebruiker</label> &nbsp;
                        <input type="radio" name="acc" id="aut" value="aut" <?php
                               if(isset($_POST["acc"])){
                                   if($_POST["acc"]=="aut"){
                                       echo"checked";
                                   } 
                               }
                               ?>>
                        <label for="aut">Auteur</label>
                    </p>
                    <p>E-mail:  &nbsp;
                        <input type="email" name="email" id="email" placeholder="e-mail" required value="<?php
                                                                                           if(isset($_POST["email"])){
                                                                                               echo($_POST["email"]);
                                                                                           }?>">
                    </p>
                    <p>Paswoord: &nbsp;
                        <input type="password" name="pw" id="pw" placeholder="paswoord" required>
                    </p>
                    <p>
                        &nbsp;
                    </p>
                    <p>
                        <input type="submit" name="verzenden" id="verzenden" value="Inloggen">
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