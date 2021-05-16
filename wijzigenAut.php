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
?>
<!DOCTYPE html>
<?php
    
if(!isset($_SESSION["ID"])){
    header("location:portfolioAut.php");
}
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
    $mysqli = new MySQLi("localhost","root","","gip");
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

  <title>Details Autuers - Workshopp.er</title>
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
        <h2>Auteursdetails</h2>

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

$mysqli = new mysqli("localhost","root","","gip");
if(mysqli_connect_errno()){
    trigger_error("Fout bij verbinding: ".$mysqli->error);
}
else{
    $sql = "SELECT a.auteurID, a.auteurNm, a.auteurBesch, a.auteurFoto, a.auteurEmail, a.rekNr
    FROM tblauteur a WHERE a.auteurID=".$_SESSION["ID"];
    if($stmt = $mysqli->prepare($sql)){
        if(!$stmt->execute()){
            echo"Het uitvoeren van qry baseInfo is mislukt: ".$stmt->error."<br>";
        }
        else{
            $stmt->bind_result($aID,$aNm,$aBesch,$aFoto,$aEmail,$aRekNr);
            $stmt->fetch();
			$aFoto1 = $aFoto;
			
        }
        $stmt->close();
    }
    else{
        echo"Er zit een fout in qry baseInfo: ".$mysqli->error."<br>";
    }
}
}
else {
	$aNm= $_POST["naam"];
	$aFoto= $_POST["foto"];
  $aEmail=$_POST["email"];
	$aBesch = $_POST["beschrijving"];
  $aRekNr = $_POST["reknr"];
	}
//----------------------------------------------------------------------------------------------------
                    echo"<a href=\"portfolio-detailsUser.php\">Terug</a><br>"
					?>
                        <img src="assets/img/uploads/<?php  echo $aFoto?> " class="img-fluid\" ><br>
                                                                                                    <input type="text" name="foto" hidden value="<?php echo $userFoto  ?>" >
                                                                                                    
<?php   if((isset($_POST["verzenden"]) && $uploadOk == 0)) { echo"  <label id=\"error\">Fout: ". $boodschapfoto . "</label><br>";}?>                                                                                                  
                                                                                                    
              <input type="file" name="fileToUpload" id="fileToUpload">         
                      <?php echo "
                        <div class=\"col-lg-4 portfolio-info\">
                            <h3>Informatie Gebruiker</h3>
                            <ul>
                            <li><label>Naam: </label><br><input type=\"text\" id=\"naam\" name=\"naam\" required value=\"".$aNm."\"></li>
                            <li><label>Email: </label><br><input type=\"email\" id=\"email\" name=\"email\" required value=\"".$aEmail."\"></li>
                            <li><label>Beschrijving: </label><br><input type=\"text\" id\"beschrijving\" name=\"beschrijving\" required value=\"".$aBesch."\"></li>
                            <li><label>Rekeningnummer: </label><br><input type=\"text\" id\"reknr\" name=\"reknr\" required value=\"".$aRekNr."\"></li>";
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