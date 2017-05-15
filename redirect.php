<?php
session_start();
require_once('anonymous.php');
require_once('js/functions.php');

$userID = $_SESSION['login_user'];
echo ($userID);

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


$all_clicked = select_topic_for_user($connection, $userID, 'Allchosen');
$var = ["Trade", "Imo", "SovandLaw", "Jobs", "DefenceandSecurity"];
if($all_clicked == 1){
  for($i=0; $i<5; $i++){
    update_selection($connection, $userID, $var[$i], 1);
  }
  update_selection($connection, $userID, 'Allchosen', 2);
}

$Trade_clicked = select_topic_for_user($connection, $userID, 'Trade');
$Imo_clicked = select_topic_for_user($connection, $userID, 'Imo');
$SovandLaw_clicked = select_topic_for_user($connection, $userID, 'SovandLaw');
$Jobs_clicked = select_topic_for_user($connection, $userID, 'Jobs');
$DefenceandSecurity_clicked = select_topic_for_user($connection, $userID, 'DefenceandSecurity');

if($Trade_clicked == 1){
  update_selection($connection, $userID, "Trade", 2);
  header("Location: trade.php");
  die();
} else if ($Imo_clicked == 1){
  update_selection($connection, $userID, "Imo", 2);
  header("Location: immigration.php");
  die();
}else if ($SovandLaw_clicked == 1){
  update_selection($connection, $userID, "SovandLaw", 2);
  header("Location: sovereignty-and-law-making.php");
  die();
}else if ($Jobs_clicked == 1){
  update_selection($connection, $userID, "Jobs", 2);
  header("Location: jobs.php");
  die();
}else if ($DefenceandSecurity_clicked == 1){
  update_selection($connection, $userID, "DefenceandSecurity", 2);
  header("Location: defence-and-security.php");
  die();
}else if ($DefenceandSecurity_clicked == 2 ||
          $Trade_clicked == 2 ||
          $SovandLaw_clicked == 2 ||
          $Jobs_clicked == 2 ||
          $Imo_clicked == 2){
  header("Location: results.php");
  die();
} else{
  header("Location: index.php");
  die();
}
?>
 <meta http-equiv="refresh" content="1; URL=http://www.leaveorstay.co.uk/redirect.php">
