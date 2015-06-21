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
	if(isset($_GET['tnum']) && is_numeric($_GET['tnum']) && $_GET['tnum'] != "" && $_GET['tnum'] > 0 && $_GET['tnum'] <=66  && isset($mysql) && $mysql)
	{
		$sql = "SELECT `tName`,`tComment`,`tComplete` FROM territories WHERE tNum='".$_GET['tnum']."'";
		$result = mysql_query($sql);
		if(!$result)
		{
			echo($sql);
		}
		$row = mysql_fetch_row($result);
		//echo($row[0]."<br/>".$row[1]."<br/>".$row[2]);
		if($row[2] == "1")
		{
			$GLOBALS['completed'] = "completed";
			//$GLOBALS['disabled'] = "disabled";
		}
		else
		{
			$GLOBALS['completed'] = "";
			//$GLOBALS['disabled'] = "";
		}
	}

require("template.php");

	if(isset($_POST['complete']) && isset($_POST['name']) && isset($_GET['tnum']))
	{
		if($_POST['name'] != "" && $_GET['tnum'] != "" && $_GET['tnum'] > 0 && $_GET['tnum'] <= 66)
		{
		$sql = "INSERT INTO territories (`tDate`,`tNum`,`tName`,`tComment`,`tComplete`) VALUES('".date("Y-m-d H:i:s")."','".$_GET['tnum']."','".$_POST['name']."','".$_POST['comments']."','1')";
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
		else
		{
			if($_POST['name'] == "")
			{
			echo("<h2><p align=\"center\"><font color=\"red\">**please enter a name**</font></p></h2>");
			}
			if($_GET['tnum'] == "" || $_GET['tnum'] <=0 || $_GET['tnum'] > 66)
			{
				echo("<h2><p align=\"center\"><font color=\"red\">**sorry your territory number is out of range**</font></p></h2>");

			}
		}
	}
	if(isset($GLOBALS['output']))
	{
		if(isset($mysql) && $mysql)
		{
/*
			$sql = "SELECT DISTINCT tNum FROM territories";
			$results = mysql_query($mysql);
			$output = array();
			$count = 0;
			for($i = 0; $row = mysql_fetch_row($results); $i = $i+1)
			{
				$output[$i] = $row[0];
				$count = $i + 1;
			}
			
			$GLOBALS['output'] = str_replace("<!--{[output]}-->","So far ".$count. "Territories have been scanned in!",$GLOBALS['output']);
*/
		}
		echo($GLOBALS['output']);
	}
	if(isset($mysql) && $mysql)
	{
		mysql_close($mysql);
	}
?>
