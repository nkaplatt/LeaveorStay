<?php
session_start();
require_once('js/functions.php');
require_once("anonymous.php");
mysqli_close($connection);
?>

<!DOCTYPE html>
<!-- This site was created in Webflow. http://www.webflow.com-->
<!-- Last Published: Tue May 24 2016 19:51:09 GMT+0000 (UTC) -->
<html data-wf-site="572762c72f3e6fea5d0339d6" data-wf-page="572762c72f3e6fea5d0339da">
<head>
  <meta charset="utf-8">
  <title>About us</title>
  <meta name="description" content="Which way should you vote in the EU referendum? We help you compare both sides and offer impartial advice on where you stand so that you can cast an informed vote.">
  <meta property="og:title" content="leaveorstay.co.uk">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="generator" content="Webflow">
  <link rel="stylesheet" type="text/css" href="css/normalize.css">
  <link rel="stylesheet" type="text/css" href="css/webflow.css">
  <link rel="stylesheet" type="text/css" href="css/leaveorstay.webflow.css">
  <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.4.7/webfont.js"></script>
  <script>
    WebFont.load({
      google: {
        families: ["Open Sans:300,300italic,400,400italic,600,600italic,700,700italic,800,800italic","Varela Round:400","Montserrat:400,700","Lato:100,100italic,300,300italic,400,400italic,700,700italic,900,900italic","Raleway:100,200,300,regular,500,600,700,800,900"]
      }
    });
  </script>
  <script type="text/javascript" src="js/modernizr.js"></script>
  <link rel="shortcut icon" type="image/x-icon" href="images/logo-favicon.png">
  <link rel="apple-touch-icon" href="https://daks2k3a4ib2z.cloudfront.net/img/webclip.png">
</head>
<body>
  <?php include_once("analyticstracking.php") ?>
  <div data-collapse="none" data-animation="default" data-duration="400" data-contain="1" class="w-nav navbar">
    <div class="w-container"><a href="index.php" class="w-nav-brand logo-container"><h1 class="logo-text"><strong>leave</strong>or<strong>stay</strong>.co.uk</h1></a>
      <?php require_once('login-logout-button.php'); ?>
      <div class="w-nav-button menu">
        <div class="w-icon-nav-menu"></div>
      </div>
    </div>
  </div>
  <div class="w-section page-header about-us-page">
    <div class="page-header-overlay about-us-page">
      <div class="w-container page-header-container centered">
        <h2 data-ix="page-title" class="page-header-title">About us</h2>
        <h2 data-ix="page-title-2" class="page-header-title subtitle">'the most important thing in a democracy is a well informed electorate'</h2>
      </div>
    </div>
  </div>
  <div id="Intro" class="w-section intro _1">
    <h1 class="next-button _1">What is this?</h1>
    <h1 class="intro-heading _1">Leaveorstay is a decision making website for <br><strong class="important-text what">undecided voters.</strong></h1>
    <div class="w-container">
      <div class="w-row what-section">
        <div class="w-col w-col-6 image"><img src="images/immi-01.png" class="image">
        </div>
        <div class="w-col w-col-6">
          <h1 class="homepage-subheader">Open and impartial.</h1>
          <p class="homepage-para">We’re here to give you both sides of the argument; the good the bad and everything in-between so that you can finally say ‘I know which way I want to vote and why’. Our brand would be damaged if we didn't keep things impartial so that's why you'll find us sitting on the fence representing both sides of the debate.</p>
          <h1 class="homepage-subheader">We keep it simple.</h1>
          <p class="homepage-para">We know that viewing conflicting and confusing information from across the web isn't something you want to spend ages on, so let us do the hard work for you. We bring together all the information you need to make an informed vote in one place and provide a really efficient and measurable way of reaching certainty in your decision.</p>
          <h1 class="homepage-subheader">The right answer.</h1>
          <p class="homepage-para">We know that choice is important to you, that’s why you’re comparing. But everyone has different needs and so we’ll help you to 'be more sure' in your decision, whether that’s by letting you pick your own topics, reacting to what you think and feel and creating custom recommendations by getting a 'Digest' delivered to your inbox. We have you covered.</p>
        </div>
      </div>
    </div>
    <p class="intro-paragraph _1">Summary: &nbsp;We're only here to give you <strong class="important-what">honest, impartial advice and answers</strong> in this referendum.</p>
  </div>
  <div id="Intro" class="w-section intro _2">
    <h1 class="next-button _2">How?</h1>
    <h1 class="intro-heading how">Casting an informed vote is now&nbsp;<strong class="important-text">SO much easier.</strong></h1>
    <div class="w-container">
      <div class="w-row">
        <div class="w-col w-col-6">
          <div class="point">
            <h1 class="number">1.</h1>
            <div class="encompass-point">
              <h1 class="point-text">We scan the web.</h1>
              <p class="point-paragraph">Our clever bits of technology trawl the web and find relevant articles, pieces of research and arguments from both sides and perfectly&nbsp;summarises the content into bitesize points.</p>
            </div>
          </div>
          <div class="point">
            <h1 class="number">2.</h1>
            <div class="encompass-point">
              <h1 class="point-text">Add context.</h1>
              <p class="point-paragraph">Our independent editor reviews the summaries and adds referenced, clear data to add context to the debate.</p>
            </div>
          </div>
          <div class="point">
            <h1 class="number">3.</h1>
            <div class="encompass-point">
              <h1 class="point-text">And feelings.</h1>
              <p class="point-paragraph">We then split the content into "thinking" and "feeling" points so that you can be sure to cover both your head and your heart.</p>
            </div>
          </div>
          <div class="point">
            <h1 class="number">4.</h1>
            <div class="encompass-point">
              <h1 class="point-text">Then give an answer.</h1>
              <p class="point-paragraph">Finally we use even more clever computing to calculate where you stand and why, and present you with an overview of your thoughts and feelings.</p>
            </div>
          </div>
        </div>
        <div class="w-col w-col-6 image"><img src="images/White Down Arrow.svg" class="image">
        </div>
      </div>
    </div>
    <p class="intro-paragraph _2">Summary : <strong class="important-text small blue">500 hrs worth</strong> of research that'll take about <strong class="important-text small blue">15 minutes</strong> to complete.
      <br>Above all, you'll feel happier with your choice either way.</p>
  </div>
  <div id="Intro" class="w-section intro _3">
    <h1 class="next-button">Why?</h1>
    <h1 class="intro-heading">We <strong class="important-text dark">don't want mis-information</strong> to rule the day.</h1>
    <p class="intro-paragraph">Only <strong class="important-text small">12%</strong> of voters feel <strong class="important-text small">"informed"</strong> or <strong class="important-text small">"well informed"</strong> about the upcoming referendum... let that sink in. We're about to make a decision that will affect the rest of our lives and those of our children and people we care about based on nothing more than confusing and conflicting information peddled by both sides.
      <br>
      <br>That's the reason we are doing this. We don't care which way you vote, we just want you to go to the polling station armed with credible information that will make you confident in the choice you are about to make, regardless of what that choice may be.</p>
    <p class="intro-paragraph _3">Summary : <span style="font-weight: 800;"><u>We exist solely to make sure that you are happy and more sure about the decision you are about to make.</u></span>
    </p>
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
</body>
</html>
