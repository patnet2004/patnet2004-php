<?php
	session_start();
	$_SESSION['IN_START'] = "started";
	require("template.php");
	if(isset($GLOBALS['output']))
	{
		echo($GLOBALS['output']);
	}
?>
