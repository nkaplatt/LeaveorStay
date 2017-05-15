<?php
require_once("../js/functions.php");
require_once("../anonymous.php");
$userID = $_SESSION["login_user"];

if(isset($_GET['category'])) {
    $category = $_GET['category'];
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

$results = get_results_emot($userID);
if(      $results[0]==0
      && $results[1]==0
      && $results[2]==0
      && $results[3]==0
      && $results[4]==0 )
      {
        $data_submitted = false;
} else {
        $data_submitted = true;
}

  echo json_encode($results);
  echo json_encode($data_submitted);
?>
