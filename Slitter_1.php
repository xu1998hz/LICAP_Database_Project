<?php

$link = mysqli_connect("localhost", "operator", "Licap123!","Manufacture");
// Check connection
if($link->connect_errno){
    die('ERROR: Could not connect. ' . $link->connect_error);

}

//escape strings
$DATE = mysqli_real_escape_string($link, $_REQUEST['DATE']);
$SLIT_OP = mysqli_real_escape_string($link, $_REQUEST['SLIT_OP']);
$ROLL_DIAMETER = mysqli_real_escape_string($link, $_REQUEST['ROLL_DIAMETER']);
$STRIP_LENGTH_FEET = mysqli_real_escape_string($link, $_REQUEST['STRIP_LENGTH_FEET']);
$COMBINED_SERIAL = mysqli_real_escape_string($link, $_REQUEST['COMBINED_SERIAL']);
$NOTES = mysqli_real_escape_string($link, $_REQUEST['NOTES']);
$TIMESTAMP = mysqli_real_escape_string($link, $_REQUEST['TIMESTAMP']);
$ELECTRODE_SERIAL_1 = mysqli_real_escape_string($link, $_REQUEST['ELECTRODE_SERIAL_1']);
$ELECTRODE_LENGTH_1 = mysqli_real_escape_string($link, $_REQUEST['ELECTRODE_LENGTH_1']);
$ELECTRODE_SERIAL_2 = mysqli_real_escape_string($link, $_REQUEST['ELECTRODE_SERIAL_2']);
$ELECTRODE_LENGTH_2 = mysqli_real_escape_string($link, $_REQUEST['ELECTRODE_LENGTH_2']);
$ELECTRODE_SERIAL_3 = mysqli_real_escape_string($link, $_REQUEST['ELECTRODE_SERIAL_3']);
$ELECTRODE_LENGTH_3 = mysqli_real_escape_string($link, $_REQUEST['ELECTRODE_LENGTH_3']);
$PERFORATED = mysqli_real_escape_string($link, $_REQUEST['PERFORATED']);
if(empty($ELECTRODE_SERIAL_1) ===! true){
$COMBINED_SERIAL = $ELECTRODE_SERIAL_1 ;
	if($PERFORATED === "1"){
	$COMBINED_SERIAL = $COMBINED_SERIAL ."-PF";
	$ELECTRODE_SERIAL_1 = $ELECTRODE_SERIAL_1 ."-PF";
	} else{
	}

} else{ 
echo "ERROR: No serial number entered!!!";
}
if(empty($ELECTRODE_SERIAL_2) ===! true){
$COMBINED_SERIAL = $COMBINED_SERIAL ."/". $ELECTRODE_SERIAL_2 ;
	if($PERFORATED === "1"){
	$COMBINED_SERIAL = $COMBINED_SERIAL ."-PF";
	$ELECTRODE_SERIAL_2 = $ELECTRODE_SERIAL_2 ."-PF";
	} else{
	}
} else{ 

}
if(empty($ELECTRODE_SERIAL_3) ===! true){
$COMBINED_SERIAL = $COMBINED_SERIAL ."/". $ELECTRODE_SERIAL_3 ;
if($PERFORATED === "1"){
	$COMBINED_SERIAL = $COMBINED_SERIAL ."-PF";
	$ELECTRODE_SERIAL_3 = $ELECTRODE_SERIAL_3 ."-PF";
	} else{
	}
} else{ 

}

$DATE = date("m/d/Y");
$TIMESTAMP = date("m/d/Y-H:i:s");
//$COMBINED_SERIAL = $ELECTRODE_SERIAL_1 . "/" . $ELECTRODE_SERIAL_2 . "/" . $ELECTRODE_SERIAL_3;
$STRIP_LENGTH_FEET = $ELECTRODE_LENGTH_1 + $ELECTRODE_LENGTH_2 + $ELECTRODE_LENGTH_3;
$STRIP_LENGTH_METERS = round($STRIP_LENGTH_FEET / 3.28084);
$ELECTRODE_AREA = $STRIP_LENGTH_METERS / 4;
$NUM_DEFECTS = $NUM_HOLES + $NUM_DELAM + $NUM_SPLICE;

