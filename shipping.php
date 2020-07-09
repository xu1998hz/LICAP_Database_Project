 <!DOCTYPE html>

    <html lang="en">

    <head>

    <meta charset="UTF-8">

    <title>SLITTER DATA ENTRY PAGE</title>

    </head>

    <body>
	
    <form action="shipping_1.php" method="post">

        <p>
<?php
$link = mysqli_connect("localhost", "operator", "Licap123!", "Manufacture");
// Check connection
if($link->connect_errno){
    die('ERROR: Could not connect. ' . $link->connect_error);

}
$sql = "SELECT PACKAGE_OP, PALLET_NUM, BOX_NUM FROM SLITTER ORDER BY ID DESC LIMIT 1";
$result = mysqli_query($link,$sql);
$row = mysqli_fetch_assoc($result); 
  
	$PACKAGE_OP_RESULT = $row['PACKAGE_OP'];
	$PALLET_NUM_RESULT = $row['PALLET_NUM'];
	$BOX_NUM_RESULT = $row['BOX_NUM'];
if ($BOX_NUM_RESULT < 16) {
 $BOX_NUM_RESULT = $BOX_NUM_RESULT + 1 ;
}else {
$BOX_NUM_RESULT = 1;
}

mysqli_close($link);

?>


<p>
<label for="PACKAGE_OP">Shipping Operator Name:</label>
<input id="PACKAGE_OP" name="PACKAGE_OP" type="text" value="<?php echo htmlentities($PACKAGE_OP_RESULT); ?>" />
</p>
<HR>
<p>
<label for="COMBINED_SERIAL">Electrode Serial</label>
<input id="COMBINED_SERIAL" name="COMBINED_SERIAL" type="text">
</p>
<p>
<label for="PALLET_NUM">Pallet_Number:</label>
<input id="PALLET_NUM" name="PALLET_NUM" type="text" value="<?php echo htmlentities($PALLET_NUM_RESULT); ?>" />
&nbsp;&nbsp;&nbsp;
<label for="BOX_NUM">Box_Number:</label>
<input id="BOX_NUM" name="BOX_NUM" type="text" value="<?php echo htmlentities($BOX_NUM_RESULT); ?>" />
</p>
<p>
<label for="WEIGHT">Shipping Weight</label>
<input id="WEIGHT" name="WEIGHT" type="text" style="max-width: 100px;">
&nbsp;&nbsp;&nbsp;
<label for="ROLL_DIAMETER">Roll Diameter</label>
<input id="ROLL_DIAMETER" name="ROLL_DIAMETER" type="text" style="max-width: 100px;">
</p>
<p>
<hr>
<label for="NOTES">Notes:</label>
<input type="text" name="NOTES" id="NOTES"> 
</p>
<input type="submit" value="Submit">

    </form>

    </body>

    </html>

