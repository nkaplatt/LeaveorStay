<?php
  session_start();
  require_once('js/functions.php');
  require_once('anonymous.php');
?>

<!DOCTYPE html>
<!-- This site was created in Webflow. http://www.webflow.com-->
<!-- Last Published: Tue May 24 2016 19:51:09 GMT+0000 (UTC) -->
<html data-wf-site="572762c72f3e6fea5d0339d6" data-wf-page="573525fd3eadd2ff25f17784">
<head>
  <meta charset="utf-8">
  <title>Trade and the EU referendum</title>
  <meta name="description" content="See where you stand on trade and the EU referendum. Compare both sides of the argument so that you can be sure that you’ve cast an informed vote.">

  <meta property="og:url"           content="http://www.leaveorstay.co.uk/trade.php" />
  <meta property="og:type"          content="website" />
  <meta property="og:title"         content="Trade" />
  <meta property="og:description"   content="See where you stand on trade and the EU referendum. Compare both sides of the argument so that you can be sure that you’ve cast an informed vote." />
  <meta property="og:image"         content="http://www.leaveorstay.co.uk/images/FBtrade-02.png" />
  <meta property="fb:admins" content="1635365006" />
  <meta property="fb:app_id" content="1383228345026326" />
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
  <script type="text/javascript" src="js/jquery-2.2.4.min.js"></script>
  <script type="text/javascript" src="js/stick.js"></script>
  <script type="text/javascript" src="graphs/canvasjs.min.js"></script>

  <?php
