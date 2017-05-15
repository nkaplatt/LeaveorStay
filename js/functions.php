<?php
function redirect_to($new_location) {
  header("Location: " . $new_location);
  exit;
}

/*function connect_to_db(){ //edit these to work accross multiple pages, saves time
  $dbname = "EU_db";
  $dbuser = "root";
  $dbpass = "nick";
  $dbhost = 'localhost';
  $connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
  return $connection;
}*/

function emailCookieID($uniqueID){//$connection)
  //This function will set a new cookie using the email given by a user
  //This function also submits the email hashed ID to the database
  $name = "emailCookie";
  $expire = time() + (60*60*24*365);
  setcookie($name, $uniqueID, $expire);
}

function deleteEmailCookie($currentSession){
  $name = "emailCookie";
  $expire = time() - (60*60*24*365);
  setcookie($name, $currentSession, $expire);
}

function sessionUniqueID () {
  //This function creates a new unique ID and returns it as a string
  return md5(uniqid(rand(), true));
}

function cookieID($uniqueID){//$connection)
  //This function will set a unique ID cookie on a new computer if it's not already there
  //This function also submits the ID to the database
  $name = "LoS";
  $expire = time() + (60*60*24*365);
  setcookie($name, $uniqueID, $expire);
  //Adds the session ID to the database, this uses the function below
  //addSessionIDtoDatabase($connection, $currentSession);
}

function addSessionIDtoDatabase($connection, $currentSession){
  //This function will add a unique ID to the database,
  //in to the database /content/ in the /user_id/ column, and /content/.
  $content = "test";
  $query = "INSERT INTO content(user_id, content) ";
  $query .= "VALUES ('{$currentSession}', '{$content}')";       //NEED TO PROTECT AGAINST SQL INJECTION
  $result = mysqli_query($connection, $query);
  if ($result) {
    echo("success!!");
  }else {
    die("Database query failed" . mysqli_error($connection));
  }
}

function deleteCookie($currentSession){
  $name = "LoS";
  $expire = time() - (60*60*24*365);
  setcookie($name, $currentSession, $expire);
}

function connectQuery() {
  if (mysqli_connect_errno()) {
    die("Database connection failed: " .
    mysqli_connect_error() .
    " (" . mysqli_connect_errno() . ")"    //dont show them errors in final product
  );
}
}

function escape_string($string_to_be_escaped) {
  global $connection;

  $string_escaped = mysqli_real_escape_string($connection, $string_to_be_escaped);
  return $string_escaped;
}


function checkIfInDatabase($connection, $email) {

  $query = "SELECT Email_Address FROM User_tbl ";
  $query .= "WHERE Email_Address = '{$email}'";
  $result = mysqli_query($connection, $query);
  if ($result) {
    return (mysqli_num_rows($result));
  }else {
    die("Database query failed" . mysqli_error($connection));
  }
  mysqli_free_result($result);
}

function validate_name($name) {
  global $connection;

  if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
    $nameError = "Only letters and white space allowed";
    return $nameError;
  }
}

function validate_email($email) {
  global $connection;

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $emailError = "Invalid email format";
    return $emailError;
  }
}

function emoticon($connection, $MUser_ID, $Emoticon_Number,$Emoticon_Type, $Category_ID) {

  $query = "INSERT INTO Card_tbl(MUser_ID, Emoticon_Number, Emoticon_Type, Category_ID) ";
  $query .= "VALUES ('$MUser_ID', '$Emoticon_Number', '$Emoticon_Type', '$Category_ID')";
  $result = mysqli_query($connection, $query);        //delete if works
  return $query;
}

function check_emoticon_in_database($connection, $userID, $emoticon_number, $category) {
  $query = "SELECT * FROM Card_tbl ";
  $query .= "WHERE MUser_ID = '{$userID}' AND ";
  $query .= "Emoticon_Number = {$emoticon_number} AND ";
  $query .= "Category_ID = {$category};";
  $result = mysqli_query($connection, $query);

  if ($result) {
    $value = mysqli_num_rows($result);
    mysqli_free_result($result);
    return $value;
  }else {
    die("Database query failed" . mysqli_error($connection));
  }
}

function update_emoticon($connection, $userID, $emoticon_number, $emoticon_type, $category){
  $query = "UPDATE Card_tbl ";
  $query .= "SET Emoticon_Type = {$emoticon_type} ";
  $query .= "WHERE MUser_ID = '{$userID}' ";
  $query .= "AND Emoticon_Number = {$emoticon_number} ";
  $query .= "AND Category_ID = {$category};";
  $result = mysqli_query($connection, $query);
}

function emoticon_to_number($emoticon){
  $Emoticon_Type = 0;
  switch($emoticon) {
    case "anger":
    $Emoticon_Type = 1;
    break;
    case "shock";
    $Emoticon_Type = 2;
    break;
    case "indifferent";
    $Emoticon_Type = 3;
    break;
    case "happy";
    $Emoticon_Type = 4;
    break;
    case "delighted";
    $Emoticon_Type = 5;
    break;
  }
  return $Emoticon_Type;
}

function opinion($connection, $MUser_ID, $Emoticon_Number,$Emoticon_Type, $Category_ID) {
  $query = "INSERT INTO Vote_tbl(MUser_ID, Choice_Number, Choice_Type, Category_ID) ";
  $query .= "VALUES ('$MUser_ID', '$Emoticon_Number', '$Emoticon_Type', '$Category_ID');";
  $result = mysqli_query($connection, $query);        //delete if works
  return $query;
}

