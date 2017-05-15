<?php
// Accessed by index and results - are they logged in, have they done intro cards, have they given email on HP.
require_once('js/functions.php');	//for check_logged in function

function gave_email_on_hp($connection, $user_ID) {
	// used on homepage if session_id has been used to login (current state: logged out)
	$query = "SELECT * FROM User_tbl WHERE MUser_ID = '{$user_ID}' AND Email_Address IS NOT NULL;";
	$result = mysqli_query($connection, $query);
        if (mysqli_num_rows($result) == 0) {
		return true;
	} else {
		return false;
	}
}

function done_intro_cards($connection, $user_ID) {
	$query = "SELECT * FROM Card_tbl WHERE MUser_ID = '{$user_ID}' AND Category_ID = 10;";
	$result = mysqli_query($connection, $query);

	if ($result) {
    $value = mysqli_num_rows($result);
    mysqli_free_result($result);
    if($value > 0){
      return $value;
    }
    return 0;
  }else {
    die("Database query failed" . mysqli_error($connection));
  }
}

function is_user_logged_in($connection, $user_ID) {
        global $connection;
	$query = "SELECT * FROM User_tbl WHERE MUser_ID = '{$user_ID}' AND LoggedIN = '1';";
	$result = mysqli_query($connection, $query);
	if (mysqli_num_rows($result) == 0) {
		return false;
	} else {
		return true;
	}
}

function confirm_query($result) {
  if (!$result) {
    die("Database query failed");
  }
}
function check_user_exists($Session_ID, $connection) {                          //N.b. not check_fbuser_exists()
  global $connection;
  $query = "SELECT * ";
  $query .= "FROM User_tbl ";
  $query .= "WHERE MUser_ID = '$Session_ID'";
  $query .= "LIMIT 1";
  $email_set = mysqli_query($connection, $query);
  confirm_query($email_set);
  if($Session_ID = mysqli_fetch_assoc($email_set)) {
    return true;
  }
  return false;
}


function write_email_to_db() { 
    global $connection;
    $current_session_id = $_SESSION['login_user'];
   
    if(($_SERVER["REQUEST_METHOD"] == "POST") && isset( $_POST['email'])) {
      
      $email = mysqli_real_escape_string($connection, $_POST['email']);
      // Test if connection occured
      if (mysqli_connect_errno()) {
        die("Database connection failed: " .
            mysqli_connect_error() .
            " (" . mysqli_connect_errno() . ")"
        );
      }
      $email = mysqli_real_escape_string($connection, $email);
      $email_noSalt = $email;
      $email = $email . 'EU';
      $Session_ID = hash('sha256', $email);
      if (check_email_exists($connection, $email_noSalt)) {                           //Check email is not already registered ?>
        <h6 class="login-divider">This email is already registered</h6>
	<?php } else {
            if (check_user_exists($current_session_id, $connection)) {
             
 $result = update_anon_to_email_submitted($connection, $Session_ID, $email_noSalt, $current_session_id);
	?> <h4 style="color:white;background-color:#7c1">Your submission has been recieved. We'll ask you for a password on the results page to activate your account.</h4> <?php
            } else { 
              $result = insert_new_email_submitted_user($connection, $Session_ID, $email_noSalt);
		?> <h4 style="color:white;background-color:#7c1">Your submission has been recieved. We'll ask you for a password on the results page to activate your account.</h4> <?php
            }
            if ($result) { ?>
              <script>
		var session = <?php echo json_encode($Session_ID); ?>;
		var oReq = new XMLHttpRequest();
		oReq.open("GET", "index.php?emailCookie=1" + "&session=" + session, true);
  		oReq.send(); 
             </script> <?php

              $_SESSION['login_user'] = $Session_ID;
            } else {
              die("Database query failed" . mysqli_error($connection));
            }
        }
    }
}

function write_password_to_db() {
        global $connection;
	$current_session_id = $_SESSION['login_user'];
  
    if($_SERVER["REQUEST_METHOD"] == "POST") {
      
      $password = mysqli_real_escape_string($connection, $_POST['password']);

      // Test if connection occured
      if (mysqli_connect_errno()) {
        die("Database connection failed: " .
            mysqli_connect_error() .
            " (" . mysqli_connect_errno() . ")"
        );
      }
      $mypassword = mysqli_real_escape_string($connection, $password);
      $passnonhash = $mypassword . "EU";
      $passhashed = hash('sha256', $passnonhash);
      $query = "UPDATE User_tbl SET UPassword = '{$passhashed}' WHERE MUser_ID = '{$current_session_id}';";
      $result = mysqli_query($connection, $query);
?>
<script>
var session = <?php echo json_encode($current_session_id); ?>;
var oReq = new XMLHttpRequest();
oReq.open("GET", "results.php?emailCookie=1" + "&session=" + session, true);
oReq.send(); 
</script> 

<?php

      if (!$result) {
        die("Database query failed" . mysqli_error($connection));
      } else {
?> <h4 style="color:white">Your submission has been recieved. You can log in at anytime from any device to access your results.</h4> <?php
      }
    }
}

function write_email_and_pass_to_db () {
    global $connection;
    $current_session_id = $_SESSION['login_user'];
    
    if($_SERVER["REQUEST_METHOD"] == "POST") {
     	
     	$email = mysqli_real_escape_string($connection, $_POST['email']);
     	$password = mysqli_real_escape_string($connection, $_POST['password']);
     	// Test if connection occured
     	if (mysqli_connect_errno()) {
     	  die("Database connection failed: " .
     	      mysqli_connect_error() .
     	      " (" . mysqli_connect_errno() . ")"
     	  );
     	}
     	$email = mysqli_real_escape_string($connection, $email);
     	$email_noSalt = $email;
     	$email = $email . 'EU';
     	$Session_ID = hash('sha256', $email);
     	
     	$mypassword = mysqli_real_escape_string($connection,$password);
     	$passnonhash = $mypassword . "EU";
     	$passhashed = hash('sha256', $passnonhash);
     	     
     	if (check_email_exists($connection, $email_noSalt)) {                           //Check email is not already registered ?>
     	   <h6 class="login-divider">This email is already registered</h6>
     	<?php 
     	} else {
     	    if (check_user_exists($current_session_id, $connection)) {
     	      $result = update_anon_to_registered($connection, $Session_ID, $passhashed, $email_noSalt, $current_session_id);
     	      ?> <h4 style="color:white">Your submission has been recieved. You can log in at anytime from any device to access your results.</h4> <?php
     	    } else { 
     	      $result = insert_new_registered_user($connection, $Session_ID, $passhashed, $email_noSalt);
						?> <h4 style="color:white">Your submission has been recieved. You can log in at anytime from any device to access your results.</h4> <?php
     	    }
     	    
     	    if ($result) {
     	      $_SESSION['login_user'] = $Session_ID;
     	    } else {
     	      die("Database query failed" . mysqli_error($connection));
     	    }
     	    
		}
    }
}
?>

