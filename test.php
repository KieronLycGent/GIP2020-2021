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

<?php?>
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


