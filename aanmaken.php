<!DOCTYPE html>
<html>
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
        <section>
            <div>
                    <?php 
                        if((isset($_POST["verzenden"]))&&(isset($_POST["naam"]))&&($_POST["naam"]!="")&&isset($_POST["besch"])&&$_POST["besch"]!=""&&(isset($_POST["foto"]))&&$_POST["foto"]!=""){
                            $mysqli= new MySQLi("localhost","root","","gip");
                            if(mysqli_connect_errno()){
                                trigger_error('Fout bij verbinding: '.$mysqli->error); 
                            }
                            else{
                                $sql = "INSERT INTO tblAuteur (auteurNm,auteurBesch,auteurFoto) VALUES (?,?,?)"; 
                                if($stmt = $mysqli->prepare($sql)) {     
                                    $stmt->bind_param('sss',$naam,$besch,$foto);
                                    $naam = $mysqli->real_escape_string($_POST["naam"]) ;
                                    $besch = $mysqli->real_escape_string($_POST["besch"]);
                                    $foto = $mysqli->real_escape_string($_POST["foto"]);
                                    if(!$stmt->execute()){
                                        echo 'het uitvoeren van de query is mislukt:';
                                    }
                                    else{  
                                        echo 'Account aangemaakt'; 
                                    }
                                    $stmt->close();
                                }
                                else{
                                    echo 'Er zit een fout in de query'; 
                                }
                            }
                        }
                ?> 
                <form id="form1" name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
                    <p>Auteur aanmaken</p>
                    <p>naam:  &nbsp;
                        <input type="text" name="naam" id="naam" placeholder="naam" value="<?php
                                                                                           if(isset($_POST["naam"])){
                                                                                               echo $naam;
                                                                                           }?>">
                    </p>
                    <p>Beschrijving: &nbsp;
                        <input type="text" name="besch" id="besch" placeholder="besch" value="<?php
                                                                                                       if(isset($_POST["besch"])){
                                                                                                           echo $besch;
                                                                                                       }?>">
                    </p>
                    <p>Foto: &nbsp;
                        <input type="text" name="foto" id="foto" placeholder="foto.jpg"  value="<?php 
                                                                                              if(isset($_POST["foto"])){ 
                                                                                                  echo $foto;
                                                                                              }?>">
                    </p>
                    <p>
                        &nbsp;
                    </p>
                    <p>
                        <input type="submit" name="verzenden" id="verzenden" value="Verzenden">
                    </p>
                    <p>
                        &nbsp;
                    </p>
                </form>
            </div>
        </section>
    </body>
</html>