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

function get_results_emot($User_ID){
  global $connection;
  global $category;
  //Queries to get answers that user entered
  $query = 'SELECT Emoticon_Type FROM Card_tbl ';
  $query .= "WHERE MUser_ID = '{$User_ID}' and Category_ID = '{$category}';";
  $result = mysqli_query($connection, $query);

  connectQuery();
  $array_emot = array(
    0 => 0,   //anger
    1 => 0,   //shocked
    2 => 0,   //indifferent
    3 => 0,   //pleased
    4 => 0,   //very happy
  );
  while($value = mysqli_fetch_array($result))
{
  $etype = $value['Emoticon_Type'];
  if($etype < 1 || $etype> 5)
    continue;
  $array_emot[$etype - 1] += 1;
}
mysqli_free_result($result);
return $array_emot;
}

$newresults = array(
  0 => 0,
  1 => 0,
  2 => 0,
);

$results_vote = get_results_vote($userID);  //will return an array e.g. [1, 2, 3];
$results_emote = get_results_emot($userID); //will return an array e.g. [1, 2, 3, 4, 5];

$newresults[0] += $results_emote[0];
$newresults[0] += $results_emote[1];
$newresults[1] += $results_emote[2];
$newresults[2] += $results_emote[3];
$newresults[2] += $results_emote[4];
$newresults[0] += $results_vote[0];
$newresults[1] += $results_vote[2];
$newresults[2] += $results_vote[1];

echo json_encode($newresults);
?>
