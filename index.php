<?php
	session_start();
	$_SESSION['IN_START'] = "started";
	$mysql = 0;
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

//if(isset($_GET['preview']) && $_GET['preview'] == "patnet2004")
//{

	if(isset($_GET['tnum']) && is_numeric($_GET['tnum']) && $_GET['tnum'] != "" && $_GET['tnum'] > 0 && $_GET['tnum'] <=66  && isset($mysql) && $mysql)
	{
		$sql = "SELECT `tName`,`tComment`,`tComplete`,`tDate` FROM territories WHERE tNum='".$_GET['tnum']."'";
		$result = mysql_query($sql);
		if(!$result)
		{
			echo($sql);
		}
		while($row = mysql_fetch_row($result))
		{
		//echo($row[0]."<br/>".$row[1]."<br/>".$row[2]);

		if(isset($GLOBALS['completeComment']) && $row[2] == "1")
		{
			$GLOBALS['completeComment']=$GLOBALS['completeComment']."\n".$row[3]."\n\n".$row[0].":\n----------\n".$row[1]."\n";
		}
		else if($row[2] == "1")
		{
			$GLOBALS['completeComment'] = $row[3]."\n\n".$row[0].":\n----------\n".$row[1]."\n";
		}
		else
		{
		}

	if(isset($GLOBALS['partialComment']) && $row[2] == "2")
		{
			$GLOBALS['partialComment']=$GLOBALS['partialComment']."\n".$row[3]."\n\n".$row[0].":\n----------\n".$row[1]."\n";
		}
		else if($row[2] == "2")
		{
			$GLOBALS['partialComment'] = $row[3]."\n\n".$row[0].":\n----------\n".$row[1]."\n";
		}
		else
		{
		}


		if($row[2] == "1")
		{
			$GLOBALS['completed'] = "completed";
			//$GLOBALS['disabled'] = "disabled";
		}
		else if($row[2] == "2")
		{
			if(isset($GLOBALS['completed']) && $GLOBALS['completed'] == "completed")
			{
				
			}
			{
				$GLOBALS['completed'] = "partial";
			}
		}
		else
		{
			$GLOBALS['completed'] = "";
			//$GLOBALS['disabled'] = "";
		}
		}
	}

require("template.php");



