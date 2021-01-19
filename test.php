<?php
echo"<form action=\"#test\" id=\"test\" name=\"test\">
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
                echo"<select name=\"interesse".($i+1)."\" id=\"interesse".($i+1)."\"><option value=\"-\">---".($i+1)."---</option>";
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
?>
<?php
//Moeten hier de shit in te database steken EN error messages erbij steken. Als dit werkt dan kunnen we dit dan in aanmakenUser.php steken.
if(isset($_POST["send"])&&$_POST["interesse1"]!="-"&&$_POST["interesse2"]!="-"&&$_POST["interesse3"]!="-"){
    
}

?>