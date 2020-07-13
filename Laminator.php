<?php // Build Date 4/17/2019 8:45AM
$link = mysqli_connect("localhost", "operator", "Licap123!", "Manufacture"); // Checks Manufacturing connection
if($link->connect_errno){
    die('ERROR: Could not connect. ' . $link->connect_error);
}
$inventory_link = mysqli_connect("localhost", "root", "PQch782tdk@@", "INVENTORY");
// Checks inventory connection
if($inventory_link->connect_errno){
    die('ERROR: Could not connect. ' . $inventory_link->connect_error);

}
//escape strings
$LAM_DATE = mysqli_real_escape_string($link, $_REQUEST['LAM_DATE']);
$LAM_OP = mysqli_real_escape_string($link, $_REQUEST['LAM_OP']);
$ELECTRODE_LENGTH = mysqli_real_escape_string($link, $_REQUEST['ELECTRODE_LENGTH']);
$ELECTRODE_LENGTH_2 = mysqli_real_escape_string($link, $_REQUEST['ELECTRODE_LENGTH_2']);
$THICKNESS = mysqli_real_escape_string($link, $_REQUEST['THICKNESS']);
$CAF_BATCH_NUM = mysqli_real_escape_string($link, $_REQUEST['CAF_BATCH_NUM']);
$CAF_BATCH_NUM_2 = mysqli_real_escape_string($link, $_REQUEST['CAF_BATCH_NUM_2']);
$CAF_LENGTH = mysqli_real_escape_string($link, $_REQUEST['CAF_LENGTH']);
$ROLL_DIAMETER = mysqli_real_escape_string($link, $_REQUEST['ROLL_DIAMETER']);
$UPPER_FILM_BATCH_NUM = mysqli_real_escape_string($link, $_REQUEST['UPPER_FILM_BATCH_NUM']);
$LOWER_FILM_BATCH_NUM = mysqli_real_escape_string($link, $_REQUEST['LOWER_FILM_BATCH_NUM']);
$UPPER_FILM_BATCH_NUM_2 = mysqli_real_escape_string($link, $_REQUEST['UPPER_FILM_BATCH_NUM_2']);
$LOWER_FILM_BATCH_NUM_2 = mysqli_real_escape_string($link, $_REQUEST['LOWER_FILM_BATCH_NUM_2']);

$LAM_TEMP_UPPER = mysqli_real_escape_string($link, $_REQUEST['LAM_TEMP_UPPER']);
$LAM_TEMP_LOWER = mysqli_real_escape_string($link, $_REQUEST['LAM_TEMP_LOWER']);
$LAM_SPEED = mysqli_real_escape_string($link, $_REQUEST['LAM_SPEED']);
$GAP_OP = mysqli_real_escape_string($link, $_REQUEST['GAP_OP']);
$GAP_MACHINE = mysqli_real_escape_string($link, $_REQUEST['GAP_MACHINE']);
$TAPE_TEST = mysqli_real_escape_string($link, $_REQUEST['TAPE_TEST']);
$BEGIN_OP = mysqli_real_escape_string($link, $_REQUEST['BEGIN_OP']);
$BEGIN_CENTER = mysqli_real_escape_string($link, $_REQUEST['BEGIN_CENTER']);
$BEGIN_MACHINE = mysqli_real_escape_string($link, $_REQUEST['BEGIN_MACHINE']);
$END_OP = mysqli_real_escape_string($link, $_REQUEST['END_OP']);
$END_CENTER = mysqli_real_escape_string($link, $_REQUEST['END_CENTER']);
$END_MACHINE = mysqli_real_escape_string($link, $_REQUEST['END_MACHINE']);
$NUM_SPLICE = mysqli_real_escape_string($link, $_REQUEST['NUM_SPLICE']);
$NUM_DELAM = mysqli_real_escape_string($link, $_REQUEST['NUM_DELAM']);
$NUM_HOLE = mysqli_real_escape_string($link, $_REQUEST['NUM_HOLE']);
$AVG_THICKNESS = mysqli_real_escape_string($link, $_REQUEST['AVG_THICKNESS']);
$NOTES = mysqli_real_escape_string($link, $_REQUEST['NOTES']);
$FOIL_TYPE = mysqli_real_escape_string($link, $_REQUEST['FOIL_TYPE']);
$LAM_ID = mysqli_real_escape_string($link, $_REQUEST['LAM_ID']);

//Sets date and timestamp
$DATE = date("m/d/Y");
$TIMESTAMP = date("m/d/Y-H:i:s");
//Checks that Foil and Film Batch numbers exist.
$check_1 = "SELECT 1 AS TEST FROM INVENTORY_TABLE WHERE SERIAL = '$CAF_BATCH_NUM'";
$check_result_1 = mysqli_query($inventory_link, $check_1);
$row = mysqli_fetch_assoc($check_result_1);
$TEST_1 = 1;
//Checks if CAF_BATCH_NUM_2 combo box is empty. If it is not empty it checks that the serial is valid.
if(empty($CAF_BATCH_NUM_2) !== true){
$check_2 = "SELECT 1 AS TEST FROM INVENTORY_TABLE WHERE SERIAL = '$CAF_BATCH_NUM_2'";
$check_result_2 = mysqli_query($inventory_link, $check_2);
$row = mysqli_fetch_assoc($check_result_2);
$TEST_2 = 1;}
else { $TEST_2 = 1;
}
//Film
$check_3 = "SELECT 1 AS TEST FROM FILM WHERE FILM_ID = '$UPPER_FILM_BATCH_NUM'";
$check_result_3 = mysqli_query($link, $check_3);
$row = mysqli_fetch_assoc($check_result_3);
$TEST_3 = 1;

