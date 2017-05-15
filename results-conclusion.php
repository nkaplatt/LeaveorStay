<?php
require_once("js/functions.php");
require_once("anonymous.php");
$userID = $_SESSION["login_user"];

global $connection;

if (isset($_GET["file"])) {
	$file = $_GET["file"];
} else {
	$file = 0;
}

if (isset($_GET["category"])) {
	$category = $_GET["category"];
}


// 5 functions for fetching results data from db 

function sql_endconclusion_emotes($userID, $connection) {
	$query_emote = 'SELECT Emoticon_Type FROM Card_tbl ';
	$query_emote .= "WHERE MUser_ID = '{$userID}';";
	$result_emote = mysqli_query($connection, $query_emote);
	return $result_emote;
}

function sql_endconclusion_votes($userID, $connection) {
	$query_vote = 'SELECT Choice_Type FROM Vote_tbl ';
	$query_vote .= "WHERE MUser_ID = '{$userID}';";
	$result_vote = mysqli_query($connection, $query_vote);
	return $result_vote;
}

function sql_topic_emotes($userID, $category, $connection) {
	$query_emote = 'SELECT Emoticon_Type FROM Card_tbl ';
	$query_emote .= "WHERE MUser_ID = '{$userID}' and Category_ID = '{$category}';";
	$result_emote = mysqli_query($connection, $query_emote);
	return $result_emote;
}

function sql_topic_votes($userID, $category, $connection) {
	$query_vote = 'SELECT Choice_Type FROM Vote_tbl ';
	$query_vote .= "WHERE MUser_ID = '{$userID}' and Category_ID = '{$category}';";
	$result_vote = mysqli_query($connection, $query_vote);
	return $result_vote;
}

function sql_intro_cards_emotes($userID, $connection) {
	$query_emote = 'SELECT Emoticon_Type FROM Card_tbl ';
	$query_emote .= "WHERE MUser_ID = '{$userID}' and Category_ID = '10';";
	$result_emote = mysqli_query($connection, $query_emote);
	return $result_emote;
}
												 
function sql_intro_cards_votes($userID, $connection) {
	$query_vote = 'SELECT Choice_Type FROM Vote_tbl ';
	$query_vote .= "WHERE MUser_ID = '{$userID}' and Category_ID = '10';";
	$result_vote = mysqli_query($connection, $query_vote);
	return $result_vote;
}
												 
// 2 functions for results into arrays for intro section on HP

function to_array_intro_emotes($result_emote) {
				$introe_values_array = array();
        while ($value = mysqli_fetch_array($result_emote)) {
                $new_values = $value['Emoticon_Type'];
                array_push($introe_values_array, $new_values);
        }
        return $introe_values_array;
}	
	
function to_array_intro_votes($result_vote) {
        $introv_values_array = array();
        while ($value = mysqli_fetch_array($result_vote)) {
                $new_values = $value['Choice_Type'];
                array_push($introv_values_array, $new_values);
        }
        return $introv_values_array;
}
												 
// 2 functions for turning all other results into arrays
                                 
function to_array_votes($result_vote) {
        $vote_values_array = array();
        while ($value = mysqli_fetch_array($result_vote)) {
                $new_value = $value['Choice_Type'];
                array_push($vote_values_array, $new_value);
        }
        return $vote_values_array;
}

function to_array_emotes($result_emote) {
        $emote_values_array = array();
        while ($value = mysqli_fetch_array($result_emote)) {
                $new_value = $value['Emoticon_Type'];
                array_push($emote_values_array, $new_value);
        }
        return $emote_values_array;
}

// 3 functions to work out the array of results in order to print the conclusion

function for_stay_or_leave_emoticons($emote_values_array) {

        $negative_score = 0;
        $positive_score = 0;

	if (count($emote_values_array) == 0) {
					return 10;
	} else {

	for ($x=0; $x < count($emote_values_array); $x++) {
		if ($emote_values_array[$x] == 1) {
						$negative_score += 2;
		} elseif ($emote_values_array[$x] == 2) {
						$negative_score++;
		} elseif ($emote_values_array[$x] == 4) {
						$positive_score++;
		} elseif ($emote_values_array[$x] == 5) {
						$positive_score += 2;
		} 
	}

		if ($negative_score > $positive_score) {
						return 1;
		} elseif ($negative_score < $positive_score) {
						return 2;
		} else {
						return 3;
		}
	}
}

