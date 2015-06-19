<?php
	session_start();
	$_SESSION['IN_START'] = "started";
	if(isset($_ENV['OPENSHIFT_MYSQL_DB_HOST']))
	{
		$mysql = 			mysql_connect($_ENV['OPENSHIFT_MYSQL_DB_HOST'],"adminI5D52Su","yeLsP315lLBv","php");
	mysql_select_db("php");
		if(!$mysql)
		{
			die("failed to reach db please contact Patrick McDermott at 951-675-6109, Thanks.");
		}
		
	}
	if(isset($_GET['tnum']) && is_numeric($_GET['tnum']) && $_GET['tnum'] != "" && isset($mysql) && $mysql)
	{
		$sql = "SELECT `tName`,`tComment`,`tComplete` FROM territories WHERE tNum='".$_GET['tnum']."'";
		$result = mysql_query($sql);
		if(!$result)
		{
			echo($sql);
		}
		$row = mysql_fetch_row($result);
		echo($row[0]."<br/>".$row[1]."<br/>".$row[2]);
		if($row[2] == "1")
		{
			$GLOBALS['completed'] = "completed";
			$GLOBALS['disabled'] = "disabled";
		}
		else
		{
			$GLOBALS['completed'] = "";
			$GLOBALS['disabled'] = "";
		}
	}

require("template.php");

	if(isset($_POST['complete']) && isset($_POST['name']) && isset($_GET['tnum']))
	{
		$sql = "INSERT INTO territories (`tNum`,`tName`,`tComment`,`tComplete`) VALUES('".$_GET['tnum']."','".$_POST['name']."','".$_POST['comments']."','1')";
		//echo(	$_POST['complete']."<br/>".$_POST['name']);
		//echo($sql);
		//echo("<br/>");
		//echo("Campaign begins 6-19-2015!");
		if(isset($mysql) && $mysql)
		{
			$results = mysql_query($sql);
			if($result)
			{
				//echo("Territory marked completed!<br/>");
				header('Location:'.$_SERVER['REQUEST_URI']);
			}
		}
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
