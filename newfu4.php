<?php
require_once("js/functions.php") ;
require_once("anonymous.php");
$userID = $_SESSION['login_user'];

if(isset($_POST['q']) && isset($_POST["k"])) {
  $type = $_POST['q'];
  $switch = $_POST["k"];
  $type = assign_num_to_column($type);
}

// Test if connection occured
connectQuery();

edit_option($connection, $userID, $type, $switch);
mysqli_close($connection);
function assign_num_to_column($class) {
  $value = 0;
  switch($class) {
    case "icon-button":
    $value = "SovandLaw";
    break;
    case "icon-button-2":
    $value = "Jobs";
    break;
    case "icon-button-3":
    $value = "DefenceandSecurity";
    break;
    case "icon-button-4":
    $value = "Imo";
    break;
    case "icon-button-5":
    $value = "Trade";
    break;
    default:
    $value = "Allchosen";
  }
  return $value;
}

function edit_option($connection, $username, $option, $typeofchange){
  /*
  * option - type of button e.g. econ
  */
  if (strcmp($typeofchange, "on") == 0){
    $query = "Update User_tbl ";
    $query .= "SET {$option} = 1 ";
    $query .= "WHERE MUser_ID = '$username';";
  }
  else if (strcmp($typeofchange, "off") == 0){
    $query = "Update User_tbl ";
    $query .= "SET {$option} = 0 ";
    $query .= "WHERE MUser_ID = '$username';";
  }
  $result = mysqli_query($connection, $query);
}
?>
