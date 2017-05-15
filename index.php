<?php
require_once('js/functions.php');
if (isset($_GET["emailCookie"]) && $_GET["emailCookie"] == 1) {
	$session_ID = $_GET["session"];
	emailCookieID($session_ID);
}
require_once('anonymous.php');
require_once('Page Functions/sql_for_users.php');
require_once('Page Functions/users.php');
?>
	<!DOCTYPE html>
	<!-- This site was created in Webflow. http://www.webflow.com-->
	<!-- Last Published: Tue May 24 2016 19:51:09 GMT+0000 (UTC) -->
	<html data-wf-site="572762c72f3e6fea5d0339d6" data-wf-page="57286699d483652b197df473">

	<head>
		<meta charset="utf-8">
		<title>Which way should I vote?</title>
		<meta name="description" content="Which way should you vote in the EU referendum? We help you compare both sides and offer impartial advice on where you stand so that you can cast an informed vote.">
		<meta name="twitter:card" content="leaveorstay">
		<meta name="twitter:site" content="@leaveorstayHQ">
		<meta name="twitter:creator" content="@leaveorstayHQ">
		<meta name="twitter:title" content="Be more sure in your decision.">
		<meta name="twitter:description" content="Want to #bemoresure with where you stand in the EU referendum? Find out today with leaveorstay. #EUref">
		<meta name="twitter:image" content="http://www.leaveorstay.co.uk/images/FB%20share%20image-01.png">
		<meta property="og:url" content="http://www.leaveorstay.co.uk" />
		<meta property="og:type" content="website" />
		<meta property="og:title" content="Home" />
		<meta property="og:description" content="Which way should you vote in the EU referendum? We help you compare both sides and offer impartial advice on where you stand so that you can cast an informed vote." />
		<meta property="og:image" content="http://www.leaveorstay.co.uk/images/FB%20share%20image-01.png" />
		<meta property="fb:admins" content="1635365006" />
		<meta property="fb:app_id" content="1383228345026326" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="generator" content="Webflow">
		<link rel="stylesheet" type="text/css" href="css/normalize.css">
		<link rel="stylesheet" type="text/css" href="css/webflow.css">
		<link rel="stylesheet" type="text/css" href="css/leaveorstay.webflow.css">
		<script src="https://ajax.googleapis.com/ajax/libs/webfont/1.4.7/webfont.js"></script>
		<script type="text/javascript" src="graphs/canvasjs.min.js"></script>
		<script>
			WebFont.load({
				google: {
					families: ["Open Sans:300,300italic,400,400italic,600,600italic,700,700italic,800,800italic", "Varela Round:400", "Montserrat:400,700", "Lato:100,100italic,300,300italic,400,400italic,700,700italic,900,900italic", "Raleway:100,200,300,regular,500,600,700,800,900"]
				}
			});
		</script>
		<script type="text/javascript" src="js/modernizr.js"></script>
		<link rel="shortcut icon" type="image/x-icon" href="images/logo-favicon.png">
		<link rel="apple-touch-icon" href="https://daks2k3a4ib2z.cloudfront.net/img/webclip.png">
		<?php
  $trade_clicked = select_topic_for_user($connection, $userID, 'Trade');
  $Imo_clicked = select_topic_for_user($connection, $userID, 'Imo');
  $SovandLaw_clicked = select_topic_for_user($connection, $userID, 'SovandLaw');
  $Jobs_clicked = select_topic_for_user($connection, $userID, 'Jobs');
  $DefenceandSecurity_clicked = select_topic_for_user($connection, $userID, 'DefenceandSecurity');
  $all_clicked = select_topic_for_user($connection, $userID, 'Allchosen');

  //connection gained from anonymous.php