if(empty($UPPER_FILM_BATCH_NUM_2) !== true){
$check_4 = "SELECT 1 AS TEST FROM FILM WHERE FILM_ID = '$UPPER_FILM_BATCH_NUM_2'";
$check_result_4 = mysqli_query($link, $check_4);
$row = mysqli_fetch_assoc($check_result_4);
$TEST_4 = 1;
}
else{$TEST_4 = 1;}

$check_5 = "SELECT 1 AS TEST FROM FILM WHERE FILM_ID = '$LOWER_FILM_BATCH_NUM'";
$check_result_5 = mysqli_query($link, $check_5);
$row = mysqli_fetch_assoc($check_result_5);
$TEST_5 = 1;

if(empty($LOWER_FILM_BATCH_NUM_2) !== true){
$check_6 = "SELECT 1 AS TEST FROM FILM WHERE FILM_ID = '$LOWER_FILM_BATCH_NUM_2'";
$check_result_6 = mysqli_query($link, $check_6);
$row = mysqli_fetch_assoc($check_result_6);
$TEST_6 = 1;
}
else{ $TEST_6 = 1;}



if ($TEST_1 > 0 && $TEST_2 > 0 && $TEST_3 > 0 && $TEST_4 > 0 && $TEST_5 > 0 && $TEST_6 > 0 ) {
//Update Amount of CAF used based on serial number
$update_length = "UPDATE INVENTORY_TABLE SET AMOUNT_USED = AMOUNT_USED + '$ELECTRODE_LENGTH' WHERE SERIAL ='$CAF_BATCH_NUM'";
mysqli_query($inventory_link, $update_length);
$update_length_2 = "UPDATE INVENTORY_TABLE SET AMOUNT_USED = AMOUNT_USED + '$ELECTRODE_LENGTH_2' WHERE SERIAL ='$CAF_BATCH_NUM_2'";
mysqli_query($inventory_link, $update_length_2);
//Pull last ELECTRODE Lot Number
$myquery = "SELECT ELECTRODE_SERIAL, THICKNESS FROM LAMINATOR ORDER BY ID DESC LIMIT 1";
$result = mysqli_query($link, $myquery);
$row = mysqli_fetch_assoc($result);
$Last_Film_ID = $row['ELECTRODE_SERIAL'];
$THICKNESS_RESULT = $row['THICKNESS'];

//Compare last batch date with current date
$Batch_Digit = explode("-",$Last_Film_ID);
$DATE_TEST = date("mdY");
$LAM_DATE = date("m/d/Y");

$E_ID = explode("-",$UPPER_FILM_BATCH_NUM);

$NUM_DEFECT = $NUM_SPLICE + $NUM_HOLE + $NUM_DELAM;
if($Batch_Digit[3] === $DATE_TEST){
	$INC_DIGIT = $Batch_Digit[4] + 1;
	$ELECTRODE_SERIAL = $LAM_ID . "E-".$E_ID[0]."-". $THICKNESS . "-" . $DATE_TEST . "-" . $INC_DIGIT;
		}
else{
	$ELECTRODE_SERIAL = $LAM_ID . "E-".$E_ID[0]."-" . $THICKNESS . "-" . $DATE_TEST . "-1";
		}

$AVG_THICKNESS = ($END_OP + $END_CENTER + $END_MACHINE)/3;
$ELECTRODE_TOTAL_LENGTH = $ELECTRODE_LENGTH + $ELECTRODE_LENGTH_2;
//SQL Insert Statement
$sql = "INSERT INTO LAMINATOR (LAM_DATE, LAM_OP, FOIL_TYPE, LAM_ID, ELECTRODE_SERIAL, ELECTRODE_LENGTH, THICKNESS, CAF_BATCH_NUM, CAF_BATCH_NUM_2, ROLL_DIAMETER, UPPER_FILM_BATCH_NUM, LOWER_FILM_BATCH_NUM, UPPER_FILM_BATCH_NUM_2, LOWER_FILM_BATCH_NUM_2, LAM_TEMP_UPPER, LAM_TEMP_LOWER, LAM_SPEED, GAP_OP, GAP_MACHINE, TAPE_TEST, BEGIN_OP, BEGIN_CENTER, BEGIN_MACHINE, END_OP, END_CENTER, END_MACHINE, NUM_HOLE, NUM_DELAM, NUM_SPLICE, AVG_THICKNESS, NOTES, TIMESTAMP, NUM_DEFECT) VALUES ('$LAM_DATE','$LAM_OP','$FOIL_TYPE','$LAM_ID','$ELECTRODE_SERIAL','$ELECTRODE_TOTAL_LENGTH','$THICKNESS','$CAF_BATCH_NUM','$CAF_BATCH_NUM_2','$ROLL_DIAMETER','$UPPER_FILM_BATCH_NUM','$LOWER_FILM_BATCH_NUM','$UPPER_FILM_BATCH_NUM_2','$LOWER_FILM_BATCH_NUM_2','$LAM_TEMP_UPPER','$LAM_TEMP_LOWER','$LAM_SPEED','$GAP_OP','$GAP_MACHINE','$TAPE_TEST','$BEGIN_OP','$BEGIN_CENTER','$BEGIN_MACHINE','$END_OP','$END_CENTER','$END_MACHINE','$NUM_HOLE','$NUM_DELAM','$NUM_SPLICE','$AVG_THICKNESS','$NOTES','$TIMESTAMP','$NUM_DEFECT')";

mysqli_close($link);
?>
