<?php
  require_once('../anonymous.php');
  require_once("functions.php");
  $userID = $_SESSION['login_user'];
  if(isset($_POST['q']) && isset($_POST['p']) && isset($_POST["k"])) {
    $emoteName = $_POST["q"];    //String
    $emoteNum  = $_POST["p"];    //Int
    $emoteCat = $_POST["k"];    //category id
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

    //Test success
    connectQuery();

    //try
    $emoticon = emoticon_to_number($emoteName);
    $category = $emoteCat;
    $number   = $emoteNum;

    //checks if emoticon is in database, if it is updates it.
    if(check_emoticon_in_database($connection, $userID, $number, $category) > 0 && $userID != 1001){
        update_emoticon($connection, $userID, $number, $emoticon, $category);
    }else {
        //Insert appropriate emoticon for user ;)
        emoticon($connection, $userID, $number, $emoticon, $category);
    }

    //Close SQL connection
    mysqli_close($connection);
?>
