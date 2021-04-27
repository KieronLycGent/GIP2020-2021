<?php

use function PHPSTORM_META\elementType;

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
$pcErr = false;
//Postcode/gemeente check----------------------------------------------------------------------------
if ((isset($_POST["verzenden"]))&& (isset($_POST["postcode"])) && ($_POST["postcode"] != "") &&isset($_POST["gemeente"]) && $_POST["gemeente"] !="" )
{
    $mysqli=new MySQLi("localhost","root","","gip");
    if (mysqli_connect_errno())
    {
        trigger_error('Fout bij verbinding: '.$mysqli->error);
    }
    else
    {
        $sql="select count(PostcodeId) from tblgemeente where PCode=? and Gemeente=?";
        if ($stmt=$mysqli->prepare($sql))
        {
            $stmt->bind_param('ss',$PCode,$gemeente);
            $PCode= $_POST["postcode"];
            $gemeente = $_POST["gemeente"];
           if(!$stmt->execute())
            {
                echo 'Het uitvoeren van de query is mislukt: '.$stmt->error.'in query';
            }
            else
            {  
                $stmt->bind_result($aantalPostcodeId);
                $stmt->fetch();
                $aantalPostcodeId1= $aantalPostcodeId;
                if ($aantalPostcodeId1 >0)
                {
                    ?>
                    <?php
                        if ((isset($_POST["verzenden"]))&& (isset($_POST["postcode"])) && ($_POST["postcode"] != "") &&isset($_POST["gemeente"]) && $_POST["gemeente"] !="" )
                        {
                            $mysqli=new MySQLi("localhost","root","","gip");

                            if (mysqli_connect_errno())
                            {
                                trigger_error('Fout bij verbinding: '.$mysqli->error);
                            }
                            else
                            {
                                $sql="select PostcodeId from tblgemeente where PCode=? and Gemeente=?";
                                if ($stmt=$mysqli->prepare($sql))
                                {
                                    $stmt->bind_param('ss',$PCode,$gemeente);
                                    $PCode= $_POST["postcode"];
                                    $gemeente = $_POST["gemeente"];
                                   if(!$stmt->execute())
                                    {
                                        echo 'Het uitvoeren van de query is mislukt: '.$stmt->error.'in query';
                                    }
                                    else
                                    {  
                                        $stmt->bind_result($PostcodeId);
                                        $stmt->fetch();
                                        $PostcodeId1= $PostcodeId;
                                    }
                                    $stmt->close();
                                }
                                else
                                {
                                    echo 'Er is een fout in de query: '.$mysqli->error;
                                }
                            }
                        }
                }
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
//---------------------------------------------------------------------------------------einde PC en Gemeente check
if ((isset($_POST["verzenden"]))&&isset($_POST["interesse1"])&&($_POST["interesse1"] != "")&&isset($_POST["interesse2"])&&($_POST["interesse2"] != "")&&isset($_POST["interesse3"])&&($_POST["interesse3"] != "")&&(isset($_POST["naam"]))&&($_POST["naam"]!="")&&(isset($_POST["email"]))&&($_POST["email"]!="")&&(isset($_POST["straat"]))&&($_POST["straat"]!="")){
    //Het deleten van de oude interesses
    $mysqli = new mysqli("localhost","root","","gip");
    if(mysqli_connect_errno()){
        trigger_error("Fout bij verbinding: ".$mysqli->error);
    }
    else{
        $sql = "DELETE FROM tblInteressesUser WHERE userID = ".$_SESSION["ID"];
        if($stmt = $mysqli->prepare($sql)){
            if(!$stmt->execute()){
                echo"Het uitvoeren van qry delOldInt is mislukt: ".$stmt->error."<br>";
            }
            else{
                echo"delSuccess";
            }
            $stmt->close();
        }
        else{
            echo"Er zit een fout in qry delOldInt: ".$mysqli->error."<br>";
        }
    }
    //Het inserten van de 3 interesses
    //int1
    if($_POST["interesse1"] != "-"){
        $mysqli= new MySQLI("localhost","root","","gip");
        if(mysqli_connect_errno()){
            trigger_error("Fout bij verbinding 'Int1': ".$mysqli->error);
        }
        else{
            $sql = "INSERT INTO tblinteressesuser (userID, interesseID) VALUES (?,?)";
            if($stmt = $mysqli->prepare($sql)){
                $stmt->bind_param('ii', $_SESSION["ID"], $_POST["interesse1"]);
                if(!$stmt->execute()){
                    echo "Het uitvoeren van qry Int1 is mislukt: ".$mysqli->error;
                }
                $stmt->close();
            }
            else{
                echo"Er zit een fout in qry Int1".$mysqli->error;
            }
        }
    }
    //int2
    if($_POST["interesse2"] != "-"){
        $mysqli= new MySQLI("localhost","root","","gip");
            if(mysqli_connect_errno()){
                trigger_error("Fout bij verbinding 'Int2': ".$mysqli->error);
            }
            else{
                $sql = "INSERT INTO tblinteressesuser (userID, interesseID) VALUES (?,?)";
                if($stmt = $mysqli->prepare($sql)){
                    $stmt->bind_param('ii', $uID, $_POST["interesse2"]);
                    if(!$stmt->execute()){
                        echo "Het uitvoeren van qry Int2 is mislukt: ".$mysqli->error;
                    }
                    $stmt->close();
                }
                else{
                    echo"Er zit een fout in qry Int2".$mysqli->error;
                }
            }                   
        }
        //int3
        if($_POST["interesse3"] != "-"){
            $mysqli= new MySQLI("localhost","root","","gip");
            if(mysqli_connect_errno()){
                trigger_error("Fout bij verbinding 'Int3': ".$mysqli->error);
            }
            else{
                $sql = "INSERT INTO tblinteressesuser (userID, interesseID) VALUES (?,?)";
                if($stmt = $mysqli->prepare($sql)){
                    $stmt->bind_param('ii', $uID, $_POST["interesse3"]);
                    if(!$stmt->execute()){
                        echo "Het uitvoeren van qry Int3 is mislukt: ".$mysqli->error;
                    }
                    $stmt->close();
                }
                else{
                    echo"Er zit een fout in qry Int3".$mysqli->error;
                }
            }
        }
}
if ((isset($_POST["verzenden"]))&&isset($_POST["interesse1"])&&($_POST["interesse1"] != "")&&isset($_POST["interesse2"])&&($_POST["interesse2"] != "")&&isset($_POST["interesse3"])&&($_POST["interesse3"] != "")&&(isset($_POST["naam"]))&&($_POST["naam"]!="")&&(isset($_POST["email"]))&&($_POST["email"]!="")&&(isset($_POST["straat"]))&&($_POST["straat"]!="")){
     
    $mysqli = new MySQLi("localhost","root","","gip");
        if(mysqli_connect_errno()){
            trigger_error("fout bij de verbinding: ".$mysqli->error);
        }
        $sql = "UPDATE tblUser SET userNm = '".$_POST["naam"]."', userStraat = '".$_POST["straat"]."',userPostCode = '".$PostcodeId1."', userFoto = '".$_POST["foto"]."', userEmail = '".$_POST["email"]."'WHERE userID = ".$_SESSION["ID"];
        if($stmt = $mysqli->prepare($sql)){
            if(!$stmt->execute()){
                echo"het uitvoeren van de qry is mislukt";
            }
            $stmt->close();
        }
        else{
            echo"Er zit een fout in de qry" .$mysqli->error;
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
          <li>Gebruikers</li>
          <li>Details</li>
        </ol>
        <h2>Gebruikersdetails</h2>

      </div>
    </section><!-- End Breadcrumbs -->

    <!-- ======= Portfolio Details Section ======= -->
    <form action="<?php $_SERVER["PHP_SELF"]?>" method="post">
        <section id="portfolio-details" class="portfolio-details">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
    <?php
    // Deze hele code moet van scratch herschreven worden: CHAOS!!!
//-----------------------------------------------qry baseInfo------------------------------------------
$mysqli = new mysqli("localhost","root","","gip");
if(mysqli_connect_errno()){
    trigger_error("Fout bij verbinding: ".$mysqli->error);
}
else{
    $sql = "SELECT u.userID, u.userNm, u.userStraat, u.userStraat, g.PCode, g.Gemeente, u.userEmail
    FROM tblUser u, tblGemeente g WHERE u.userID=".$_SESSION["ID"]." AND u.userPostcode = g.PostcodeId";
    if($stmt = $mysqli->prepare($sql)){
        if(!$stmt->execute()){
            echo"Het uitvoeren van qry baseInfo is mislukt: ".$stmt->error."<br>";
        }
        else{
            $stmt->bind_result($userID,$userNm,$userFoto,$userStraat,$pCode,$gemeente,$email);
            $stmt->fetch();
        }
        $stmt->close();
    }
    else{
        echo"Er zit een fout in qry baseInfo: ".$mysqli->error."<br>";
    }
}
//----------------------------------------------------------------------------------------------------
                    echo"<a href=\"portfolio-detailsUser.php\">Terug</a>
                        <img src=\"assets/img/uploads/".$userFoto."\" class=\"img-fluid\" alt=\"\"><br>
                                                                                                    <input type=\"text\" name=\"foto\" value=\"".$userFoto."\">
                        <div class=\"col-lg-4 portfolio-info\">
                            <h3>Informatie Gebruiker</h3>
                            <ul>
                            <li><label>Naam: </label><br><input type=\"text\" id=\"naam\" name=\"naam\" value=\"".$userNm."\"></li>
                            <li><label>Email: </label><br><input type=\"email\" id=\"email\" name=\"email\" value=\"".$email."\"></li>
                            <li><label>Straat + Nr: </label><br><input type=\"text\" id\"straat\" name=\"straat\" value=\"".$userStraat."\"></li>";
            if($pcErr){echo"<li id=\"error\">De postcode komt niet overeen met de gemeente: Gelieve dit opnieuw in te vullen.</li>";}
                    echo"   <li><label>Gemeente: </label><br><input type=\"text\" id\"gemeente\" name=\"gemeente\" value=\"".$gemeente."\"></li>
                            <li><label>Postcode: </label><br><input type=\"text\" id\"postcode\" name=\"postcode\" value=\"".$pCode."\"></li>
                            <li><label>Interesses: </label><br></li>";
//-------------------------------------------------------qry getUserInts-----------------------------
$i = 0;
$mysqli = new mysqli("localhost","root","","gip");
if(mysqli_connect_errno()){
    trigger_error("Fout bij verbinding: ".$mysqli->error);
}
else{
    $sql = "SELECT interesseID FROM tblInteressesuser WHERE userID=".$_SESSION["ID"];
    if($stmt = $mysqli->prepare($sql)){
        if(!$stmt->execute()){
            echo"Het uitvoeren van qry getUserInts is mislukt: ".$stmt->error."<br>";
        }
        else{
            $stmt->bind_result($selectedInt);
            while($stmt->fetch()){
                $i++;
                switch($i){
                    case 1:
                        $selInt1 = $selectedInt;
                        break;
                    case 2:
                        $selInt2 = $selectedInt;
                        break;
                    case 3:
                        $selInt3 = $selectedInt;
                        break;
                }
            }
            $stmt->close();
        }
    }
    else{
        echo"Er zit een fout in qry getUserInts".$mysqli->error."<br>";
    }
}
$i = 0;
$j = 0;
//---------------------------------------------qry getAllInts-------------------------------------
//for($i=0;$i<3;$i++){
echo"&nbsp; <li><select name=\"interesse".($i+1)."\" id=\"interesse".($i+1)."\"><option value=\"-\">-</option>";
    $mysqli = new mysqli("localhost","root","","gip");
    if(mysqli_connect_errno()){
        trigger_error("Fout bij verbinding: ".$mysqli->error);
    }
    else{
        $sql = "SELECT interesseID, interesseNm FROM tblInteresse";
        if($stmt->prepare($sql)){
            if(!$stmt->execute()){
                echo"Het uitvoeren van qry getAllInts is mislukt: ".$stmt->error."<br>";       
            }
            else{
                $stmt->bind_result($ID, $interesse);
                while($stmt->fetch()){
                    /*$j++;
                    echo"<option value=\"$ID\"";
                    switch($j){
                        case 1:
                            if($ID == $selInt1){
                                echo"selected";
                            }
                            break;
                        case 2:
                            if($ID == $selInt2){
                                echo"selected";
                            }
                            break;
                        case 3:
                            if($ID == $selInt3){
                                echo"selected";
                            }
                            break;
                    }
                    echo"> ".$interesse."</option>";*/
                    echo"<option value=\"".$ID."\">".$interesse."</option>";
                }
                $stmt->close();
            }
        }
        else{
            echo"Er zit een fout in qry getAllInts: ".$mysqli->error."<br>";
        }
    }
//}
echo"</select></li>
    <br>&nbsp;
    </ul>
    </div>
    <input type=\"submit\" name =\"verzenden\" value=\"Wijzigen\">
    </div>
    </div>
    </div>
    </section>";
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