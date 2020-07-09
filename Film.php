<?php

$link = mysqli_connect("localhost", "operator", "Licap123!", "Manufacture");
// Check connection
if($link->connect_errno){
    die('ERROR: Could not connect. ' . $link->connect_error);

}
//escape strings
$FILM_MILL = mysqli_real_escape_string($link, $_REQUEST['FILM_MILL']);
$FILM_1_OP = mysqli_real_escape_string($link, $_REQUEST['FILM_1_OP']);
$FILM_2_OP = mysqli_real_escape_string($link, $_REQUEST['FILM_2_OP']);
$LENGTH = mysqli_real_escape_string($link, $_REQUEST['LENGTH']);
$MIX_BATCH_NUM = mysqli_real_escape_string($link, $_REQUEST['MIX_BATCH_NUM']);
$MIX_BATCH_NUM_2 = mysqli_real_escape_string($link, $_REQUEST['MIX_BATCH_NUM_2']);
$THICKNESS = mysqli_real_escape_string($link, $_REQUEST['THICKNESS']);
$MILL_TEMP = mysqli_real_escape_string($link, $_REQUEST['MILL_TEMP']);
$CAL_1_TEMP = mysqli_real_escape_string($link, $_REQUEST['CAL_1_TEMP']);
$CAL_2_TEMP = mysqli_real_escape_string($link, $_REQUEST['CAL_2_TEMP']);
$LINE_SPEED = mysqli_real_escape_string($link, $_REQUEST['LINE_SPEED']);
$BEGIN_OP = mysqli_real_escape_string($link, $_REQUEST['BEGIN_OP']);
$BEGIN_CENTER = mysqli_real_escape_string($link, $_REQUEST['BEGIN_CENTER']);
$BEGIN_MACHINE = mysqli_real_escape_string($link, $_REQUEST['BEGIN_MACHINE']);
$END_OP = mysqli_real_escape_string($link, $_REQUEST['END_OP']);
$END_CENTER = mysqli_real_escape_string($link, $_REQUEST['END_CENTER']);
$END_MACHINE = mysqli_real_escape_string($link, $_REQUEST['END_MACHINE']);
$DEFECT_NUM = mysqli_real_escape_string($link, $_REQUEST['DEFECT_NUM']);
$FILM_WEIGHT = mysqli_real_escape_string($link, $_REQUEST['FILM_WEIGHT']);
$AVG_THICKNESS = mysqli_real_escape_string($link, $_REQUEST['AVG_THICKNESS']);
$FILM_DENSITY = mysqli_real_escape_string($link, $_REQUEST['FILM_DENSITY'] );
$FILM_NOTE = mysqli_real_escape_string($link, $_REQUEST['FILM_NOTE']);
$TIMESTAMP = mysqli_real_escape_string($link, $_REQUEST['TIMESTAMP']);

$DATE = date("m/d/Y");
$TIMESTAMP = date("m/d/Y-H:i:s");

//Pull last Film Lot Number
$myquery = "SELECT FILM_ID FROM FILM ORDER BY ID DESC LIMIT 1";
$result = mysqli_query($link, $myquery);
$row = mysqli_fetch_assoc($result);
$Last_Film_ID = $row['FILM_ID'];


//Compare last batch date with current date
$Batch_Digit = explode("-",$Last_Film_ID);
$DATE_TEST = date("mdY");

if($Batch_Digit[2] === $DATE_TEST){
	$INC_DIGIT = $Batch_Digit[3] + 1;
	$FILM_ID = $FILM_MILL . "F-". $THICKNESS . "-" . $Batch_Digit[2] . "-" . $INC_DIGIT;
		}
else{
	$FILM_ID = $FILM_MILL . "F-" . $THICKNESS . "-" . $DATE_TEST . "-1";
		}

//AVG Thickness and Film Density Calc
$AVG_THICKNESS = ($END_OP + $END_CENTER + $END_MACHINE)/3;
$NORMALIZE_WEIGHT = $FILM_WEIGHT/8;
$FILM_DENSITY = $NORMALIZE_WEIGHT/(5.064506 * $AVG_THICKNESS/10000);
/*
if ($AVG_THICKNESS > 145 && $AVG_THICKNESS < "155") {
$MESSAGE = "IN-SPEC Proceed to Laminate";
} else if ($AVG_THICKNESS > '95' && $AVG_THICKNESS < '105') {
$MESSAGE = "IN-SPEC Proceed to Laminate";
} else if ($AVG_THICKNESS > '85' && $AVG_THICKNESS < '95') {
$MESSAGE = "IN-SPEC Proceed to Laminate";
else { $MESSAGE = "OUT OF SPEC HOLD FOR EVALUATION";}
*/
//SQL Insert Statement
$sql = "INSERT INTO FILM (DATE, FILM_MILL, FILM_1_OP, FILM_2_OP, FILM_ID, LENGTH, MIX_BATCH_NUM, MIX_BATCH_NUM_2, THICKNESS, MILL_TEMP, CAL_1_TEMP, CAL_2_TEMP, LINE_SPEED, END_OP, END_CENTER, END_MACHINE, DEFECT_NUM, FILM_WEIGHT, AVG_THICKNESS, FILM_DENSITY, FILM_NOTE,TIMESTAMP) VALUES ('$DATE','$FILM_MILL','$FILM_1_OP','$FILM_2_OP','$FILM_ID','$LENGTH','$MIX_BATCH_NUM','$MIX_BATCH_NUM_2','$THICKNESS','$MILL_TEMP','$CAL_1_TEMP','$CAL_2_TEMP','$LINE_SPEED','$END_OP','$END_CENTER','$END_MACHINE','$DEFECT_NUM','$FILM_WEIGHT','$AVG_THICKNESS','$FILM_DENSITY','$FILM_NOTE','$TIMESTAMP')";


