<?php
require_once('js/functions.php');
require_once("anonymous.php");
?>
	<!DOCTYPE html>
	<!-- Last Published: Sun May 01 2016 23:29:55 GMT+0000 (UTC) -->
	<html data-wf-site="56fd24aaaea6500c763220cf" data-wf-page="57248b879134bc281e3f2548">

	<head>
		<meta charset="utf-8">
		<title>Login</title>
		<meta name="description" content="Which way should you vote in the EU referendum? We help you compare both sides and offer impartial advice on where you stand so that you can cast an informed vote.">
		<meta property="og:title" content="Signup">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" type="text/css" href="css/leaveorstay.webflow.css">
		<link rel="stylesheet" type="text/css" href="css/normalize.css">
		<link rel="stylesheet" type="text/css" href="css/webflow.css">
		<link rel="stylesheet" type="text/css" href="css/los-template.webflow.css">
		<script src="https://ajax.googleapis.com/ajax/libs/webfont/1.4.7/webfont.js"></script>
		<script>
			WebFont.load({
				google: {
					families: ["Open Sans:300,300italic,400,400italic,600,600italic,700,700italic,800,800italic", "Varela Round:400", "Ubuntu:300,300italic,400,400italic,500,500italic,700,700italic", "Lato:100,100italic,300,300italic,400,400italic,700,700italic,900,900italic", "Montserrat:400,700", "Raleway:100,200,300,regular,500,600,700,800,900"]
				}
			});
		</script>
		<script type="text/javascript" src="js/modernizr.js"></script>
		<link rel="shortcut icon" type="image/x-icon" href="images/logo-favicon.png">
		<link rel="apple-touch-icon" href="https://daks2k3a4ib2z.cloudfront.net/img/webclip.png">
		<script type="text/javascript">
			var _gaq = _gaq || [];
			_gaq.push(['_setAccount', 'UA-76346760-1'], ['_trackPageview']);
			(function () {
				var ga = document.createElement('script');
				ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
				var s = document.getElementsByTagName('script')[0];
				s.parentNode.insertBefore(ga, s);
			})();
		</script>
	</head>

	<body class="body">
		<?php include_once("analyticstracking.php") ?>
			<div data-collapse="medium" data-animation="default" data-duration="400" data-contain="1" class="w-nav navbar">
				<div class="w-container">
					<a href="index.php" class="w-nav-brand logo-container">
						<h1 class="logo-text"><strong>leave</strong>or<strong>stay</strong>.co.uk</h1> </a>
				</div>
			</div>
			<!-- header -->
			<div class="w-section hero login">
				<div class="w-container hero-container signup">
					<h1 class="hero-title title-2">Enter your details to log in.</h1>
					<!-- login form built from scratch -->
					<?php
	if($_SERVER["REQUEST_METHOD"] == "POST") {
		//1. Create database connection
  		// Test if connection occured
  		if (mysqli_connect_errno()) {
    		die("Database connection failed: " .
    	    mysqli_connect_error() .
    	    " (" . mysqli_connect_errno() . ")"  );
  		}

  		$email = mysqli_real_escape_string($connection,$_POST['email']);
    	$mypassword = mysqli_real_escape_string($connection,$_POST['password']);
        $passnonhash = $mypassword . "EU";
        $passhashed = hash('sha256', $passnonhash);


    	$sql = "SELECT MUser_ID FROM User_tbl ";
    	$sql .= "WHERE Email_Address = '$email' and ";
    	$sql .= "UPassword = '$passhashed';";
    	$result = mysqli_query($connection, $sql);

    	if(mysqli_fetch_assoc($result) != null) {
    		//session_register("myemail");
	  		$email_noSalt = $email;
	  		$email = $email . 'EU';
	  		$Session_ID = hash('sha256', $email);
    	  $_SESSION['login_user'] = $Session_ID;
        emailCookieID($Session_ID);
				set_to_logged_in($connection, $Session_ID);
          mysqli_close($connection);
    	    header("location: index.php");
		} else { ?>
						<h6 class="login-divider">Your email address or password is invalid</h6>
						<?php
    	}
  } ?>
							<section class="loginform cf">
								<div align="center">
									<form name="login" action="" method="post">
										<input type="email" placeholder="enter your email" name="email" class="box" required/>
										<br />
										<br />
										<input type="password" placeholder="enter your password" name="password" class="box" required/>
										<br/>
										<br />
										<input type="submit" value="LOGIN" />
										<br />
										<br>
										<br> </form>
									<div style="font-size:11px; color:#cc0000; margin-top:10px">
										<?php if (isset($error)) {echo $error;} ?>
									</div>
								</div>
							</section>
							<!-- -->
				</div>
			</div>
			<!-- all below is the footer -->
			<div id="contact" class="w-section footer">
				<div class="w-row about-us">
					<div class="w-col w-col-4 our-pages">
						<h4 class="about-us-heading">Our Pages</h4>
						<div data-collapse="none" data-animation="default" data-duration="400" data-contain="1" class="w-nav footer-nav">
							<div class="w-container">
								<nav role="navigation" class="w-nav-menu"> <a href="index.php" class="w-nav-link footer-page">Home</a> <a href="about-us.php" class="w-nav-link footer-page">About Us</a> <a href="the-black-and-white.php" class="w-nav-link footer-page">The Black &amp; White</a> </nav>
								<div class="w-nav-button">
									<div class="w-icon-nav-menu"></div>
								</div>
							</div>
						</div>
					</div>
					<div class="w-col w-col-4 about-us-block">
						<h4 class="about-us-heading">About Us</h4>
						<p>Designed with love at Exeter University
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
	</body>

	</html>