//SQL Insert Statement
$sql = "INSERT INTO SLITTER (DATE, SLIT_OP, COMBINED_SERIAL, STRIP_LENGTH_FEET, STRIP_LENGTH_METERS, ELECTRODE_AREA, NUM_DEFECTS, NUM_HOLES, NUM_DELAM, NUM_SPLICE, PERFORATED, NOTES, TIMESTAMP) VALUES ('$DATE','$SLIT_OP','$COMBINED_SERIAL','$STRIP_LENGTH_FEET','$STRIP_LENGTH_METERS','$ELECTRODE_AREA','$NUM_DEFECTS','$NUM_HOLES','$NUM_DELAM','$NUM_SPLICE','$PERFORATED','$NOTES','$TIMESTAMP')";

//$inventory_sql = "INSERT INTO INVENTORY_ELECTRODE.ELECTRODE_INVENTORY (COMBINED_SERIAL, LENGTH, LOCATION, TIMESTAMP) VALUES ('$COMBINED_SERIAL','$STRIP_LENGTH_METERS','$LOCATION','$TIMESTAMP')";

if(mysqli_query($link, $sql)){
echo "Records added successfully.";
/* Get the port for the service. */
for ($x =1; $x<=2; $x++) { 
/* Get the port for the service. */
$port = "9100";

/* Get the IP address for the target host. */
$host = "10.1.10.193";

/* construct the label */
$label = "CT~~CD,~CC^~CT~
^XA~TA000~JSN^LT0^MNW^MTD^PON^PMN^LH0,0^JMA^PR5,5~SD15^JUS^LRN^CI0^XZ
^XA
^MMC
^PW609
^LL0203
^LS0
^FO352,128^GFA,03072,03072,00032,:Z64:
eJzt1TGS3CAQBVAoAhKXOQJH4WhoawJfS1u+CEdQqICi3U03Egi868zJKFjN8Gq0Lfg0Sr0vBQdkugdQStMnAwkAlh7gaH7eHqvHoiyOb+LQeamelcfh1Hy/PDSPOHw0T7eDONT/K35c7tlPTcO5+flwOA0Nl9kdFk13Kh9fQDx3jrVqek6tW7xcbtlTwGdi3aaWvvQdp+Bgj3B5HTCwU52ueZjdFbqJ+965MJv9T02vSe5mN2dwG+Tm29P1AQ4Xi93ertk39QHu9LMrdiruWy8eZo8ynxbdjV4XurkDB3Zwv/BX5xyU0K3n4Ibd42+D5MH0br9xx05zHQ+u5tW7X/nvhW80DfXrx8LpMw1OXhP3pe/su6YMTk47k3ecWXuefHi/eA4+zY+4pj2wLZxe+h8c9+4VZ/uF0/r/Gjyxx5OWkPIFQ74ejvnMQz7b80P23Bkf+X7462/us8eZ0Dt6WHqheOkUXYrZTPOHLxbwr83BfcLguTnFyxXz4+oP/fpSThO3DDd4kOCxD/2pzw/tgyRpa/2tz6f43R+fbqq7q78ufJfdBqpz2X8YEPp29/fmZvD7fGiu20HBx1EEOV+ay0hzKmcfPIhzt7bSxW53zQtnl7rM0L8COx9x+Jjj4e/rfb2v/3T9AQ7vY/c=:6EFC
^FT600,38^A0I,25,24^FH\^FDLOT:". $ELECTRODE_SERIAL_3."^FS
^FT600,73^A0I,25,24^FH\^FDLOT:". $ELECTRODE_SERIAL_2."^FS
^FT599,108^A0I,25,24^FH\^FDLOT:". $ELECTRODE_SERIAL_1."^FS
^FT600,8^A0I,20,19^FH\^FDLENGTH:". $STRIP_LENGTH_METERS . " METERS^FS
^FT17,222^BQN,2,7
^FH\^FDLA,".$COMBINED_SERIAL."^FS
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



} else{
echo "ERROR: Not able to execute $sql. " . mysqli_error($link);
}
/*if(mysqli_query($link, $inventory_sql)){
echo "Records added successfully.";
} else{
echo "ERROR: Could not able to execute $inventory_sql. " . mysqli_error($link);
}
*/
// close connection

mysqli_close($link);
header("Location:http://10.1.10.215/Slitter.php");
?> 
