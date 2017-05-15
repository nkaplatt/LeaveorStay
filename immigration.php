<?php
  session_start();
  require_once('js/functions.php');
  require_once('anonymous.php');
?>

<!DOCTYPE html>
<!-- This site was created in Webflow. http://www.webflow.com-->
<!-- Last Published: Tue May 24 2016 19:51:09 GMT+0000 (UTC) -->
<html data-wf-site="572762c72f3e6fea5d0339d6" data-wf-page="572f506f755df9fe38a45160">
<head>
  <meta charset="utf-8">
  <title>Immigration and the EU referendum</title>
  <meta name="description" content="See where you stand on immigration and the EU referendum. Compare both sides of the argument so that you can be sure that you’ve cast an informed vote.">

  <meta property="og:url"           content="http://www.leaveorstay.co.uk/immigration.php" />
  <meta property="og:type"          content="website" />
  <meta property="og:title"         content="Immigration" />
  <meta property="og:description"   content="See where you stand on immigration and the EU referendum. Compare both sides of the argument so that you can be sure that you’ve cast an informed vote." />
  <meta property="og:image"         content="http://www.leaveorstay.co.uk/images/FBimmigration-06.png" />
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
      families: ["Open Sans:300,300italic,400,400italic,600,600italic,700,700italic,800,800italic","Varela Round:400","Montserrat:400,700","Lato:100,100italic,300,300italic,400,400italic,700,700italic,900,900italic","Raleway:100,200,300,regular,500,600,700,800,900"]
    }
  });
  </script>
  <script type="text/javascript" src="js/modernizr.js"></script>
  <script type="text/javascript" src="js/jquery-2.2.4.min.js"></script>
  <script type="text/javascript" src="js/stick.js"></script>

  <?php

  //connection gained from anonymous.php
  //Test success
  connectQuery();
  $category = 2;
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
    var category = 2;
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

  <script>
  WebFont.load({
    google: {
      families: ["Open Sans:300,300italic,400,400italic,600,600italic,700,700italic,800,800italic","Varela Round:400","Montserrat:400,700","Lato:100,100italic,300,300italic,400,400italic,700,700italic,900,900italic","Raleway:100,200,300,regular,500,600,700,800,900"]
    }
  });
  </script>

  <link rel="shortcut icon" type="image/x-icon" href="images/logo-favicon.png">
  <link rel="apple-touch-icon" href="https://daks2k3a4ib2z.cloudfront.net/img/webclip.png">
