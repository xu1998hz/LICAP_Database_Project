<!DOCTYPE html>
<html>
<body>
<?php

$link = mysqli_connect("localhost", "root", "PQch782tdk@@", "Manufacture");
// Check connection
if($link->connect_errno){
    die('ERROR: Could not connect. ' . $link->connect_error);

}
//escape strings
$ELECTRODE_LENGTH = mysqli_real_escape_string($link, $_REQUEST['ELECTRODE_LENGTH']);
$NUM_DEFECT = mysqli_real_escape_string($link, $_REQUEST['NUM_DEFECT']);
$AVG_THICKNESS = mysqli_real_escape_string($link, $_REQUEST['AVG_THICKNESS']);
$TIMESTAMP = mysqli_real_escape_string($link, $_REQUEST['TIMESTAMP']);
$DATE = date("m/d/Y");
$TIMESTAMP = date("m/d/Y-H:i:s");
$ELECTRODE_SERIAL = mysqli_real_escape_string($link, $_REQUEST['ELECTRODE_SERIAL']);
echo "Current Time: " . $TIMESTAMP . "<br>";
echo "Time Stamp &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Electrode Serial &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Length &nbsp Avg Thickness &nbsp&nbspNumber of Defects <br>";
//Pulls data for query
$myquery = "SELECT ELECTRODE_SERIAL, ELECTRODE_LENGTH, NUM_DEFECT, AVG_THICKNESS, TIMESTAMP FROM LAMINATOR WHERE LAM_DATE = '$DATE' ORDER BY ID DESC";
$result = mysqli_query($link, $myquery);
$count = "SELECT COUNT(ELECTRODE_SERIAL) FROM LAMINATOR WHERE LAM_DATE = '$DATE'";
$count_result = mysqli_query($link, $count);
$count_count = mysqli_fetch_array($count_result);
WHILE($row = mysqli_fetch_assoc($result))
{
	echo $row['TIMESTAMP']." |&nbsp";
	echo $row['ELECTRODE_SERIAL']." |&nbsp&nbsp&nbsp";
	echo $row['ELECTRODE_LENGTH']."|&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp                 ";
	echo round($row['AVG_THICKNESS'])."|&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp         ";
	echo $row['NUM_DEFECT']." "."<br>";
	$TOTAL_LENGTH = $TOTAL_LENGTH + $row['ELECTRODE_LENGTH'];
}
	echo "&nbsp&nbsp&nbsp&nbspTOTAL LENGTH:" . $TOTAL_LENGTH;
	$AVG = $count_count['COUNT(ELECTRODE_SERIAL)'];
	$AVG_LENGTH = $TOTAL_LENGTH/$AVG;
	echo "&nbsp&nbspAVG_LENGTH:" . $AVG_LENGTH;



// close connection

mysqli_close($link);

?>
<form action="csv_export_lam.php" method="get">
  <p>
    <label for="T1">Begin Time:</label>
    <input type="text" name="T1" id="T1">
    <label for="T1">Month/Day/Year Ex: 05/04/2020</label>
  </p>
  <p>
    <label for="T2">End Time:</label>
    <input type="text" name="T2" id="T2">
    <label for="T2">Month/Day/Year</label>
  </p>
  <input type="submit" value="Download CSV">
</form>
</body>
</html>