if(isset($_POST['partiallyComplete']) && isset($_POST['name']) && isset($_GET['tnum']))
		{
if($_POST['name'] != "" && $_GET['tnum'] != "" && $_GET['tnum'] > 0 && $_GET['tnum'] <= 66)
		{
		$sql = "INSERT INTO territories (`tDate`,`tNum`,`tName`,`tComment`,`tComplete`) VALUES('".date("Y-m-d H:i:s")."','".$_GET['tnum']."','".$_POST['name']."','".$_POST['comments']."','2')";
		//echo(	$_POST['complete']."<br/>".$_POST['name']);
		//echo($sql);
		//echo("<br/>");
		//echo("Campaign begins 6-19-2015!");
			if(isset($mysql) && $mysql)
			{
				$results = mysql_query($sql);
				if($results)
				{
				//echo("Territory marked completed!<br/>");
					header('Location:'.$_SERVER['REQUEST_URI']);
				}
			}
			}

		}
		else
		{
			if(isset($_POST['partiallyComplete']) && isset($_POST['name']) && $_POST['name'] == "")			{
			//echo("<h2><p align=\"center\"><font color=\"red\">**please enter a name**</font></p></h2>");
			}
			if(isset($_GET['tnum']) && ($_GET['tnum'] == "" || $_GET['tnum'] <=0 || $_GET['tnum'] > 66))
			{
				//echo("<h2><p align=\"center\"><font color=\"red\">**sorry your territory number is out of range**</font></p></h2>");

			}
		}





	if(isset($_POST['complete']) && isset($_POST['name']) && isset($_GET['tnum']))
	{
		if($_POST['name'] != "" && $_GET['tnum'] != "" && $_GET['tnum'] > 0 && $_GET['tnum'] <= 66)
		{
		$sql = "INSERT INTO territories (`tDate`,`tNum`,`tName`,`tComment`,`tComplete`) VALUES('".date("Y-m-d H:i:s")."','".$_GET['tnum']."','".addslashes($_POST['name'])."','".addslashes($_POST['comments'])."','1')";
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
		
if(isset($GLOBALS['completeComment']))
{
	$GLOBALS['output'] = str_replace("<!--{[complete_comments]}-->",$GLOBALS['completeComment']."<!--{[complete_comments]}-->",$GLOBALS['output']);		
}
$GLOBALS['output'] = str_replace("<!--{[complete_comments]}-->","",$GLOBALS['output']);

if(isset($GLOBALS['partialComment']))
{
	$GLOBALS['output'] = str_replace("<!--{[partial_comments]}-->",$GLOBALS['partialComment']."<!--{[partial_comments]}-->",$GLOBALS['output']);		
}
$GLOBALS['output'] = str_replace("<!--{[partial_comments]}-->","",$GLOBALS['output']);


if(isset($mysql) && $mysql)
{


$sql = "SELECT DISTINCT `tNum` FROM `territories` WHERE `tComplete` ='2'";
	$results = mysql_query($sql);
	if($results)
	{
		while($row = mysql_fetch_row($results))
		{
			$GLOBALS['status'][$row[0]] = "2";
				//echo($row[0]."<br/>");
		}
	}

	$sql = "SELECT DISTINCT `tNum` FROM `territories` WHERE `tComplete` ='1'";
	$results = mysql_query($sql);
	if($results)
	{
		while($row = mysql_fetch_row($results))
		{
			$GLOBALS['status'][$row[0]] = "1";
				//echo($row[0]."<br/>");
		}
	}
	//echo("<br/>");

/*
	foreach($GLOBALS['status'] as $i => $v)
	{	
		echo($v."-".$i."-<br/>");
	}
*/

//echo("???".$GLOBALS['status'][1]."<br/>");

$GLOBALS['output'] = str_replace("<!--{[output]}-->","<br/><table align=\"top\" style=\"width:300px;padding:0;margin:0;border-collapse:collapse\">
<tr >
<td text-align=\"top\"\">\n<table style=\"width:75px;padding:0;margin:0;border-collapse:collapse\">\n<!--{[group1_output]}--></table>\n</td>
<td text-align=\"top\"\">\n<table style=\"width:75px;padding:0;margin:0;border-collapse:collapse\">\n<!--{[group2_output]}--></table>\n</td><td text-align=\"top\"\">\n<table style=\"width:75px;padding:0;margin:0;border-collapse:collapse\">\n<!--{[group3_output]}--></table>\n</td><td text-align=\"top\"\">\n<table style=\"width:75px;padding:0;margin:0;border-collapse:collapse\">\n<!--{[group4_output]}--></table>\n</td>
<td text-align=\"top\"\">\n<table style=\"width:75px;padding:0;margin:0;border-collapse:collapse\">\n<!--{[group5_output]}--></table>\n</td>
<td text-align=\"top\"\">\n<table style=\"width:75px;padding:0;margin:0;border-collapse:collapse\">\n<!--{[group6_output]}--></table>\n</td>
<td text-align=\"top\"\">\n<table style=\"width:75px;padding:0;margin:0;border-collapse:collapse\">\n<!--{[group7_output]}--></table>\n</td>
<td text-align=\"top\"\">\n<table style=\"width:75px;padding:0;margin:0;border-collapse:collapse\">\n<!--{[group8_output]}--></table>\n</td>
<td text-align=\"top\"\">\n<table style=\"width:75px;padding:0;margin:0;border-collapse:collapse\">\n<!--{[group9_output]}--></table>\n</td>
<td valign=\"top\" text-align=\"top\"\">\n<table align=\"top\" style=\"width:75px;padding:0;margin:0;border-collapse:collapse\">\n<!--{[group10_output]}--></table>\n</td>
</tr></table><!--{[output]}-->",$GLOBALS['output']);

for($i = 0; $i < 7; $i++)
{

if(isset($GLOBALS['status'][$i+1]) && $GLOBALS['status'][$i+1] == "1")
{
	$statusColor = "<td style=\"background-color:green;height:50px;width:50px;text-align:center;border:1px solid black\">";
}
else if(isset($GLOBALS['status'][$i+1]) && $GLOBALS['status'][$i+1] == "2")
{
	$statusColor = "<td style=\"background-color:yellow;height:50px;width:50px;text-align:center;border:1px solid black\">";
}
else
{
	$statusColor = "<td style=\"background-color:red;height:50px;width:50px;text-align:center;border:1px solid black\">";

}

	$GLOBALS['output'] = str_replace("<!--{[group1_output]}-->","<tr>".$statusColor."<a href=\"?tnum=".($i + 1)."\">".($i + 1)."</a></td></tr>\n<!--{[group1_output]}-->",$GLOBALS['output']);
}

for($i = 7; $i < 14; $i++)
{
if(isset($GLOBALS['status'][$i+1]) && $GLOBALS['status'][$i+1] == "1")
{
	$statusColor = "<td style=\"background-color:green;height:50px;text-align:center;border:1px solid black\">";
}
else if(isset($GLOBALS['status'][$i+1]) && $GLOBALS['status'][$i+1] == "2")
{
	$statusColor = "<td style=\"background-color:yellow;height:50px;text-align:center;border:1px solid black\">";
}
else
{
	$statusColor = "<td style=\"background-color:red;height:50px;text-align:center;border:1px solid black\">";

}


	$GLOBALS['output'] = str_replace("<!--{[group2_output]}-->","<tr>".$statusColor."<a href=\"?tnum=".($i + 1)."\">".($i + 1)."</a></td></tr>\n<!--{[group2_output]}-->",$GLOBALS['output']);
}

for($i = 14; $i < 21; $i++)
{
if(isset($GLOBALS['status'][$i+1]) && $GLOBALS['status'][$i+1] == "1")
{
	$statusColor = "<td style=\"background-color:green;height:50px;text-align:center;border:1px solid black\">";
}
else if(isset($GLOBALS['status'][$i+1]) && $GLOBALS['status'][$i+1] == "2")
{
	$statusColor = "<td style=\"background-color:yellow;height:50px;text-align:center;border:1px solid black\">";
}
else
{
	$statusColor = "<td style=\"background-color:red;height:50px;text-align:center;border:1px solid black\">";

}

	$GLOBALS['output'] = str_replace("<!--{[group3_output]}-->","<tr>".$statusColor."<a href=\"?tnum=".($i + 1)."\">".($i + 1)."</a></td></tr>\n<!--{[group3_output]}-->",$GLOBALS['output']);
}

for($i = 21; $i < 28; $i++)
{
if(isset($GLOBALS['status'][$i+1]) && $GLOBALS['status'][$i+1] == "1")
{
	$statusColor = "<td style=\"background-color:green;height:50px;text-align:center;border:1px solid black\">";
}
else if(isset($GLOBALS['status'][$i+1]) && $GLOBALS['status'][$i+1] == "2")
{
	$statusColor = "<td style=\"background-color:yellow;height:50px;text-align:center;border:1px solid black\">";
}
else
{
	$statusColor = "<td style=\"background-color:red;height:50px;text-align:center;border:1px solid black\">";

}

	$GLOBALS['output'] = str_replace("<!--{[group4_output]}-->","<tr>".$statusColor."<a href=\"?tnum=".($i + 1)."\">".($i + 1)."</a></td></tr>\n<!--{[group4_output]}-->",$GLOBALS['output']);
}

}

for($i = 28; $i < 35; $i++)
{

if(isset($GLOBALS['status'][$i+1]) && $GLOBALS['status'][$i+1] == "1")
{
	$statusColor = "<td style=\"background-color:green;height:50px;width:50px;text-align:center;border:1px solid black\">";
}
else if(isset($GLOBALS['status'][$i+1]) && $GLOBALS['status'][$i+1] == "2")
{
	$statusColor = "<td style=\"background-color:yellow;height:50px;width:50px;text-align:center;border:1px solid black\">";
}
else
{
	$statusColor = "<td style=\"background-color:red;height:50px;width:50px;text-align:center;border:1px solid black\">";

}

	$GLOBALS['output'] = str_replace("<!--{[group5_output]}-->","<tr>".$statusColor."<a href=\"?tnum=".($i + 1)."\">".($i + 1)."</a></td></tr>\n<!--{[group5_output]}-->",$GLOBALS['output']);
}


for($i = 35; $i < 42; $i++)
{

if(isset($GLOBALS['status'][$i+1]) && $GLOBALS['status'][$i+1] == "1")
{
	$statusColor = "<td style=\"background-color:green;height:50px;width:50px;text-align:center;border:1px solid black\">";
}
else if(isset($GLOBALS['status'][$i+1]) && $GLOBALS['status'][$i+1] == "2")
{
	$statusColor = "<td style=\"background-color:yellow;height:50px;width:50px;text-align:center;border:1px solid black\">";
}
else
{
	$statusColor = "<td style=\"background-color:red;height:50px;width:50px;text-align:center;border:1px solid black\">";

}

	$GLOBALS['output'] = str_replace("<!--{[group6_output]}-->","<tr>".$statusColor."<a href=\"?tnum=".($i + 1)."\">".($i + 1)."</a></td></tr>\n<!--{[group6_output]}-->",$GLOBALS['output']);
}


for($i = 42; $i < 49; $i++)
{

if(isset($GLOBALS['status'][$i+1]) && $GLOBALS['status'][$i+1] == "1")
{
	$statusColor = "<td style=\"background-color:green;height:50px;width:50px;text-align:center;border:1px solid black\">";
}
else if(isset($GLOBALS['status'][$i+1]) && $GLOBALS['status'][$i+1] == "2")
{
	$statusColor = "<td style=\"background-color:yellow;height:50px;width:50px;text-align:center;border:1px solid black\">";
}
else
{
	$statusColor = "<td style=\"background-color:red;height:50px;width:50px;text-align:center;border:1px solid black\">";

}

	$GLOBALS['output'] = str_replace("<!--{[group7_output]}-->","<tr>".$statusColor."<a href=\"?tnum=".($i + 1)."\">".($i + 1)."</a></td></tr>\n<!--{[group7_output]}-->",$GLOBALS['output']);
}


for($i = 49; $i < 56; $i++)
{

if(isset($GLOBALS['status'][$i+1]) && $GLOBALS['status'][$i+1] == "1")
{
	$statusColor = "<td style=\"background-color:green;height:50px;width:50px;text-align:center;border:1px solid black\">";
}
else if(isset($GLOBALS['status'][$i+1]) && $GLOBALS['status'][$i+1] == "2")
{
	$statusColor = "<td style=\"background-color:yellow;height:50px;width:50px;text-align:center;border:1px solid black\">";
}
else
{
	$statusColor = "<td style=\"background-color:red;height:50px;width:50px;text-align:center;border:1px solid black\">";

}

	$GLOBALS['output'] = str_replace("<!--{[group8_output]}-->","<tr>".$statusColor."<a href=\"?tnum=".($i + 1)."\">".($i + 1)."</a></td></tr>\n<!--{[group8_output]}-->",$GLOBALS['output']);
}


for($i = 56; $i < 63; $i++)
{

if(isset($GLOBALS['status'][$i+1]) && $GLOBALS['status'][$i+1] == "1")
{
	$statusColor = "<td style=\"background-color:green;height:50px;width:50px;text-align:center;border:1px solid black\">";
}
else if(isset($GLOBALS['status'][$i+1]) && $GLOBALS['status'][$i+1] == "2")
{
	$statusColor = "<td style=\"background-color:yellow;height:50px;width:50px;text-align:center;border:1px solid black\">";
}
else
{
	$statusColor = "<td style=\"background-color:red;height:50px;width:50px;text-align:center;border:1px solid black\">";

}

	$GLOBALS['output'] = str_replace("<!--{[group9_output]}-->","<tr>".$statusColor."<a href=\"?tnum=".($i + 1)."\">".($i + 1)."</a></td></tr>\n<!--{[group9_output]}-->",$GLOBALS['output']);
}


for($i = 63; $i < 66; $i++)
{

if(isset($GLOBALS['status'][$i+1]) && $GLOBALS['status'][$i+1] == "1")
{
	$statusColor = "<td style=\"background-color:green;height:50px;width:50px;text-align:center;border:1px solid black\">";
}
else if(isset($GLOBALS['status'][$i+1]) && $GLOBALS['status'][$i+1] == "2")
{
	$statusColor = "<td style=\"background-color:yellow;height:50px;width:50px;text-align:center;border:1px solid black\">";
}
else
{
	$statusColor = "<td style=\"background-color:red;height:50px;width:50px;text-align:center;border:1px solid black\">";

}

	$GLOBALS['output'] = str_replace("<!--{[group10_output]}-->","<tr>".$statusColor."<a href=\"?tnum=".($i + 1)."\">".($i + 1)."</a></td></tr>\n<!--{[group10_output]}-->",$GLOBALS['output']);
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
			//$GLOBALS['output'] = str_replace("<!--{[output]}-->","Territoies scanned:".$count,$GLOBALS['output']);

$GLOBALS['output'] = str_replace("<!--{[count_update]}-->","Territoies scanned:".$count."<br/><!--{[count_update]}-->",$GLOBALS['output']);

			$sql = "SELECT COUNT(`tComplete`) FROM `territories` WHERE `tComplete`='1'";
			 $results = mysql_query($sql);
			$row = mysql_fetch_row($results);
			
$GLOBALS['output'] = str_replace("<!--{[count_update]}-->","Fully Completed: ".$row[0]."<br/><!--{[count_update]}-->",$GLOBALS['output']);

$GLOBALS['output'] = str_replace("<!--{[status_report]}-->","Total Percent Complete: ".((float)($row[0]/66) * 100)."&#37;<br/><!--{[status_report]}-->",$GLOBALS['output']);


$GLOBALS['output'] = str_replace("<!--{[status_report_link]}-->","<a href=\"?=status\">Status Report</a>",$GLOBALS['output']);

/*
$sql = "SELECT COUNT(DISTINCT `tNum`) FROM `territories` WHERE `tComplete`<'2'";
		 $results = mysql_query($sql);
		$row = mysql_fetch_row($results);
$GLOBALS['output'] = str_replace("<!--{[count_update]}-->","Partially completed: ".$row[0]."<br/><!--{[count_update]}-->",$GLOBALS['output']);
*/

		}
		echo($GLOBALS['output']);
	}
//}
//else
//{
//	die("Campaign begins 2-27-2016!");
//}
	if(isset($mysql) && $mysql)
	{
		mysql_close($mysql);
	}
?>
