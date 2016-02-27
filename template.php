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
	$style = "style=\"background-color:green; padding:15px 15px;\"";
	$status = "Complete";
	$disabled = "disabled=\"disabled\"";
}
else if(isset($GLOBALS['completed']) && $GLOBALS['completed'] == "partial")
{
	$style = "style=\"background-color:#ffd633; padding:15px 15px;\"";
	$status = "Partially Complete";
	$disabled = "";
}
else
{
	$style = "style=\"background-color:red; padding:15px 15px;\"";
	$status = "Not Complete";
	$disabled = "";
}
/*
if(isset($GLOBALS['disabled']) && $GLOBALS['disabled'] != "")
{
	
}
else
{
	$disabled = "";
} 
*/
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
<h3>Check in Territory:</h3>
<table>
<tr>
<td valign=\"top\">
<input type=\"submit\" style=\"
background:#fff0b3;
	background:-moz-linear-gradient(top,#fff0b3 0%,#e6b800 100%);
	background:-webkit-gradient(linear,left top,left bottom,color-stop(0%,#fff0b3),color-stop(100%,#e6b800));
	background:-webkit-linear-gradient(top,#fff0b3 0%,#e6b800 100%);
	background:-o-linear-gradient(top,#fff0b3 0%,#e6b800 100%);
	background:-ms-linear-gradient(top,#fff0b3 0%,#e6b800 100%);
	background:linear-gradient(top,#fff0b3 0%,#e6b800 100%);
	filter: progid: DXImageTransform.Microsoft.gradient( startColorstr='#fff0b3', endColorstr='#e6b800',GradientType=0);
	padding:10px 15px;
	color:#ffffff;
	text-shadow: -1px -1px #aaaaaa;
	font-family:'Helvetica Neue',sans-serif;
	font-size:16px;
	border-radius:5px;
	-moz-border-radius:5px;
	-webkit-border-radius:5px;
	border:1px solid #e6b800\"
name=\"partiallyComplete\" value=\"Mark Partially Complete\" ".$disabled."/>
</td>
<td>
<br/>
<br/>
Name:<br/>
<input type=\"text\" name=\"name\" style=\"width:500px\"/>
<br/>
Comments:<br/>
<textarea name=\"comments\" cols=\"50\" rows=\"10\" style=\"width:500px\" />
</textarea>
</td>
<td valign=\"top\">
<input type=\"submit\" style=\"
background:#5CCD00;
	background:-moz-linear-gradient(top,#5CCD00 0%,#4AA400 100%);
	background:-webkit-gradient(linear,left top,left bottom,color-stop(0%,#5CCD00),color-stop(100%,#4AA400));
	background:-webkit-linear-gradient(top,#5CCD00 0%,#4AA400 100%);
	background:-o-linear-gradient(top,#5CCD00 0%,#4AA400 100%);
	background:-ms-linear-gradient(top,#5CCD00 0%,#4AA400 100%);
	background:linear-gradient(top,#5CCD00 0%,#4AA400 100%);
	filter: progid: DXImageTransform.Microsoft.gradient( startColorstr='#5CCD00', endColorstr='#4AA400',GradientType=0);
	padding:10px 15px;
	color:#fff;
	text-shadow: -1px -1px #aaaaaa;
	font-family:'Helvetica Neue',sans-serif;
	font-size:16px;
	border-radius:5px;
	-moz-border-radius:5px;
	-webkit-border-radius:5px;
	border:1px solid #459A00\"
name=\"complete\" value=\"Mark Fully Complete\" ".$disabled."/>
</td>
</tr>
</table>
<br/>
<br/>
</td>
</tr>
<tr>
<td align=\"center\">
<!--{[count_update]}-->
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
