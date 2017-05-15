<?php

	require_once('js/functions.php');
	if (isset($_COOKIE['emailCookie'])) {
		$userID = $_COOKIE['emailCookie'];
		$_SESSION["login_user"] = $userID;
	} elseif(isset($_COOKIE['LoS'])){
		//Cookie is found
		$userID = $_COOKIE['LoS'];
		$_SESSION["login_user"] = $userID;
	} elseif(isset($_SESSION["login_user"])){
		//SessionID but no cookie
		$userID = $_SESSION["login_user"];
		cookieID($userID);
	} else {
		//gives out a new uniqueID
		$userID = md5(uniqid(rand(), true));
		cookieID($userID);
		$_SESSION["login_user"] = $userID;
	}

	//Open Database connection
	$myfile = fopen("/var/www/lemons.txt", "r") or die("Unable to lemons!");
	$myIP   = fopen( "/var/www/IP.txt", "r") or die("Unable to open file!");
	$dbpass = fread($myfile,filesize("/var/www/lemons.txt"));
	$dbhost = fread($myIP,filesize("/var/www/IP.txt"));
	fclose($myfile);
	fclose($myIP);
	$dbuser = "remoterootuser";
	$dbpass = trim($dbpass);
	$dbhost = trim($dbhost);
	$dbname = "test";
	$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
	
	if(check_if_in_database($connection, $userID) == 0){
		insert_new_user($connection, $userID);
	}
?>