function for_stay_or_leave_votes($vote_values_array) {

		$leave_score = 0;
		$stay_score = 0;

		if (count($vote_values_array) == 0) {
						return 10;
		} else {

			for ($x=0; $x < count($vote_values_array); $x++) {
							if ($vote_values_array[$x] == 1) {
											$leave_score++;
							} elseif ($vote_values_array[$x] == 2) {
											$stay_score++;
							} 
			}

			if ($leave_score > $stay_score) {
							return 1;
			} elseif ($leave_score < $stay_score) {
							return 2;
			} else {
							return 3;
			}
		}
}

function for_stay_or_leave_intro($introv_values_array, $introe_values_array) {

	for ($x=0; $x < count($introv_values_array); $x++) {
		if ($introv_values_array[$x] == 2) {
			$introv_values_array[$x] += 2;
		} else {
		}
	}

        $total_values_array = array_merge($introv_values_array, $introe_values_array);
	
  $negative_score = 0;
  $positive_score = 0;

	if (count($total_values_array) == 0) {
					return 10;
	} else {

	for ($x=0; $x < count($total_values_array); $x++) {
		if ($total_values_array[$x] == 1) {
						$negative_score++;
		} elseif ($total_values_array[$x] == 2) {
						$negative_score++;
		} elseif ($total_values_array[$x] == 4) {
						$positive_score++;
		} elseif ($total_values_array[$x] == 5) {
						$positive_score++;
		} 
	}

		if ($negative_score > $positive_score) {
						return 1;
		} elseif ($negative_score < $positive_score) {
						return 2;
		} else {
						return 3;
		}
	}
}

function leave_but_happy() { ?>
        <h3 style="color:white;text-align:center;padding:20px 0px">Ah, It looks like you’re pretty sure you want to <strong style="background-color:#e30164">LEAVE BUT</strong> you still have some strong views which seem to keep you on the fence…</h3>
<?php
}

function leave_and_angry() { ?>
        <h3 style="color:white;text-align:center;padding:20px 0px">Based on how you’ve reacted it looks like you want to <strong style="background-color:#e30164">LEAVE the EU</strong> and feel NEGATIVELY about the UK being part of the EU.</h3>
<?php
}

function stay_but_angry() { ?>
        <h3 style="color:white;text-align:center;padding:20px 0px">Ah, It looks like you’re pretty sure you want to <strong style="background-color:#e30164">STAY BUT</strong> you still have some strong views which seem to keep you on the fence…</h3>
<?php
}

function stay_and_happy() { ?>
        <h3 style="color:white;text-align:center;padding:20px 0px">Based on how you’ve reacted it looks like you want to <strong style="background-color:#e30164">STAY in the EU</strong> and reacted POSITIVELY towards the UK being part of the EU.</h3>
<?php
}

function fifty_fifty() { ?>
        <h3 style="color:white;text-align:center;padding:20px 0px"><strong style="background-color:#e30164">Well, it looks like you’re well and truly on the fence…</strong> People who have gotten the same result find re-doing a topic helps them decide OR e-mailing us any questions you want answering.</h3>
<?php
} 

function stay_fifty() { ?>
        <h3 style="color:white;text-align:center;padding:20px 0px">Your 'think' score suggests that you want to <strong style="background-color:#e30164">STAY</strong>  in the EU HOWEVER you still seem pretty torn about how you 'feel' about the UK being part of the EU...</h3>
<?php
}

function leave_fifty() { ?>
        <h3 style="color:white;text-align:center;padding:20px 0px">Your 'think' score suggests that you want to <strong style="background-color:#e30164">LEAVE</strong> the EU HOWEVER you still seem pretty torn about how you 'feel' about the UK leaving the EU</h3>
<?php
}

function fifty_happy() { ?>
        <h3 style="color:white;text-align:center;padding:20px 0px">
Your 'think’ result wasn’t clearly in favour of voting one particular way <strong style="background-color:#e30164">HOWEVER</strong> your 'feel’  result showed that you felt positively about the UK being part of the EU...</h3>
<?php
}

function fifty_angry() { ?>
        <h3 style="color:white;text-align:center;padding:20px 0px">Your 'think’ result wasn’t clearly in favour of voting one particular way<strong style="background-color:#e30164">HOWEVER</strong> your 'feel’  result showed that you felt negatively about the UK being part of the EU...</h3>
<?php
}

function complete_los() { ?>
        <h3 style="color:white;text-align:center;padding:20px 0px">Please use our homepage topic selector to get started with <strong style="background-color:#e30164">LeaveorStay</strong> and get one step closer to an informed vote.</h3>
<?php
}

function vote_stay() { ?>
        <h3 class="results-sub-title" style="text-align:center;padding:20px 0px 0px">It looks like you’re pretty sure you want to <strong style="background-color:#e30164;color:white">STAY</strong></h3><h5 class="results-sub-title width" style="text-align:center"> Based entirely on the 5 responses that you have given us above, this is where you stand on the EU Referendum today.</h5>
<?php
}
	
