<?php

	session_start();
	$_SESSION['IN_START'] = "started";
	$mysql = 0;


//Get Heroku ClearDB connection information



$cleardb_url      = parse_url(getenv("CLEARDB_DATABASE_URL"));
$cleardb_server   = $cleardb_url["host"];
$cleardb_username = $cleardb_url["user"];
$cleardb_password = $cleardb_url["pass"];
$cleardb_db       = substr($cleardb_url["path"],1);

$active_group = 'default';
$query_builder = TRUE;

$db['default'] = array(
    'dsn'   => '',
    'hostname' => $cleardb_server,
    'username' => $cleardb_username,
    'password' => $cleardb_password,
    'database' => $cleardb_db,
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
);


$dbmysql = new mysqli("us-cdbr-iron-east-05.cleardb.net", "b37499699241ed", "65e8a140", "heroku_b21d4843ebabccb");
if($dbmysql->connect_errno > 0){
    die('Unable to connect to database [' . $dbmysql->connect_error . ']');
}

$results = $dbmysql->query("SELECT `tName`,`tComment`,`tComplete`,`tDate` FROM territories WHERE tNum='1'");
//echo("tables".mysqli_fetch_row($results)[0]);
//mysqli_free_result($results);
/**
	if(isset($_ENV['OPENSHIFT_MYSQL_DB_HOST']))
	{
		$mysql = 			mysql_connect($_ENV['OPENSHIFT_MYSQL_DB_HOST'],"adminI5D52Su","yeLsP315lLBv","php");
	//mysql_select_db("php");
		if(!$mysql)
		{
			die("failed to reach db please contact Patrick McDermott at 951-675-6109, Thanks.");
		}
		
	}
	else
	{
						ini_set('display_errors', 1);
						error_reporting(E_ALL ^ E_NOTICE);
		$mysql = 			mysqli_connect("localhost","root","amdturion64","php");
	//mysql_select_db("php");
		if(!$mysql)
		{
			die("failed to reach db please contact Patrick McDermott at 951-675-6109, Thanks.");
		}

	}
**/


//if(isset($_GET['preview']) && $_GET['preview'] == "patnet2004")
//{

	if(isset($_GET['tnum']) && is_numeric($_GET['tnum']) && $_GET['tnum'] != "" && $_GET['tnum'] > 0 && $_GET['tnum'] <=112  && isset($dbmysql))
	{
		$sql = "SELECT `tName`,`tComment`,`tComplete`,`tDate` FROM territories WHERE tNum='".$_GET['tnum']."'";
		//$result = mysql_query($sql);
		$result = $dbmysql->query($sql);
		while($row = mysqli_fetch_row($result))
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
				else
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
		mysqli_free_result($result);
	}


require("template.php");



