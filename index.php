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
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Home - Workshopp.er</title>
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
          <li class="active"><a href="index.php">Home</a></li>
            <li><a href="about.php">Over</a></li>
          <li><a href="contact.php">Contact</a></li>
          <?php
            if(isset($_SESSION["admin"])){
              if($_SESSION["admin"]){
                echo"
                <li><a href=\"portfolioAut.php\">Auteurs</a></li>
                <li><a href=\"portfolioUser.php\">Gebruikers</a></li>
                ";
              }
            }
            if(isset($_SESSION["login"])){
              if(($_SESSION["login"])){
                if($_SESSION["loginType"] == "aut"){
                    echo"<li><a href=\"aanmakenWS.php\">Workshop aanmaken</a></li>
                         <li><a href=\"portfolioWSEigen.php\">Mijn workshops</a></li>";
                }
                else if($_SESSION["loginType"] == "user"){
                    echo"<li><a href=\"portfolioWS.php\">Workshops</a></li>";
                }
              }
            }
            else{
              echo"<li><a href=\"portfolioWS.php\">Workshops</a></li>";
            }                
            ?>

        </ul>
      </nav><!-- .nav-menu -->

    </div>
  </header><!-- End Header -->

  <!-- ======= Hero Section ======= -->
  <section id="hero">
    <div class="hero-container">
      <div id="heroCarousel" class="carousel slide carousel-fade" data-ride="carousel">

        <ol class="carousel-indicators" id="hero-carousel-indicators"></ol>

        <div class="carousel-inner" role="listbox">

          <!-- Slide 1 -->
          <div class="carousel-item active" style="background: url(assets/img/slide/slide-1.jpg)">
            <div class="carousel-container">
              <div class="carousel-content">
                <h2 class="animate__animated animate__fadeInDown">Welkom bij <span>Workshopp.er</span></h2>
                <p class="animate__animated animate__fadeInUp">Dé site voor workshops.</p>
              </div>
            </div>
          </div>

          <!-- Slide 2 -->
          <div class="carousel-item" style="background: url(assets/img/slide/slide-2.jpg)">
            <div class="carousel-container">
              <div class="carousel-content">
                <h2 class="animate__animated fanimate__adeInDown">Beleef <span> de beste activiteiten</span></h2>
                <p class="animate__animated animate__fadeInUp">Onze community zorgt ervoor dat je vanalles kunt beleven met een glimlach.</p>
              </div>
            </div>
          </div>

          <!-- Slide 3 -->
          <div class="carousel-item" style="background: url(assets/img/slide/slide-3.jpg)">
            <div class="carousel-container">
              <div class="carousel-content">
                <h2 class="animate__animated animate__fadeInDown">Wordt deel van <span>onze community&#33;</span></h2>
                <p class="animate__animated animate__fadeInUp">Wordt deel van de community en beleef alle activiteiten die ooit zou willen.</p>
                <a href="" class="btn-get-started animate__animated animate__fadeInUp">Lees meer</a>
              </div>
            </div>
          </div>

        </div>

        <a class="carousel-control-prev" href="#heroCarousel" role="button" data-slide="prev">
          <span class="carousel-control-prev-icon icofont-rounded-left" aria-hidden="true"></span>
          <span class="sr-only">Previous</span>
        </a>

        <a class="carousel-control-next" href="#heroCarousel" role="button" data-slide="next">
          <span class="carousel-control-next-icon icofont-rounded-right" aria-hidden="true"></span>
          <span class="sr-only">Next</span>
        </a>

      </div>
    </div>
  </section><!-- End Hero -->

  <main id="main">

    <!-- ======= Featured Section ======= -->
    <section id="featured" class="featured">
      <div class="container">

        <div class="row">
          <div class="col-lg-4">
            <div class="icon-box">
              <i class="icofont-computer"></i>
              <h3><a href="">Online</a></h3>
              <p>We zorgen voor de beste experiences online.</p>
            </div>
          </div>
          <div class="col-lg-4 mt-4 mt-lg-0">
            <div class="icon-box">
              <i class="icofont-image"></i>
              <h3><a href="">Foto's en video's</a></h3>
              <p>We zorgen voor genoeg mogelijkheden.</p>
            </div>
          </div>
          <div class="col-lg-4 mt-4 mt-lg-0">
            <div class="icon-box">
              <i class="icofont-tasks-alt"></i>
              <h3><a href="">Overzicht</a></h3>
              <p>We zorgen voor overzicht via de agenda-functie.</p>
            </div>
          </div>
        </div>

      </div>
    </section><!-- End Featured Section -->

    <!-- ======= About Section ======= -->
    <section id="about" class="about">
      <div class="container">

        <div class="row">
          <div class="col-lg-6">
            <img src="assets/img/about.jpg" class="img-fluid" alt="">
          </div>
          <div class="col-lg-6 pt-4 pt-lg-0 content">
            <h3>Dag &amp; nacht</h3>
            <p class="font-italic">
              We staan dag en nacht klaar om ervoor te zorgen dat we de beste ervaring op de website kunnen geven.
            </p>
            <ul>
              <li><i class="icofont-check-circled"></i> Administrators die ervoor zorgen dat incorrecte dingen worden afgestraft.</li>
              <li><i class="icofont-check-circled"></i> De website wordt om de zoveel tijd nagekeken om te zien of alles nog werkt.</li>
            </ul>
            <p>
              Als er iets niet werkt of als je u nog vragen hebt, dan kunt u ons altijd contacteren via email.
            </p>
          </div>
        </div>

      </div>
    </section><!-- End About Section -->
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