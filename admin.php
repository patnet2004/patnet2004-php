<?php

session_start();
$_SESSION['IN_START'] = "started";
$mysql = 0;

if(!isset($_GET['pass']))
{
	die("");
}


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

require("template.php");
$GLOBALS['output'] = "
	<!DOCTYPE html>
	<html>
	<head>
	<title>Campaign Territory - Admin</title>
	<style>
		a.redlink:link
		{
			color: red;
			text-decoration:none;
			text-shadow: -1px 0px #000000,1px 0px 					#000000, 0px -1px #000000, 0px 1px #000000;			
		}
		a.redlink:visited
		{
			color: red;
			text-decoration:none;
			text-shadow: -1px 0px #000000,1px 0px 					#000000, 0px -1px #000000, 0px 1px #000000;
		}

		a.redlink:hover
		{
			color: red;
			text-decoration:underline;
			text-shadow: -1px 0px #000000,1px 0px 					#000000, 0px -1px #000000, 0px 1px #000000;
		}

		a.greenlink:link
		{
			color: green;
			text-decoration:none;
			text-shadow: -1px 0px #000000,1px 0px 					#000000, 0px -1px #000000, 0px 1px #000000;			
		}
		a.greenlink:visited
		{
			color: green;
			text-decoration:none;
			text-shadow: -1px 0px #000000,1px 0px 					#000000, 0px -1px #000000, 0px 1px #000000;
		}

		a.greenlink:hover
		{
			color: green;
			text-decoration:underline;
			text-shadow: -1px 0px #000000,1px 0px 					#000000, 0px -1px #000000, 0px 1px #000000;
		}

	</style>
	</head>
	<body>
	<div align=\"center\">
	<p align=\"center\" style=\"font-size:150%\">
	Campaign Territory - Status Report<br/>
	</p>
	<!--{[status_report]}-->
	<div align=\"left\">
	<!--{[output]}-->
	</div>
	<!--{[count_update]}-->
	</div>
	</body>
	</html>
";

$sql = "SELECT `key` FROM secrets WHERE `name`= 'password'";
$result = $dbmysql->query($sql);
$password;
while($row = mysqli_fetch_row($result))
{
	$password = $row[0];	
}

if($_GET['pass'] != $password)
{
	die("");
}

if(isset($_POST['submit']) && $_POST['submit'] && isset($_POST['tname']) && isset($_POST['tnum']) && $_POST['tname'] != "" && $_POST['tnum'] != "")
{
	$sql = "INSERT INTO tcheckout (`tname`, `tnum`) VALUES ('".$_POST['tname']."','".$_POST['tnum']."')";
	if($dbmysql->query($sql) === TRUE)
	{
		echo($_POST['tname']."assigned territory #".$_POST['tnum']."<br/>");
	}
	else
	{
		echo "Error: " . $sql . "<br>" . $dbmysql->error;
	}
}

$sql = "SELECT `tnum` FROM territories WHERE `tcomplete`= 1";
$result = $dbmysql->query($sql);
$complete;
while($row = mysqli_fetch_row($result))
{
	$complete[$row[0]] = 1;
}
mysqli_free_result($result);


$sql = "SELECT `tname`, `tnum` FROM tcheckout ORDER BY `tname` ASC";
$result = $dbmysql->query($sql);
$lastname = "";
$namechange = 0;



while($row = mysqli_fetch_row($result))
{
	if($lastname == $row[0])
	{
		if(isset($complete[$row[1]]) && $complete[$row[1]] == 1)
		{
			$GLOBALS['output'] = str_replace("<!--{[output]}-->"," <a class=\"greenlink\" href=\"index.php?tnum=".$row[1]."\">".$row[1]."</a> <!--{[tnum]}--> \n<!--{[output]}-->",$GLOBALS['output']);
		}
		else
		{
			
			$GLOBALS['output'] = str_replace("<!--{[output]}-->"," <a class=\"redlink\" href=\"index.php?tnum=".$row[1]."\">".$row[1]."</a> <!--{[tnum]}--> \n<!--{[output]}-->",$GLOBALS['output']);
		}
	}
	else
	{
		if(isset($complete[$row[1]]) && $complete[$row[1]] == 1)
		{
			$GLOBALS['output'] = str_replace("<!--{[output]}-->","<br/><b>".$row[0]."</b>: <a class=\"greenlink\" href=\"index.php?tnum=".$row[1]."\">".$row[1]."</a> <!--{[tnum]}--> \n<!--{[output]}-->",$GLOBALS['output']);
			$lastname = $row[0];
		}
		else
		{
			$GLOBALS['output'] = str_replace("<!--{[output]}-->","<br/><b>".$row[0]."</b>: <a class=\"redlink\" href=\"index.php?tnum=".$row[1]."\">".$row[1]."</a> <!--{[tnum]}--> \n<!--{[output]}-->",$GLOBALS['output']);
			$lastname = $row[0];
		}
	}

	//mysqli_free_result($tresult);
	$GLOBALS['output'] = str_replace("<!--{[tnum]}-->","", $GLOBALS['output']);
}
mysqli_free_result($result);

$GLOBALS['output'] = str_replace("<!--{[output]}-->","<p><b>Assign:</b></p><p/><form action=\"\" method=\"post\">Name:<br/> <input type=\"text\" name=\"tname\"><br>Number:<br/> <input type=\"text\" name=\"tnum\"><br/><br/><input type=\"submit\" value=\"Submit\" name=\"submit\">
</form></p><!--{[output]}-->",$GLOBALS['output']);


echo($GLOBALS['output']);

?>
