<?php
// Build Date 9/5/2019 12:38PM
//9/5/2019 Program to print 1x3 sticker now.
$link = mysqli_connect("localhost", "operator", "Licap123!", "Manufacture");
// Check connection
if($link->connect_errno){
    die('ERROR: Could not connect. ' . $link->connect_error);

}
$inventory_link = mysqli_connect("localhost", "operator", "Licap123!", "INVENTORY");
// Check connection
if($inventory_link->connect_errno){
    die('ERROR: Could not connect. ' . $inventory_link->connect_error);

}
//Pull last ELECTRODE Lot Number
$myquery = "SELECT BATCH_NUM FROM blend ORDER BY ID DESC LIMIT 1";
$result = mysqli_query($link, $myquery);
$row = mysqli_fetch_assoc($result);
$BATCH_NUM_LAST = $row['BATCH_NUM'];

//Compare last batch date with current date
$Batch_Digit = explode("-",$BATCH_NUM_LAST);
$DATE_TEST = date("mdY");
$DATE = date("m/d/Y");

//Creates new batch number
if($Batch_Digit[1] === $DATE_TEST){
	$INC_DIGIT = $Batch_Digit[2] + 1;	
	$NEW_BATCH_NUM = "M-" . $DATE_TEST . "-" . $INC_DIGIT;
		}
else{
	$NEW_BATCH_NUM = "M-" . $DATE_TEST . "-1";
		}


//escape strings
$DATE = mysqli_real_escape_string($link, $_REQUEST['DATE']);
$MIX_OP = mysqli_real_escape_string($link, $_REQUEST['MIX_OP']);
$BATCH_NUM = mysqli_real_escape_string($link, $_REQUEST['BATCH_NUM']);
$BATCH_WEIGHT = mysqli_real_escape_string($link, $_REQUEST['BATCH_WEIGHT']);
//$BAG_WEIGHT = mysqli_real_escape_string($link, $_REQUEST['BAG_WEIGHT']);
$AC_WEIGHT = mysqli_real_escape_string($link, $_REQUEST['AC_WEIGHT']);
$ACETONE_LOT = mysqli_real_escape_string($link, $_REQUEST['ACETONE_LOT']);
$ACETONE_WEIGHT = mysqli_real_escape_string($link, $_REQUEST['ACETONE_WEIGHT']);
$STRIP_RECYCLE = mysqli_real_escape_string($link, $_REQUEST['STRIP_RECYCLE']);
$BAGHOUSE_RECYCLE = mysqli_real_escape_string($link, $_REQUEST['BAGHOUSE_RECYCLE']);
$OUTSIDE_RECYCLE = mysqli_real_escape_string($link, $_REQUEST['OUTSIDE_RECYCLE']);
$PTFE_LOT = mysqli_real_escape_string($link, $_REQUEST['PTFE_LOT']);
$PTFE_WEIGHT = mysqli_real_escape_string($link, $_REQUEST['PTFE_WEIGHT']);
$TIMESTAMP = mysqli_real_escape_string($link, $_REQUEST['TIMESTAMP']);
$AC_LOT_1 = mysqli_real_escape_string($link, $_REQUEST['AC_LOT_1']);
$AC_LOT_2 = mysqli_real_escape_string($link, $_REQUEST['AC_LOT_2']);
$AC_LOT_3 = mysqli_real_escape_string($link, $_REQUEST['AC_LOT_3']);
$AC_NUM_1 = mysqli_real_escape_string($link, $_REQUEST['AC_NUM_1']);
$AC_NUM_2 = mysqli_real_escape_string($link, $_REQUEST['AC_NUM_2']);
$AC_NUM_3 = mysqli_real_escape_string($link, $_REQUEST['AC_NUM_3']);
//$AC_BAG_WEIGHT = mysqli_real_escape_string($link, $_REQUEST['AC_BAG_WEIGHT']);
$MIX_TIME = mysqli_real_escape_string($link, $_REQUEST['MIX_TIME']);
$PERIPHERAL_PRESSURE = mysqli_real_escape_string($link, $_REQUEST['PERIPHERAL_PRESSURE']);
$INJECTOR_PRESSURE = mysqli_real_escape_string($link, $_REQUEST['INJECTOR_PRESSURE']);
$GRIND_OP = mysqli_real_escape_string($link, $_REQUEST['GRIND_OP']);
$MIXER_RPM = mysqli_real_escape_string($link, $_REQUEST['MIXER_RPM']);

$AC_LOT_NUM_1 = $AC_LOT_1 . "-" . $AC_NUM_1;
$AC_LOT_NUM_2 = $AC_LOT_2 . "-" . $AC_NUM_2;
$AC_LOT_NUM_3 = $AC_LOT_3 . "-" . $AC_NUM_3;


$DATE = date("m/d/Y");
$TIMESTAMP = date("m/d/Y-H:i:s");

$AC_LOT[1] = $AC_LOT_1;
$AC_LOT[2] = $AC_LOT_2;
$AC_LOT[3] = $AC_LOT_3;

//$AC_WEIGHT = $AC_BAG_WEIGHT - $BAG_WEIGHT;
$BATCH_WEIGHT = $AC_WEIGHT + $ACETONE_WEIGHT + $STRIP_RECYCLE + $BAGHOUSE_RECYCLE + $OUTSIDE_RECYCLE + $PTFE_WEIGHT;
$ACETONE_PERCENT = (($ACETONE_WEIGHT/($BATCH_WEIGHT - $RECYCLE_WEIGHT - $NEW_RECYCLE_WEIGHT - $PTFE_RECYCLE))*100);
$PTFE_RECYCLE = (0.0204 * ($STRIP_RECYCLE + $BAGHOUSE_RECYCLE + $OUTSIDE_RECYCLE));
$PTFE_PERCENT = ((($PTFE_WEIGHT - $PTFE_RECYCLE)/($AC_WEIGHT + $PTFE_WEIGHT - $PTFE_RECYCLE))*100);


