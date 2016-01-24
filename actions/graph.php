<?php
	
require_once ('../lib/jpgraph/jpgraph.php');
require_once ('../lib/jpgraph/jpgraph_line.php');
 
// Some (random) data
//$ydata = array(11,3,8,12,5,1,9,13,5,7);
 
// Size of the overall graph
$width=350;
$height=250;
 
// echo $_GET['ydata'];
 
// Create the graph and set a scale.
// These two calls are always required
$graph = new Graph($width,$height);
$graph->SetScale('intlin');
 
// Create the linear plot
$lineplot=new LinePlot(unserialize(urldecode($_GET['ydata1'])));
 
// Add the plot to the graph
$graph->Add($lineplot);
 
// Display the graph
$graph->Stroke();

?>


</body>
</html>