function vote_leave() { ?>
        <h3 class="results-sub-title" style="text-align:center;padding:20px 0px 0px">It looks like you’re pretty sure you want to <strong style="background-color:#e30164;color:white">LEAVE</strong></h3><h5 class="results-sub-title width" style="text-align:center"> Based entirely on the 5 responses that you have given us above, this is where you stand on the EU Referendum today.</h5>
<?php
}
	
function on_the_fence() { ?>
        <h3 class="results-sub-title" style="text-align:center;padding:20px 0px"><strong style="background-color:#e30164;color:white">Well, it looks like you’re well and truly on the fence…</strong> use the rest of leaveorstay to learn more about the EU and see if it affects your results</h3>
<?php
}
	
function complete_intro() { ?>
        <h3 class="results-sub-title" style="text-align:center;padding:20px 0px">Please use the 5 intro cards <strong style="background-color:#e30164;color:white">above</strong> to get a result.</h3>
<?php
}

function calculate($result_vote, $result_emote) {
	$vote_r = to_array_votes($result_vote);
	$emote_r = to_array_emotes($result_emote);
	$feel = for_stay_or_leave_emoticons($emote_r);
	$vote = for_stay_or_leave_votes($vote_r);
	mysqli_free_result($result_vote);
	mysqli_free_result($result_emote);
	if (($feel == 1) && ($vote == 1)) {
		leave_and_angry();
		return 1;
	} elseif (($feel == 1) && ($vote == 2)) {
		stay_but_angry();
	        return 2;
	} elseif (($feel == 1) && ($vote == 3)) {
		fifty_angry();
	      return 3;
	} elseif (($feel == 2) && ($vote == 1)) {
		leave_but_happy();
	      return 4;
	} elseif (($feel == 2) && ($vote == 2)) {
		stay_and_happy();
		return 5;
	} elseif (($feel == 2) && ($vote == 3)) {
		fifty_happy();
		return 6;
	} elseif (($feel == 3) && ($vote == 1)) {
		leave_fifty();
		return 7;
	} elseif (($feel == 3) && ($vote == 2)) {
		stay_fifty();
		return 8;
	} elseif (($feel == 3) && ($vote == 3)) {
		fifty_fifty();
		return 9;
	} else {
		 complete_los();
	}
}

function calculate_topics($result_vote, $result_emote) {
	$vote_r = to_array_votes($result_vote); 
	$emote_r = to_array_emotes($result_emote); 
	$feel = for_stay_or_leave_emoticons($emote_r); 
	$vote = for_stay_or_leave_votes($vote_r); 
	if (($feel == 1) && ($vote == 1)) {
					return leave_and_angry();
	} else if (($feel == 1) && ($vote == 2)) {
					return stay_but_angry();
	} else if (($feel == 1) && ($vote == 3)) {
					return fifty_angry(); 
	} else if (($feel == 2) && ($vote == 1)) {
					return leave_but_happy(); 
	} else if (($feel == 2) && ($vote == 2)) {
					return stay_and_happy(); 
	} else if (($feel == 2) && ($vote == 3)) {
					return fifty_happy(); 
	} else if (($feel == 3) && ($vote == 1)) {
					return leave_fifty(); 
	} else if (($feel == 3) && ($vote == 2)) {
					return stay_fifty(); 
	} else if (($feel == 3) && ($vote == 3)) {
					return fifty_fifty(); 
	} else {
				 return complete_los(); 
	} 
}

	
function calculate_intro($result_vote, $result_emote) {
	$vote_r = to_array_votes($result_vote); 
	$emote_r = to_array_emotes($result_emote); 
	$vote = for_stay_or_leave_intro($vote_r, $emote_r); 
	if ($vote == 1) {
		vote_leave(); 
		  return 2;
	} else if ($vote == 2) {
		vote_stay(); 
		  return 1;
	} else if ($vote == 3) {
		on_the_fence(); 
		  return 3;
	} else {
		complete_intro(); 
		return 4;
	}
} 

if (($file == 1) && ($category != 10)) {
	  $results_votes = sql_topic_votes($userID, $category, $connection);
	$results_emotes = sql_topic_emotes($userID, $category, $connection);
	calculate_topics($results_votes, $results_emotes);
} elseif (($file == 1) && ($category == 10)) {
	$intro_emotes = sql_intro_cards_emotes($userID, $connection);
	  $intro_votes = sql_intro_cards_votes($userID, $connection);
	  calculate_intro($intro_votes, $intro_emotes);
} 

?>
