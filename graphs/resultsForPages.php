<?php 

require_once('js/functions.php');
require_once('anonymous.php');

?>

<script>
function reqListener () {
  console.log(this.responseText);
}

function conclusion_topic(){

  var category = <?php echo json_encode($category); ?>;

  var oReq = new XMLHttpRequest();
  oReq.onreadystatechange = function() {
      if (oReq.readyState == XMLHttpRequest.DONE) {
          var selected = this.responseText;
					document.getElementById("conclusion").innerHTML = selected;
          
			} 
  }

  oReq.open("GET", "results-conclusion.php?file=1" + "&category=" + category, true);
  oReq.send();
};

</script>

