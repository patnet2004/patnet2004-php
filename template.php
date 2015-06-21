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
if(isset($GLOBALS['completed']) && $GLOBALS['completed'] == "completed")
{
	$style = "style=\"background-color:green\"";
	$status = "complete";
	$disabled = "disabled=\"disabled\"";
}
else
{
	$style = "style=\"background-color:red\"";
	$status = "not complete";
	$disabled = "";
}
if(isset($GLOBALS['diabled']) && $GLOBALS['disabled'] != "")
{
	
}
else
{
	$disabled = "";
} 
if(isset($_GET['tnum']) && is_numeric($_GET['tnum']) && $_GET['tnum'] != "" && $_GET['tnum'] > 0 && $_GET['tnum'] <= 66)
{

$GLOBALS['output'] = "

<!DOCTYPE html>
<html>
<body>
<p align=\"center\" style=\"font-size:150%\">
Territory &#35;: ".$_GET['tnum']."</br>
Status: ".$status."
</p>
<p>
<form action=\"\" method=\"post\">
<table align=\"center\">
<tr>
<br/>
<td ".$style." align=\"center\">
".$img."
<br/>
</td>
</tr>
<tr>
<td style=\"background-color:lightgrey\" align=\"center\" valign=\"middle\">
Check in Territory:<br/>
<br/>
<table>
<tr>
<td valign=\"top\">
Name:<br/>
<input type=\"text\" name=\"name\" />
</td>
<td>
Comments:<br/>
<textarea name=\"comments\" cols=\"50\" rows=\"10\"/>
</textarea>
</td>
<td valign=\"top\">
<br/>
<input type=\"submit\" name=\"complete\" value=\"Mark Complete\" ".$disabled."/>

</td>
</tr>
</table>
<br/>
<br/>
</td>
</tr>
</table>
</form>
</p>
</body>
</html>

";
}
else
{
	$GLOBALS['output'] = "
		<!DOCTYPE html>
		<html>
		<body>
		<!--{[output]}-->
		</body>
		</html>
	";
}

}
