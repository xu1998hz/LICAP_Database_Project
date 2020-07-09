<?php
// Build Date 04/17/2019 10:21 AM
$link = mysqli_connect("localhost", "operator", "Licap123!", "Manufacture");
// Check connection
if($link->connect_errno){
    die('ERROR: Could not connect. ' . $link->connect_error);

}
//escape strings
$DATE = mysqli_real_escape_string($link, $_REQUEST['DATE']);
$PACKAGE_OP = mysqli_real_escape_string($link, $_REQUEST['PACKAGE_OP']);
$ROLL_DIAMETER = mysqli_real_escape_string($link, $_REQUEST['ROLL_DIAMETER']);
$COMBINED_SERIAL = mysqli_real_escape_string($link, $_REQUEST['COMBINED_SERIAL']);
$PALLET_NUM = mysqli_real_escape_string($link, $_REQUEST['PALLET_NUM']);
$BOX_NUM = mysqli_real_escape_string($link, $_REQUEST['BOX_NUM']);
$WEIGHT = mysqli_real_escape_string($link, $_REQUEST['WEIGHT']);
$NOTES = mysqli_real_escape_string($link, $_REQUEST['NOTES']);
$TIMESTAMP = mysqli_real_escape_string($link, $_REQUEST['TIMESTAMP']);



$link = mysqli_connect("localhost", "operator", "Licap123!", "Manufacture");
// Check connection
if($link->connect_errno){
    die('ERROR: Could not connect. ' . $link->connect_error);

}
if(empty($COMBINED_SERIAL) !== TRUE){
$check = "SELECT 1 AS TEST FROM SLITTER WHERE COMBINED_SERIAL = '$COMBINED_SERIAL'";
$check_result = mysqli_query($link, $check);
if(mysqli_fetch_row($check_result)){

	//if($TEST = 1){
	$sql = "UPDATE SLITTER SET WEIGHT = '$WEIGHT', ROLL_DIAMETER = '$ROLL_DIAMETER', PALLET_NUM = '$PALLET_NUM', BOX_NUM = '$BOX_NUM', NOTES = '$NOTES', PACKAGE_OP 	= '$PACKAGE_OP' WHERE COMBINED_SERIAL = '$COMBINED_SERIAL'";
	}else{

		$check_2 = "SELECT 1 AS TEST FROM LAMINATOR WHERE ELECTRODE_SERIAL = '$COMBINED_SERIAL'";
		$check_result_2 = mysqli_query($link, $check_2);
		if(mysqli_fetch_row($check_result_2)){
				$FETCH = "SELECT ELECTRODE_LENGTH, NUM_DEFECT, NUM_HOLE, NUM_DELAM, NUM_SPLICE FROM LAMINATOR WHERE ELECTRODE_SERIAL = 'COMBINED_SERIAL' LIMIT 1";
				$check_result = mysqli_query($link, $FETCH);
				$row = mysqli_fetch_assoc($check_result);
				$NUM_DEFECT = $row['NUM_DEFECT'];
				$NUM_HOLE = $row['NUM_HOLE'];
				$NUM_DELAM = $row['NUM_DELAM'];
				$NUM_SPLICE = $row['NUM_SPLICE'];
				$ELECTRODE_LENGTH_METERS = $row['ELECTRODE_LENGTH'];
				$STRIP_LENGTH_FEET = $ELECTRODE_LENGTH * 3.28;
				$SLIT_OP = "NO SLITTER";
				$PERFORATED = "0";
				$ELECTRODE_AREA = $ELECTRODE_LENGTH/4;
				$TIMESTAMP = date("m/d/Y-H:i:s");
				$DATE = date("m/d/Y");
				$sql = "INSERT INTO SLITTER (DATE, SLIT_OP, COMBINED_SERIAL, PERFORATED, STRIP_LENGTH_FEET, STRIP_LENGTH_METERS, ELECTRODE_AREA, NUM_DEFECT, NUM_HOLE, NUM_DELAM, NUM_SPLICE, PALLET_NUM, BOX_NUM, WEIGHT, ROLL_DIAMETER, NOTES, TIMESTAMP, PACKAGE_OP) VALUES ('$DATE','$SLIT_OP','$COMBINED_SERIAL','$PERFORATED','$STRIP_LENGTH_FEET','$ELECTRODE_LENGTH','$ELECTRODE_AREA','$NUM_DEFECT','$NUM_HOLE','$NUM_DELAM','$NUM_SPLICE','$PALLET_NUM','$BOX_NUM','$WEIGHT','$ROLL_DIAMETER','$NOTES','$TIMESTAMP','$PACKAGE_OP')";




//Creates new label
for ($x =1; $x<=1; $x++) { 
/* Get the port for the service. */
$port = "9100";

/* Get the IP address for the target host. */
$host = "10.1.10.194";
/*construct the label */
$label = "﻿CT~~CD,~CC^~CT~
^XA~TA000~JSN^LT0^MNW^MTD^PON^PMN^LH0,0^JMA^PR5,5~SD15^JUS^LRN^CI0^XZ
^XA
^MMT
^PW609
^LL0203
^LS0
^FO384,96^GFA,03584,03584,00028,:Z64:
eJzt1LFuxCAMAFAiBkY+Aak/wqdxUoeO/SU69Teo8gOMDAjXxuGAJCd17IB1wyXvxBlsI8SKFStW/MNwOzyEkCA2CPj0A5BG80IoEBoKW+n2c5gFXICM1hlNF4EvA5sfLJBJfBnZwtNCtayAsqgWZzNZA2VRLXVz+DuTDL4EtjybTZYN1xjN4ho2WhAGHmS2nM0k3KR3NefBaLWoI50amRqsZhGU3/0rk+97dLEadKOdgd/UnuwLE2bPs0n6p2bFJDJ5mGtW6zLZBpPp0VTNsJ7WxTTbQ94YbS1saLhd/PqZB7NsXrF9XEw87Xs0V6hi4HW+sVQtHDatiSXHJzy2dM2FOhI/VNjLHvAY8QmLHl+bfWkmkdkdzK0FsnJnNb1nbSfT2XkhdeuJG6NeCpf9qYKl1V+tB9u5dDPxTXJfT2cmqSVsbj3fLB9GXdVmZapftT5jk210bRie21O/sOk6sOc+40k97oJTf7Idd0g3yeZowutQjPNwWOK8xGSmm6aVRxuj32MrVqxY8Yf4BfwKIv4=:5FAF
^FT593,54^A0I,14,14^FH\^FDLOT:" . $COMBINED_SERIAL . "^FS
^FT593,11^A0I,28,28^FH\^FDLENGTH:" . $ELECTRODE_LENGTH_METERS . " METERS^FS
^FT175,204^BQN,2,5
^FH\^FDLA," . $COMBINED_SERIAL . "^FS
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


}else{
					echo 'No Valid Serial Entered';
				}}


}else{
echo 'No Serial Entered';
}



if(mysqli_query($link, $sql)){
echo "Records added successfully.";
sleep(2);
header("Location:http://10.1.10.215/shipping.php");
}else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}

// close connection

mysqli_close($link);
?>
