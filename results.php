<?php
require_once('js/functions.php');
if (isset($_GET["emailCookie"]) && $_GET["emailCookie"] == 1) {
	$session_ID = $_GET["session"];
	deleteEmailCookie($session_ID);
}
require_once('anonymous.php');
require_once('Page Functions/sql_for_users.php');
require_once('Page Functions/users.php');
/*
$userID = $_SESSION['login_user'];
*/
?>

<!DOCTYPE html>
<!-- This site was created in Webflow. http://www.webflow.com-->
<!-- Last Published: Fri May 27 2016 22:34:49 GMT+0000 (UTC) -->
<html data-wf-site="572762c72f3e6fea5d0339d6" data-wf-page="572762c72f3e6fea5d0339dc">
<head>
  <meta charset="utf-8">
  <title>Your EU referendum results</title>
  <meta name="description" content="Which way should you vote in the EU referendum? We help you compare both sid
es and offer impartial advice on where you stand so that you can cast an informed vote.">
  <meta property="og:title" content="Results">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="generator" content="Webflow">
  <link rel="stylesheet" type="text/css" href="css/normalize.css">
  <link rel="stylesheet" type="text/css" href="css/webflow.css">
  <link rel="stylesheet" type="text/css" href="css/leaveorstay.webflow.css">
	<script type="text/javascript" src="graphs/canvasjs.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.4.7/webfont.js"></script>
  <script>
    WebFont.load({
      google: {
        families: ["Open Sans:300,300italic,400,400italic,600,600italic,700,700italic,800,800italic","Varela Round:400","Montserrat:400,700","Lato:100,100italic,300,300italic,400,400italic,700,700italic,900,900italic","Raleway:100,200,300,regular,500,600,700,800,900"]
      }
    });
  </script>
  <script type="text/javascript" src="js/modernizr.js"></script>
  <script src="https://use.fontawesome.com/32a1049545.js"></script>
  <link rel="shortcut icon" type="image/x-icon" href="images/logo-favicon.png">
  <link rel="apple-touch-icon" href="https://daks2k3a4ib2z.cloudfront.net/img/webclip.png">
</head>
<body class="body">
<?php include_once("analyticstracking.php") ?>
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
  <div class="hero results">
    <div class="w-container hero-container homepage _1">
      <h1 class="hero-title word">your results are in...</h1>
      <h1 class="hero-title title-3">Based entirely on what you've told us on this site, this is how you seem to currently feel about the EU:</h1>
    </div>
  </div>
  <div class="w-section results">
    <div class="w-container">
      <h1 class="how-to-header topic-result">Here are your <strong class="highlight-word result">results</strong>:</h1>
    </div><a onclick='drawVoteChart(); drawEmoteChart();' class="w-button results-button">Click to reveal&nbsp;</a>
    <div class="w-container">
<?php require_once('results-conclusion.php'); 
$re = sql_endconclusion_emotes($userID, $connection);
$rv = sql_endconclusion_votes($userID, $connection);
$share = calculate($rv, $re);
?>
      <div class="w-row results-row">
        <div class="w-col w-col-6 result-1">
          <h1 class="results-header-1">What you think:</h1>

					<div id="voteGraph"class = "endgraph" >
            <div id="result-vote">
						<?php $results_vote = require_once('graphs/votegraphend.php'); ?>
            </div>
					</div>

        </div>
        <div class="w-col w-col-6 result-2">
          <h1 class="results-header-1">What you feel:</h1>

          <div id="emoteGraph" class = "endgraph">
            <div id="result-emote">
              <?php $results_emot = require_once('graphs/emoticongraphend.php'); ?>
            </div>
          </div>  
        </div>
      </div>
      <?php 
		
    // accessed from users.php and sql_for_users.php in js file.
  	$logged_in = is_user_logged_in($connection, $userID);
  	$email_given = gave_email_on_hp($connection, $userID);
  	
  	if (!$logged_in) {
  		if ($email_given == true) {
  			results_password_and_email();
  			write_email_and_pass_to_db();
  			
  			// dont show email field later
  		} else {
  			results_just_password(); 
  			write_password_to_db();
  		
  		}
  	}
  	?> 
			
    </div>
    <h1 class="hero-title word share sub-title ask-question"><strong class="email-header">Got a question that's still keeping you on the fence?</strong> <br><strong class="email-bold">Email hello@leaveorstay.co.uk</strong> and we'll do our best to help.</h1>
  </div>
  <div class="w-section share-and-save-section">
    <div class="w-container share-and-save">
      <h1 class="hero-title word share">Share the #leaveorstay challenge</h1>
      <h1 class="hero-title word share sub-title">and help your friends and family #bemoresure</h1>

<div>
	<?php 
		require_once("Page Functions/socialShare.php");
		resultsPage($share);
	?>			
</div>

    </div>
  </div>
  <div id="contact" class="w-section footer">
    <div class="w-row about-us">
      <div class="w-col w-col-4 our-pages">
        <h4 class="about-us-heading">Our Pages</h4>
        <div data-collapse="none" data-animation="default" data-duration="400" data-contain="1" class="w-nav footer-nav">
          <div class="w-container">
            <nav role="navigation" class="w-nav-menu"><a href="index.php" class="w-nav-link footer-page">Home</a><a href="about-us.php" class="w-nav-link footer-page">About Us</a><a href="the-black-and-white.php" class="w-nav-link footer-page">The Black &amp; White</a>
            </nav>
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
