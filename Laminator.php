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
//Creates new label
for ($x =1; $x<=3; $x++) { 
/* Get the port for the service. */
$port = "9100";

/* Get the IP address for the target host. */
$host = "10.1.10.191";

/* construct the label */
$label = "﻿CT~~CD,~CC^~CT~
^XA~TA000~JSN^LT0^MNW^MTD^PON^PMN^LH0,0^JMA^PR5,5~SD15^JUS^LRN^CI0^XZ
^XA
^MMT
^PW609
^LL0203
^LS0
^FO192,96^GFA,03584,03584,00028,:Z64:
eJzt1D1u7CAQB3CQC0qSE3AUzpQTQJRiy1zJuQlPuQDuKAiTGQZ7MbaeXvM6Rivvip/X5uMPQsyaNWvWfywJn4BfCrywQQiTLZSLiYUaySAc9mhmAFa21JkXQoNwAJEtD1YEUGO1ctgHPgptAWqsBmczWQE1svnd3tk0mWdbn4Z9tsmQrQb74Z7m2SwE/JCpfYBkkf+HjZFMDuaizvi7mqCGZo7eFFV89S5Vc4MFFbZoL2arya8N5dZW+diKZrNP45EJvYEuvVk4zGzw6G2B3XAKX9TJaBK9hn16l97sYYIf0xkNzdesXA2aqVLv++5MVluQ2f50tuym7yxLtnxjAU3CemOKgMykS1+wh2gCo5AuYzisze7JSjX3N4u7dfOpd3OYrGGNmtlEhmv7eWtrtT4T7X0m4bJTXsyNZbwsmDObL+PTP3hR4S207J6MIqEThX2cTzIcQsGhx3Ed8Bag6GBwwri2aIVjxXuzz0Q7ddqePmeJTbezYDAJdSfzGXLOJ5tsZ89hplniZ0Qx7gdsTpz/0JtsxhNBM9Dvza6Os3HWrFmz/qF+AdfROhU=:6460
^FT593,94^A0I,23,24^FH\^FDSerial: " . $ELECTRODE_SERIAL . "^FS
^BY2,3,32^FT593,53^BCI,,N,N
^FD>:" . $ELECTRODE_SERIAL . "^FS
^FT593,11^A0I,28,28^FH\
^FT593,9^A0I,23,24^FH\^FDLENGTH: " . $ELECTRODE_TOTAL_LENGTH . "meters Diameter:" . $ROLL_DIAMETER."^FS
^PQ1,0,1,Y^XZ";
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if ($socket === false) {
    echo "socket_create() failed: reason: " . socket_strerror(socket_last_error    ()) . "\n";
} else {
    echo "OK.\n";
}

echo "Attempting to connect to '$host' on port '$port'...";
$result = socket_connect($socket, $host, $port);
if ($result === false) {
    echo "socket_connect() failed.\nReason: ($result) " . socket_strerror    (socket_last_error($socket)) . "\n";
} else {
    echo "OK.\n";
}

socket_write($socket, $label, strlen($label));
socket_close($socket);
}

if(mysqli_query($link, $sql)){
echo "Records added successfully.";
header("Location:http://10.1.10.190/Laminator_".$LAM_ID.".php");
} else{

    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);

}
}
else { echo "ERROR: Incorrect BATCH NUMBER!". "TEST 1: ". $TEST_1 . "TEST 2: " . $TEST_2 . "TEST 3: " . $TEST_3 . "TEST 4: " . $TEST_4 . "TEST 5: " . $TEST_5 . "TEST 6:" . $TEST_6;
}


// close connection

mysqli_close($link);
?> 