if(mysqli_query($link, $sql)){
echo "Records added successfully.";


//Creates new label


/* Get the port for the service. */
$port = "9100";

/* Get the IP address for the target host. */
$host = "10.1.10.192";

/* construct the label */
$label = "﻿CT~~CD,~CC^~CT~
^XA~TA000~JSN^LT0^MNW^MTD^PON^PMN^LH0,0^JMA^PR5,5~SD15^JUS^LRN^CI0^XZ
^XA
^MMT
^PW609
^LL0203
^LS0
^FO256,128^GFA,02304,02304,00024,:Z64:
eJzt0rFtxDAMBVAaKlQyG3iNdF4sgLTBraRNottAhzQqBDGfupwty84AB5iFITwQBE2S6Ior3jP4pxLZQi4SP0Q2f+Bts5HSPA7OSFVPqyc451kkqOfOPT4OJdTL4IIS6nVz52m+wzMn4t6DepgL3MjmS6AlCNkKn3pHjfBFRp1G/0Rqc786Whf/AVJ3g+PlendoW57jcrfNjWy+dG7h9sRZPU919Fm9HB29J1OmcvDEcHNwFzhN1eSDe3V76iT/ejo4qXMa59Dc3SINc1t9mHOrv3xHeu1l74Fee+z+C0MK2KstnS+xuZ/+7qSf29Ofd9XPGUtAM7q4E293u98XltkSAu/3jgtsCdTfibqpmlB2voaLdMUVbx2/69wvHw==:09F5
^FT596,109^A0I,23,24^FH\^FDLOT: " . $FILM_ID . "^FS
^BY2,3,32^FT597,68^BCI,,N,N
^FD>:" . $FILM_ID . "^FS
^FT597,38^A0I,28,28^FH\^FDLENGTH: " . $LENGTH . " METERS^FS
^FT597,7^A0I,28,28^FH\^FDMS: ". $END_MACHINE ."             C:". $END_CENTER ."                  OS:". $END_OP ." ^FS
^PQ1,0,1,Y^XZ";
/*$label = "﻿CT~~CD,~CC^~CT~
^XA~TA000~JSN^LT0^MNW^MTD^PON^PMN^LH0,0^JMA^PR5,5~SD15^JUS^LRN^CI0^XZ
^XA
^MMT
^PW609
^LL0203
^LS0
^FO192,96^GFA,03584,03584,00028,:Z64:
eJzt1D1u7CAQB3CQC0qSE3AUzpQTQJRiy1zJuQlPuQDuKAiTGQZ7MbaeXvM6Rivvip/X5uMPQsyaNWvWfywJn4BfCrywQQiTLZSLiYUaySAc9mhmAFa21JkXQoNwAJEtD1YEUGO1ctgHPgptAWqsBmczWQE1svnd3tk0mWdbn4Z9tsmQrQb74Z7m2SwE/JCpfYBkkf+HjZFMDuaizvi7mqCGZo7eFFV89S5Vc4MFFbZoL2arya8N5dZW+diKZrNP45EJvYEuvVk4zGzw6G2B3XAKX9TJaBK9hn16l97sYYIf0xkNzdesXA2aqVLv++5MVluQ2f50tuym7yxLtnxjAU3CemOKgMykS1+wh2gCo5AuYzisze7JSjX3N4u7dfOpd3OYrGGNmtlEhmv7eWtrtT4T7X0m4bJTXsyNZbwsmDObL+PTP3hR4S207J6MIqEThX2cTzIcQsGhx3Ed8Bag6GBwwri2aIVjxXuzz0Q7ddqePmeJTbezYDAJdSfzGXLOJ5tsZ89hplniZ0Qx7gdsTpz/0JtsxhNBM9Dvza6Os3HWrFmz/qF+AdfROhU=:6460
^FT593,94^A0I,23,24^FH\^FDFILM ROLL ID: " . $FILM_ID . "^FS
^BY2,3,32^FT593,53^BCI,,N,N
^FD>:" . $FILM_ID . "^FS
^FT593,11^A0I,28,28^FH\
^FT593,9^A0I,23,24^FH\^FDLENGTH: " . $LENGTH . "meters^FS
^PQ1,0,1,Y^XZ";*/
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

header("Location:http://10.1.10.215/Film_".$FILM_MILL.".php");
} else{

    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);

}


// close connection

mysqli_close($link);
?>
