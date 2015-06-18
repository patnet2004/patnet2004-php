<?php
	if(!isset($_SESSION['IN_START']))
	{
		die("");
	}
	else
{
$img = "";
$file = "";
if(isset($_GET['tnum']))
{
	$file = "tPic/".$_GET['tnum'].".jpg";
}
if(isset($_ENV['OPENSHIFT_HOMEDIR']))
{
	$file = $_ENV['OPENSHIFT_HOMEDIR'].$file;
}

echo($file);
if(isset($_GET['tnum']) && is_file($file))
{
	$img = "<img src=\"tPic/".$_GET['tnum'].".jpg\" />"; 
}
$GLOBALS['output'] = "

<!DOCTYPE html>
<html>
<body>
<p>
Territory &#35;:{[tnum]}
</p>
".$img."
<p>
</p>
</body>
</html>

";

}