if(isset($_POST['partiallyComplete']) && isset($_POST['name']) && isset($_GET['tnum']))
		{
if($_POST['name'] != "" && $_GET['tnum'] != "" && $_GET['tnum'] > 0 && $_GET['tnum'] <= 112)
		{
		$sql = "INSERT INTO territories (`tDate`,`tNum`,`tName`,`tComment`,`tComplete`) VALUES('".date("Y-m-d H:i:s")."','".$_GET['tnum']."','".$_POST['name']."','".$_POST['comments']."','2')";
		//echo(	$_POST['complete']."<br/>".$_POST['name']);
		//echo($sql);
		//echo("<br/>");
		//echo("Campaign begins 6-19-2015!");
			if(isset($dbmysql))
			{
				//$results = mysql_query($sql);
				$results = $dbmysql->query($sql);
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
			if(isset($_GET['tnum']) && ($_GET['tnum'] == "" || $_GET['tnum'] <=0 || $_GET['tnum'] > 112))
			{
				//echo("<h2><p align=\"center\"><font color=\"red\">**sorry your territory number is out of range**</font></p></h2>");

			}
		}





	if(isset($_POST['complete']) && isset($_POST['name']) && isset($_GET['tnum']))
	{
		if($_POST['name'] != "" && $_GET['tnum'] != "" && $_GET['tnum'] > 0 && $_GET['tnum'] <= 112)
		{
		$sql = "INSERT INTO territories (`tDate`,`tNum`,`tName`,`tComment`,`tComplete`) VALUES('".date("Y-m-d H:i:s")."','".$_GET['tnum']."','".addslashes($_POST['name'])."','".addslashes($_POST['comments'])."','1')";
		//echo(	$_POST['complete']."<br/>".$_POST['name']);
		//echo($sql);
		//echo("<br/>");
		//echo("Campaign begins 6-19-2015!");
			if(isset($dbmysql) )
			{
				//$results = mysql_query($sql);
				$result = $dbmysql->query($sql);
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
			if($_GET['tnum'] == "" || $_GET['tnum'] <=0 || $_GET['tnum'] >112)
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


if(isset($dbmysql))
{


$sql = "SELECT DISTINCT `tNum` FROM `territories` WHERE `tComplete` ='2'";
	//$results = mysql_query($sql);
	$results = $dbmysql->query($sql);
	if($results)
	{
		while($row = mysqli_fetch_row($results))
		{
			$GLOBALS['status'][$row[0]] = "2";
				//echo($row[0]."<br/>");
		}
		mysqli_free_result($results);
	}

	$sql = "SELECT DISTINCT `tNum` FROM `territories` WHERE `tComplete` ='1'";
	//$results = mysql_query($sql);
	$results = $dbmysql->query($sql);
	if($results)
	{
		while($row = mysqli_fetch_row($results))
		{
			$GLOBALS['status'][$row[0]] = "1";
				//echo($row[0]."<br/>");
		}
		mysqli_free_result($results);
	}

	$sql = "SELECT DISTINCT `tNum`,`tName` FROM `tcheckout`";
	$results = $dbmysql->query($sql);
	//$results = mysql_query($sql);
	if($results)
	{
		while($row = mysqli_fetch_row($results))
		{
			$GLOBALS['checkout'][$row[0]] = "1";
			$GLOBALS['checkoutname'][$row[0]] = $row[1];
				//echo($row[0]."<br/>");
		}
		mysqli_free_result($results);
	}

	//echo("<br/>");

/*
	foreach($GLOBALS['status'] as $i => $v)
	{	
		echo($v."-".$i."-<br/>");
	}
*/

//echo("???".$GLOBALS['status'][1]."<br/>");

$GLOBALS['output'] = str_replace("<!--{[output]}-->","<br/><table align=\"top\" style=\"width:300px;padding:0;margin:0;border-collapse:separate\">
<tr >
<td text-align=\"top\"\">\n<table style=\"width:75px;padding:0;margin:0;border-collapse:separate\">\n<!--{[group1_output]}--></table>\n</td>
<td text-align=\"top\"\">\n<table style=\"width:75px;padding:0;margin:0;border-collapse:separate\">\n<!--{[group2_output]}--></table>\n</td>
<td text-align=\"top\"\">\n<table style=\"width:75px;padding:0;margin:0;border-collapse:separate\">\n<!--{[group3_output]}--></table>\n</td>
<td text-align=\"top\"\">\n<table style=\"width:75px;padding:0;margin:0;border-collapse:separate\">\n<!--{[group4_output]}--></table>\n</td>
<td text-align=\"top\"\">\n<table style=\"width:75px;padding:0;margin:0;border-collapse:separate\">\n<!--{[group5_output]}--></table>\n</td>
<td text-align=\"top\"\">\n<table style=\"width:75px;padding:0;margin:0;border-collapse:separate\">\n<!--{[group6_output]}--></table>\n</td>
<td text-align=\"top\"\">\n<table style=\"width:75px;padding:0;margin:0;border-collapse:separate\">\n<!--{[group7_output]}--></table>\n</td>
<td valign=\"top\" text-align=\"top\"\">\n<table align=\"top\" style=\"width:75px;padding:0;margin:0;border-collapse:separate\">\n<!--{[group8_output]}--></table>\n</td>
</tr></table><!--{[output]}-->",$GLOBALS['output']);

for($i = 0; $i < 14; $i++)
{

if(isset($GLOBALS['status'][$i+1]) && $GLOBALS['status'][$i+1] == "1")
{
	$statusColor = "<td style=\"background-color:green;text-align:center;<!--{[border]}-->\">";
}
else if(isset($GLOBALS['status'][$i+1]) && $GLOBALS['status'][$i+1] == "2")
{
	$statusColor = "<td style=\"background-color:yellow;text-align:center;<!--{[border]}-->\">";
}
else
{
	$statusColor = "<td style=\"background-color:red;text-align:center;<!--{[border]}-->\">";

}

if(isset($GLOBALS['checkout'][$i+1]) && $GLOBALS['checkout'][$i+1])
{
	$statusColor = str_replace("<!--{[border]}-->","border:2px 	solid black;height:50px;width:50px",$statusColor);
}
else
{
	$statusColor = str_replace("<!--{[border]}-->","border:2px 	dashed grey;height:50px;width:50px",$statusColor);
}


$GLOBALS['output'] = str_replace("<!--{[group1_output]}-->","<tr>".$statusColor."<a href=\"?tnum=".($i + 1)."\">".($i + 1)."</a></td></tr>\n<!--{[group1_output]}-->",$GLOBALS['output']);
}

for($i = 14; $i < 28; $i++)
{
if(isset($GLOBALS['status'][$i+1]) && $GLOBALS['status'][$i+1] == "1")
{
	$statusColor = "<td style=\"background-color:green;height:50px;text-align:center;<!--{[border]}-->\">";
}
else if(isset($GLOBALS['status'][$i+1]) && $GLOBALS['status'][$i+1] == "2")
{
	$statusColor = "<td style=\"background-color:yellow;height:50px;text-align:center;<!--{[border]}-->\">";
}
else
{
	$statusColor = "<td style=\"background-color:red;height:50px;text-align:center;<!--{[border]}-->\">";

}

if(isset($GLOBALS['checkout'][$i+1]) && $GLOBALS['checkout'][$i+1])
{
	$statusColor = str_replace("<!--{[border]}-->","border:2px 	solid black;height:50px;width:50px",$statusColor);
}
else
{
	$statusColor = str_replace("<!--{[border]}-->","border:2px 	dashed grey;height:50px;width:50px",$statusColor);
}


	$GLOBALS['output'] = str_replace("<!--{[group2_output]}-->","<tr>".$statusColor."<a href=\"?tnum=".($i + 1)."\">".($i + 1)."</a></td></tr>\n<!--{[group2_output]}-->",$GLOBALS['output']);
}

for($i = 28; $i < 42; $i++)
{
if(isset($GLOBALS['status'][$i+1]) && $GLOBALS['status'][$i+1] == "1")
{
	$statusColor = "<td style=\"background-color:green;height:50px;text-align:center;<!--{[border]}-->\">";
}
else if(isset($GLOBALS['status'][$i+1]) && $GLOBALS['status'][$i+1] == "2")
{
	$statusColor = "<td style=\"background-color:yellow;height:50px;text-align:center;<!--{[border]}-->\">";
}
else
{
	$statusColor = "<td style=\"background-color:red;height:50px;text-align:center;<!--{[border]}-->\">";

}

if(isset($GLOBALS['checkout'][$i+1]) && $GLOBALS['checkout'][$i+1])
{
	$statusColor = str_replace("<!--{[border]}-->","border:2px 	solid black;height:50px;width:50px",$statusColor);
}
else
{
	$statusColor = str_replace("<!--{[border]}-->","border:2px 	dashed grey;height:50px;width:50px",$statusColor);
}


	$GLOBALS['output'] = str_replace("<!--{[group3_output]}-->","<tr>".$statusColor."<a href=\"?tnum=".($i + 1)."\">".($i + 1)."</a></td></tr>\n<!--{[group3_output]}-->",$GLOBALS['output']);
}

for($i = 42; $i < 56; $i++)
{
if(isset($GLOBALS['status'][$i+1]) && $GLOBALS['status'][$i+1] == "1")
{
	$statusColor = "<td style=\"background-color:green;height:50px;text-align:center;<!--{[border]}-->\">";
}
else if(isset($GLOBALS['status'][$i+1]) && $GLOBALS['status'][$i+1] == "2")
{
	$statusColor = "<td style=\"background-color:yellow;height:50px;text-align:center;<!--{[border]}-->\">";
}
else
{
	$statusColor = "<td style=\"background-color:red;height:50px;text-align:center;<!--{[border]}-->\">";

}

if(isset($GLOBALS['checkout'][$i+1]) && $GLOBALS['checkout'][$i+1])
{
	$statusColor = str_replace("<!--{[border]}-->","border:2px 	solid black;height:50px;width:50px",$statusColor);
}
else
{
	$statusColor = str_replace("<!--{[border]}-->","border:2px 	dashed grey;height:50px;width:50px",$statusColor);
}


	$GLOBALS['output'] = str_replace("<!--{[group4_output]}-->","<tr>".$statusColor."<a href=\"?tnum=".($i + 1)."\">".($i + 1)."</a></td></tr>\n<!--{[group4_output]}-->",$GLOBALS['output']);
}

}

for($i = 56; $i < 70; $i++)
{

if(isset($GLOBALS['status'][$i+1]) && $GLOBALS['status'][$i+1] == "1")
{
	$statusColor = "<td style=\"background-color:green;height:50px;width:50px;text-align:center;<!--{[border]}-->\">";
}
else if(isset($GLOBALS['status'][$i+1]) && $GLOBALS['status'][$i+1] == "2")
{
	$statusColor = "<td style=\"background-color:yellow;height:50px;width:50px;text-align:center;<!--{[border]}-->\">";
}
else
{
	$statusColor = "<td style=\"background-color:red;height:50px;width:50px;text-align:center;<!--{[border]}-->\">";

}

if(isset($GLOBALS['checkout'][$i+1]) && $GLOBALS['checkout'][$i+1])
{
	$statusColor = str_replace("<!--{[border]}-->","border:2px 	solid black;height:50px;width:50px",$statusColor);
}
else
{
	$statusColor = str_replace("<!--{[border]}-->","border:2px 	dashed grey;height:50px;width:50px",$statusColor);
}


	$GLOBALS['output'] = str_replace("<!--{[group5_output]}-->","<tr>".$statusColor."<a href=\"?tnum=".($i + 1)."\">".($i + 1)."</a></td></tr>\n<!--{[group5_output]}-->",$GLOBALS['output']);
}


for($i = 70; $i < 84; $i++)
{

if(isset($GLOBALS['status'][$i+1]) && $GLOBALS['status'][$i+1] == "1")
{
	$statusColor = "<td style=\"background-color:green;height:50px;width:50px;text-align:center;<!--{[border]}-->\">";
}
else if(isset($GLOBALS['status'][$i+1]) && $GLOBALS['status'][$i+1] == "2")
{
	$statusColor = "<td style=\"background-color:yellow;height:50px;width:50px;text-align:center;<!--{[border]}-->\">";
}
else
{
	$statusColor = "<td style=\"background-color:red;height:50px;width:50px;text-align:center;<!--{[border]}-->\">";

}

if(isset($GLOBALS['checkout'][$i+1]) && $GLOBALS['checkout'][$i+1])
{
	$statusColor = str_replace("<!--{[border]}-->","border:2px 	solid black;height:50px;width:50px",$statusColor);
}
else
{
	$statusColor = str_replace("<!--{[border]}-->","border:2px 	dashed grey;height:50px;width:50px",$statusColor);
}


	$GLOBALS['output'] = str_replace("<!--{[group6_output]}-->","<tr>".$statusColor."<a href=\"?tnum=".($i + 1)."\">".($i + 1)."</a></td></tr>\n<!--{[group6_output]}-->",$GLOBALS['output']);
}


for($i = 84; $i < 98; $i++)
{

if(isset($GLOBALS['status'][$i+1]) && $GLOBALS['status'][$i+1] == "1")
{
	$statusColor = "<td style=\"background-color:green;height:50px;width:50px;text-align:center;<!--{[border]}-->\">";
}
else if(isset($GLOBALS['status'][$i+1]) && $GLOBALS['status'][$i+1] == "2")
{
	$statusColor = "<td style=\"background-color:yellow;height:50px;width:50px;text-align:center;<!--{[border]}-->\">";
}
else
{
	$statusColor = "<td style=\"background-color:red;height:50px;width:50px;text-align:center;<!--{[border]}-->\">";

}

if(isset($GLOBALS['checkout'][$i+1]) && $GLOBALS['checkout'][$i+1])
{
	$statusColor = str_replace("<!--{[border]}-->","border:2px 	solid black;height:50px;width:50px",$statusColor);
}
else
{
	$statusColor = str_replace("<!--{[border]}-->","border:2px 	dashed grey;height:50px;width:50px",$statusColor);
}


	$GLOBALS['output'] = str_replace("<!--{[group7_output]}-->","<tr>".$statusColor."<a href=\"?tnum=".($i + 1)."\">".($i + 1)."</a></td></tr>\n<!--{[group7_output]}-->",$GLOBALS['output']);
}


for($i = 98; $i < 112; $i++)
{

if(isset($GLOBALS['status'][$i+1]) && $GLOBALS['status'][$i+1] == "1")
{
	$statusColor = "<td style=\"background-color:green;height:50px;width:50px;text-align:center;<!--{[border]}-->\">";
}
else if(isset($GLOBALS['status'][$i+1]) && $GLOBALS['status'][$i+1] == "2")
{
	$statusColor = "<td style=\"background-color:yellow;height:50px;width:50px;text-align:center;<!--{[border]}-->\">";
}
else
{
	$statusColor = "<td style=\"background-color:red;height:50px;width:50px;text-align:center;<!--{[border]}-->\">";

}

if(isset($GLOBALS['checkout'][$i+1]) && $GLOBALS['checkout'][$i+1])
{
	$statusColor = str_replace("<!--{[border]}-->","border:2px 	solid black;height:50px;width:50px",$statusColor);
}
else
{
	$statusColor = str_replace("<!--{[border]}-->","border:2px 	dashed grey;height:50px;width:50px",$statusColor);
}


	$GLOBALS['output'] = str_replace("<!--{[group8_output]}-->","<tr>".$statusColor."<a href=\"?tnum=".($i + 1)."\">".($i + 1)."</a></td></tr>\n<!--{[group8_output]}-->",$GLOBALS['output']);
}




		if(isset($dbmysql))
		{

			$sql = "SELECT DISTINCT `tNum` FROM `territories`";
			//$results = mysql_query($sql);
			$results = $dbmysql->query($sql);
			$count = 0;
			while($row = mysqli_fetch_row($results))
			{
				$count = $count + 1;
				
			}
			//$GLOBALS['output'] = str_replace("<!--{[output]}-->","Territoies scanned:".$count,$GLOBALS['output']);

$GLOBALS['output'] = str_replace("<!--{[count_update]}-->","Territoies scanned: ".$count."<br/><!--{[count_update]}-->",$GLOBALS['output']);

			$sql = "SELECT COUNT(`tComplete`) FROM `territories` WHERE `tComplete`='1'";
			//$results = mysql_query($sql);
			$results = $dbmysql->query($sql);
			$row = mysqli_fetch_row($results);
			
$GLOBALS['output'] = str_replace("<!--{[count_update]}-->","Fully Completed: ".$row[0]."<br/><!--{[count_update]}-->",$GLOBALS['output']);

$GLOBALS['output'] = str_replace("<!--{[status_report]}-->","Total Percent Complete: ".round((float)($row[0]/112) * 100,2)."&#37;<br/><!--{[status_report]}-->",$GLOBALS['output']);


$GLOBALS['output'] = str_replace("<!--{[status_report_link]}-->","<a href=\"?=status\">Status Report</a>",$GLOBALS['output']);


if(isset($_GET['tnum']) && isset($GLOBALS['checkoutname'][$_GET['tnum']]))
{
       $GLOBALS['output'] = str_replace("<!--{[assigned]}-->","Assigned to: ".$GLOBALS['checkoutname'] [$_GET['tnum']]."<br/>",$GLOBALS['output']);
}
else
{
               $GLOBALS['output'] = str_replace("<!--{[assigned]}-->","Unassigned<br/>",$GLOBALS['output']);
}


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
	if(isset($dbmysql))
	{
		//mysql_close($mysql);
	}
?>