//Test success
connectQuery();
$category = 10;
//Queries to get Emoticon_Number
$query = 'SELECT Emoticon_Number FROM Card_tbl ';
$query .= "WHERE MUser_ID = '{$userID}' AND ";
$query .= "Category_ID = {$category};";
$result1 = mysqli_query($connection, $query); //gets emoticon numbers
//Queries to get Emoticon_Type
$query = 'SELECT Emoticon_Type FROM Card_tbl ';
$query .= "WHERE MUser_ID = '{$userID}' AND ";
$query .= "Category_ID = {$category};";
$result2 = mysqli_query($connection, $query);    //gets emoticon selected
$emotes_type = array();
while($value = mysqli_fetch_array($result2))
{
  $etype = $value['Emoticon_Type'];
  if($etype < 1 || $etype > 5)
  continue;
  switch($etype-1){
    case 0:
    $emoticon = "anger-";
    break;
    case 1:
    $emoticon = "shock-";
    break;
    case 2:
    $emoticon = "indifferent-";
    break;
    case 3:
    $emoticon = "happy-";
    break;
    case 4:
    $emoticon =  "delighted-";
    break;
  }
  array_push($emotes_type, $emoticon);
}
//This while loop gets all the types of emoticons submitted
$emotes_nums = array();
while($value = mysqli_fetch_array($result1))
{
  $enum = $value['Emoticon_Number'];
  array_push($emotes_nums, $enum);
}
//Returns all the emoticons as strings, e.g. happy-1
$all_emotes = array();
for($i=0; $i<count($emotes_nums); $i++){
  $a = trim($emotes_type[$i]);
  $a .= trim($emotes_nums[$i]);
  array_push($all_emotes, $a);
}
//we have to arrays, emoticon_type and emoticon_nums
mysqli_free_result($result1);
mysqli_free_result($result2);

  ?>
			<script type="text/javascript">
				var TradeClicked = <?php echo json_encode($trade_clicked); ?>;
				var ImoClicked = <?php echo json_encode($Imo_clicked); ?>;
				var SovandLawClicked = <?php echo json_encode($SovandLaw_clicked); ?>;
				var JobsClicked = <?php echo json_encode($Jobs_clicked); ?>;
				var DefenceandSecurityClicked = <?php echo json_encode($DefenceandSecurity_clicked); ?>;
				var AllClicked = <?php echo json_encode($all_clicked); ?>;
				var totalTime = 0;
				var AllTheTime = 0;
				var NumberOfTopics = 5;

				function update_server_data(type, num, category) {
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.open("POST", "js/emotesDB.php", true);
					xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
					xmlhttp.send("q=" + type + "&p=" + num + "&k=" + category);
				}

				function update_leave_data(type, num, category) {
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.open("POST", "js/opinionDB.php", true);
					xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
					xmlhttp.send("q=" + type + "&p=" + num + "&k=" + category);
				}

				function refresh_page() {
					location.reload();
				}

				function update_topic_data(type) {
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.open("POST", "newfu4.php", true);
					var colour = document.getElementsByClassName(type)[0].style.backgroundColor;
					if ((colour == '') || (colour == 'transparent')) {
						document.getElementsByClassName(type)[0].style.backgroundColor = "#7c1";
						xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
						xmlhttp.send("q=" + type + "&k=" + "on");
						totalTime += 5;
						if (type == "icon-button-6") {
							AllTheTime = 1;
						}
					}
					else {
						document.getElementsByClassName(type)[0].style.backgroundColor = "transparent";
						xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
						xmlhttp.send("q=" + type + "&k=" + "off");
						totalTime -= 5;
						if (type == "icon-button-6") {
							AllTheTime = 0;
						}
					}
					if (AllTheTime == 1) {
						document.getElementById("MyEdit").innerHTML = NumberOfTopics * 5;
					}
					else {
						document.getElementById("MyEdit").innerHTML = totalTime + " ";
					}
				}

				function page_redirection(type) {
					//redirects to the redirection page
					document.location.href = "redirect.php";
				}
				window.onload = function () {
					var clicked = [SovandLawClicked, JobsClicked, DefenceandSecurityClicked, ImoClicked, TradeClicked, AllClicked];
					var buttons = ["icon-button", "icon-button-2", "icon-button-3", "icon-button-4", "icon-button-5", "icon-button-6"];
					//update_server_data(type);
					for (var i = 0; i < clicked.length; i++) {
						if (clicked[i] == 1) {
							document.getElementsByClassName(buttons[i])[0].style.backgroundColor = "#7c1";
							totalTime += 5;
						}
						else if (clicked[i] == 2) {
							document.getElementsByClassName(buttons[i])[0].style.backgroundColor = "#e31064";
							totalTime += 5;
						}
					}
					if (clicked[5] == 1 || clicked[5] == 2) { //If Number of topics changed, change 5 to be index of
						AllTheTime = 1;
					}
					if (AllTheTime == 1) {
						document.getElementById("MyEdit").innerHTML = NumberOfTopics * 5;
					}
					else {
						document.getElementById("MyEdit").innerHTML = totalTime + " ";
					}
					var options = ["icon-button", "icon-button-2", "icon-button-3", "icon-button-4", "icon-button-5", "icon-button-6"];
					for (var j = 0; j < 6; j++) {
						var option = options[j];
						var emotes_array = document.getElementsByClassName(option);
						if (emotes_array.length > 0) {
							emotes_array[0].onclick = function (type) {
								update_topic_data(type);
							}.bind(undefined, options[j]);
						}
					}
					var get_started_button = document.getElementsByClassName('topic-continue')
					get_started_button[0].onclick = function (type) {
						page_redirection();
					}.bind(undefined, 1);
					//Works out which category the file is
					var category = 10;
					var emoticons = ["anger", "shock", "indifferent", "happy", "delighted"];
					num = 100;
					for (var i = 0; i < num; i++) {
						for (var j = 0; j < 5; j++) {
							var string = emoticons[j] + "-" + i;
							if (i == 0) {
								string = emoticons[j];
							}
							var emotes_array = document.getElementsByClassName(string);
							if (emotes_array.length > 0) {
								emotes_array[0].onclick = function (type, num) {
									update_server_data(type, num, category);
								}.bind(undefined, emoticons[j], i);
							}
						}
					}
					var options = ["leave", "stay", "neither"];
					num = 10;
					for (var k = 0; k < num; k++) {
						for (var m = 0; m < 3; m++) {
							var string = "think-" + options[m] + "-" + k;
							if (k == 0) {
								string = "think-" + options[m];
							}
							var options_array = document.getElementsByClassName(string);
							if (options_array.length > 0) {
								options_array[0].onclick = function (type, num) {
									update_leave_data(type, num, category);
								}.bind(undefined, options[m], k)
							}
						}
					}
					var length = <?php echo json_encode(count($all_emotes)); ?>;
					for (var z = 0; z < length; z++) {
						var my_var = <?php echo json_encode($all_emotes); ?>;
						var el_array = document.getElementsByClassName(my_var[z]);
						if (el_array.length > 0) {
							el_array[0].click();
						}
					}
				};
			</script>
	</head>

	<body class="body">
		<?php include_once("analyticstracking.php") ?>
			<div class="w-section privacy-policy">
				<div class="w-container"><a href="#" data-ix="close-privacy-policy" class="w-button close-policy">X</a>
					<div class="policy">We use cookies to give you the best online experience. By using our website you agree to our use of cookies in accordance with our cookie policy.&nbsp;<a class="policy-link" href="privacy-policy.php">Learn more here.</a> </div>
				</div>
			</div>
			<div class="hero homepage">
				<div data-collapse="none" data-animation="default" data-duration="400" data-contain="1" class="w-nav navbar">
					<div class="w-container"><a href="index.php" class="w-nav-brand logo-container"><h1 class="logo-text"><strong>leave</strong>or<strong>stay</strong>.co.uk</h1></a>
						<?php
        require_once('login-logout-button.php');
        ?>
							<div class="w-nav-button menu">
								<div class="w-icon-nav-menu"></div>
							</div>
					</div>
				</div>
				<div class="w-container hero-container homepage _1">
					<h1 class="hero-title word">Be more sure.</h1>
					<h1 class="hero-title title-3">Relax. Still not sure which way to vote? We've got you.<br>Compare both sides of the debate based on issues that matter to you and make an informed decision about which way to vote at the EU Referendum on Thursday June 23rd 2016.</h1> </div>
			</div>
			<div class="divider pink">
				<div class="next-button divider-text">
					<h2 class="h1">See where you really stand</h2>
					<div class="t1">the result may surprise you!</div>
				</div><img width="80" height="30" src="images/pink arrow.png" class="triangle"> </div>
			<div class="w-section drop-down-topic-selecter">
				<div class="w-container topic-container">
					<div style="text-align:left">
						<h1 class="next-button small">Let's get started...</h1> </div>
					<h2 class="topic-selector-text-2">Which topic(s) do you want to explore?</h2>
					<div class="hide-on-mobile"> </div>
					<div class="w-row topic-row">
						<div class="w-col w-col-4 topic-colum">
							<a href="#" class="w-button icon-button"></a>
							<div class="icon-name">Law</div>
						</div>
						<div class="w-col w-col-4 topic-colum">
							<a href="#" class="w-button icon-button-2"></a>
							<div class="icon-name">Jobs</div>
						</div>
						<div class="w-col w-col-4 topic-colum">
							<a href="#" class="w-button icon-button-3"></a>
							<div class="icon-name">Defence</div>
						</div>
					</div>
					<div class="w-row topic-row">
						<div class="w-col w-col-4 topic-colum">
							<a href="#" class="w-button icon-button-4"></a>
							<div class="icon-name immigration">Immigration</div>
						</div>
						<div class="w-col w-col-4 topic-colum">
							<a href="#" class="w-button icon-button-5"></a>
							<div class="icon-name">Trade</div>
						</div>
						<div class="w-col w-col-4 topic-colum">
							<a href="#" class="w-button icon-button-6"></a>
							<div class="icon-name">All</div>
						</div>
					</div>
					<?php
        if ($logged_in):
        elseif (gave_email_on_hp($connection, $userID) == true):
          email_hp();
          write_email_to_db();
        endif;
        ?>
						<!-- this is where the get started and email go -->
						<!--get_started() included in email_hp(). DON'T TRY TO SIMPLIFY -->
						<!-- NICK IS DA BEST AWW MARK YOU'RE THE BEST :P --><a style="margin:20px auto 0px" name="topic-continue" class="w-button topic-continue">Get started</a>
						<h5 style="width:80%; margin:20px auto">This will take you approximately: &nbsp <strong id="MyEdit"> 0 &nbsp </strong> minutes</h5> </div>
			</div>
			<!-- use user.php to allow correct version of homepage dependant on the users requirement. -->
			<?php
  $logged_in = is_user_logged_in($connection, $userID);
  $done_intro = done_intro_cards($connection, $userID);

  if (!$logged_in) {
    if ($done_intro == false) {
      introCards_hp();
      // dont show email field later
    }
  } else {
    // show email field later
    if ($done_intro == false) {
      introCards_hp();
    }
  }
  ?>
				<div class="divider pink">
					<div class="next-button divider-text">
						<h2 class="h1">Scroll down for your results...</h2> </div><img width="80" height="30" src="images/pink arrow.png" class="triangle"> </div>
				<div id="Results-HP" class="started-block">
					<div class="w-container hero-container homepage">
						<div class="results-card-hp">
							<h2 class="hero-title word hp">Your results:</h2>
							<div id="conclusion" style="width:80%;margin:0px auto"></div> <a onclick="drawIndexChart();conclusion_topic();this.style.visibility='hidden';" class="w-button results-button">Click to reveal&nbsp;</a>
							<?php 
	require_once('graphs/resultsForPages.php'); 
