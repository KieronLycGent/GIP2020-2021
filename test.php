<?php
    //steekt al iets in de variabelen als placeholder om te het te kunnen te zien printen, zonder geeft hij gwn blancos
//$iid = 1;
//$int1 = "a";
    //start sql statement
    $mysqli= new MySQLi("localhost","root","","gip");
    if (mysqli_connect_errno())
    {
        trigger_error('Fout bij verbinding: '.$mysqli->error);
    }
    else{
        $sql = "SELECT i.interesseID, i.interesseNm FROM tblinteresse i WHERE i.interesseID = 2";
        if($stmt = $mysqli->prepare($sql)){
            if(!$stmt->execute()){
                echo"Het uitvoeren van de qry is mislukt: ".$stmt->error."in qry";
            }  
            else{

                    echo"ok1<br>";//Werkt ok
                    $stmt->bind_result($iid, $int1);//werkt niet, geen errors gegeven<----------------------------------<------------------------------------------<-----------------------------------
                    echo"ok2<br>";//Werkt ok
                    echo($iid."<br>");//Werkt ok
                    echo($int1."<br>ok3");//Werkt ok

            }
            $stmt->close();
        }
        else{
            echo"Er zit een fout in de qry: ".$mysqli->error;//Wordt niet gerunt
        }
    }
?>