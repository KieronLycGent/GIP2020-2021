<?php
session_start();
//print_r($_POST);
//print_r($_FILES);print_r($_SESSION);

//use function PHPSTORM_META\elementType;
 $intevoegennaamfoto= "";
if(isset($_GET["end"])){
    if($_GET["end"]){
        session_destroy();
        header("location:".$_SERVER["PHP_SELF"]);
    }
}    
if(!isset($_SESSION["ID"])){
    header("location:portfolioUser.php");
}
$pcErr = false;
//Postcode/gemeente check----------------------------------------------------------------------------

if ((isset($_POST["verzenden"]))&& (isset($_POST["postcode"])) && ($_POST["postcode"] != "") &&isset($_POST["gemeente"]) && $_POST["gemeente"] !="" )
{
  $mysqli= new mysqli("localhost","root","","gip");
  //$mysqli= new MySQLi("fdb18.awardspace.net","3833910_gip","Paswoord100","3833910_gip");
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
					 $pcErr = false;
                        if ((isset($_POST["verzenden"]))&& (isset($_POST["postcode"])) && ($_POST["postcode"] != "") &&isset($_POST["gemeente"]) && $_POST["gemeente"] !="" )
                        {
                          $mysqli= new mysqli("localhost","root","","gip");
                          //$mysqli= new MySQLi("fdb18.awardspace.net","3833910_gip","Paswoord100","3833910_gip");

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
$uploadOk = 0;
if ((isset($_POST["verzenden"]))&&(isset($_POST["naam"]))&&($_POST["naam"]!="")&&(isset($_POST["email"]))&&($_POST["email"]!="")&&(isset($_POST["straat"]))&&($_POST["straat"]!="") && $pcErr == false){
	
  if (isset($_POST["verzenden"]) && $_FILES["fileToUpload"]["name"] !=""){
	
    if (!$_FILES["fileToUpload"]["name"] == ""){
      $target_dir = "assets/img/uploads/";
      $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
      $uploadOk = 1;
      $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
      // Check if image file is a actual image or fake image
      if(isset($_POST["verzenden"])) {
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        $foto_ok = "goed";
        if($check !== false) {
//          echo "File is an image - " . $check["mime"] . ".";
          $uploadOk = 1;
        } 
        else{
	        $boodschapfoto= "Dit is geen foto.";
	        $foto_ok = "fout";
          $uploadOk = 0;
        }
      }

      if (file_exists($target_file)) {
       // echo "Sorry, file already exists.";
        $boodschapfoto = "Foto moet hernoemd worden voor deze kan geüpload kan worden.";
        $foto_ok = "fout";
        $uploadOk = 0;
      }

      // Check file size
      if ($_FILES["fileToUpload"]["size"] > 500000) {
        //echo "Sorry, your file is too large.";
        $boodschapfoto= "De foto is te zwaar.";
        $foto_ok = "fout";
        $uploadOk = 0;
      }

      // Allow certain file formats
      if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
      && $imageFileType != "gif" ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $boodschapfoto= "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $foto_ok = "fout";
        $uploadOk = 0;
      }

      // Check if $uploadOk is set to 0 by an error
      if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
      }
      else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
          echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
        }
        else {
          echo "Sorry, there was an error uploading your file.";
        }
        echo "De naam van het bestand, nodig voor up te daten in tabel user is : " . $_FILES["fileToUpload"]["name"];
        $intevoegennaamfoto= $_FILES["fileToUpload"]["name"];
      }
    }
    if ($intevoegennaamfoto== ""){
      $intevoegennaamfoto= $_POST["foto"];	
    }
  }
  else if((isset($_POST["verzenden"]) && $_FILES["fileToUpload"]["name"] =="")){
    $uploadOk = 1;
    $noUpload = true;
  }
    
    if ($uploadOk == 1){    
      //Het deleten van de oude interesses
      $mysqli= new mysqli("localhost","root","","gip");
      //$mysqli= new MySQLi("fdb18.awardspace.net","3833910_gip","Paswoord100","3833910_gip");
      if(mysqli_connect_errno()){
          trigger_error("Fout bij verbinding: ".$mysqli->error);
      }
      else{
          $sql = "DELETE FROM tblinteressesuser WHERE userID = ".$_SESSION["ID"];
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
    //Het inserten van alle interesses
    //int1
    foreach ( $_POST["interesse"] as $interes){
      $mysqli= new mysqli("localhost","root","","gip");
      //$mysqli= new MySQLi("fdb18.awardspace.net","3833910_gip","Paswoord100","3833910_gip");
        if(mysqli_connect_errno()){
            trigger_error("Fout bij verbinding 'Int1': ".$mysqli->error);
        }
        else{
            $sql = "INSERT INTO tblinteressesuser (userID, interesseID) VALUES (?,?)";
            if($stmt = $mysqli->prepare($sql)){
                $stmt->bind_param('ii', $_SESSION["ID"], $interes);
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
    $mysqli= new mysqli("localhost","root","","gip");
    //$mysqli= new MySQLi("fdb18.awardspace.net","3833910_gip","Paswoord100","3833910_gip");
    if(mysqli_connect_errno()){
      trigger_error("fout bij de verbinding: ".$mysqli->error);
    }
    if($noUpload){
      $sql = "UPDATE tbluser SET userNm = '".$_POST["naam"]."', userStraat = '".$_POST["straat"]."',userPostCode = '".$PostcodeId1."', userEmail = '".$_POST["email"]."'WHERE userID = ".$_SESSION["ID"];
    }
    else{
      $sql = "UPDATE tbluser SET userNm = '".$_POST["naam"]."', userStraat = '".$_POST["straat"]."',userPostCode = '".$PostcodeId1."', userFoto = '".$intevoegennaamfoto."', userEmail = '".$_POST["email"]."'WHERE userID = ".$_SESSION["ID"];
    }
    if($stmt = $mysqli->prepare($sql)){
      if(!$stmt->execute()){
        echo"het uitvoeren van de qry is mislukt";
      }
			else{
				header("location:portfolio-detailsUser.php");
				}
            $stmt->close();
      }
    else{
      echo"Er zit een fout in de qry" .$mysqli->error;
    }
  }
}
?>       
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Aanpassen Gebruiker - Workshopp.er</title>
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
          <li>Profiel</li>
          <li>Aanpassen</li>
        </ol>
        <h2>Gebruiker aanpassen</h2>
      </div>
    </section><!-- End Breadcrumbs -->
    <!-- ======= Portfolio Details Section ======= -->
    <form action="<?php $_SERVER["PHP_SELF"]?>" method="post" enctype="multipart/form-data">
        <section id="portfolio-details" class="portfolio-details">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
<?php
//-----------------------------------------------qry baseInfo------------------------------------------
if(!isset($_POST["verzenden"])){
  $mysqli= new mysqli("localhost","root","","gip");
  //$mysqli= new MySQLi("fdb18.awardspace.net","3833910_gip","Paswoord100","3833910_gip");
if(mysqli_connect_errno()){
    trigger_error("Fout bij verbinding: ".$mysqli->error);
}
else{
    $sql = "SELECT u.userID, u.userNm, u.userFoto, u.userStraat, g.PCode, g.Gemeente, u.userEmail
    FROM tbluser u, tblgemeente g WHERE u.userID=".$_SESSION["ID"]." AND u.userPostcode = g.PostcodeId";
    if($stmt = $mysqli->prepare($sql)){
        if(!$stmt->execute()){
            echo"Het uitvoeren van qry baseInfo is mislukt: ".$stmt->error."<br>";
        }
        else{
            $stmt->bind_result($userID,$userNm,$userFoto,$userStraat,$pCode,$gemeente,$email);
            $stmt->fetch();
            $userFoto1 = $userFoto;		
        }
        $stmt->close();
    }
    else{
        echo"Er zit een fout in qry baseInfo: ".$mysqli->error."<br>";
    }
}
}
else {
	$userNm= $_POST["naam"];
	$userFoto= $_POST["foto"];
	$userStraat= $_POST["straat"];
	$pCode= $_POST["postcode"];
	$gemeente= $_POST["gemeente"];
	$email= $_POST["email"];
	}
//----------------------------------------------------------------------------------------------------
                    echo"<a href=\"portfolio-detailsUser.php\">Terug</a><br>"
					?>
                        <img src="assets/img/uploads/<?php  echo $userFoto ?> " class="img-fluid\" ><br>
                                                                                                    <input type="text" name="foto" hidden value="<?php echo $userFoto  ?>" >
                                                                                                    
<?php
if($pcErr ||  (isset($_POST["verzenden"]) && $uploadOk == 0)) { echo"  <label id=\"error\">Fout: ". $boodschapfoto . "</label><br>";}?>                                                                                                  
                                                                                                    
              <input type="file" name="fileToUpload" id="fileToUpload"       >         
                      <?php echo "
                        <div class=\"col-lg-4 portfolio-info\">
                            <h3>Informatie Gebruiker</h3>
                            <ul>
                            <li><label>Naam: </label><br><input type=\"text\" id=\"naam\" name=\"naam\" required value=\"".$userNm."\"></li>
                            <li><label>Email: </label><br><input type=\"email\" id=\"email\" name=\"email\" required value=\"".$email."\"></li>
                            <li><label>Straat + Nr: </label><br><input type=\"text\" id\"straat\" name=\"straat\" required value=\"".$userStraat."\"></li>";
            if($pcErr){echo"<li id=\"error\">De postcode komt niet overeen met de gemeente: Gelieve dit opnieuw in te vullen.</li>";}
                    echo"   <li><label>Postcode: </label><br><input type=\"text\" id\"postcode\" name=\"postcode\" required value=\"".$pCode."\"></li>
					<li><label>Gemeente: </label><br><input type=\"text\" id\"gemeente\" name=\"gemeente\" required value=\"".$gemeente."\"></li>   "            	
							;
            if($pcErr){echo"<li id=\"error\">Gelieve de interesses opnieuw aan te duiden aub.</li>";}        
            echo"  <li><label>Interesses: (Hou Ctrl ingedrukt tijdens het selecteren voor meerdere selecties)</label><br></li>";
           // echo"  <li><label>Interesses: (de gemarkeerde interesses zijn de interesses tot nu toe, iemand kan meerdere interesses hebben) bij verzenden ontstaat er een array interesse met indexen 0 tot een het aantal geselecteerd waarden -1 . Hou daar rekening bij wanneer je de update wil doen Meerdere selecties doe je met de controltoets</label><br></li>";
//-------------------------------------------------------qry getUserInts-----------------------------
$mysqli= new mysqli("localhost","root","","gip");
//$mysqli= new MySQLi("fdb18.awardspace.net","3833910_gip","Paswoord100","3833910_gip");
if(mysqli_connect_errno()){
    trigger_error("Fout bij verbinding: ".$mysqli->error);
}
else{
    $sql = "SELECT interesseID FROM tblinteressesuser WHERE userID=".$_SESSION["ID"];
    if($stmt = $mysqli->prepare($sql)){
        if(!$stmt->execute()){
            echo"Het uitvoeren van qry getUserInts is mislukt: ".$stmt->error."<br>";
        }
        else{
            $stmt->bind_result($selectedInt);
			$aantalinteresses=0;
            while($stmt->fetch()){
                $aantalinteresses++;
				 $selInt[$aantalinteresses] = $selectedInt;
           
            }
            $stmt->close();
        }
    }
    else{
        echo"Er zit een fout in qry getUserInts".$mysqli->error."<br>";
    }
	
	 $sql = "SELECT COUNT(*) FROM tblinteresse";
    if($stmt1 = $mysqli->prepare($sql)){
        if(!$stmt1->execute()){
            echo"Het uitvoeren van qry aantal interesses is mislukt: ".$stmt->error."<br>";
        }
        else{
            $stmt1->bind_result($aantalinteresses1);
			
            $stmt1->fetch();
            $stmt1->close();
        }
    }
    else{
        echo"Er zit een fout in qry aantal interesses".$mysqli->error."<br>";
    }
}
//---------------------------------------------qry getAllInts-------------------------------------
echo"&nbsp; <li><select  multiple size= ". $aantalinteresses1 ." name=\"interesse[] \" id=\"interesse\">";
$mysqli= new mysqli("localhost","root","","gip");
//$mysqli= new MySQLi("fdb18.awardspace.net","3833910_gip","Paswoord100","3833910_gip");
    if(mysqli_connect_errno()){
        trigger_error("Fout bij verbinding: ".$mysqli->error);
    }
    else{
        $sql = "SELECT interesseID, interesseNm FROM tblinteresse";
        if($stmt = $mysqli->prepare($sql)){
            if(!$stmt->execute()){
                echo"Het uitvoeren van qry getAllInts is mislukt: ".$stmt->error."<br>";       
            }
            else{
                $stmt->bind_result($ID, $interesse);
                while($stmt->fetch()){
					?>
                    <option value="<?php echo $ID; ?>" 
					<?php for(  $teller=1; $teller <= $aantalinteresses; $teller++)
					{ 
					
					if($ID ==  $selInt[$teller]){ echo "selected ";}}
					
					?>
					> <?php
                                        echo $interesse
                                        ?></option>
<?php
                }
                $stmt->close();
            }
        }
        else{
            echo"Er zit een fout in qry getAllInts: ".$mysqli->error."<br>";
        }
    }
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