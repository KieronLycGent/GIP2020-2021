<?php
/*echo"<form action=\"#test\" id=\"test\" name=\"test\" method=\"post\">
Interesses:<br>";
$i = 0;
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
                echo"<select name=\"interesse".($i+1)."\" id=\"interesse".($i+1)."\"><option value=\"-\">-</option>";
                $stmt->bind_result($ID, $interesse);
                while($stmt->fetch()){
                    echo"<option value=\"$ID\">".$interesse."</option>";
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
echo"
<input type=\"submit\" value=\"verzenden\" name=\"send\" id=\"send\">
</select>
</form>";
*/
?>
<?php
//Moeten hier de shit in te database steken EN error messages erbij steken. Als dit werkt dan kunnen we dit dan in aanmakenUser.php steken.

//Als er variabelen gelijk zijn die niet "-" zijn
if((isset($_POST["interesse1"]))&&(isset($_POST["interesse2"]))&&(isset($_POST["interesse3"]))){
    if((($_POST["interesse1"]==$_POST["interesse2"])&&($_POST["interesse1"]!="-")) || (($_POST["interesse1"] == $_POST["interesse3"])&&($_POST["interesse3"]!="-")) || (($_POST["interesse2"] == $_POST["interesse3"])&&($_POST["interesse2"]!="-"))){
    echo"U hebt een interesse 2 of meerdere keren aangeduid, u kunt een keuze ook openlaten";
    }
    else{   
        $mysqli= new MySQLi("localhost","root","","gip");
        if(mysqli_connect_errno()){
            trigger_error("Fout bij verbinding: ".$mysqli->error);
        }
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
            $sql = "INSERT INTO `tblinteressesuser` (`interessesID`, `interesseID1`, `interesseID2`, `interesseID3`) VALUES (NULL,?,?,?) ";
            if($stmt = $mysqli->prepare($sql)) {     
                $stmt->bind_param('sss',$i1,$i2,$i3);
                if(!$stmt->execute()){
                    echo 'het uitvoeren van de query is mislukt:';
                }
                else{  
                    echo 'interesses geüpload';
                }
                $stmt->close();
            }
            else{
                echo"Er zit een fout in de qry: ".$mysqli->error;
            }
        }
    }
}


?>