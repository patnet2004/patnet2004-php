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
if(isset($_ENV['OPENSHIFT_REPO_DIR']))
{
	$file = $_ENV['OPENSHIFT_REPO_DIR'].$file;
}
if(isset($_GET['tnum']) && is_file($file))
{
	$img = "<img src=\"tPic/".$_GET['tnum'].".jpg\" />"; 
}
$status = "";
if(isset($completed) && $completed == "completed")
{
	$style = "style=\"background-color:green\"";
	$status = "complete";
}
else
{
	$style = "style=\"background-color:red\"";
	$status = "not complete";
}
if(isset($_GET['tnum']) && is_numeric($_GET['tnum']) && $_GET['tnum'] != "")
{

$GLOBALS['output'] = "

<!DOCTYPE html>
<html>
<body>
<p>
Territory &#35;: ".$_GET['tnum']."</br>
Status: ".$status."
</p>
<p>
<table>
<tr>
<td ".$style.">
".$img."
</td>
</tr>
</table>
</p>
</body>
</html>

";
}
}
