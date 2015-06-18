<?php
	if(!isset($_SESSION['IN_START']))
	{
		die("");
	}
	else
{
$img = "";
$file = "tPic/".$_GET['tNum'].".jpg";
if(isset($_ENV['OPENSHIFT_DATA_DIR']))
{
	$file = $_ENV['OPENSHIFT_DATA_DIR'].$file;
}

echo($file);
if(isset($_GET['tNum']) && is_file($file))
{
	$img = "<img src=\"tPic/".$_GET['tNum'].".jpg\" />"; 
}
$GLOBALS['output'] = "

<!DOCTYPE html>
<html>
<body>
<p>
Territory &#35;:{[tNum]}
</p>
".$img."
<p>
</p>
</body>
</html>

";

}
