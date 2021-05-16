<?php
session_start();
if(isset($_GET["end"])){
    if($_GET["end"]){
        session_destroy();
        header("location:".$_SERVER["PHP_SELF"]);
    }
}
if(!isset($_SESSION["actID"])){
    header("location:portfolioWS.php");
}
$mysqli = new mysqli("localhost","root","","gip");
if(mysqli_connect_errno()){
    trigger_error("Fout bij verbinding: ".$mysqli->error);
}
else{
    $sql = "SELECT ac.actNm, au.auteurNm, ac.actPrijs
    FROM tblactiviteit ac, tblauteur au 
    WHERE au.auteurID = ac.actAuteursID AND ac.actID = ".$_SESSION["actID"];
    if($stmt=$mysqli->prepare($sql)){
        if(!$stmt->execute()){
            echo"Het uitvoren van qry getCheckout is mislukt: ".$stmt->error."<br>";
        }
        else{
            $stmt->bind_result($actNm,$autNm,$actPrijs);
            $stmt->fetch();
        }
        $stmt->close();
    }
    else{
        echo"Er zit een fout in qry getCheckout: ".$mysqli->error;
    }
}


//PAYPAL
// Below the key is the product ID and the value is the quantity
$products_in_cart = array(
	1 => 1, // Product with the ID 1 has a quantity of 1
);
// Products should look like the following, you can execute a SQL query to get products from your database
$products = array(
	array(
		'id' => 1, 
		'name' => 'WS'.$actNm,
		'price' => $actPrijs
	)
);
  // For testing purposes set this to true, if set to true it will use paypal sandbox
$testmode = true;
$paypalurl = $testmode ? 'https://www.sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr';
// If the user clicks the PayPal checkout button...
if (isset($_POST['paypal']) && $products_in_cart && !empty($products_in_cart)) {
    // Variables we need to pass to paypal
    // Make sure you have a business account and set the "business" variable to your paypal business account email
    $data = array(
        'cmd'			=> '_cart',
        'upload'        => '1',
        'lc'			=> 'EN',
        'business' 		=> 'payments@yourwebsite.com',
        'cancel_return'	=> 'https://yourwebsite.com/index.php?page=cart',
        'notify_url'	=> 'https://yourwebsite.com/index.php?page=cart&ipn_listener=paypal',
        'currency_code'	=> 'USD',
        'return'        => 'https://yourwebsite.com/index.php?page=placeorder'
    );
    // Add all the products that are in the shopping cart to the data array variable
    for ($i = 0; $i < count($products); $i++) {
        $data['item_number_' . ($i+1)] = $products[$i]['id'];
        $data['item_name_' . ($i+1)] = $products[$i]['name'];
        $data['quantity_' . ($i+1)] = $products_in_cart[$products[$i]['id']];
        $data['amount_' . ($i+1)] = $products[$i]['price'];
    }
    // Send the user to the paypal checkout screen
    header('location:' . $paypalurl . '?' . http_build_query($data));
    // End the script don't need to execute anything else
    exit;
}
// Below is the listener for paypal, make sure to set the IPN URL (e.g. http://example.com/cart.php?ipn_listener=paypal) in your paypal account, this will not work on a local server
    if (isset($_GET['ipn_listener']) && $_GET['ipn_listener'] == 'paypal') {
        // Get all input variables and convert them all to URL string variables
        $raw_post_data = file_get_contents('php://input');
        $raw_post_array = explode('&', $raw_post_data);
        $myPost = array();
        foreach ($raw_post_array as $keyval) {
            $keyval = explode ('=', $keyval);
            if (count($keyval) == 2) $myPost[$keyval[0]] = urldecode($keyval[1]);
        }
        $req = 'cmd=_notify-validate';
        $get_magic_quotes_exists = function_exists('get_magic_quotes_gpc') ? true : false;
        foreach ($myPost as $key => $value) {
            if ($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
                $value = urlencode(stripslashes($value));
            } else {
                $value = urlencode($value);
            }
            $req .= "&$key=$value";
        }
        // Below will verify the transaction, it will make sure the input data is correct
        $ch = curl_init($paypalurl);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
        $res = curl_exec($ch);
        curl_close($ch);
        if (strcmp($res, 'VERIFIED') == 0) {
            // Transaction is verified and successful...
            $item_id = array();
            $item_quantity = array();
            $item_mc_gross = array();
            // Add all the item numbers, quantities and prices to the above array variables
            for ($i = 1; $i < ($_POST['num_cart_items']+1); $i++) {
                array_push($item_id, $_POST['item_number' . $i]);
                array_push($item_quantity, $_POST['quantity' . $i]);
                array_push($item_mc_gross, $_POST['mc_gross_' . $i]);
            }
            // Insert the transaction into our transactions table, as the payment status changes the query will execute again and update it, make sure the "txn_id" column is unique
            $stmt = $pdo->prepare('INSERT INTO transactions VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) ON DUPLICATE KEY UPDATE payment_status = VALUES(payment_status)');
            $stmt->execute([
                NULL,
                $_POST['txn_id'],
                $_POST['mc_gross'],
                $_POST['payment_status'],
                implode(',', $item_id),
                implode(',', $item_quantity),
                implode(',', $item_mc_gross),
                date('Y-m-d H:i:s'),
                $_POST['payer_email'],
                $_POST['first_name'],
                $_POST['last_name'],
                $_POST['address_street'],
                $_POST['address_city'],
                $_POST['address_state'],
                $_POST['address_zip'],
                $_POST['address_country']
            ]);
        }
        exit;
    }
