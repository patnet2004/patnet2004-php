<?php
	session_start();
	$_SESSION['IN_START'] = "started";
	require("template.php");
	if(isset($_ENV['OPENSHIFT_MYSQL_DB_HOST']))
	{
		$mysql = 			mysql_connect($_ENV['OPENSHIFT_MYSQL_DB_HOST'],"adminI5D52Su","yeLsP315ILBv","php");
		if(!$mysql)
		{
			die("mysql failed!");
		}
	}
	if(isset($_POST['complete']) && isset($_POST['name']) && isset($_GET['tnum']))
	{
		$sql = "INSERT INTO territories ``,``,``,``,``,``,``";
		//echo(	$_POST['complete']."<br/>".$_POST['name']);
		echo("Campaign begins 6-19-2015!");
	}
	if(isset($GLOBALS['output']))
	{
		echo($GLOBALS['output']);
	}
	if(isset($mysql) && $mysql)
	{
		mysql_close($mysql);
	}
?>
