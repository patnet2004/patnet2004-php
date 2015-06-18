<?php
	if(!isset($_SESSION['IN_START']))
	{
		die("");
	}
	else
{
$img = "";
$file = "tPic/".$_GET['tNum'].".jpg";
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
