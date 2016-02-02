<?php
	
require_once ('../lib/jpgraph/jpgraph.php');
require_once ('../lib/jpgraph/jpgraph_stock.php');
 
// Some (random) data
//$ydata = array(11,3,8,12,5,1,9,13,5,7);
 
// Size of the overall graph
$width=350;
$height=250;
 
// echo $_GET['ydata'];
 
// Create the graph and set a scale.
// These two calls are always required
$graph = new Graph($width,$height);

// Create the linear plot
$ydata1=unserialize(urldecode($_GET['ydata1']));
$lineplot=new BoxPlot($ydata1);

$graph->SetScale('intint', max($ydata1[12]-1, $ydata1[7]-1), max($ydata1[13]+1, $ydata1[8]+1),-0.2,2.2);

$graph->Set90AndMargin(1,1,1,1);

 
// Add the plot to the graph
$graph->Add($lineplot);
 
// Display the graph
$graph->Stroke();

?>


</body>
</html>