</head>
<body class="body topics">
  <?php include_once("analyticstracking.php") ?>
  <div class="w-section hero">
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
    <div class="hero-overlay topic-page">
      <div data-collapse="none" data-animation="default" data-duration="400" data-contain="1" data-ix="stick-to-top" class="w-nav progress-navbar">
        <div class="w-container progress-container">
          <div class="what-page">\Immigration</div>
          
          <div class="w-nav-button">
            <div class="w-icon-nav-menu"></div>
          </div>
        </div>
      </div>
      <div class="w-container hero-container immigration"><img src="images/immi-01.png" class="hero-image">
        <h1 class="hero-title word">immigration</h1>
        <h1 class="hero-title">Questions covered include:</h1>
        <h1 class="hero-title title-2">Who can enter the UK?<br>Do we have 'control' of our borders?<br>Do migrants 'take' UK jobs?</h1>
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
      <h1 class="how-to-header">Get a sense of what's currently happening by reading the <strong class="highlight-word">overview:</strong></h1>
    </div>
    <div class="w-container overview-section">
      <h1 class="grid-header">Overview:</h1>
      <p class="overview-subtitle">Immigration is potentially the most 'heated' topic involved in this referendum. Why? Because to a large extent it's the least understood. &nbsp;The vast majority of immigrants entering the UK come from outside the EU so aren't bound by EU "free movement rules", although this percentage is shrinking. The UK already operates a points based system, commonly referred to as the "Australian style" system, for non-EU migrants. Experts believe reform of this system is what is needed to reduce immigration to the 'tens of thousands' which is believed to be the sustainable level of immigration to the UK per year.
        <br>
        <br><strong class="overview-text-link-phrase">Below are the 3&nbsp;main&nbsp;headline stats you'll need to know. Keep scrolling to continue.</strong>
      </p>
    </div>
    <div class="w-row overview-row">
      <div class="w-col w-col-4 column-1">
        <div class="overview-card-1">
          <div class="sticky-footer">
            <h3 class="card-header-1">What are the current levels of immigration?</h3><img src="images/Screen Shot 2016-04-16 at 14.45.51.png" class="immigration-trend-graph">
            <p class="p1">Currently 330,000 more people enter the UK than leave it. Net migration is immigration (people entering the UK) minus people leaving the UK (emigration) with the difference between them known as net migration.</p>
            <div class="evidence"><a target="_blank" href="http://www.migrationobservatory.ox.ac.uk/briefings/migration-flows-a8-and-other-eu-migrants-and-uk">Where did we get this from?</a>
            </div>
            <div class="fill-empty-space"></div>
          </div>
        </div>
      </div>
      <div class="w-col w-col-4 column-2">
        <div class="overview-card-2">
          <div class="sticky-footer">
            <h3 class="card-header-2">How many EU migrants currently live in the UK?</h3>
            <h1 class="headline-fact">3.3</h1>
            <h1 class="headline-fact-subtitle">million</h1>
            <p class="p2">According to the Office for National Statistics (ONS) Labour Force Survey estimates for 2015, there are 3.3 million EU citizens in the UK and 5.3 million from non-EU countries.</p>
            <div class="evidence"><a target="_blank" href="http://www.migrationwatchuk.org/statistics-population-country-birth">Where did we get this from?</a>
            </div>
            <div class="fill-empty-space"></div>
          </div>
        </div>
      </div>
      <div class="w-col w-col-4 column-3">
        <div class="overview-card-3">
          <div class="sticky-footer">
            <h1 class="card-heading-3">How many EU migrants work in the UK?</h1>
            <h1 class="headline-fact">2.1</h1>
            <h1 class="headline-fact-subtitle">million</h1>
            <p class="p3">There is a general increasing trend in the number of EU born in the UK labour market over time, reaching its peak in the first quarter of 2015 with about 1.9 million EU workers. The upward trend is primarily attributed to increases in the number of accession workers over time.</p>
            <div class="evidence"><a target="_blank" href="http://www.migrationobservatory.ox.ac.uk/briefings/migration-flows-a8-and-other-eu-migrants-and-uk">Where did we get this from?</a>
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
      <h1 class="how-to-header">Let us know what you <strong class="highlight-word">think</strong> about immigration and the EU by reading the following 3 cards and seeing which way you lean most:</h1>
    </div>
    <div data-ix="first-think-card" class="w-container think-container">
      <div class="key-point-1">
        <div class="card-progress">
          <div class="card-progress">Card 1/3</div>
        </div>
        <h1 class="card-1-header">&nbsp;Would leaving the EU allow us to better control our borders?</h1>
        <p class="card-text"><strong>Popular argument:</strong> 'EU membership stops us controlling who comes into our country, on what terms, and who can be removed. The system is out of control.'</p>
      </div>
      <div class="think-background-div">
        <div class="slider-overview">
          <h1 class="overview-heading">Overview&nbsp;</h1>
          <p class="basics-paragraph">The UK currently has over 600,000 new migrants entering the UK to live and work every year, an unsustainable amount given the size of the UK. To bring immigration under control experts suggest we need net migration levels (the number entering minus the number leaving ) to be about 100,000 or less.
            <br>
            <br> The UK does have an "Australian" style points based system for non-EU migrants which doesn't apply to EU migrants BUT currently the majority of migrants are from non EU countries anyway. The UK border force currently check every passport of every citizen entering the country and has the right to decline entry if certain criteria is not met for each group.</p>
          </div>
        </div>
        <div class="w-row argument-row">
          <div class="w-col w-col-6 overview-1">
            <div class="think-background stay">
              <div class="sticky-footer">
                <h1 class="stay-heading">Impact if we stay</h1>
                <p class="basics-paragraph"><strong class="impact-answer-stay">Unknown.</strong>&nbsp;
                  <br>
                  <br>- The main influx of EU migrants came from countries that joined the EU in 2004, which includes Poland and Bulgaria.
                  <br>
                  <br>- With those countries now members there is not a large pool of economic migrants out there (besides Turkey) &nbsp;that could be 'released' and lead to a new wave.
                  <br>
                  <br>- None the less the UK would still have to accept free movement rules but can reform its non EU points based system from within the EU.</p>
                  <div class="fill-empty-space"></div>
                </div>
              </div>
            </div>
            <div class="w-col w-col-6 leave-1">
              <div class="think-background leave">
                <div class="sticky-footer _2">
                  <h1 class="leave-heading">Impact if we leave</h1>
                  <p class="basics-paragraph"><strong class="impact-answer-leave">Moderate.</strong>
                    <br>
                    <br>- All migrants would probably have to enter through the same points based system and require a visa.
                    <br>
                    <br>- No one knows how free movement of people would work for UK nationals abroad or under what terms EU nationals could enter the UK.
                    <br>
                    <br>- However it is important to note that reform of the points based system would still need to take place by the UK government to truly bring migration levels under control.</p>
                    <div class="fill-empty-space"></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="think-choice">
              <h1 class="which-way">Does the current immigration system in place make you lean a particular way?</h1><a href="#Slider" data-ix="show-next-think-card" class="w-button think-leave">Yes, Vote Leave</a><a href="#Slider" data-ix="show-next-think-card" class="w-button think-neither">Not sure</a><a href="#Slider" data-ix="show-next-think-card" class="w-button think-stay">Yes, Vote Stay</a>
            </div>
          </div>
          <div data-ix="hide-think-cards" class="w-container think-container-2">
            <div class="key-point-2">
              <div class="card-progress">Card 2/3</div>
              <h1 class="card-2-header">Do immigrants take jobs away from UK nationals?</h1>
              <p class="card-text"><strong>Popular argument:</strong> 'As a result of the 'free movement of people' principle, EU nationals directly compete against UK citizens for jobs in the UK.'</p>
            </div>
            <div class="think-background-div-2">
              <div class="slider-overview">
                <h1 class="overview-heading">Overview&nbsp;</h1>
                <p class="basics-paragraph">There is very little evidence to suggest that free movement of people causes job loss for UK nationals. The vast majority of the studies we examined (36 in total) found that immigration in total had little effect on native employment or on average wages. They did find, however, that it increased wage inequality slightly, especially for those on the lower end of the income scale (approx. £16k-£21k per year)</p>
                <div class="evidence"><a href="https://www.cer.org.uk/sites/default/files/publications/attachments/pdf/2013/pb_imm_uk_27sept13-7892.pdf">Further reading</a>
                </div>
              </div>
              <div class="w-row argument-row-2">
                <div class="w-col w-col-6 overview-2">
                  <div class="think-background stay">
                    <div class="sticky-footer">
                      <h1 class="stay-heading">Impact if we stay</h1>
                      <p class="basics-paragraph"><strong class="impact-answer-stay">Minimal.</strong>&nbsp;
                        <br>
                        <br>- The UK would continue to operate under the rules of free movement.
                        <br>
                        <br>- This does mean that the immigration system will continue to discriminate in favour of EU migrants as they do not have to adhere to the non-EU citizen points based system and as such can enter the UK more easily for work.
                        <br>
                        <br>- However there are plenty of professionals from the EU especially in the fields of medicine, technology and research who also benefit from free movement. It's not just low skilled migrants.</p>
                        <div class="fill-empty-space"></div>
                      </div>
                    </div>
                  </div>
                  <div class="w-col w-col-6 leave-2">
                    <div class="think-background leave">
                      <div class="sticky-footer">
                        <h1 class="leave-heading">Impact if we leave</h1>
                        <p class="basics-paragraph"><strong class="impact-answer-leave">Moderate.</strong>
                          <br>
                          <br>- The UK would, theoretically, have greater control over who entered the UK and for what reasons as all migrant applicants are process through the points based system.
                          <br>
                          <br>- Some "leave" scenarios involve much tighter immigration policies for EU citizens, while other scenarios would bring no change to immigration policies at all.
                          <br>
                          <br>- It’s not possible to resolve this question before the referendum, since post-leave policies would depend not just on the UK government’s decisions, but also on potentially lengthy negotiations with the EU.</p>
                          <div class="fill-empty-space"></div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="think-choice-2">
                  <h1 class="which-way">Does the current situation with immigration and jobs swing your vote?</h1><a href="#Slider" data-ix="show-next-feel-card-2" class="w-button think-leave-2">Yes,&nbsp;Vote Leave</a><a href="#Slider" data-ix="show-next-feel-card-2" class="w-button think-neither-2">Not sure</a><a href="#Slider" data-ix="show-next-feel-card-2" class="w-button think-stay-2">Yes,&nbsp;Vote Stay</a>
                </div>
              </div>
              <div data-ix="hide-think-cards" class="w-container think-container-3">
                <div class="key-point-3">
                  <div class="card-progress">Card 3/3</div>
                  <h1 class="why-people-card-header">Do migrants put pressure on public services like the NHS?</h1>
                  <p class="card-text"><strong class="popular-argument">Popular argument:</strong> 'We can not just reduce the pressure on the NHS, but can stop sending £350m to the EU every week and instead spend it on our priorities. £350m is enough to build a brand new, fully staffed hospital every week.'</p>
                </div>
                <div class="think-background-div-3">
                  <div class="slider-overview">
                    <h1 class="overview-heading">Overview&nbsp;</h1>
                    <p class="basics-paragraph">There are serious difficulties in accurately measuring the effects of migration on the availability and quality of public services. Studies on the 'net fiscal impact' of migration have generally found that, overall, the foreign born make national and local tax contributions that are&nbsp;roughly comparable&nbsp;to the cost of the services and benefits they receive (in other words migrants don't drain public resource.)
                      <br>
                      <br>The extent to which migrants rely on public services will vary depending on the characteristics of migrants and on the service in question. For example, newly arriving migrants tend to be young adults. Because of their age, they are expected to be less likely to use adult social care and most health services than the UK born. However, they are more likely than the UK born to have young children, and so they are expected to rely more heavily on education and maternity care.</p>
                      <div class="evidence"><a href="https://fullfact.org/immigration/how-immigrants-affect-public-finances/">Further reading</a>
                      </div>
                    </div>
                    <div class="w-row argument-row-3">
                      <div class="w-col w-col-6 overview-3">
                        <div class="think-background stay">
                          <div class="sticky-footer">
                            <h1 class="stay-heading">Impact if we stay</h1>
                            <p class="basics-paragraph"><strong class="impact-answer-stay">Minimal.</strong>
                              <br>
                              <br>- The UK would have to be prepared to continually adjust what resources it had in place to deal with an increasing migrant population but overall little day to day impact would be felt.
                              <br>
                              <br>- The bigger impact on the provision of health care and other public services is the budget set by the government. Budget cuts are more likely to have an impact on public service then immigration by a considerable margin.</p>
                              <div class="fill-empty-space"></div>
                            </div>
                          </div>
                        </div>
                        <div class="w-col w-col-6 leave-3">
                          <div class="think-background leave">
                            <div class="sticky-footer">
                              <h1 class="leave-heading">Impact if we leave</h1>
                              <p class="basics-paragraph"><strong class="impact-answer-leave">Unknown.</strong>
                                <br>
                                <br>- The NHS in England, which is the one that the Westminster government can directly increase funding to, had a budget last year of £116bn, which works out at £2.25bn a week. or £321,428,571.42 per day.
                                <br>
                                <br>- Out of a £276 mn ‘saved’ from paying the membership fee to the EU, the maximum amount that could feasibly be given to the NHS is about £160 mn (once you remove EU programs that support farmers, universities, local government etc.
                                <br>
				<br>- If you decided to give all of the money to the NHS (and ignore farmers, universities etc) the £276 mn would be used up by NHS England in around 18 hours based on current level of expenditure.
				<br>
                                <br>- Depending on what model was adopted with regards to free movement of people, it will not be possible to tell what the impact will be on the NHS and other public services.</p>
                                <div class="fill-empty-space"></div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="think-choice-3">
                        <h1 class="which-way">Does the impact of immigration on public services make you lean either way?</h1><a href="#Finally" class="w-button think-leave-3">Yes,&nbsp;Vote Leave</a><a href="#Finally" class="w-button think-neither-3">Don't care</a><a href="#Finally" class="w-button think-stay-3">Yes,&nbsp;Vote Stay</a>
                      </div>
                    </div>
                  </div>
                  <div id="Finally" class="w-section section-1">
                    <h1 class="next-button">Step 3: What do you feel?</h1>
                    <div class="w-container">
                      <h1 class="how-to-header">Click on each card and react to the answer using the emotions shown.</h1>
                    </div>
                    <div class="click-tabs"><strong class="clickandrespond">Click on each of the pink buttons below to explore a sub-category.<br><br>Then, click on a face to give your reaction to the fact on the card. <br><br>&nbsp;Don't care about a fact? Don't click a face.</strong>
                    </div>
                    <div data-duration-in="300" data-duration-out="100" class="w-tabs feelings-tab">
                      <div class="w-tab-menu tabs-menu">
                        <a data-w-tab="Tab 1" class="w-tab-link w-inline-block tab-link selected">
                          <div class="button-text">Most searched</div>
                        </a>
                        <a data-w-tab="Tab 2" class="w-tab-link w--current w-inline-block tab-link selected">
                          <div class="button-text">Rights and responsibilities</div>
                        </a>
                        <a data-w-tab="Tab 3" class="w-tab-link w-inline-block tab-link selected">
                          <div class="button-text">Crime and punishment</div>
                        </a>
                        <a data-w-tab="Tab 4" class="w-tab-link w-inline-block tab-link selected">
                          <div class="button-text">Impact on society</div>
                        </a>
                      </div>
                      <div class="w-tab-content">
                        <div data-w-tab="Tab 1" class="w-tab-pane">
                          <div class="w-row feeling-row-2">
                            <div class="w-col w-col-4 column-1">
                              <div class="card-4">
                                <div class="sticky-footer test">
                                  <h1 class="card-header-8">How many EU immigrants are currently looking for work?</h1>
                                  <h1 class="headline-fact-long">30.10%</h1><a href="#" data-ix="p9" class="w-button button-9">More info</a>
                                  <p data-ix="display-none-on-load" class="p9">In the first quarter of 2015, 69.9% of non-UK born working-age migrants were in some kind of employment, compared with 74% of the UK-born. However, this represents a considerable increase from historically low levels of employment among migrants.</p>
                                  <div class="evidence"><a target="_blank" href="http://www.migrationwatchuk.org/key-topics/employment-welfare">Where did we get this from?</a>
                                  </div>
                                  <div class="fill-empty-space"></div>
                                  <div class="emoticon-div">
                                    <div class="how-emoticons-work">How does the current immigrant employment rate make you feel?</div>
                                    <div class="emoticons-9"><img width="60" src="images/angry with word.png" data-ix="anger-selected-9" class="anger-9"><img width="60" src="images/shocked.png" data-ix="shock-selected-9" class="shock-9"><img width="60" src="images/indifferent with word.png" data-ix="indifferent-selected-9" class="indifferent-9"><img width="60" src="images/pleased.png" data-ix="happy-selected-9" class="happy-9"><img width="64" src="images/very happy.png" data-ix="delighted-selected-9" class="delighted-9">
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="w-col w-col-4 column-2">
                              <div class="card-5">
                                <div class="sticky-footer">
                                  <h1 class="card-header-5">What is the main motivation for coming to the UK?</h1>
                                  <h1 class="headline-fact-long">Economic (jobs)</h1><a href="#" data-ix="p5" class="w-button button-5">More info</a>
                                  <p data-ix="display-none-on-load" class="p5">About 65% of EU nationals migrating to the UK come for work related reasons, followed by those who come for formal study.</p>
                                  <div class="evidence"><a target="_blank" href="http://www.migrationobservatory.ox.ac.uk/briefings/migration-flows-a8-and-other-eu-migrants-and-uk">Where did we get this from?</a>
                                  </div>
                                  <div class="fill-empty-space"></div>
                                  <div class="emoticon-div">
                                    <div class="how-emoticons-work">How does this motivation for immigration &nbsp;make you feel?</div>
                                    <div class="emoticons-5"><img width="60" src="images/angry with word.png" data-ix="anger-selected-5" class="anger-5"><img width="60" src="images/shocked.png" data-ix="shock-selected-5" class="shock-5"><img width="60" src="images/indifferent with word.png" data-ix="indifferent-selected-5" class="indifferent-5"><img width="60" src="images/pleased.png" data-ix="happy-selected-5" class="happy-5"><img width="64" src="images/very happy.png" data-ix="delighted-selected-5" class="delighted-5">
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="w-col w-col-4 column-3">
                              <div class="card-6">
                                <div class="sticky-footer">
                                  <h1 class="card-header-6">Is the UK becoming overcrowded?</h1>
                                  <h1 class="headline-fact-long">It's on its way.</h1><a href="#" data-ix="p6" class="w-button button-6">Show context</a>
                                  <p data-ix="display-none-on-load" class="p6">High net migration has resulted in rapid population growth. The UK population currently stands at around 65 million. The Office of National Statistics ‘high’ migration scenario projects that the UK population will now increase by around 500,000 a year - the equivalent to a new city the size of Liverpool every year. This is unsustainable. It would result in the population growing by nearly eight million over the next fifteen years bringing it to 73 million.</p>
                                  <div class="evidence"><a target="_blank" href="http://www.migrationwatchuk.org/what-is-the-problem">Where did we get this from?</a>
                                  </div>
                                  <div class="fill-empty-space"></div>
                                  <div class="emoticon-div">
                                    <div class="how-emoticons-work">How does the fact the UK faces becoming over crowded make you feel?</div>
                                    <div class="emoticons-6"><img width="60" src="images/angry with word.png" data-ix="anger-selected-6" class="anger-6"><img width="60" src="images/shocked.png" data-ix="shock-selected-6" class="shock-6"><img width="60" src="images/indifferent with word.png" data-ix="indifferent-selected-6" class="indifferent-6"><img width="60" src="images/pleased.png" data-ix="happy-selected-6" class="happy-6"><img width="64" src="images/very happy.png" data-ix="delighted-selected-6" class="delighted-6">
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div width="60" data-w-tab="Tab 2" class="w-tab-pane w--tab-active">
                          <div class="w-row feeling-row-4">
                            <div class="w-col w-col-4 column-1">
                              <div class="card-10">
                                <div class="sticky-footer">
                                  <h1 class="card-header-8">What workers rights does an EU immigrant have in another country?</h1>
                                  <h1 class="headline-fact-long">The same as a citizen of that country.</h1>
                                  <div class="fill-empty-space"></div>
                                  <div class="emoticon-div">
                                    <div class="how-emoticons-work">How does the idea of immigrant rights being the same as a nationals rights make you feel?</div>
                                    <div class="emoticons-10"><img width="60" src="images/angry with word.png" data-ix="anger-selected-10" class="anger-10"><img width="60" src="images/shocked.png" data-ix="shock-selected-10" class="shock-10"><img width="60" src="images/indifferent with word.png" data-ix="indifferent-selected-10" class="indifferent-10"><img width="60" src="images/pleased.png" data-ix="happy-selected-10" class="happy-10"><img width="64" src="images/very happy.png" data-ix="delighted-selected-10" class="delighted-10">
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="w-col w-col-4 column-2">
                              <div class="card-11">
                                <div class="sticky-footer">
                                  <h1 class="card-header-8">What can immigrants currently do when in the UK?</h1>
                                  <h1 class="headline-fact-long">Pretty much everything a UK citizen can do&nbsp;</h1>
                                  <h1 class="headline-small">(apart from vote at a General Election or serve on a jury)</h1>
                                  <div class="evidence"><a target="_blank" href="http://findlaw.co.uk/law/government/constitutional_law/do-i-have-to-do-jury-service.html">Where did we get this from?</a>
                                  </div>
                                  <div class="fill-empty-space"></div>
                                  <div class="emoticon-div">
                                    <div class="how-emoticons-work">How does this make you feel?</div>
                                    <div class="emoticons-11"><img width="60" src="images/angry with word.png" data-ix="anger-selected-11" class="anger-11"><img width="60" src="images/shocked.png" data-ix="shock-selected-11" class="shock-11"><img width="60" src="images/indifferent with word.png" data-ix="indifferent-selected-11" class="indifferent-11"><img width="60" src="images/pleased.png" data-ix="happy-selected-11" class="happy-11"><img width="64" src="images/very happy.png" data-ix="delighted-selected-11" class="delighted-11">
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="w-col w-col-4 column-3">
                              <div class="card-12">
                                <div class="sticky-footer">
                                  <h1 class="card-header-8">Whose law do they abide by?</h1>
                                  <h1 class="headline-fact-long">Whichever country they reside in.</h1>
                                  <div class="fill-empty-space"></div>
                                  <div class="emoticon-div">
                                    <div class="how-emoticons-work">How does this make you feel?</div>
                                    <div class="emoticons-12"><img width="60" src="images/angry with word.png" data-ix="anger-selected-12" class="anger-12"><img width="60" src="images/shocked.png" data-ix="shock-selected-12" class="shock-12"><img width="60" src="images/indifferent with word.png" data-ix="indifferent-selected-12" class="indifferent-12"><img width="60" src="images/happy with word.png" data-ix="happy-selected-12" class="happy-12"><img width="64" src="images/very happy.png" data-ix="delighted-selected-12" class="delighted-12">
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div data-w-tab="Tab 3" class="w-tab-pane">
                          <div class="w-row feeling-row-5">
                            <div class="w-col w-col-4 column-1">
                              <div class="card-13">
                                <div class="sticky-footer">
                                  <h1 class="card-header-8">Where is an EU immigrant sent to prison if they are in another EU country?</h1>
                                  <h1 class="headline-fact-long">Depends on the crime.</h1><a href="#" data-ix="p10" class="w-button button-11">More info</a>
                                  <p data-ix="display-none-on-load" class="p10">Generally speaking if a EU immigrant commits a crime in the UK they will serve their time in a prison in the UK if their sentance is less than 24 months (12 months or less for crimes related to drugs, sex, violence or other serious criminal activity).
                                    <br>
                                    <br> For those over 24 months the Home Office is likely to deport them to their country of origin for them to serve their sentance there. Foreign criminals make up approx. 14% of the overall prison population in the UK.</p>
                                    <div class="evidence"><a href-disabled="http://www.migrationobservatory.ox.ac.uk/briefings/migration-flows-a8-and-other-eu-migrants-and-uk" href-disabled-default-color="" href-disabled-underline="" href="https://www.justice.gov.uk/offenders/types-of-offender/foreign" target="_blank">Where did we get this from?</a>
                                    </div>
                                    <div class="fill-empty-space"></div>
                                    <div class="emoticon-div">
                                      <div class="how-emoticons-work">How does this make you feel?</div>
                                      <div class="emoticons-13"><img width="60" src="images/angry with word.png" data-ix="anger-selected-13" class="anger-13"><img width="60" src="images/shocked.png" data-ix="shock-selected-13" class="shock-13"><img width="60" src="images/indifferent with word.png" data-ix="indifferent-selected-13" class="indifferent-13"><img width="60" src="images/pleased.png" data-ix="happy-selected-13" class="happy-13"><img width="64" src="images/very happy.png" data-ix="delighted-selected-13" class="delighted-13">
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="w-col w-col-4 column-2">
                                <div class="card-14">
                                  <div class="sticky-footer">
                                    <h1 class="card-header-8">Can immigrants currently be removed from the UK?</h1>
                                    <h1 class="headline-fact-long">Yes. Either via deportation or via a 'removal' letter.</h1><a href="#" data-ix="p11" class="w-button button-12">More info</a>
                                    <p data-ix="display-none-on-load" class="p11">The Home Office is likely to begin deportation proceedings if you were sentenced to prison for 24 months or over for any offences, or to one year or more if the offence related to drugs, sex, violence or other serious criminal activity. The sentence must relate to one conviction. Sentences cannot be added together.
                                      <br>
                                      <br> However, an EU/EEA national who does not meet the above criteria but who the Home Office believes to be a 'low level persistent offender', a risk to public health, a risk to national security or a risk to the public can also be deported.</p>
                                      <div class="evidence"><a target="_blank" href="http://www.biduk.org/sites/default/files/BID%20Factsheet%206%20Deportation%20Appeals%20Deportation%20of%20EU%20nationals_pdf%20version_0.pdf">Where did we get this from?</a>
                                      </div>
                                      <div class="fill-empty-space"></div>
                                      <div class="emoticon-div">
                                        <div class="how-emoticons-work">How does the current system of deportation make you feel?</div>
                                        <div class="emoticons-14"><img width="60" src="images/angry with word.png" data-ix="anger-selected-14" class="anger-14"><img width="60" src="images/shocked.png" data-ix="shock-selected-14" class="shock-14"><img width="60" src="images/indifferent with word.png" data-ix="indifferent-selected-14" class="indifferent-14"><img width="60" src="images/pleased.png" data-ix="happy-selected-14" class="happy-14"><img width="64" src="images/very happy.png" data-ix="delighted-selected-14" class="delighted-14">
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                <div class="w-col w-col-4 column-3"></div>
                              </div>
                            </div>
                            <div data-w-tab="Tab 4" class="w-tab-pane">
                              <div class="w-row feeling-row-6">
                                <div class="w-col w-col-4 column-1">
                                  <div class="card-15">
                                    <div class="sticky-footer">
                                      <h1 class="card-header-8">What is the current impact of immigration on public services?</h1>
                                      <h1 class="headline-fact-long">Mixed. Less likely to use social care and health services. More likely to use education and childcare.</h1><a href="#" data-ix="p12" class="w-button button-13">More info</a>
                                      <p data-ix="display-none-on-load" class="p12">The extent to which migrants rely on public services will vary depending on the characteristics of migrants and on the service in question. For example, newly arriving migrants tend to be young adults. Because of their age, they are expected to be less likely to use adult social care and most health services than the UK born. However, they are more likely than the UK born to have young children, and so they are expected to rely more heavily on education and maternity care.</p>
                                      <div class="evidence"><a target="_blank" href="http://www.migrationobservatory.ox.ac.uk/briefings/election-2015-briefing-impacts-migration-local-public-services">Where did we get this from?</a>
                                      </div>
                                      <div class="fill-empty-space"></div>
                                      <div class="emoticon-div">
                                        <div class="how-emoticons-work">How does the current impact of immigration on public service make you feel?</div>
                                        <div class="emoticons-15"><img width="60" src="images/angry with word.png" data-ix="anger-selected-15" class="anger-15"><img width="60" src="images/shocked.png" data-ix="shock-selected-15" class="shock-15"><img width="60" src="images/indifferent with word.png" data-ix="indifferent-selected-15" class="indifferent-15"><img width="60" src="images/pleased.png" data-ix="happy-selected-15" class="happy-15"><img width="64" src="images/very happy.png" data-ix="delighted-selected-15" class="delighted-15">
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                <div class="w-col w-col-4 column-2">
                                  <div class="card-16">
                                    <div class="sticky-footer">
                                      <h1 class="card-header-8">What is the current impact of immigration on housing?</h1>
                                      <h1 class="headline-fact-long">Migrants mainly rent houses.</h1><a href="#" data-ix="p13" class="w-button button-14">More info</a>
                                      <p data-ix="display-none-on-load" class="p13">Evidence suggests that immigration decreases house prices in England and Wales. Estimates suggest that a 1% increase in the migrant share the population in an area reduces house prices by almost 2%. In addition UK-born individuals and foreign-born individuals have similar levels of participation in social housing.</p>
                                      <div class="evidence"><a target="_blank" href="http://www.migrationobservatory.ox.ac.uk/briefings/migrants-and-housing-uk-experiences-and-impacts">Where did we get this from?</a>
                                      </div>
                                      <div class="fill-empty-space"></div>
                                      <div class="emoticon-div">
                                        <div class="how-emoticons-work">How does the current impact of immigration on housing make you feel?</div>
                                        <div class="emoticons-16"><img width="60" src="images/angry with word.png" data-ix="anger-selected-16" class="anger-16"><img width="60" src="images/shocked.png" data-ix="shock-selected-16" class="shock-16"><img width="60" src="images/indifferent with word.png" data-ix="indifferent-selected-16" class="indifferent-16"><img width="60" src="images/pleased.png" data-ix="happy-selected-16" class="happy-16"><img width="64" src="images/very happy.png" data-ix="delighted-selected-16" class="delighted-16">
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                <div class="w-col w-col-4 column-3">
                                  <div class="card-17">
                                    <div class="sticky-footer">
                                      <h1 class="card-header-8">What is the impact of immigration on claiming government support?</h1>
                                      <h1 class="headline-fact-long">Mixed.</h1><a href="#" data-ix="p14" class="w-button button-16">More info</a>
                                      <p data-ix="display-none-on-load" class="p14">The fiscal impact of migration in the UK is small and differs by migrant group (e.g. EEA migrants vs. non-EEA migrants, recent migrants vs. all migrants) In theory, the fiscal effects of immigration largely depend on migrants’ characteristics (skills, age, length of stay), their impacts on the labour market and welfare entitlements</p>
                                      <div class="evidence"><a target="_blank" href="http://www.migrationobservatory.ox.ac.uk/briefings/fiscal-impact-immigration-uk">Where did we get this from?</a>
                                      </div>
                                      <div class="fill-empty-space"></div>
                                      <div class="emoticon-div">
                                        <div class="how-emoticons-work">How does this make you feel?</div>
                                        <div class="emoticons-17"><img width="60" src="images/angry with word.png" data-ix="anger-selected-17" class="anger-17"><img width="60" src="images/shocked.png" data-ix="shock-selected-17" class="shock-17"><img width="60" src="images/indifferent with word.png" data-ix="indifferent-selected-17" class="indifferent-17"><img width="60" src="images/pleased.png" data-ix="happy-selected-17" class="happy-17"><img width="64" src="images/very happy.png" data-ix="delighted-selected-17" class="delighted-17">
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

                          <h1 class="how-to-header topic-result">Here are your <strong class="highlight-word result">results</strong> for Immigration:</h1>
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
                        <p class="lets-get-started-pap">Next topic will take you to another of your selected topics, or if you'd prefer to not do any more click end result page to see all your results collated in one place.</p><a class="w-button continue-button" href="redirect.php">Next topic</a><a class="w-button continue-button _2" href="results.php">End results page</a>
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
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-57667ae2f9bb9613"></script>
                        <!--[if lte IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/placeholders/3.0.2/placeholders.min.js"></script><![endif]-->
                      </body>
                      </html>
