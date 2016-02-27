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
	else
	{
		$mysql = 			mysql_connect("localhost","root","amdturion64","php");
	mysql_select_db("php");
		if(!$mysql)
		{
			die("failed to reach db please contact Patrick McDermott at 951-675-6109, Thanks.");
		}

	}

if(isset($_GET['preview']) && $_GET['preview'] == "patnet2004")
{

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
		else if($row[2] == "2")
		{
			$GLOBALS['completed'] = "partial";
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
		$GLOBALS['output'] = str_replace("<!--{[output]}-->","<br/><table style=\"width:100%\" border=\"1\" ><tr align=\"top\"><td>Group 1<br/>\n<table>\n<!--{[group1_output]}--></table>\n</td><td>Group 2<br/>\n<table>\n<!--{[group2_output]}--></table>\n</td><td>Group 3<br/>\n<table>\n<!--{[group3_output]}--></table>\n</td><td>Group 4<br/>\n<table>\n<!--{[group4_output]}--></table>\n</td></tr></table><!--{[output]}-->",$GLOBALS['output']);

for($i = 0; $i < 17; $i++)
{
	$GLOBALS['output'] = str_replace("<!--{[group1_output]}-->","<tr><td><a href=\"?tnum=".($i + 1)."\">".($i + 1)."</a></td></tr>\n<!--{[group1_output]}-->",$GLOBALS['output']);
}

for($i = 17; $i < 34; $i++)
{
	$GLOBALS['output'] = str_replace("<!--{[group2_output]}-->","<tr><td>".($i + 1)."</td></tr>\n<!--{[group2_output]}-->",$GLOBALS['output']);
}

for($i = 34; $i < 50; $i++)
{
	$GLOBALS['output'] = str_replace("<!--{[group3_output]}-->","<tr><td>".($i + 1)."</td></tr>\n<!--{[group3_output]}-->",$GLOBALS['output']);
}

for($i = 50; $i < 66; $i++)
{
	$GLOBALS['output'] = str_replace("<!--{[group4_output]}-->","<tr><td>".($i + 1)."</td></tr>\n<!--{[group4_output]}-->",$GLOBALS['output']);
}

		if(isset($mysql) && $mysql)
		{

			$sql = "SELECT DISTINCT `tNum` FROM `territories`";
			$results = mysql_query($sql);
			$count = 0;
			while($row = mysql_fetch_row($results))
			{
				$count = $count + 1;
				
			}
			$GLOBALS['output'] = str_replace("<!--{[output]}-->","So far ".$count." territories have been scanned in!",$GLOBALS['output']);

$GLOBALS['output'] = str_replace("<!--{[count_update]}-->","So far ".$count." territories have been scanned in!",$GLOBALS['output']);

			//$sql = "SELECT 

		}
		echo($GLOBALS['output']);
	}
}
else
{
	die("Campaign begins 2-27-2016!");
}
	if(isset($mysql) && $mysql)
	{
		mysql_close($mysql);
	}
?>
