<?php
session_start();
require_once('../anonymous.php');
require_once("functions.php");
$userID = $_SESSION['login_user'];

if(isset($_POST['q']) && isset($_POST['p']) && isset($_POST["k"])) {
  $question_opinion = $_POST["q"];    //String
  $number  = $_POST["p"];    //Int
  $category = $_POST["k"];    //category id
}



//Open Database connection
$myfile = fopen( "/var/www/lemons.txt", "r") or die("Unable to open file!");
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


//Test success
connectQuery();

//try
$opinion = opinion_to_number($question_opinion);

//checks if opinion is in database, if it is updates it.
if(check_opinion_in_database($connection, $userID, $number, $category) > 0 && $userID != 1001){
  update_opinion($connection, $userID, $number, $opinion, $category);
}else {
  //Insert appropriate emoticon for user ;)
  opinion($connection, $userID, $number, $opinion, $category);
}

//Close SQL connection
mysqli_close($connection);
?>
