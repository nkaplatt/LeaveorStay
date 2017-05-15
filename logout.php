<?php 
session_start();
$userID = $_SESSION['login_user'];
require_once('js/functions.php');
$result = set_to_logged_out($connection, $userID);
deleteEmailCookie($userID);
mysqli_close($connection);
redirect_to('index.php');
?>
