<?php
require_once('js/functions.php');
require_once('anonymous.php');
?>

<script>
function reqListener () {
  console.log(this.responseText);
}

function drawVoteChart(){

  var category = <?php echo json_encode($category); ?>;

  var oReq = new XMLHttpRequest();
  oReq.onreadystatechange = function() {
      if (oReq.readyState == XMLHttpRequest.DONE) {
          var selected = this.responseText;
          var obj = [Number(selected[1]), Number(selected[3]), Number(selected[5])];

          sum = 0;
          for(i=0;i<obj.length;i++){
            sum += obj[i];
          }

          if(sum > 0){

          //initial value of dataPoints
          var dps = [
            { label: "Vote Leave",
              legendText: "Vote Leave",
              color: "#42AAC4",
              y: Math.round(((obj[0] /sum)*100))
            },
            { label: "Vote Stay",
              legendText: "Vote Stay",
              color: "#F0AA2E",
              y: Math.round(((obj[1] /sum)*100))
            },
            { label: "Undecided",
              legendText: "Undecided",
              color: "#E30164",
              y: Math.round(((obj[2] /sum)*100))}
            ]


        var chart = new CanvasJS.Chart("voteGraph",{
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
      } else{
        //Else display an error message?
      document.getElementById("result-vote").innerHTML = '<h2 style="align:center; padding:6px;display:inline; color:white; background:#c06">To get results, use our "think" section.</h2>';
    }
  }
}
  oReq.open("GET", "graphs/get-data-opinions.php?category=" + category, true);
  oReq.send();
}
</script>