function opinion_to_number($opinion){
  $Emoticon_Type = 0;
  switch($opinion) {
    case "leave":
    $Emoticon_Type = 1;
    break;
    case "stay";
    $Emoticon_Type = 2;
    break;
    case "neither";
    $Emoticon_Type = 3;
    break;
  }
  return $Emoticon_Type;
}

function check_opinion_in_database($connection, $userID, $choice_number, $category) {
  $query = "SELECT * FROM Vote_tbl ";
  $query .= "WHERE MUser_ID = '{$userID}' AND ";
  $query .= "Choice_Number = {$choice_number} AND ";
  $query .= "Category_ID = {$category};";
  $result = mysqli_query($connection, $query);
  if ($result) {
    $value = mysqli_num_rows($result);
    mysqli_free_result($result);
    return $value;
  }else {
    die("Database query failed" . mysqli_error($connection));
  }
}

function update_opinion($connection, $userID, $choice_number, $choice_type, $category){
  $query = "UPDATE Vote_tbl ";
  $query .= "SET Choice_Type = {$choice_type} ";
  $query .= "WHERE MUser_ID = '{$userID}' ";
  $query .= "AND Choice_Number = {$choice_number} ";
  $query .= "AND Category_ID = {$category};";
  $result = mysqli_query($connection, $query);
}

function insert_new_user($connection, $userID){
  $query = "INSERT INTO User_tbl(MUser_ID) ";
  $query .= "VALUES ('{$userID}');";
  $result = mysqli_query($connection, $query);
}

// beginning of functions for login/logout

function set_to_logged_in($connection, $MUser_ID) {
  $query = "UPDATE User_tbl SET LoggedIN = 1 WHERE MUser_ID = '{$MUser_ID}' and Email_Address IS NOT NULL;";
  $result = mysqli_query($connection, $query);
  return $result;
}

function set_to_logged_out($connection, $MUser_ID) {
  $query = "UPDATE User_tbl SET LoggedIN = 0 WHERE MUser_ID = '{$MUser_ID}';";
  $result = mysqli_query($connection, $query);
  return $result;
}

function check_if_logged_in($MUser_ID) {
  global $connection;
  $query = "SELECT LoggedIN FROM User_tbl WHERE MUser_ID = '{$MUser_ID}';";
  $result = mysqli_query($connection, $query);

  while($value = mysqli_fetch_array($result)){
    $new = $value['LoggedIN'];
    mysqli_free_result($result);
    if($new > 0){
      return 1;
    }
    return 0;;
  }
}

function update_anon_to_registered($connection, $Session_ID, $pass1, $email_noSalt, $current_session_id){
  $query = "UPDATE User_tbl SET MUser_ID = '{$Session_ID}', Email_Address = '{$email_noSalt}', UPassword = '{$pass1}' ";
  $query .= "WHERE MUser_ID = '{$current_session_id}';";
  $result = mysqli_query($connection, $query);
  return $result;
}

function update_anon_to_email_submitted($connection, $Session_ID, $email_noSalt, $current_session_id) {
  $query = "UPDATE User_tbl SET MUser_ID = '{$Session_ID}', Email_Address = '{$email_noSalt}' ";
  $query .= "WHERE MUser_ID = '{$current_session_id}';";
  $result = mysqli_query($connection, $query);
  return $result;
}

function insert_new_registered_user($connection, $Session_ID, $pass1, $email_noSalt){
  $query = "INSERT INTO User_tbl(MUser_ID, UPassword, Email_Address) ";
  $query .= "VALUES ('{$Session_ID}', '{$pass1}', '{$email_noSalt}');";
  $result = mysqli_query($connection, $query);
  return $result;
}

//ADDED USING NANO
function insert_new_email_submitted_user($connection, $Session_ID, $email_noSalt){
  $query = "INSERT INTO User_tbl(MUser_ID, Email_Address) ";
  $query .= "VALUES ('{$Session_ID}', '{$email_noSalt}');";
  $result = mysqli_query($connection, $query);
  return $result;
}

function check_email_exists($connection, $Email) {
  $query = "SELECT * FROM User_tbl WHERE Email_Address = '{$Email}';";
  $result = mysqli_query($connection, $query);
  if($Email = mysqli_fetch_assoc($result)) {
    return true;
  }
  return false;
}

// end of functions for login/logout

function check_if_in_database($connection, $userID){
  $query = "SELECT * FROM User_tbl ";
  $query .= "WHERE MUser_ID = '{$userID}';";
  $result = mysqli_query($connection, $query);

  if ($result) {
    $value = mysqli_num_rows($result);
    mysqli_free_result($result);
    if($value > 0){
      return 1;
    }
    return 0;
  }else {
    die("Database query failed" . mysqli_error($connection));
  }
}

function select_topic_for_user($connection, $userID, $topic){
  $query = "SELECT {$topic} FROM User_tbl ";
  $query .= "WHERE MUser_ID = '{$userID}';";
  $result = mysqli_query($connection, $query);

  while($value = mysqli_fetch_array($result)) {
		$enum = $value[$topic];
    mysqli_free_result($result);
		return $enum;
	}
}

function update_selection($connection, $userID, $topic, $newvalue){
  $query = "UPDATE User_tbl ";
  $query .= "SET {$topic} = {$newvalue} ";
  $query .= "WHERE MUser_ID = '{$userID}';";
  $result = mysqli_query($connection, $query);
}


?>