?>
								<div style="margin:10px auto 40pxwidth:400px;height:400px">
									<div id="intro-results" style="margin:0px auto">
										<?php require_once('graphs/intrograph.php'); ?>
									</div>
								</div>
								<div>
									<h2 class="homepage-share">Help spread the word</h2>
									<h6 style="margin:0px auto 30px;width:90%">Share leaveorstay with friends, family and colleagues so that they can #Bemoresure about the decision they are about to make</h6>
									<?php 
	  require_once("Page Functions/socialShare.php");
	shareText();
	  ?>
								</div>
						</div>
					</div>
					<! -- share and email used to be here -->
					<div class="w-section share-and-save-section _2">
						<div class="w-container share-and-save">
							<div class="w-row">
								<div class="w-col w-col-6 save home">
									<h2 class="save-and-share-header">Remind me about this</h2>
									<p class="save-and-share-body">We'll send you an email a couple of weeks before the referendum to remind you about the site AND on June 23rd to remind you to vote. No spam. Nothing else.
										<?php
          if(($_SERVER["REQUEST_METHOD"] == "POST") && isset( $_POST['email-hp'])) {
            //1. Create database connection
              // Test if connection occured
              if (mysqli_connect_errno()) {
                die("Database connection failed: " .
                  mysqli_connect_error() .
                  " (" . mysqli_connect_errno() . ")"  );
              }
              $email = mysqli_real_escape_string($connection,$_POST['email-hp']);
              $sql = "INSERT INTO Mailing_list (Email_Address) VALUES ('{$email}');";
              $result = mysqli_query($connection, $sql);
              ?>
											<h4 style="color:white">Your submission has been recieved.</h4>
											<?php
                                       }
        ?>
												<div style="text-align:center" id="mailing">
													<div class="emailform cf">
														<form method="post" id="email-hp" name="email-hp" class="form-ts">
															<input style="margin-top:5px;width:300px" type="email" placeholder="enter your email" name="email-hp" class="box" required/>
															<br/>
															<br />
															<input type="submit" href="#mailing" class="submit-email-button" style="font-size:18px;margin-top:5px" value="Submit email" /> </form>
													</div>
												</div>
								</div>
								<div class="w-col w-col-6 share home">
									<h2 class="save-and-share-header">Spread the word</h2>
									<p class="save-and-share-body">Help your family, friends and colleagues make an informed choice about which way to vote in the European Referendum 2016.</p>
									<iframe src="https://www.facebook.com/plugins/share_button.php?href=http%3A%2F%2Fwww.leaveorstay.co.uk%2F&layout=button_count&mobile_iframe=true&width=89&height=20&appId" width="89" height="20" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true"></iframe>
								</div>
							</div>
						</div>
					</div>
					<!-- has now moved further up page -->
					<div id="contact" class="w-section footer">
						<div class="w-row about-us">
							<div class="w-col w-col-4 our-pages">
								<h4 class="about-us-heading">Our Pages</h4>
								<div data-collapse="none" data-animation="default" data-duration="400" data-contain="1" class="w-nav footer-nav">
									<div class="w-container">
										<nav role="navigation" class="w-nav-menu"> <a href="index.php" class="w-nav-link footer-page">Home</a> <a href="about-us.php" class="w-nav-link footer-page">About Us</a><a href="the-black-and-white.php" class="w-nav-link footer-page">The Black &amp; White</a> </nav>
										<div class="w-nav-button">
											<div class="w-icon-nav-menu"></div>
										</div>
									</div>
								</div>
							</div>
							<div class="w-col w-col-4 about-us-block">
								<h4 class="about-us-heading">About Us</h4>
								<p>Designed and built at the University of Exeter
									<br>
									<br>Innovation Centre
									<br>Rennes Drive
									<br>EX4 4RN</p>
							</div>
							<div class="w-col w-col-4 get-in-touch">
								<h4 class="about-us-heading">Get in touch</h4>
								<p>Want to say hi? &nbsp; hello@leaveorstay.co.uk</p>
								<div class="make-twitter-central">
									<div class="w-widget w-widget-twitter twitter">
										<iframe src="https://platform.twitter.com/widgets/follow_button.html#screen_name=leaveorstayHQ&amp;show_count=false&amp;size=m&amp;show_screen_name=true&amp;dnt=true" scrolling="no" frameborder="0" allowtransparency="true" style="border: none; overflow: hidden; width: 100%; height: 21px;"></iframe>
									</div>
								</div>
								<div class="w-widget w-widget-facebook facebook">
									<iframe src="https://www.facebook.com/plugins/like.php?href=http%3A%2F%2Ffacebook.com%2FleaveorstayHQ&amp;layout=box_count&amp;locale=en_US&amp;action=like&amp;show_faces=false&amp;share=false" scrolling="no" frameborder="0" allowtransparency="true" style="border: none; overflow: hidden; width: 55px; height: 65px;"></iframe>
								</div>
							</div>
						</div>
					</div>
					<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
					<script type="text/javascript" src="js/webflow.js"></script>
					<!--[if lte IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/placeholders/3.0.2/placeholders.min.js"></script><![endif]-->
					<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-57667ae2f9bb9613"></script>
	</body>

	</html>
	<?php  mysqli_close($connection); ?>