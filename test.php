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
</form>


