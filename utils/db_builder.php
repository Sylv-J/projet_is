<?php
include_once("../master_db.php");
$db = masterDB::getDB();
echo("<h2>Database builder : </h2>");
$tableFile = fopen("tables_struct","r");
while($line = fgets($tableFile)){
	$db->query("CREATE TABLE IF NOT EXISTS ".$line);
	echo("Creating : ".$line."<br>");
}
fclose($tableFile);
echo("<h4>Done</h4>");