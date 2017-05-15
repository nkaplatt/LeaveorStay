<?php
require_once('js/functions.php');
require_once('anonymous.php');
?>

<script>
function reqListener () {
  console.log(this.responseText);
}

function drawEmoteChart(){

  var category = <?php echo json_encode($category); ?>;

  var oReq = new XMLHttpRequest();
  oReq.onreadystatechange = function() {
      if (oReq.readyState == XMLHttpRequest.DONE) {
          var selected = this.responseText;
          var obj = [Number(selected[1]), Number(selected[3]), Number(selected[5]), Number(selected[7]), Number(selected[9])];

          sum = 0;
          for(i=0;i<obj.length;i++){
            sum += obj[i];
          }

          if(sum > 0){

          //initial value of dataPoints
          var dps = [
            { label: "Angry",
              legendText: "Angry",
              color: "#1A25F0",
              y: Math.round(((obj[0] /sum)*100))
            },
            { label: "Shocked",
              legendText: "Shocked",
              color: "#A30AFA",
              y: Math.round(((obj[1] /sum)*100))
            },
            { label: "Indifferent",
              legendText: "Indifferent",
              color: "#E31064",
              y: Math.round(((obj[2] /sum)*100))
            },
            { label: "Pleased",
              legendText: "Pleased",
              color: "#FA490C",
              y: Math.round(((obj[3] /sum)*100))
            },
            { label: "Very Happy",
              legendText: "Very Happy",
              color: "#F09609",
              y: Math.round(((obj[4] /sum)*100))
            }
          ];


        var chart = new CanvasJS.Chart("emoteGraph",{
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
      document.getElementById("result-emote").innerHTML = '<h2 style="align:center; padding:6px;display:inline; color:white; background:#c06">To get results, use our "feel" section.</h2>';
    }
  }
}
  oReq.open("GET", "graphs/get-data-emotes.php?category=" + category, true);
  oReq.send();
}
</script>
