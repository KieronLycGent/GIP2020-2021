<!DOCTYPE html>
<?php
if(isset($_GET["item"])){
            setcookie("autID",$_GET["item"]);   
            header("location:portfolio-detailsAut.php");
        }    
?>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Auteurs - Workshopp.er</title>
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
      <div class="contact-info mr-auto">
        <i class="icofont-envelope"></i><a href="mailto:contact@example.com">kieron.parmentier@telenet.be</a>
        <i class="icofont-phone"></i> +32 499 75 98 34
      </div>
      <div class="social-links">
        <a href="#" class="twitter"><i class="icofont-twitter"></i></a>
        <a href="#" class="facebook"><i class="icofont-facebook"></i></a>
        <a href="#" class="instagram"><i class="icofont-instagram"></i></a>
        <a href="#" class="skype"><i class="icofont-skype"></i></a>
        <a href="#" class="linkedin"><i class="icofont-linkedin"></i></a>
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
            <li class="active"><a href="portfolio.php">Auteurs</a></li>

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
        </ol>
        <h2>Auteurs</h2>
      </div>
    </section>
    <!-- End Breadcrumbs -->
    
    <!-- ======== Search ======== -->  
    <section id="search" class="search">
        <div class = "container">
            <form method="post" action="<?php $_SERVER['PHP_SELF'] ?>">
                <input type="text" name="search" id="search">
                <button type="submit"><i class="icofont-search"></i></button>
            </form>  
        </div>
    </section>
    <!-- End Search -->

    <!-- ======= Portfolio Section ======= -->
    <section id="portfolio" class="portfolio">
      <div class="container">
          <div class="container">
              <div class="row portfolio-container">
                  <?php
    

    if(!isset($_POST["search"])){
        $mysqli= new MySQLi("localhost","root","","gip");
                  if(mysqli_connect_errno()){
                      trigger_error("Fout bij verbinding: ".$mysqli->error);
                  }
                  else{
                      
                          $sql = "select * from tblAuteur";
                      
                   
                      if($stmt = $mysqli->prepare($sql)){
                          if(!$stmt->execute()){
                              echo"Het uitvoeren van de qry is mislukt: ".$stmt->error."in query";
                          }
                          else{
                              $stmt->bind_result($auteurID, $auteurNm, $auteurBesch, $auteurFoto);
                              while($stmt->fetch()){
                                  //alle foto's moeten een aspect ratio hebben van 8:6 --> zo breekt de opmaak niet.
                                  echo"
                                  <div class=\"col-lg-4 col-md-6 portfolio-item filter-app\">
                                    <div class=\"portfolio-wrap\">
                                      <img src=\"assets/img/uploads/".$auteurFoto."\" width=\"800\" class=\"img-fluid\" alt=\"\">
                                      <div class=\"portfolio-info\">
                                        <h4>".$auteurNm."</h4>
                                        <p>".$auteurBesch."</p>
                                        <div class=\"portfolio-links\">
                                          <a href=\"assets/img/uploads/".$auteurFoto."\" data-gall=\"portfolioGallery\" class=\"venobox\"><i class=\"bx bx-plus\"></i></a>
                                          <a href=\"portfolioAut.php?item=".$auteurID."\" title=\"More Details\"><i class=\"bx bx-link\"></i></a>
                                        </div>
                                      </div>
                                    </div>
                                  </div>";
                              }
                          }
                          $stmt->close();
                      }
                      else{
                          echo"Er zit een fout in de qry: ".$mysqli->error;
                      }
                  }
    }
    else{
                   $term = "%".$_POST["search"]."%";
         $mysqli= new MySQLi("localhost","root","","gip");
                  if(mysqli_connect_errno()){
                      trigger_error("Fout bij verbinding: ".$mysqli->error);
                  }
                  else{
                      
                         $sql = "SELECT * FROM tblAuteur WHERE auteurNm LIKE ? ORDER BY auteurNm";
                      
                   
                      if($stmt = $mysqli->prepare($sql)){
                           $stmt->bind_param("s",$zoek);
                          $zoek = $term;
                          if(!$stmt->execute()){
                              echo"Het uitvoeren van de qry is mislukt: ".$stmt->error."in query";
                          }
                          else{
                              $stmt->bind_result($auteurID, $auteurNm, $auteurBesch, $auteurFoto);
                              while($stmt->fetch()){
                                  //alle foto's moeten een aspect ratio hebben van 8:6 --> zo breekt de opmaak niet.
                                  echo"
                                  <div class=\"col-lg-4 col-md-6 portfolio-item filter-app\">
                                    <div class=\"portfolio-wrap\">
                                      <img src=\"assets/img/".$auteurFoto."\" width=\"800\" class=\"img-fluid\" alt=\"\">
                                      <div class=\"portfolio-info\">
                                        <h4>".$auteurNm."</h4>
                                        <p>".$auteurBesch."</p>
                                        <div class=\"portfolio-links\">
                                          <a href=\"assets/img/".$auteurFoto."\" data-gall=\"portfolioGallery\" class=\"venobox\" title=\"App 1\"><i class=\"bx bx-plus\"></i></a>
                                          <a href=\"portfolio.php?item=".$auteurID."\" title=\"More Details\"><i class=\"bx bx-link\"></i></a>
                                        </div>
                                      </div>
                                    </div>
                                  </div>";
                              }
                          }
                          $stmt->close();
                      }
                      else{
                          echo"Er zit een fout in de qry: ".$mysqli->error;
                      }
                  }
        
    }
                  
                  ?>
                  <br>
              </div>
          </div>
           <div>
               <br>
               <a href="aanmakenAut.php">Wilt u een auteursaccount aanmaken?</a>
               <br>
        </div>
        </div>
      </section><!-- End Portfolio Section -->
<!-- ====== Auteurs ====== -->
      
    <!-- ======= Clients Section ======= -->
    <section id="clients" class="clients">
      
        <div class="section-title">
          <h2>Auteurs</h2>
          <p>Dit is een lijst van al onze auteurs. Hier kunt u naar bepaalde auteurs op naam.</p>
        </div>
        
       
    </section><!-- End Clients Section -->

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