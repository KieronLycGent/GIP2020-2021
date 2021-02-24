<?php
session_start();
?>
<!DOCTYPE html>
<?php
    
if(!isset($_COOKIE["uID"])){
    header("location:portfolioUser.php");
}
$pcErr = false;
if ((isset($_POST["verzenden"]))&& (isset($_POST["postcode"])) && ($_POST["postcode"] != "") &&isset($_POST["gemeente"]) && $_POST["gemeente"] !="" ){
    $mysqli=new MySQLI("localhost","root","","gip");
    if((isset($_POST["interesse1"]))&&(isset($_POST["interesse2"]))&&(isset($_POST["interesse3"]))){
        
        if (mysqli_connect_errno()){
            trigger_error('Fout bij verbinding: '.$mysqli->error);
        }
        //doet de check voor postcode = gemeente
        else{
            $sql="SELECT COUNT(PostcodeId) FROM tblgemeente WHERE PCode=? AND Gemeente=?";
            if ($stmt=$mysqli->prepare($sql)){
                $stmt->bind_param('ss',$PCode,$gemeente);
                $PCode= $_POST["postcode"];
                $gemeente = $_POST["gemeente"];
                if(!$stmt->execute()){
                    echo 'Het uitvoeren van de query is mislukt: '.$stmt->error.'in query';
                }
                else{  
                    $stmt->bind_result($aantalPostcodeId);
                    $stmt->fetch();
                    $aantalPostcodeId1= $aantalPostcodeId;
                    if($aantalPostcodeId1 > 0){
                        if((isset($_POST["verzenden"]))&& (isset($_POST["postcode"])) && ($_POST["postcode"] != "") && isset($_POST["gemeente"]) && $_POST["gemeente"]!=""){
                            $mysqli=new MySQLI("localhost","root","","gip");
                            if(mysqli_connect_errno()){
                                trigger_error('Fout bij verbinding: '.$mysqli->error);
                            }
                            else{
                                $sql="SELECT PostcodeId FROM tblgemeente WHERE PCode=? AND Gemeente=?";
                                if($stmt=$mysqli->prepare($sql)){
                                    $stmt->bind_param('ss', $PCode, $gemeente);
                                    $PCode= $_POST["postcode"];
                                    $gemeente = $_POST["gemeente"];
                                    if(!$stmt->execute()){
                                        echo 'Het uitvoeren van de query is mislukt: '.$stmt->error.'in query';
                                    }
                                    else{  
                                        $stmt->bind_result($PostcodeId);
                                        $stmt->fetch();
                                        $PostcodeId1= $PostcodeId;
                                    }
                                    //einde postcode/gemeente check
                                    $stmt->close();
                                    //Begin verandering van general stuff
                                    if(isset($_POST["naam"])&&$_POST["naam"]!=""&&(isset($_POST["foto"]))&&$_POST["foto"]!=""){
                                        $mysqli = new MySQLi("localhost","root","","gip");
                                        if(mysqli_connect_errno()){
                                            trigger_error("fout bij de verbinding: ".$mysqli->error);
                                        }
                                        $sql = "UPDATE tblUser SET userNm = '".$_POST["naam"]."', userFoto = '".$_POST["foto"]."', userPostcode = '".$PostcodeId1."' WHERE userID = ".$_COOKIE["uID"];
                                        if($stmt = $mysqli->prepare($sql)){
                                            if(!$stmt->execute()){
                                                echo"het uitvoeren van de qry is mislukt";
                                            }
                                            $stmt->close();
                                            //einde verandering general stuff
                                            //Begin interessesID preparen voor tbluser
                                            $mysqli= new MySQLi("localhost","root","","gip");
                                            if(mysqli_connect_errno()){
                                                trigger_error("Fout bij verbinding: ".$mysqli->error);
                                            }
                                            else{
                                                $sql = "select COUNT(interessesID) from tblInteressesuser";
                                                if($stmt = $mysqli->prepare($sql)){
                                                    if(!$stmt->execute()){
                                                        echo"Het uitvoeren van de qry is mislukt: ".$stmt->error."in query";
                                                    }
                                                    else{                          
                                                        $stmt->bind_result($bindID);
                                                        while($stmt->fetch()){
                                                            $bindID = $bindID+1;
                                                        }
                                                    }
                                                    $stmt->close();
                                                }
                                                else{
                                                    echo"Er zit een fout in de qry: ".$mysqli->error;
                                                }
                                            }
                                            //Inserting the number
                                            $mysqli= new MySQLi("localhost","root","","gip");
                                            if(mysqli_connect_errno()){
                                                trigger_error('Fout bij verbinding: '.$mysqli->error); 
                                            }
                                            else{
                                                $sql = "INSERT INTO tblUser (userNm, userFoto, userPostcode, userStraat, interessesID) VALUES (?,?,?,?,?)"; 
                                                if($stmt = $mysqli->prepare($sql)) {     
                                                    $stmt->bind_param('ssisi',$naam,$foto,$post,$straat,$bindID);
                                                    $naam = $mysqli->real_escape_string($_POST["naam"]) ;
                                                    $foto = $mysqli->real_escape_string("ws.png");
                                                    $post = $PostcodeId1;
                                                    $straat = $mysqli->real_escape_string($_POST["straat"]);
                                                    if(!$stmt->execute()){
                                                        echo 'Het uitvoeren van de qry is mislukt:'.$mysqli->error;
                                                    }
                                                    else{  
                                                        echo 'Account aangemaakt';
                                                    }
                                                    $stmt->close();
                                                }
                                                else{
                                                    echo 'Er zit een fout in de query'.$mysqli->error; 
                                                } 
                                            }
                                            //einde nieuwe interessesID
                                            
                                            
                                            //Begin check ofdat interesses NULL zijn
                                            $mysqli= new MySQLi("localhost","root","","gip");
                                            if(mysqli_connect_errno()){
                                                trigger_error("Fout bij verbinding: ".$mysqli->error);
                                            }
                                            //Zorgt ervoor dat als er een interesse als "-" wordt geselecteerd dat deze worden weggelaten.
                                            else{
                                                if($_POST["interesse1"] == "-"){
                                                    $i1 = NULL;
                                                }
                                                else{
                                                    $i1 = $_POST["interesse1"];
                                                }
                                                if($_POST["interesse2"] == "-"){
                                                    $i2 = NULL;
                                                }
                                                else{
                                                    $i2 = $_POST["interesse2"];
                                                }
                                                if($_POST["interesse3"] == "-"){
                                                    $i3 = NULL;
                                                }
                                                else{
                                                    $i3 = $_POST["interesse3"];
                                                }
                                                //maken van een nieuw interessesID
                                                $sql = "INSERT INTO `tblinteressesuser` (`interessesID`, `interesseID1`, `interesseID2`, `interesseID3`) VALUES (NULL,?,?,?) ";
                                                if($stmt = $mysqli->prepare($sql)) {     
                                                    $stmt->bind_param('sss',$i1,$i2,$i3);
                                                    if(!$stmt->execute()){
                                                        echo 'het uitvoeren van de query is mislukt:';
                                                    }
                                                    $stmt->close();
                                                }
                                                //error interessesID aanmaken
                                                else{
                                                    echo"Er zit een fout in de qry: ".$mysqli->error;
                                                }
                                            }
                                            
                                        }
                                        //error check general
                                        else{
                                            echo"Er zit een fout in de qry:" .$mysqli->error;
                                        }
                                    }
                                }
                                else{
                                    echo 'Er zit een fout in de qry: '.$mysqli->error;
                                }
                            }
                        } 
                    }
                    //Einde gemeente/postcode check
                    else
                    {
                        $pcErr = true;
                    }
                }        
                @$stmt->close();
            }
            else
            {
                echo 'Er is een fout in de query: '.$mysqli->error;
            }
        }
    }
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
    <style type="text/css">
        #error{
            color: red;
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
          <li>Auteurs</li>
          <li>Details</li>
        </ol>
        <h2>Auteur Details</h2>

      </div>
    </section><!-- End Breadcrumbs -->

    <!-- ======= Portfolio Details Section ======= -->
    <form action="<?php $_SERVER["PHP_SELF"]?>" method="post">
    <?php
    $mysqli= new MySQLi("localhost","root","","gip");
    if(mysqli_connect_errno()){
        trigger_error("Fout bij verbinding: ".$mysqli->error);
    }
    else{
        $sql = "SELECT u.userID, u.userNm, u.userFoto, u.userStraat, g.PCode, g.Gemeente FROM tblUser u, tblgemeente g WHERE u.userID=".$_COOKIE["uID"]." AND u.userPostcode = g.PostcodeId";
        if($stmt = $mysqli->prepare($sql)){
            if(!$stmt->execute()){
                echo"Het uitvoeren van de qry is mislukt: ".$stmt->error."in query";
            }
            else{
                $stmt->bind_result($userID, $userNm, $userFoto, $userStraat, $pCode, $gemeente);
                echo"
                <section id=\"portfolio-details\" class=\"portfolio-details\">
                    <div class=\"container\">
                        <div class=\"row\">
                        <div class=\"col-lg-8\">
                ";
                while($stmt->fetch()){
                }
                echo"<a href=\"portfolio-detailsUser.php\">terug</a><br>
                <img src=\"assets/img/uploads/".$userFoto."\" class=\"img-fluid\" alt=\"\"><br>
                     <input type=\"text\" name=\"foto\" id=\"foto\" value=".$userFoto.">
                <div class=\"col-lg-4 portfolio-info\">
                    <h3>Informatie User</h3>
                    <ul>
                        <li><label>Naam: </label><input type=\"text\" id=\"naam\" name=\"naam\" value=\"".$userNm."\"></li>
                        <li><label>Straat + Nr: </label><input type=\"text\" id\"straat\" name=\"straat\" value=\"".$userStraat."\"></li>";
                if($pcErr){echo"<li id=\"error\">De postcode komt niet overeen met de gemeente: Gelieve dit opnieuw in te vullen.</li>";}
                echo"   <li><label>Gemeente: </label><input type=\"text\" id\"gemeente\" name=\"gemeente\" value=\"".$gemeente."\"></li>
                        <li><label>Postcode: </label><input type=\"text\" id\"postcode\" name=\"postcode\" value=\"".$pCode."\"></li>
                        <li><label>Interesses: </label></li>";
                    //------------------------------
                    for($i=0; $i<3; $i++){
                        $mysqli= new MySQLi("localhost","root","","gip");
                        if(mysqli_connect_errno()){
                            trigger_error("Fout bij verbinding: ".$mysqli->error);
                        }
                        else{
                            $sql = "select interesseID, interesseNm from tblInteresse";
                            if($stmt = $mysqli->prepare($sql)){
                                if(!$stmt->execute()){
                                    echo"Het uitvoeren van de qry is mislukt: ".$stmt->error."in query";
                                }
                                else{                          
                                    echo"&nbsp; <li><select name=\"interesse".($i+1)."\" id=\"interesse".($i+1)."\"><option value=\"-\">-</option>";
                                    $stmt->bind_result($ID, $interesse);
                                    while($stmt->fetch()){
                                        echo"<option value=\"$ID\">".$interesse."</option>";
                                    }
                                }
                                echo"</select></li>
                                <br>
                                &nbsp;
                                ";
                                $stmt->close();
                            }
                            else{
                                echo"Er zit een fout in de qry: ".$mysqli->error;
                            }
                        }
                    }
                    //-------------------------------------
                    echo"
                    </ul>
                </div>
                <input type=\"submit\" name =\"verzenden\" value=\"Wijzigen\">
                </div>
                </div>
                </div>
                </section>
                ";
            }
            @$stmt->close();
        }
        else{
            echo"Er zit een fout in de qry: ".$mysqli->error;
        }
    }

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