$check_ac_1 = "SELECT 1 AS TEST FROM INVENTORY_TABLE WHERE SERIAL = '$AC_LOT_1'";
$check_ac_result_1 = mysqli_query($inventory_link, $check_ac_1);
$row = mysqli_fetch_assoc($check_ac_result_1);
$TEST_1 = 1;//$row['TEST'];

if(empty($AC_LOT_2) !== true){
$check_ac_2 = "SELECT 1 AS TEST FROM INVENTORY_TABLE WHERE SERIAL = '$AC_LOT_2'";
$check_ac_result_2 = mysqli_query($inventory_link, $check_ac_2);
$row = mysqli_fetch_assoc($check_ac_result_2);
$TEST_2 = 1; //$row['TEST'];
}
else{ $TEST_2 = 1;}


if(empty($AC_LOT_3) !== true){
$check_ac_3 = "SELECT 1 AS TEST FROM INVENTORY_TABLE WHERE SERIAL = '$AC_LOT_3'";
$check_ac_result_3 = mysqli_query($inventory_link, $check_ac_3);
$row = mysqli_fetch_assoc($check_ac_result_3);
$TEST_3 = 1;//$row['TEST'];
}
else{ $TEST_3 = 1;}



$CHECK_ACETONE = "SELECT 1 AS TEST FROM INVENTORY_TABLE WHERE SERIAL = '$ACETONE_LOT'";
$CHECK_ACETONE_RESULT = mysqli_query($inventory_link, $CHECK_ACETONE);
$row = mysqli_fetch_assoc($CHECK_ACETONE_RESULT);
$TEST_ACETONE = 1;//$row['TEST'];

$CHECK_PTFE = "SELECT 1 AS TEST FROM INVENTORY_TABLE WHERE SERIAL = '$PTFE_LOT'";
$CHECK_PTFE_RESULT = mysqli_query($inventory_link, $CHECK_PTFE);
$row = mysqli_fetch_assoc($CHECK_PTFE_RESULT);
$TEST_PTFE = 1;//$row['TEST'];
/*
if ($TEST_1 > 0 && $TEST_2 > 0 && $TEST_3 > 0 && $TEST_PTFE > 0 && $TEST_ACETONE > 0 ) {
	$acetone_sql = "UPDATE INVENTORY_TABLE SET AMOUNT_USED = AMOUNT_USED + '$ACETONE_WEIGHT' WHERE SERIAL ='$ACETONE_LOT'";
	mysqli_query($inventory_link, $acetone_sql);
	
	$ptfe_sql = "UPDATE INVENTORY_TABLE SET AMOUNT_USED = AMOUNT_USED + '$PTFE_WEIGHT' WHERE SERIAL ='$PTFE_LOT'";
	mysqli_query($inventory_link, $ptfe_sql);
for ($i=1; $i<=3; $i++) {
	$ac_sql = "UPDATE INVENTORY_TABLE 
		SET AMOUNT_USED = AMOUNT_USED + '10' 
		WHERE SERIAL ='$AC_LOT[$i]'";
	mysqli_query($inventory_link, $ac_sql);
}

} else {

echo "ERROR: Serial Number is entered incorrectly or not in the database. Recheck your data entry. If error persists contact Process Engineer.\n";
mysqli_close($link);
}
*/
//SQL Insert Statement
$sql = "INSERT INTO blend (DATE, MIXING_OP, BATCH_NUM, BATCH_WEIGHT, AC_LOT_NUM, AC_LOT_NUM_2, AC_LOT_NUM_3, AC_WEIGHT, ACETONE_LOT, ACETONE_WEIGHT, ACETONE_PERCENT, STRIP_RECYCLE, BAGHOUSE_RECYCLE, OUTSIDE_RECYCLE, PTFE_RECYCLE, PTFE_LOT, PTFE_WEIGHT, PTFE_PERCENT, TIMESTAMP, MIX_TIME, PERIPHERAL_PRESSURE, INJECTOR_PRESSURE, GRIND_OP,MIXER_RPM) VALUES ('$DATE','$MIX_OP','$NEW_BATCH_NUM','$BATCH_WEIGHT','$AC_LOT_NUM_1','$AC_LOT_NUM_2','$AC_LOT_NUM_3','$AC_WEIGHT','$ACETONE_LOT','$ACETONE_WEIGHT','$ACETONE_PERCENT','$STRIP_RECYCLE','$BAGHOUSE_RECYCLE','$OUTSIDE_RECYCLE','$PTFE_RECYCLE','$PTFE_LOT','$PTFE_WEIGHT','$PTFE_PERCENT','$TIMESTAMP','$MIX_TIME','$PERIPHERAL_PRESSURE','$INJECTOR_PRESSURE','$GRIND_OP','$MIXER_RPM')";

if(mysqli_query($link, $sql)){
echo "Records added successfully.";

//Creates new label
error_reporting(E_ALL);

/* Get the port for the service. */
$port = "9100";

/* Get the IP address for the target host. */
$host = "10.1.10.196";

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
^FT593,94^A0I,23,24^FH\^FDBATCH NUMBER: " . $NEW_BATCH_NUM . "^FS
^BY2,3,32^FT593,53^BCI,,N,N
^FD>:" . $NEW_BATCH_NUM . "^FS
^FT593,11^A0I,28,28^FH\
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


header("Location:http://10.1.10.190/blend.php");
} else{

    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);

}


// close connection

mysqli_close($link);
?> 