?>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Details auteur - Workshopp.er</title>
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
          <?php
          if(isset($_SESSION["login"])){
              if(!($_SESSION["login"])){
                  echo"<a href=\"inloggen.php\">Inloggen</a>";
                  echo"<a href=\"registreer.php\">Registreren</a>";
              }
              else{
                  echo"<a href=\"".$_SERVER["PHP_SELF"]."?end=true\">Uitloggen</a>";
                  if($_SESSION["loginType"] == "user"){
                      
                      echo"<a href=\"wijzigenUser.php\">Profiel</a>";
                  }
                  else{
                      echo"<a href=\"wijzigenAut.php\">Profiel</a>";
                  }
              }
          }
          else{
              echo"<a href=\"inloggen.php\">Inloggen</a>";
              echo"<a href=\"registreer.php\">Registreren</a>";
          }
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
              <li class="active"><a href="portfolioAut.php">Auteurs</a></li>
            <li><a href="portfolioUser.php">Gebruikers</a></li>

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
          <li>Workshops</li>
          <li>Details</li>
          <li>Betaling</li>
        </ol>
        <h2>Betaling</h2>

      </div>
    </section><!-- End Breadcrumbs -->

    <!-- ======= Portfolio Details Section ======= -->
    <section id="content" class="content">
          <div class="container">
            <div class="row">
                <table id="checkout" class="checkout">
                    <tr>
                        <th>Titel workshop</th>
                        <th>Naam auteur</th>
                        <th>Prijs</th>
                    </tr>
                    <?php
$mysqli = new mysqli("localhost","root","","gip");
if(mysqli_connect_errno()){
    trigger_error("Fout bij verbinding: ".$mysqli->error);
}
else{
    $sql = "SELECT ac.actNm, au.auteurNm, ac.actPrijs
    FROM tblactiviteit ac, tblauteur au 
    WHERE au.auteurID = ac.actAuteursID AND ac.actID = ".$_SESSION["actID"];
    if($stmt=$mysqli->prepare($sql)){
        if(!$stmt->execute()){
            echo"Het uitvoren van qry getCheckout is mislukt: ".$stmt->error."<br>";
        }
        else{
            $stmt->bind_result($actNm,$autNm,$actPrijs);
            while($stmt->fetch()){
                echo"
                <tr>
                    <td>".$actNm."</td>
                    <td>".$autNm."</td>
                    <td>&euro;".$actPrijs."</td>
                </td>";
            }
        }
        $stmt->close();
    }
    else{
        echo"Er zit een fout in qry getCheckout: ".$mysqli->error;
    }
}
                    ?>
                </table>
            </div>
            <div class="row">
                <div class="paypal">
                    <br>
                    <br>
                    <button type="submit" name="paypal"><img src="https://www.paypalobjects.com/webstatic/mktg/Logo/pp-logo-100px.png" alt="PayPal Logo"></button>
                </div>
            </div>
          </div>
    </section>
  </main><!-- End #main -->
  
  <!-- ======= Footer ======= -->
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