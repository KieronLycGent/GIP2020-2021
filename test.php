<?php
if(isset($_POST["verzenden"])){
    print_r($_POST["date"]);
    print_r($_POST["time"]);
}
?>
<form id="form1" name="form1" method="post" action="test.php">
    <input type="date" name="date" id="date" required>
    <input type="time" name="time" id="time" required>
    <input type="submit" name="verzenden" id="verzenden">
</form>

<form id="login" name="login" method="post" action="test.php">
    <input type="text" name="email" id="email" required>
    <input type="password" name="pw" id="pw" required>
    <input type="submit" name="login" id="login">
</form>
<?php
if(isset($_POST["login"])&&isset($_POST["email"])&&$_POST["email"]!=""&&isset($_POST["pw"])&&$_POST["pw"]!=""){
    $mysqli= new MySQLi("localhost","root","","gip");
        if(mysqli_connect_errno()){
            trigger_error('Fout bij verbinding: '.$mysqli->error); 
        }
        else{
            $sql = "SELECT userEmail, userPasw, userID FROM tblUser WHERE userEmail = '".$_POST["email"]."'"; 
            //De reden voor de "'" is omdat SQL de @ anders niet ziet als deel van een string.
            if($stmt = $mysqli->prepare($sql)) {     
                if(!$stmt->execute()){
                    echo 'het uitvoeren van de query is mislukt:'.$stmt->error."in qry";
                }
                else{  
                    $stmt->bind_result($emailCheck,$pwCheck,$ID);
                    while($stmt->fetch()){
                        if(password_verify($_POST["pw"], $pwCheck)){
                            echo "yeah";
                        }
                    }
                }
                $stmt->close();
            }
        }
    }
?>
<?php
for($i=0;$i<3;$i++){
    echo"&nbsp; <li><select name=\"interesse".($i+1)."\" id=\"interesse".($i+1)."\"><option value=\"-\">-</option>";
        $mysqli = new mysqli("localhost","root","","gip");
        if(mysqli_connect_errno()){
            trigger_error("Fout bij verbinding: ".$mysqli->error);
        }
        else{
            $sql = "SELECT interesseID, interesseNm FROM tblInteresse";
            if($stmt->prepare($sql)){
                if(!$stmt->execute()){
                    echo"Het uitvoeren van qry getAllInts is mislukt: ".$stmt->error."<br>";       
                }
                else{
                    $stmt->bind_result($ID, $interesse);
                    while($stmt->fetch()){
                        /*$j++;
                        echo"<option value=\"$ID\"";
                        switch($j){
                            case 1:
                                if($ID == $selInt1){
                                    echo"selected";
                                }
                                break;
                            case 2:
                                if($ID == $selInt2){
                                    echo"selected";
                                }
                                break;
                            case 3:
                                if($ID == $selInt3){
                                    echo"selected";
                                }
                                break;
                        }
                        echo"> ".$interesse."</option>";*/
                        echo"<option value=\"".$ID."\">".$interesse."</option>";
                    }
                    $stmt->close();
                }
            }
            else{
                echo"Er zit een fout in qry getAllInts: ".$mysqli->error."<br>";
            }
        }
    }
?>


