<!DOCTYPE html>
<html>
<body>
<?php

$link = mysqli_connect("localhost", "operator", "Licap123!", "Manufacture");
// Check connection
if($link->connect_errno){
    die('ERROR: Could not connect. ' . $link->connect_error);

}
$DATE = date("m/d/Y");
$TIMESTAMP = date("m/d/Y-H:i:s");

echo "Current Time: " . $TIMESTAMP . "<br>";
echo "Time Stamp &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp |Batch ID &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp |AC Used (KG) &nbsp&nbsp&nbsp&nbsp |Acetone Used (KG) &nbsp&nbsp |PTFE WEIGHT (KG) |Strip Recycle  |Baghouse Recycle |Outside Recycle (KG)  <br>";
//Pulls data for query
$myquery = "SELECT AC_WEIGHT, ACETONE_WEIGHT, STRIP_RECYCLE, BAGHOUSE_RECYCLE, OUTSIDE_RECYCLE, PTFE_WEIGHT, BATCH_NUM, TIMESTAMP FROM blend WHERE DATE = '$DATE' ORDER BY ID DESC";
$result = mysqli_query($link, $myquery);
$count = "SELECT COUNT(FILM_ID) FROM FILM WHERE DATE = '$DATE'";
$count_result = mysqli_query($link, $count);
$count_count = mysqli_fetch_array($count_result);
WHILE($row = mysqli_fetch_assoc($result))
{
	echo $row['TIMESTAMP']." |";
	echo $row['BATCH_NUM']." &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp|";
	echo $row['AC_WEIGHT']." &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp|";	
	echo $row['ACETONE_WEIGHT']."&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp|  ";
	echo $row['PTFE_WEIGHT']." &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp|";
	echo $row['STRIP_RECYCLE']."&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp|        ";
	echo $row['BAGHOUSE_RECYCLE']." "."&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp|";
	echo $row['OUTSIDE_RECYCLE']." "."&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp <br> ";
	
$TOTAL_AC = $TOTAL_AC + $row['AC_WEIGHT'];
$TOTAL_ACETONE = $TOTAL_ACETONE + $row['ACETONE_WEIGHT'];
$TOTAL_PTFE = $TOTAL_PTFE + $row['PTFE_WEIGHT'];
$TOTAL_RECYCLE = $TOTAL_RECYCLE + $row['STRIP_RECYCLE'] + $row['BAGHOUSE_RECYCLE'] + $row['OUTSIDE_RECYCLE'];

}
	echo "<hr>Total AC Used: " . $TOTAL_AC ."kg". "<br>";
	echo "Total Acetone Used: " . $TOTAL_ACETONE."kg". "<br>";
	echo "Total PTFE Used: " . $TOTAL_PTFE."kg". "<br>";
	echo "Total Recycle Used: " . $TOTAL_RECYCLE."kg";
	

// close connection

mysqli_close($link);

?> 
<form action="material_export.php" method="get">
  <input type="submit" value="Download CSV">

</body>
</html>