require_once('analyticstracking.php');
//Test success
connectQuery();
$category = 1;
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
  function update_server_data(type, num, category){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.open("POST", "js/emotesDB.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("q=" + type + "&p=" + num + "&k=" + category);
  }
  function update_leave_data(type, num, category){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.open("POST", "js/opinionDB.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("q=" + type + "&p=" + num + "&k=" + category);
  }
  function refresh_page(){
    location.reload();
  }
  window.onload=function(){
  //Works out which category the file is
  var category = 1;
  var emoticons = ["anger", "shock", "indifferent", "happy", "delighted"];
    num = 100;
    for(var i=0; i<num; i++)
    {
      for(var j=0; j<5; j++)
       {
       var string = emoticons[j] + "-" + i;
       if(i == 0){
          string = emoticons[j];
       }
       var emotes_array = document.getElementsByClassName(string);
       if(emotes_array.length > 0){
          emotes_array[0].onclick = function(type, num){
          update_server_data(type, num, category);
       }.bind(undefined, emoticons[j], i);
     }
   }
 }
  var options = ["leave", "stay", "neither"];
  num = 10;
  for(var k=0; k<num; k++)
    {
    for(var m=0; m<3; m++)
      {
      var string = "think-" + options[m] +"-"+ k;
      if(k==0)
        {
        string = "think-" + options[m];
      }
      var options_array = document.getElementsByClassName(string);
      if(options_array.length > 0)
        {
        options_array[0].onclick = function(type, num){
          update_leave_data(type, num, category);
        }.bind(undefined, options[m], k)
      }
    }
  }
  var length = <?php echo json_encode(count($all_emotes)); ?>;
  for(var z=0; z<length; z++){
    var my_var = <?php echo json_encode($all_emotes); ?>;
    var el_array = document.getElementsByClassName(my_var[z]);
      if(el_array.length > 0) {
        el_array[0].click();
      }
    }
};
</script>


  <link rel="shortcut icon" type="image/x-icon" href="images/logo-favicon.png">
  <link rel="apple-touch-icon" href="https://daks2k3a4ib2z.cloudfront.net/img/webclip.png">
</head>
<body class="body topics">
<?php include_once("analyticstracking.php") ?>
  <div class="w-section hero">
    <div data-collapse="none" data-animation="default" data-duration="400" data-contain="1" class="w-nav navbar">
      <div class="w-container"><a href="index.php" class="w-nav-brand logo-container"><h1 class="logo-text"><strong>leave</strong>or<strong>stay</strong>.co.uk</h1></a>

        <?php require_once('login-logout-button.php'); ?>

        <div class="w-nav-button menu">
          <div class="w-icon-nav-menu"></div>
        </div>
      </div>
    </div>
    <div class="hero-overlay topic-page">
      <div data-collapse="none" data-animation="default" data-duration="400" data-contain="1" data-ix="stick-to-top" class="w-nav progress-navbar">
        <div class="w-container progress-container">
          <div class="what-page">\Trade</div>
          
          <div class="w-nav-button">
            <div class="w-icon-nav-menu"></div>
          </div>
        </div>
      </div>
      <div class="w-container hero-container immigration"><img src="images/trade-01.png" class="hero-image">
        <h1 class="hero-title word">Trade</h1>
        <h1 class="hero-title">Questions covered include:</h1>
        <h1 class="hero-title title-2">Who does the UK trade with?<br>How important is the EU to us?<br>Can we negotiate our own trade deals?</h1>
      </div>
    </div>
  </div>
  <div class="w-section page-progress" id="sticky">
    <div class="w-row progress-columns">
      <div class="w-col w-col-3 part-1" id="1">
        <h1 class="progress _1" >1. Topic overview</h1>
      </div>
      <div class="w-col w-col-3 part-2" id="2">
        <h1 class="progress _2">2. What you think</h1>
      </div>
      <div class="w-col w-col-3 part-3" id="3">
        <h1 class="progress _3">3. What you feel</h1>
      </div>
      <div class="w-col w-col-3 part-4" id="4">
        <h1 class="progress _4">4. Topic results</h1>
      </div>
    </div>
  </div>
  <div id="Intro--start" class="w-section intro-panel">
      <h1 class="next-button">Step 1: An overview </h1>
    <div class="w-container">
      <h1 class="how-to-header">Get a sense of what's currently happening by reading the <strong class="highlight-word">overview...</strong></h1>
    </div>
    <div class="w-container overview-section">
      <h1 class="grid-header">Overview:</h1>
      <p class="overview-subtitle">The UK is a maritime nation, coupled with being low in many natural resources, it has depended on trade for centuries as its main form of revenue. The UK joined the EEC in 1973 before it became the EU in a bid to open up new markets for UK trade and help boost the economy. The UK exports globally and being a member of the EU has its drawbacks and advantages for the UK and its trading efforts. Out of all the topics, this one has the largest potential impact (economically at least) on what the UK will look like in the future.
        <br>
        <br>Below are the 3 main headline stats you'll need to know. Keep scrolling to continue.</p>
    </div>
    <div class="w-row overview-row">
      <div class="w-col w-col-4 column-1">
        <div class="overview-card-1">
          <div class="sticky-footer">
            <h3 class="card-header-1">How much do we export to the EU?</h3>
            <h1 class="headline-fact-long">44% of our exports go to the EU.</h1>
            <p class="p1">The EU is by far the UKs single trading partner (the next biggest is the US at 17%) accounting for over £229 billion in exports in 2015 alone. However the level of exports to the EU is falling, down 6% from 2008 when the EU accounted for just over 50% of all UK exports.</p>
            <div class="evidence"><a target="_blank" href="https://fullfact.org/economy/do-half-uks-exports-go-europe/">Where did we get this from?</a>
            </div>
            <div class="fill-empty-space"></div>
          </div>
        </div>
      </div>
      <div class="w-col w-col-4 column-2">
        <div class="overview-card-2">
          <div class="sticky-footer">
            <h3 class="card-header-2">How much of our trade goes to countries outside of the EU?</h3>
            <h1 class="headline-fact-long">EU = 44%<br>USA= 17%<br>Brazil, Russia, India, China and South Africa (BRICS)= 8%<br>Other= 30%</h1>
            <p class="p2">In 2014, British companies sold around £515 billion worth of goods and services to foreign buyers, according to the Office for National Statistics. While the EU remains our largest market by some margin, over time we’re selling less to the EU and more to other economies as a proportion of total trade.</p>
            <div class="evidence"><a target="_blank" href="https://fullfact.org/europe/ask-full-fact-uks-trade-eu/">Where did we get this from?</a>
            </div>
            <div class="fill-empty-space"></div>
          </div>
        </div>
      </div>
      <div class="w-col w-col-4 column-3">
        <div class="overview-card-3">
          <div class="sticky-footer">
            <h1 class="card-heading-3">Does the EU trade with China?</h1>
            <h1 class="headline-fact-long">Yes.<br>Via the EU-China trade deal.</h1>
            <p class="p3">The European Union and China are two of the biggest traders in the world. China is now the EU's 2nd trading partner behind the United States and the EU is China's biggest trading partner.</p>
            <div class="evidence"><a target="_blank" href="http://ec.europa.eu/trade/policy/countries-and-regions/countries/china/index_en.htm">Where did we get this from?</a>
            </div>
            <div class="fill-empty-space"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="Slider" class="w-section tb-slider-section">
<div style='text-align:left'>
    <h1 class="next-button" >Step 2: What do you think? </h1>
    </div>    <div class="w-container">
      <h1 class="how-to-header">Let us know what you <strong class="highlight-word">think</strong> about trade and the EU by reading the following 3 cards and seeing which way you lean most:</h1>
    </div>
    <div data-ix="first-think-card" class="w-container think-container">
      <div class="key-point-1">
        <div class="card-progress">
          <div class="card-progress">Card 1/3</div>
        </div>
        <h1 class="card-1-header">How much trade does the EU generate for the UK?</h1>
        <p class="card-text"><strong>Popular argument:</strong> 'The EU is the UKs single largest export market taking 44% of our exports.'</p>
      </div>
      <div class="think-background-div">
        <div class="slider-overview">
          <h1 class="overview-heading">Overview&nbsp;</h1>
          <p class="basics-paragraph">The EU is currently the UKs single largest exporter taking 44% of all UK exports in 2014. The single market , which has over 500 million consumers as part of it, is deemed 'vital' to the UK economy.
            <br>
            <br>UK exports to the EU have declined with 55% of our exports going to the EU in 2008 compared to 44% in 2014, a statistically significant drop. However this is in part due to the world economy growing, increasing the number of exports the UK makes which in turn makes the 'relative' portion of each trade partner smaller.</p>
        </div>
      </div>
      <div class="w-row argument-row">
        <div class="w-col w-col-6 overview-1">
          <div class="think-background stay">
            <div class="sticky-footer">
              <h1 class="stay-heading">Impact if we stay</h1>
              <p class="basics-paragraph"><span style="font-size: 18px;"><strong class="impact-answer-stay">Minimal.</strong></span>
                <br>
                <br>- The UK will continue to trade with the EU and benefit from access to the single market.
                <br>
                <br>- The EU is currently negotiating a series of trade agreements with countries like the US (known as TTIP) and China so the UK would be part of that as well.</p>
              <div class="fill-empty-space"></div>
            </div>
          </div>
        </div>
        <div class="w-col w-col-6 leave-1">
          <div class="think-background leave">
            <div class="sticky-footer _2">
              <h1 class="leave-heading">Impact if we leave</h1>
              <p class="basics-paragraph"><strong class="impact-answer-leave">Unknown.</strong>
                <br>
                <br>-There would be a short to medium term 'shock' to the economy as the UK adjusted its position.
                <br>
                <br>- There are a multitude of different models on the table when it comes to trade, none of which offer the same terms that the current UK/EU deal have.
                <br>
                <br>- No one knows under which terms trade will be struck so we (leaveorstay) aren't going to speculate under which terms the UK will trade under.</p>
              <div class="fill-empty-space"></div>
            </div>
          </div>
        </div>
      </div>
      <div class="think-choice">
        <h1 class="which-way">Does the current EU/UK trade deal make you lean a particular way?</h1><a href="#Slider" data-ix="show-next-think-card" class="w-button think-leave">Yes,&nbsp;Vote Leave&nbsp;</a><a href="#Slider" data-ix="show-next-think-card" class="w-button think-neither">Not sure</a><a href="#Slider" data-ix="show-next-think-card" class="w-button think-stay">Yes,&nbsp;Vote Stay</a>
      </div>
    </div>
    <div data-ix="hide-think-cards" class="w-container think-container-2">
      <div class="key-point-2">
        <div class="card-progress">Card 2/3</div>
        <h1 class="card-2-header">If we voted to leave the EU who else could we trade with?</h1>
        <p class="card-text"><strong>Popular argument:</strong>&nbsp;'The UK would be free to secure trade deals with any country it wanted on the terms it decides, not the EU.'</p>
      </div>
      <div class="think-background-div-2">
        <div class="slider-overview">
          <h1 class="overview-heading">Overview&nbsp;</h1>
          <p class="basics-paragraph">The UKs second largest export market behind the EU is the US ,taking 15% of all UK exports. This is despite the UK currently not having a trade deal with the US. The UK would then have to work out under which conditions it would form a trading relationship with the remaining 27 other members states. This could range from country to country negotiations (bi-lateral agreements) or via a trading bloc (like Norway has) which could come at a cost. In addition the EU has 51 trade agreements in place with other countries so the UK would also need to address those as well.&nbsp;</p>
        </div>
        <div class="w-row argument-row-2">
          <div class="w-col w-col-6 overview-2">
            <div class="think-background stay">
              <div class="sticky-footer">
                <h1 class="stay-heading">Impact if we stay</h1>
                <p class="basics-paragraph"><strong class="impact-answer-stay">Minimal.</strong>&nbsp;
                  <br>
                  <br>- The UK will continue to trade with the EU and benefit from access to the single market.
                  <br>
                  <br>- The EU is currently negotiating a series of trade agreements with countries like the US (known as TTIP) and China so the UK would be part of that as well.
                  <br>
                  <br>- The UK CANNOT form its own trade deals whilst remaining a member of the EU.
                  <br>
                  <br>-The advantage of being part of a larger, 500 million person block, is that the terms of trade are generally better as countries generally want access to the EU due to its sheer size, wealth and purchasing power.&nbsp;</p>
                <div class="fill-empty-space"></div>
              </div>
            </div>
          </div>
          <div class="w-col w-col-6 leave-2">
            <div class="think-background leave">
              <div class="sticky-footer">
                <h1 class="leave-heading">Impact if we leave</h1>
                <p class="basics-paragraph"><strong class="impact-answer-leave">Severe.</strong>
                  <br>
                  <br>- No country has ever left the EU in its current incarnation before so no one quite knows what will happen.
                  <br>
                  <br>- The UK economy would most likely suffer a negative impact in the short and medium term (generally considered to be 10 years or less) in the result of a leave vote primarily due to the uncertainty of the trade deal(s) the UK could secure.
                  <br>
                  <br>- The UK would be able to create its own separate trade deals with countries around the world, the EU included. There are over 50 deals to be made, each on a separate country by country basis and ranging from taking a couple of months to over 5 years.
                  <br>
                  <br>- However, the UK is more then capable of securing a deal as it is one of the worlds wealthiest nations but as a trading nation these deals are vital to the economy and jobs so it won't be easy or quick.
                  <br>
                  <br>- The UK would be able to negotiate these deals without relying on other member states (other then the one it was negotiating with) to be happy.</p>
                <div class="fill-empty-space"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="think-choice-2">
        <h1 class="which-way">Does the idea of the UK being separate from the EU and trading under different terms influence your vote?</h1><a href="#Slider" data-ix="show-next-feel-card-2" class="w-button think-leave-2">Yes,&nbsp;Vote Leave</a><a href="#Slider" data-ix="show-next-feel-card-2" class="w-button think-neither-2">Not sure</a><a href="#Slider" data-ix="show-next-feel-card-2" class="w-button think-stay-2">Yes,&nbsp;Vote Stay</a>
      </div>
    </div>
    <div data-ix="hide-think-cards" class="w-container think-container-3">
      <div class="key-point-3">
        <div class="card-progress">Card 3/3</div>
        <h1 class="why-people-card-header">What model could the UK trade under if it voted to leave?</h1>
        <p class="card-text"><strong>Popular argument:</strong>&nbsp;'The UK would have a whole host of trading options open to it if it voted to leave.'</p>
      </div>
      <div class="think-background-div-3">
        <div class="slider-overview">
          <h1 class="overview-heading">Overview&nbsp;</h1>
          <p class="basics-paragraph">This is by far the hardest question to answer in this referendum debate. Why? No one has a clue how it would work because it has never happened before.
            <br>
            <br>Here are the 3 most likely options:
            <br>
            <br>1. Access to the single market via European Economic Area (EEA) membership - Norwegian model&nbsp;
            <br>
            <br>2. Single market access via a EFTA membership underpinned by multiple bilateral agreements - Swiss model.
            <br>
            <br>3. Free Trade Agreement (FTA) between the UK and each trading European state.</p>
        </div>
        <div class="w-row argument-row-3">
          <div class="w-col w-col-6 overview-3">
            <div class="think-background stay">
              <div class="sticky-footer">
                <h1 class="stay-heading">Impact if we stay</h1>
                <p class="basics-paragraph"><strong class="impact-answer-stay">None.</strong>
                  <br>
                  <br>- We'd be in the EU so we would not be trying to re negotiate a trade deal with it.
                  <br>
                  <br>- Amendments to trade terms could change but the UK would be heavily involved in this process so would be more likely to influence a favourable outcome and could veto any amendments it does not agree with.</p>
                <div class="fill-empty-space"></div>
              </div>
            </div>
          </div>
          <div class="w-col w-col-6 leave-3">
            <div class="think-background leave">
              <div class="sticky-footer">
                <h1 class="leave-heading">Impact if we leave</h1>
                <p class="basics-paragraph"><strong class="impact-answer-leave">Severe,</strong>
                  <br>
                  <br>- Each of these models means that the UK would NOT have any influence over EU trade deals as it would have lost its seat at the table by leaving.
                  <br>
                  <br>- Equally to secure trade deals the UK would have to be prepared to potentially; pay a membership fee and/or accept free movement of people and/or have laws imposed on it that it didn't vote for.
                  <br>
                  <br>- Norwegian model: Cannot negotiate its own free trade agreements, even with countries outside of the EU.
                  <br>
                  <br>- Swiss Model: Would have to negotiate bilateral trade deals with each country (Switzerland has 120 such deals)- unknown timescale and with unknown outcomes.
                  <br>
                  <br>- Free Trade Agreement (FTA) model: &nbsp;A UK FTA with the EU would also mean ceasing to have access to those FTAs held between the EU and other countries. In due course the UK might sign new deals. The terms are unknowable, however, and the expectation must be that they will be worse than those the EU has, for &nbsp;the simple reason of market scale (440 million vs 67 million)</p>
                <div class="fill-empty-space"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="think-choice-3">
        <h1 class="which-way">How do these different models of potential exit make you think about the UK and its EU membership?</h1><a href="#Finally" class="w-button think-leave-3">I want to leave</a><a href="#Finally" class="w-button think-neither-3">Unsure</a><a href="#Finally" class="w-button think-stay-3">I want to stay</a>
      </div>
    </div>
  </div>
  <div id="Finally" class="w-section section-1">
      <h1 class="next-button">Step 3: What do you feel?</h1>
    <div class="w-container">
      <h1 class="how-to-header">Click on each card and react to the answer using the emotions shown.</h1>
      <div class="click-tabs"><strong class="clickandrespond">Click on each of the pink buttons below to explore a sub-category.<br><br>Then, click on a face to give your reaction to the fact on the card. <br><br>&nbsp;Don't care about a fact? Don't click a face.</strong>
      </div>
    </div>
    <div data-duration-in="300" data-duration-out="100" class="w-tabs feelings-tab">
      <div class="w-tab-menu tabs-menu">
        <a data-w-tab="Tab 2" class="w-tab-link w--current w-inline-block tab-link selected">
          <div class="button-text">UK trade today</div>
        </a>
      </div>
      <div class="w-tab-content">
        <div data-w-tab="Tab 2" class="w-tab-pane w--tab-active">
          <div class="w-row feeling-row-4">
            <div class="w-col w-col-4 column-1">
              <div class="card-10">
                <div class="sticky-footer">
                  <h1 class="card-header-8">Who are the UKs top trading partners (by country) ?</h1>
                  <h1 class="headline-fact-long">The United States, Germany and China.</h1><a href="#" data-ix="p4" class="w-button button-9">More info</a>
                  <p data-ix="display-none-on-load" class="p4">The UK exports to countries both within and outside of the EU. The US, Germany and China are the three largest growing trade partners the UK has and there is little evidence to show that the EU is slowing down these trade arrangements.</p>
                  <div class="evidence"><a target="_blank" href="https://www.uktradeinfo.com/Statistics/OverseasTradeStatistics/Pages/Commodities.aspx">Where did we get this from?</a>
                  </div>
                  <div class="fill-empty-space"></div>
                  <div class="emoticon-div">
                    <div class="how-emoticons-work">How does this make you feel?</div>
                    <div class="emoticons-10"><img width="60" src="images/angry with word.png" data-ix="anger-selected-10" class="anger-10"><img width="60" src="images/shocked.png" data-ix="shock-selected-10" class="shock-10"><img width="60" src="images/indifferent with word.png" data-ix="indifferent-selected-10" class="indifferent-10"><img width="60" src="images/pleased.png" data-ix="happy-selected-10" class="happy-10"><img width="64" src="images/very happy.png" data-ix="delighted-selected-10" class="delighted-10">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="w-col w-col-4 column-2">
              <div class="card-11">
                <div class="sticky-footer">
                  <h1 class="card-header-8">Do EU countries need us more than we need them (in terms of trade) ?</h1>
                  <h1 class="headline-fact-long">Yes.</h1>
                  <h1 class="headline-small">Apart from 3.</h1><a href="#" data-ix="p10" class="w-button button-1">More info</a>
                  <p data-ix="display-none-on-load" class="p10">Every country in the EU, bar 3 (Ireland, Luxembourg, and Malta), has a trade surplus with the UK in 2015. This means that they sell to us more then they buy from us and as a result we are more valuable to them as trading partner then they are to us.</p>
                  <div class="evidence"><a target="_blank" href="https://fullfact.org/europe/where-does-eu-export/">Where did we get this from?</a>
                  </div>
                  <div class="fill-empty-space"></div>
                  <div class="emoticon-div">
                    <div class="how-emoticons-work">How does this make you feel about the UKs relationship with the EU?</div>
                    <div class="emoticons-11"><img width="60" src="images/angry with word.png" data-ix="anger-selected-11" class="anger-11"><img width="60" src="images/shocked.png" data-ix="shock-selected-11" class="shock-11"><img width="60" src="images/indifferent with word.png" data-ix="indifferent-selected-11" class="indifferent-11"><img width="60" src="images/pleased.png" data-ix="happy-selected-11" class="happy-11"><img width="64" src="images/very happy.png" data-ix="delighted-selected-11" class="delighted-11">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="w-col w-col-4 column-3">
              <div class="card-12">
                <div class="sticky-footer">
                  <h1 class="card-header-8">How much investment into the UK comes from the EU?</h1>
                  <h1 class="headline-fact-long">46% of foreign direct investment comes from the EU</h1><a href="#" data-ix="p11" class="w-button button-4">More info</a>
                  <p data-ix="display-none-on-load" class="p11">The EU is still by far the largest source for foreign direct investment into the UK. This investment is spent on everything from building factories to starting businesses. It's important to note however that investment from the EU has fallen by 7% since 2008, potentially showing a change in where the UK sources its investment from.</p>
                  <div class="evidence"><a target="_blank" href="http://www.ey.com/UK/en/Issues/Business-environment/2015-UK-attractiveness-survey">Where did we get this from?</a>
                  </div>
                  <div class="fill-empty-space"></div>
                  <div class="emoticon-div">
                    <div class="how-emoticons-work">How does this make you feel about the UKs relationship with the EU?</div>
                    <div class="emoticons-12"><img width="60" src="images/angry with word.png" data-ix="anger-selected-12" class="anger-12"><img width="60" src="images/shocked.png" data-ix="shock-selected-12" class="shock-12"><img width="60" src="images/indifferent with word.png" data-ix="indifferent-selected-12" class="indifferent-12"><img width="60" src="images/happy with word.png" data-ix="happy-selected-12" class="happy-12"><img width="64" src="images/very happy.png" data-ix="delighted-selected-12" class="delighted-12">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="results" class="w-section results">
      <h1 class="next-button">Step 4: Your results</h1>
    <div class="w-container">
      <h1 class="how-to-header topic-result">Here are your <strong class="highlight-word result">results</strong> for Trade:</h1>
    </div><a href="#" onclick='drawVoteChart(); drawEmoteChart(); conclusion_topic();' class="w-button results-button">Click to reveal&nbsp;</a>
    <div class="w-container">
<?php 
	require_once('graphs/resultsForPages.php'); 			
?>
	<div id="conclusion"></div>
      <div class="w-row results-row">
        <div class="w-col w-col-6 result-1">
          <h1 class="results-header-1">What you think:</h1>

          <div id="voteGraph" style="height: 300px; width: 100;">
              <div id="result-vote">
                  <?php require_once('graphs/votegraph.php'); ?>
              </div>
          </div>


        </div>
        <div class="w-col w-col-6 result-2">
          <h1 class="results-header-1">What you feel:</h1>



          <div id="emoteGraph" style="height: 300px; width: 100;">
              <div id="result-emote">

                <?php require_once('graphs/emoticongraph.php'); ?>
                <?php mysqli_close($connection); ?>
              </div>
          </div>

        </div>
      </div>
    </div>
  </div>
  <div class="w-section topic-select-section">
    <div class="w-row end-section">
      <div class="w-col w-col-6 end-column-1"></div>
      <div class="w-col w-col-6 end-column-2"></div>
    </div>
    <h1 class="basics-link">What next?</h1>
    <p class="lets-get-started-pap">Next topic will take you to another of your selected topics, or if you'd prefer to not do any more click end result page to see all your results collated in one place.</p><a class="w-button continue-button" href="redirect.php" href="redirect.php">Next topic</a><a href="results.php" class="w-button continue-button _2">End results page</a>
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

