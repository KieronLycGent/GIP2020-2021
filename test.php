<form action="#upload" method='post' enctype="multipart/form-data">
<input type="file" name="file"/><br><br>
	<input type="submit" value="Upload"/>
</form>
<?php 

$name= $_FILES['file']['name'];

$tmp_name= $_FILES['file']['tmp_name'];

$position= strpos($name, ".");

$fileextension= substr($name, $position + 1);

$fileextension= strtolower($fileextension);


if (isset($name)) {

$path= 'assets/img/auteurs/';
if (empty($name))
{
echo "Please choose a file";
}
else if (!empty($name)){
if (($fileextension !== "jpg") && ($fileextension !== "jpeg") && ($fileextension !== "png") && ($fileextension !== "bmp"))
{
echo "The file extension must be .jpg, .jpeg, .png, or .bmp in order to be uploaded";
}


else if (($fileextension == "jpg") || ($fileextension == "jpeg") || ($fileextension == "png") || ($fileextension == "bmp"))
{
if (move_uploaded_file($tmp_name, $path.$name)) {
echo 'Uploaded!';
}
}
}
}
?>
