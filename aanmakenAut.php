<?php
//emailCheck
if(isset($_POST["verzenden"])&&isset($_POST["email"])&&$_POST["email"]!=""){
  $mysqli= new mysqli("localhost","root","","gip");
  //$mysqli= new MySQLi("fdb18.awardspace.net","3833910_gip","Paswoord100","3833910_gip");
    if(mysqli_connect_errno()){
      trigger_error("Fout bij verbinding: ".$mysqli->error);
  }
  else{
      $sql ="SELECT COUNT(auteurEmail) FROM tblauteur WHERE auteurEmail = '".$_POST["email"]."'";
      if($stmt=$mysqli->prepare($sql)){
          if(!$stmt->execute()){
              echo"Het uitvoeren van qry emailCheck is mislukt: ".$stmt->error."<br>";
          }
          else{
              $stmt->bind_result($emailCount);
              $stmt->fetch();
              if($emailCount >= 1){
                  $emailCheck = false;
              }
              else{
                  $emailCheck = true;
              }
          }
          $stmt->close();
      }
      else{
          echo"Er zit een fout in qry emailCheck: ".$mysqli->error."<br>";
      }
  }
}
//Insert
if((isset($_POST["verzenden"]))&&(isset($_POST["naam"]))&&($_POST["naam"]!="")&&isset($_POST["besch"])&&$_POST["besch"]!=""&&isset($_POST["email"])&&$_POST["email"]!=""&&isset($_POST["pw"])&&isset($_POST["pwCheck"])&&$_POST["pwCheck"]==$_POST["pw"]){
  if($emailCheck){
    $mysqli= new mysqli("localhost","root","","gip");
    //$mysqli= new MySQLi("fdb18.awardspace.net","3833910_gip","Paswoord100","3833910_gip");
        if(mysqli_connect_errno()){
        trigger_error('Fout bij verbinding: '.$mysqli->error); 
    }
    else{
      $sql = "INSERT INTO tblauteur (auteurNm,auteurEmail,auteurPasw,auteurBesch,auteurFoto,rekNr) VALUES (?,?,?,?,?,?)"; 
      if($stmt = $mysqli->prepare($sql)) {     
        $stmt->bind_param('ssssss',$naam,$email,$hashedPw,$besch,$foto,$rekNr);
        $naam = $mysqli->real_escape_string($_POST["naam"]) ;
        $email = $mysqli->real_escape_string($_POST["email"]);
        $hashedPw = password_hash($mysqli->real_escape_string($_POST["pw"]), PASSWORD_DEFAULT);
        $besch = $mysqli->real_escape_string($_POST["besch"]);
        $foto = $mysqli->real_escape_string("ws.png");
        $rekNr = $mysqli->real_escape_string($_POST["rekNr"]);
        if(!$stmt->execute()){
          echo 'het uitvoeren van de query is mislukt:'.$stmt->error;
        }
        else{  
          header("location:inloggen.php");
        }
        $stmt->close();
      }
      else{
        echo 'Er zit een fout in de query'; 
      }
    }
  }  
}
?>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Auteur aanmaken - Workshopp.er</title>
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
                <form id="form1" name="form1" method="post" action="aanmakenAut.php">
                    <h2>Auteur aanmaken</h2>
                    <a href="registreer.php">Toch al een account? Log hier dan in.</a>
                    <p>Naam:  &nbsp;
                        <input type="text" name="naam" id="naam" placeholder="naam" required value="<?php
                                                                                           if(isset($_POST["naam"])){
                                                                                               echo($_POST["naam"]);
                                                                                           }?>">
                    </p>
                    <p>E-mail: &nbsp;
                        <input type="email" name="email" id="email" placeholder="e-mail" required value="<?php
                                                                                           if(isset($_POST["email"])){
                                                                                               echo($_POST["email"]);
                                                                                           }?>">
                    <?php
                    if(isset($emailCheck)&&!$emailCheck){
                      echo"<br><a id=\"error\">Deze email is al in gebruik bij een auteursaccount.</a>";
                    }
                    ?>
                    </p>
                     <p>
                        Paswoord: &nbsp;
                        <input type="password" name="pw" id="pw" placeholder="Paswoord" required>
                    </p>
                    <p>
                        Paswoord bevestigen: &nbsp;
                        <input type="password" name="pwCheck" id="pwCheck" placeholder="Paswoord bevestigen" required>
                        <?php
                        if(isset($_POST["pw"])&& isset($_POST["pwCheck"])){
                            if($_POST["pw"]!=$_POST["pwCheck"]){
                                echo"<br><a id=\"error\">Paswoord in het eerste veld komt niet overeen met paswoord in het tweede veld!</a>";
                            }
                        }    
                        ?>
                    </p>
                    <p>Beschrijving: &nbsp;
                        <input type="text" name="besch" id="besch" placeholder="besch" required value="<?php
                                                                                                       if(isset($_POST["besch"])){
                                                                                                           echo($_POST["besch"]);
                                                                                                       }?>">
                    </p>
                    <p>Rekeningnummer: &nbsp;
                      <input type="text" name="rekNr" id="rekNr" placeholder="Rekeningnummer" required value="<?php
                                                                                                              if(isset($_POST["rekNr"])){
                                                                                                                echo($_POST["rekNr"]);
                                                                                                              }
                                                                                                              ?>">
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