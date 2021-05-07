<?php
//----------------------------------------------------------De check ofdat de email al gebruikt wordt voor een UserAcc----------------------------------------//
if(isset($_POST["verzenden"])&&isset($_POST["email"])&&$_POST["email"]!=""){
    $mysqli=new mysqli("localhost","root","","gip");
    if(mysqli_connect_errno()){
        trigger_error("Fout bij verbinding: ".$mysqli->error);
    }
    else{
        $sql ="SELECT COUNT(userEmail) FROM tbluser WHERE userEmail = '".$_POST["email"]."'";
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
//----------------------------------------------------------De check voor postcodes en gemeentes-------------------------------------------------------------//
if ((isset($_POST["verzenden"]))&& (isset($_POST["postcode"])) && ($_POST["postcode"] != "") &&isset($_POST["gemeente"]) && $_POST["gemeente"] !="" ){
    $mysqli=new MySQLI("localhost","root","","gip");
    if (mysqli_connect_errno()){
        trigger_error('Fout bij verbinding: '.$mysqli->error);
    }
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
                                        $postCheck = true;
                                    }
                                    $stmt->close();
                                }
                                else{
                                    echo 'Er zit een fout in de qry: '.$mysqli->error;
                                }
                            }
                        } 
                }
                else
                {
                    $postCheck = false;
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
//================================================================================================================================================================//
if(isset($emailCheck)&&$emailCheck){
    //Kijkt of naam, straat, paswoord (en zijn check) zijn ingevuld
    if((isset($_POST["verzenden"]))&&(isset($_POST["naam"]))&&($_POST["naam"]!="")&&(isset($_POST["straat"]))&&($_POST["straat"]!="")&&isset($_POST["pw"])&&($_POST["pw"]!="")&&isset($_POST["pwCheck"])&&isset($_POST["email"])&&($_POST["email"]!="")){                           
        //Kijkt of paswoordCheck wel correct is
        if($_POST["pw"]== $_POST["pwCheck"]){
            //Kijkt of interesses bestaan. (exceptionhandeling)
            if((isset($_POST["interesse1"]))&&(isset($_POST["interesse2"]))&&(isset($_POST["interesse3"]))){                            
                //Kijkt ofdat er dubbele zijn binnenin de interesses behalve als deze leeggelaten worden.
                if((($_POST["interesse1"]==$_POST["interesse2"])&&($_POST["interesse1"]!="-")) || (($_POST["interesse1"] == $_POST["interesse3"])&&($_POST["interesse3"]!="-")) || (($_POST["interesse2"] == $_POST["interesse3"])&&($_POST["interesse2"]!="-"))){
                    echo"U hebt een interesse 2 of meerdere keren aangeduid, u kunt een keuze ook openlaten";
                }
                $mysqli= new MySQLi("localhost","root","","gip");
                if(mysqli_connect_errno()){
                    trigger_error("Fout bij verbinding: ".$mysqli->error);
                }
                else{
                    $mysqli= new MySQLi("localhost","root","","gip");
                    if(mysqli_connect_errno()){
                        trigger_error('Fout bij verbinding: '.$mysqli->error); 
                    }
                    else{
                        $sql = "INSERT INTO tbluser (userNm, userEmail, userPasw, userFoto, userPostcode, userStraat) VALUES  (?,?,?,?,?,?)"; 
                        if($stmt = $mysqli->prepare($sql)) {     
                        $stmt->bind_param('ssssis',$naam,$email,$hashedPw,$foto,$post,$straat);
                        $email = $mysqli->real_escape_string($_POST["email"]);
                        $hashedPw = $hashedPassword = password_hash($mysqli->real_escape_string($_POST["pw"]), PASSWORD_DEFAULT);
                        $naam = $mysqli->real_escape_string($_POST["naam"]) ;
                        $foto = $mysqli->real_escape_string("ws.png");
                        $post = $PostcodeId1;
                        $straat = $mysqli->real_escape_string($_POST["straat"]);
                            if(!$stmt->execute()){
                                echo 'Het uitvoeren van de qry is mislukt:'.$mysqli->error;
                            }
                            else{  
                                header("location:inloggen.php");
                            }
                        $stmt->close();
                        }
                        else{
                        echo 'Er zit een fout in de query'.$mysqli->error; 
                        } 
                    }
                    //----------Einde van insert 1 (alles behalve interesses)-------------------
                    //----------Stuk code dat de ID van de user returnt-------------------------
                    $mysqli = new MySQLI("localhost","root","","gip");
                    if(mysqli_connect_errno()){
                        trigger_error("Fout bij verbinding: ".$mysqli->error);
                    }
                    else{
                        $sql = "SELECT COUNT(userID) FROM tbluser";
                        if($stmt=$mysqli->prepare($sql)){
                            @$stmt->bind_param('i', $uID);
                            if(!$stmt->execute()){
                                echo 'Het uitvoeren van de query is mislukt: '.$stmt->error.'in query';
                            }
                            else{  
                                $stmt->bind_result($uID);
                                $stmt->fetch();
                            }
                            @$stmt->close();
                        }
                        else{
                            echo 'Er zit een fout in de qry: '.$mysqli->error;
                        }
                    }
                    //----------Einde ID-return-------------------------------------------------
                    //----------Begin van insert 2 (interesses)---------------------------------
                    //code insert int1
                    //Zorgt ervoor dat als er een interesse als "-" wordt geselecteerd dat deze worden weggelaten.
                    if($_POST["interesse1"] != "-"){
                        $mysqli= new MySQLI("localhost","root","","gip");
                        if(mysqli_connect_errno()){
                            trigger_error("Fout bij verbinding 'Int1': ".$mysqli->error);
                        }
                        else{
                            $sql = "INSERT INTO tblinteressesuser (userID, interesseID) VALUES (?,?)";
                            if($stmt = $mysqli->prepare($sql)){
                                $stmt->bind_param('ii', $uID, $_POST["interesse1"]);
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
                    //------------------------------
                    //code insert int2
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
                    //-----------------------------
                    //<code insert int3
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
                    //-------------Einde insert 2(interesses)----------------------------
                }
            }
        }
    }
}
?>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Gebruiker aanmaken - Workshopp.er</title>
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
    <!-- ======= Portfolio Details Section ======= -->
        <section>
            <div class="container">             
                <form id="form1" name="form1" method="post" action="aanmakenUser.php">
                    <h2>User aanmaken</h2>
                    <a href="registreer.php">Toch al een account? Log hier dan in.</a>
                    <p>naam:  &nbsp;
                        <input type="text" name="naam" id="naam" placeholder="naam" required value="<?php
                                                                                           if(isset($_POST["naam"])){
                                                                                               echo($_POST["naam"]);
                                                                                           }?>">
                    </p>
                    <p>
                        E-mail: &nbsp;
                        <input type="email" name="email" id="email" placeholder="e-mail" required value="<?php
                                                                                                         if(isset($_POST["email"])){
                                                                                                             echo($_POST["email"]);
                                                                                                         }
                                                                                                         ?>">
                        <?php
                        if(isset($emailCheck)&&!$emailCheck){
                            echo"<br><a id=\"error\">Deze email is al in gebruik bij een andere gebruiker.</a>";
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
                    <p>
                        Gemeente: &nbsp;
                        <input type="text" name="gemeente" id="gemeente" placeholder="gemeente" required value="<?php
                                                                                                                if(isset($_POST["gemeente"])){
                                                                                                                    echo($_POST["gemeente"]);
                                                                                                                }
                                                                                                                ?>">
                    </p>
                    <p>
                        Postcode: &nbsp;
                        <input type="text" name="postcode" id="postcode" placeholder="postcode" required value="<?php
                                                                                                                if(isset($_POST["postcode"])){
                                                                                                                    echo($_POST["postcode"]);
                                                                                                                }?>">
                        <?php
                            if(isset($postCheck)&&!$postCheck){
                                echo "<br><a id=\"error\">De postcode komt niet overeen met de gemeente.</a>";
                            }
                        ?>
                    </p>
                    <p>
                        Straat + Nr: &nbsp;
                        <input type="text" name="straat" id="straat" placeholder="straat + HuisNr." required value="<?php
                                                                                                                    if(isset($_POST["straat"])){
                                                                                                                        echo($_POST["straat"]);
                                                                                                                    }
                                                                                                                    ?>">
                    </p>
                    <p>
                        Interesses (optioneel)<br>
                    <?php
                    $i = 0;
                    for($i=0; $i<3; $i++){
                        $mysqli= new MySQLi("localhost","root","","gip");
                        if(mysqli_connect_errno()){
                            trigger_error("Fout bij verbinding: ".$mysqli->error);
                        }
                        else{
                            $sql = "select interesseID, interesseNm from tblinteresse";
                            if($stmt = $mysqli->prepare($sql)){
                                if(!$stmt->execute()){
                                    echo"Het uitvoeren van de qry is mislukt: ".$stmt->error."in query";
                                }
                                else{                          
                                    echo"&nbsp; <select name=\"interesse".($i+1)."\" id=\"interesse".($i+1)."\"><option value=\"-\">-</option>";
                                    $stmt->bind_result($ID, $interesse);
                                    while($stmt->fetch()){
                                        if(isset($_POST["verzenden"])&&isset($_POST["interesse1"])&&isset($_POST["interesse2"])&&isset($_POST["interesse3"])){
                                            if($i == 0){
                                                if($_POST["interesse1"]== $ID){
                                                    echo"<option value=\"$ID\" selected>".$interesse."</option>";    
                                                }
                                                else{
                                                    echo"<option value=\"$ID\">".$interesse."</option>";
                                                }
                                            }
                                            elseif($i == 1){
                                                if($_POST["interesse2"]== $ID){
                                                    echo"<option value=\"$ID\" selected>".$interesse."</option>";    
                                                }
                                                else{
                                                    echo"<option value=\"$ID\">".$interesse."</option>";
                                                }
                                            }
                                            elseif($i == 2){
                                                if($_POST["interesse3"]== $ID){
                                                    echo"<option value=\"$ID\" selected>".$interesse."</option>";    
                                                }
                                                else{
                                                    echo"<option value=\"$ID\">".$interesse."</option>";
                                                }
                                            }
                                        }
                                        else{
                                            echo"<option value=\"$ID\">".$interesse."</option>";
                                        }
                                        
                                    }
                                }
                                echo"</select>
                                <br>
                                &nbsp;
                                <br>
                                ";
                                $stmt->close();
                            }
                            else{
                                echo"Er zit een fout in de qry: ".$mysqli->error;
                            }
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