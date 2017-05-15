<!DOCTYPE HTML>
<html>
  
<?php
require_once("js/functions.php");
require_once("anonymous.php");
$userID = $_SESSION["login_user"];


function get_results_emot($User_ID){
  //Open Database connection
  /*$myfile = fopen( "../lemons.txt", "r") or die("Unable to open file!");
  $myIP   = fopen( "../IP.txt", "r") or die("Unable to open file!");
  $dbpass = fread($myfile,filesize("../lemons.txt"));
  $dbhost = fread($myIP,filesize("../IP.txt"));
  fclose($myfile);
  fclose($myIP);*/
  $connection = connect_to_db();
  
  //Queries to get answers that user entered
  $query = 'SELECT Emoticon_Type FROM Card_tbl ';
  $query .= "WHERE MUser_ID = '{$User_ID}' and Category_ID = '10';";
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
  mysqli_close($connection);
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
?>
  
<head>
  
  <script>
  var obj = <?php echo json_encode($results); ?>;
  function sum(a, b) {
      return a + b;
  }
    var sum = obj.reduce(sum, 0); 
  </script>
	<script type="text/javascript">
    
	window.onload = function () {
		
		//initial value of dataPoints 
		var dps = [
		{label: "Angry", legendText: "Angry", color: "#1A25F0", y: Math.round(((obj[0] /sum)*100))},
		{label: "Shocked", legendText: "Shocked", color: "#A30AFA", y: Math.round(((obj[1] /sum)*100))},
		{label: "Indifferent", legendText: "Indifferent", color: "#E31064", y: Math.round(((obj[2] /sum)*100))},
		{label: "Pleased", legendText: "Pleased", color: "#FA490C", y: Math.round(((obj[3] /sum)*100))},
		{label: "Very Happy", legendText: "Very Happy", color: "#F09609", y: Math.round(((obj[4] /sum)*100))},
		];	

		var chart = new CanvasJS.Chart("chartContainer",{			
			title: {		
			},
			axisY: {				
				suffix: " %"
			},		
		  legend:{
			  verticalAlign: "bottom",
			  horizontalAlign: "center",
        fontSize: 16
		  },
			data: [
			{
				type: "doughnut",	
				bevelEnabled: true,	
        showInLegend: true,
				indexLabel: "{y} %",
        toolTipContent: "{legendText}: <strong>{y}%</strong>",
				dataPoints: dps					
			}
			]
		});
	
			chart.render();	

		// update chart after specified interval 
		setInterval(function(){updateChart()}, updateInterval);


	}
	</script>
	<script type="text/javascript" src="canvasjs.min.js"></script>
</head>
<body>
	<div id="chartContainer" style="height: 300px; width: 100;">
	</div>
</body>
</html>