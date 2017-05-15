<?php

require_once("../js/functions.php");
require_once("../anonymous.php");
$userID = $_SESSION["login_user"];

if(isset($_GET['category'])) {
    $category = $_GET['category'];
}


function get_results_vote($User_ID){
  global $connection;
  global $category;
  //Queries to get answers that user entered
  $query = 'SELECT Choice_Type FROM Vote_tbl ';
  $query .= "WHERE MUser_ID = '{$User_ID}' and Category_ID = '{$category}';";
  $result = mysqli_query($connection, $query);

  connectQuery();
  $array = array(
    0 => 0,   //vote leave
    1 => 0,   //vote stay
    2 => 0,   //dont care
  );
  while($value = mysqli_fetch_array($result))
  {
    $etype = $value['Choice_Type'];
    if($etype < 1 || $etype > 3)
    continue;
    $array[$etype - 1] += 1;
  }
  mysqli_free_result($result);
  return $array;
}

$results = get_results_vote($userID);
if(      $results[0]==0
      && $results[1]==0
      && $results[2]==0 )
      {
        $data_submitted = false;
  } else {
    $data_submitted = true;
  }

  echo json_encode($results);
  echo json_encode($data_submitted);
?>
